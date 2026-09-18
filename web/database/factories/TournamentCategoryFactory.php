<?php

namespace Database\Factories;

use App\Models\TournamentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TournamentCategory>
 */
class TournamentCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'color' => 'primary',
            'sort_order' => 0,
        ];
    }
}
