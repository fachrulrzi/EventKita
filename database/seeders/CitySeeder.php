<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Jakarta',
                'description' => 'Ibukota Indonesia dengan berbagai event menarik',
            ],
            [
                'name' => 'Bandung',
                'description' => 'Kota kreatif dengan banyak festival seni dan musik',
            ],
            [
                'name' => 'Surabaya',
                'description' => 'Kota pahlawan dengan event olahraga dan budaya',
            ],
            [
                'name' => 'Yogyakarta',
                'description' => 'Kota pelajar dengan event pendidikan dan budaya',
            ],
            [
                'name' => 'Semarang',
                'description' => 'Kota dengan kuliner khas dan event tradisional',
            ],
            [
                'name' => 'Medan',
                'description' => 'Kota metropolitan di Sumatera Utara',
            ],
            [
                'name' => 'Malang',
                'description' => 'Kota wisata dengan berbagai festival',
            ],
            [
                'name' => 'Makassar',
                'description' => 'Gerbang Indonesia Timur dengan event bahari',
            ],
            [
                'name' => 'Denpasar',
                'description' => 'Ibukota Bali dengan event pariwisata',
            ],
            [
                'name' => 'Mataram',
                'description' => 'Ibukota NTB dengan event budaya Sasak',
            ],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name' => $city['name']],
                ['description' => $city['description']]
            );
        }
    }
}
