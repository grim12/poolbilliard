<?php

namespace Database\Factories;

use App\Models\CompetitionSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompetitionSection>
 */
class CompetitionSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'anchor' => $this->faker->unique()->slug(2),
            'nav_label' => $this->faker->words(2, true),
            'eyebrow' => $this->faker->sentence(3),
            'title' => $this->faker->sentence(4),
            'body' => '<p>'.$this->faker->paragraph().'</p>',
            'sort_order' => 0,
        ];
    }
}
