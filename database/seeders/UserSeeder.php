<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@qurban.com'],
            [
                'name' => 'Owner Admin',
                'password' => Hash::make('password'),
                'role' => 'owner/admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pekerja@qurban.com'],
            [
                'name' => 'Pekerja Satu',
                'password' => Hash::make('password'),
                'role' => 'pekerja',
            ]
        );
    }
}
