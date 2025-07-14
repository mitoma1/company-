<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => '管理者A',
            'email' => 'adminA@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => '管理者B',
            'email' => 'adminB@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => '管理者C',
            'email' => 'adminC@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}
