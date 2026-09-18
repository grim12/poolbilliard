<?php

namespace Database\Factories;

use App\Models\RecurringTournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecurringTournament>
 */
class RecurringTournamentFactory extends Factory
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
            'frequency' => 'Každou středu, 19:00',
            'location_text' => $this->faker->city(),
            'herna_id' => null,
            'url' => null,
            'sort_order' => 0,
        ];
    }
}
