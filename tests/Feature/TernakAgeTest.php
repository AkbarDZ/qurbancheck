<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ternak;
use App\Models\Kandang;
use App\Models\RasTernak;
use App\Models\TipeTernak;
use App\Models\KriteriaKurban;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TernakAgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_dynamic_age_calculation(): void
    {
        $tipe = TipeTernak::create([
            'nama_jenis' => 'Sapi',
            'umur_minimal_qurban' => 24,
        ]);
        
        $ras = RasTernak::create([
            'tipe_ternak_id' => $tipe->id,
            'nama_ras' => 'Limosin',
        ]);
        
        $kandang = Kandang::create([
            'nama_kandang' => 'Kandang A',
            'kapasitas_maksimal' => 10,
        ]);
        
        // Animal born 24 months ago
        $ternak1 = Ternak::create([
            'ras_id' => $ras->id,
            'kandang_id' => $kandang->id,
            'nomor_eartag' => 'TAG-123',
            'jenis_kelamin' => 'jantan',
            'tanggal_lahir' => now()->subMonths(24)->toDateString(),
        ]);
        
        $this->assertEquals(24, $ternak1->umur_bulan);
        
        // Animal born 18 months ago
        $ternak2 = Ternak::create([
            'ras_id' => $ras->id,
            'kandang_id' => $kandang->id,
            'nomor_eartag' => 'TAG-456',
            'jenis_kelamin' => 'jantan',
            'tanggal_lahir' => now()->subMonths(18)->toDateString(),
        ]);
        
        $this->assertEquals(18, $ternak2->umur_bulan);
    }

    public function test_store_purchased_ternak_calculates_birthdate(): void
    {
        $user = User::factory()->create([
            'role' => 'owner/admin',
        ]);

        $tipe = TipeTernak::create([
            'nama_jenis' => 'Sapi',
            'umur_minimal_qurban' => 24,
        ]);
        
        $ras = RasTernak::create([
            'tipe_ternak_id' => $tipe->id,
            'nama_ras' => 'Limosin',
        ]);
        
        $kandang = Kandang::create([
            'nama_kandang' => 'Kandang A',
            'kapasitas_maksimal' => 10,
        ]);

        $response = $this->actingAs($user)->postJson('/ternak', [
            'nomor_eartag' => 'TAG-TEST',
            'ras_id' => $ras->id,
            'kandang_id' => $kandang->id,
            'jenis_kelamin' => 'jantan',
            'berat_awal' => 250,
            'asal_hewan' => 'beli',
            'harga_beli_awal' => 15000000,
            'umur_bulan_beli' => 18,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $ternak = Ternak::where('nomor_eartag', 'TAG-TEST')->first();
        $this->assertNotNull($ternak);
        // Birthdate should be set to 18 months ago, start of month
        $expectedDob = now()->subMonths(18)->startOfMonth()->toDateString();
        $this->assertEquals($expectedDob, $ternak->tanggal_lahir->toDateString());
        $this->assertEquals(18, $ternak->umur_bulan);
    }

    public function test_pemeriksaan_syariat_blocks_under_age_animals(): void
    {
        $user = User::factory()->create();

        $tipe = TipeTernak::create([
            'nama_jenis' => 'Sapi',
            'umur_minimal_qurban' => 24,
        ]);
        
        $ras = RasTernak::create([
            'tipe_ternak_id' => $tipe->id,
            'nama_ras' => 'Limosin',
        ]);
        
        $kandang = Kandang::create([
            'nama_kandang' => 'Kandang A',
            'kapasitas_maksimal' => 10,
        ]);

        // Under-age animal (18 months old, min is 24)
        $underAgeTernak = Ternak::create([
            'ras_id' => $ras->id,
            'kandang_id' => $kandang->id,
            'nomor_eartag' => 'TAG-UNDER',
            'jenis_kelamin' => 'jantan',
            'tanggal_lahir' => now()->subMonths(18)->toDateString(),
        ]);

        $kriteria = KriteriaKurban::create([
            'nama_kriteria' => 'Sehat',
            'is_fatal' => true,
            'deskripsi' => 'Kondisi sehat wal afiat',
        ]);

        $response = $this->actingAs($user)->postJson('/syariat/pemeriksaan', [
            'ternak_id' => [$underAgeTernak->id],
            'tanggal_pemeriksaan' => now()->toDateString(),
            'kriteria' => [
                $kriteria->id => [
                    'is_lolos' => '1',
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
        $response->assertJsonFragment([
            'message' => "Hewan dengan Tag TAG-UNDER belum cukup umur untuk kelayakan kurban (18 dari minimal 24 bulan)."
        ]);
    }
}
