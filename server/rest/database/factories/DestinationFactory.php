<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

class DestinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Destination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $destinations = [
            'Paris, France',
            'Bali, Indonesia',
            'Santorini, Greece',
            'Maldives',
            'Venice, Italy',
            'Kyoto, Japan',
            'Hawaii, USA',
            'Bora Bora, French Polynesia',
            'Amalfi Coast, Italy',
            'Seychelles'
        ];

        return [
            'name' => $this->faker->randomElement($destinations),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(1000, 10000),
            'duration' => $this->faker->numberBetween(5, 14),
            'image' => $this->faker->imageUrl(800, 600, 'travel'),
        ];
    }
}
