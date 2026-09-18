<?php

namespace Database\Factories;

use App\Models\Myth;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Myth>
 */
class MythFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'myth_text' => $this->faker->sentence(),
            'correct_text' => '<p>'.$this->faker->sentence().'</p>',
            'sort_order' => 0,
        ];
    }
}
