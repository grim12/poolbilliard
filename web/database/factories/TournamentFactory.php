<?php

namespace Database\Factories;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tournament>
 */
class TournamentFactory extends Factory
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
            'url' => '#',
            'tag_text' => 'ČMBS',
            'tag_color' => 'primary',
            'date_text' => $this->faker->date(),
            'start_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'location_text' => $this->faker->city(),
            'badge' => false,
            'sort_order' => 0,
        ];
    }
}
