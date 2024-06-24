<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => '$2y$12$TOvIGtwmqRKOXdeA09zF6e/Aw//l/8UUuR83oUibh1wr0GMpBiHpy'
            ],

            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'role' => 'user',
                'password' => '$2y$12$TOvIGtwmqRKOXdeA09zF6e/Aw//l/8UUuR83oUibh1wr0GMpBiHpy'
            ],

        ]);
    }
}
