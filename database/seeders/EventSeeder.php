<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Konser Musik Indie - event yang penting
        $musikCategory = Category::where('slug', 'musik')->first();
        $jakartaCity = \App\Models\City::where('slug', 'jakarta')->first();
        
        if ($musikCategory && $jakartaCity) {
            Event::updateOrCreate(
                ['slug' => 'konser-nada-senja'],
                [
                    'title' => 'Konser "Nada Senja"',
                    'category_id' => $musikCategory->id,
                    'city_id' => $jakartaCity->id,
                    'description' => 'Konser musik indie terbesar akhir tahun dengan tata panggung immersive, kolaborasi lintas musisi, dan experience booth dari brand lokal.',
                    'location' => 'Lapangan Banteng',
                    'date' => '2025-12-05',
                    'time_start' => '19:00',
                    'time_end' => '23:30',
                    'price_min' => 75000,
                    'price_max' => 250000,
                    'is_featured' => true,
                    'status' => 'published',
                    'tags' => '#Indie #Festival #Outdoor',
                    'website_url' => 'https://www.synchronizefestival.com/',
                ]
            );
        }
    }
}
