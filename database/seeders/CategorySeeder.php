<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Zarzou',
                'slug' => 'zarzou',
                'status' => 1,
                'show_at_home' => 1
            ],
            [
                'name' => 'Mimpimika',
                'slug' => 'mimpimika',
                'status' => 1,
                'show_at_home' => 1
            ]
            ]);
    }
}
