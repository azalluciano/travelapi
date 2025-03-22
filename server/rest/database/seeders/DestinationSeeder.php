<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Paris',
                'description' => '3 nights in an hotel',
                'price' => 100,
                'duration' => 7,
                'image' => 'paris.jpg'
            ],
            [
                'name' => 'Tunis',
                'description' => '10 in a villa with a swimming pool',
                'price' => 200,
                'duration' => 17,
                'image' => 'tunis.jpg'
            ],
            [
                'name' => 'Rome',
                'description' => '5 nights in a luxury hotel near the Colosseum',
                'price' => 150,
                'duration' => 5,
                'image' => 'rome.jpg'
            ],
            [
                'name' => 'Bali',
                'description' => '14 nights in a beach resort with all inclusive',
                'price' => 350,
                'duration' => 14,
                'image' => 'bali.jpg'
            ],
            [
                'name' => 'New York',
                'description' => '7 nights in Manhattan with Broadway show tickets',
                'price' => 450,
                'duration' => 7,
                'image' => 'newyork.jpg'
            ]
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}