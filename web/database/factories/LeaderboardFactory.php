<?php

namespace Database\Factories;

use App\Models\Leaderboard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Leaderboard>
 */
class LeaderboardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'featured' => false,
            'entries' => collect(range(1, 10))->map(fn () => [
                'name' => $this->faker->name(),
                'club' => $this->faker->company(),
            ])->all(),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
