<?php

namespace Database\Factories;

use App\Models\RuleCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RuleCard>
 */
class RuleCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'subtitle' => $this->faker->words(3, true),
            'text' => $this->faker->paragraph(),
            'icon' => 'book-open',
            'button_url' => '#',
            'sort_order' => 0,
        ];
    }
}
