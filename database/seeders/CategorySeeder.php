<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Sites web & vitrines',
            'Applications web',
            'Branding & identité visuelle',
            'Marketing digital',
            'Community management',
            'E-commerce',
            'Solutions sur mesure',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $name,
                    'is_active' => true,
                ]
            );
        }
    }
}