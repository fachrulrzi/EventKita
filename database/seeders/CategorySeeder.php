<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Musik',
                'slug' => 'musik',
                'description' => 'Event konser, festival musik, dan pertunjukan musik lainnya',
            ],
            [
                'name' => 'Seni',
                'slug' => 'seni',
                'description' => 'Pameran seni, galeri, dan event seni lainnya',
            ],
            [
                'name' => 'Kuliner',
                'slug' => 'kuliner',
                'description' => 'Festival makanan, food market, dan event kuliner lainnya',
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'description' => 'Event olahraga, turnamen, dan kompetisi',
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'description' => 'Conference, workshop, dan event teknologi',
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Seminar, workshop, dan event edukatif',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
