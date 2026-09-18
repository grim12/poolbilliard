<?php

namespace Database\Factories;

use App\Enums\HernaStatus;
use App\Enums\Region;
use App\Models\Herna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Herna>
 */
class HernaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Herna '.$this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'region' => $this->faker->randomElement(Region::cases()),
            'status' => HernaStatus::Approved,
        ];
    }
}
