<?php

namespace Database\Factories;

use App\Enum\SampleType;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sample>
 */
class SampleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'barcode' => fake()->unique()->regexify('[0-9]{12}'),
            'type' => fake()->randomElement(SampleType::cases()),
            'collected_at' => fake()->optional(0.9)->dateTimeBetween('-1 year'),
        ];
    }
}
