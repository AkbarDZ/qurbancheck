<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KriteriaKurbanSeeder::class,
            TipeTernakSeeder::class,
            RasTernakSeeder::class,
            KandangSeeder::class,
        ]);

        User::create([
            'name' => 'Owner Admin',
            'email' => 'admin@qurban.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'owner/admin',
        ]);

        User::create([
            'name' => 'Pekerja Satu',
            'email' => 'pekerja@qurban.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'pekerja',
        ]);
    }
}
