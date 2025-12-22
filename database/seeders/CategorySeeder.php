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
                'icon_path' => 'https://img.icons8.com/fluency/96/music.png',
            ],
            [
                'name' => 'Seni',
                'slug' => 'seni',
                'description' => 'Pameran seni, galeri, dan event seni lainnya',
                'icon_path' => 'https://img.icons8.com/fluency/96/paint-palette.png',
            ],
            [
                'name' => 'Kuliner',
                'slug' => 'kuliner',
                'description' => 'Festival makanan, food market, dan event kuliner lainnya',
                'icon_path' => 'https://img.icons8.com/fluency/96/restaurant.png',
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'description' => 'Event olahraga, turnamen, dan kompetisi',
                'icon_path' => 'https://img.icons8.com/fluency/96/sports-mode.png',
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'description' => 'Conference, workshop, dan event teknologi',
                'icon_path' => 'https://img.icons8.com/fluency/96/laptop.png',
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Seminar, workshop, dan event edukatif',
                'icon_path' => 'https://img.icons8.com/fluency/96/education.png',
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
