<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'daffa',
                'role' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('superadmin'),
            ],
            [
                'name' => 'sukuna',
                'role' => 'petugas',
                'email' => 'petugas@gmail.com',
                'password' => bcrypt('superpetugas'),
            ],
        ];

        foreach ($user as $key => $user) {
            User::create($user);
        }
    }
}
