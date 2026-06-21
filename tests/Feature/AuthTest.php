<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');

        $response2 = $this->get('/ternak');
        $response2->assertRedirect('/login');
    }

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('QurbanCheck');
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@qurban.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@qurban.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@qurban.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@qurban.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'owner/admin',
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_pekerja_cannot_access_master_data(): void
    {
        $pekerja = User::factory()->create([
            'role' => 'pekerja',
        ]);

        $response = $this->actingAs($pekerja)->get('/master');
        $response->assertStatus(403);
    }

    public function test_pekerja_cannot_access_pengguna_management(): void
    {
        $pekerja = User::factory()->create([
            'role' => 'pekerja',
        ]);

        $response = $this->actingAs($pekerja)->get('/pengguna');
        $response->assertStatus(403);
    }

    public function test_pekerja_cannot_delete_ternak(): void
    {
        $pekerja = User::factory()->create([
            'role' => 'pekerja',
        ]);

        $response = $this->actingAs($pekerja)->delete('/ternak/1');
        $response->assertStatus(403);
    }

    public function test_pekerja_cannot_view_rapor_keuangan(): void
    {
        $pekerja = User::factory()->create([
            'role' => 'pekerja',
        ]);

        $response = $this->actingAs($pekerja)->get('/ternak/1/keuangan');
        $response->assertStatus(403);
    }

    public function test_pekerja_cannot_delete_kesehatan(): void
    {
        $pekerja = User::factory()->create([
            'role' => 'pekerja',
        ]);

        $response = $this->actingAs($pekerja)->delete('/kesehatan/1');
        $response->assertStatus(403);
    }

    public function test_owner_admin_can_access_master_and_pengguna(): void
    {
        $admin = User::factory()->create([
            'role' => 'owner/admin',
        ]);

        $responseMaster = $this->actingAs($admin)->get('/master');
        $responseMaster->assertStatus(200);

        $responsePengguna = $this->actingAs($admin)->get('/pengguna');
        $responsePengguna->assertStatus(200);
    }

    public function test_guest_cannot_access_profile_edit_page(): void
    {
        $response = $this->get('/profil');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profil');
        $response->assertStatus(200);
        $response->assertSee('Edit Profil');
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@email.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->put('/profil', [
            'name' => 'New Name',
            'email' => 'new@email.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertRedirect('/profil');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@email.com', $user->email);
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    public function test_profile_update_validation_prevents_duplicate_email(): void
    {
        $user1 = User::factory()->create([
            'email' => 'user1@email.com'
        ]);
        $user2 = User::factory()->create([
            'email' => 'user2@email.com'
        ]);

        $response = $this->actingAs($user1)->put('/profil', [
            'name' => 'User One',
            'email' => 'user2@email.com',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals('user1@email.com', $user1->fresh()->email);
    }
}
