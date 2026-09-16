<?php

namespace Database\Factories;

use App\Models\JakZacitSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JakZacitSection>
 */
class JakZacitSectionFactory extends Factory
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
            'intro' => '<p>'.$this->faker->paragraph().'</p>',
            'steps' => [
                ['icon' => 'map-pin', 'title' => '1. '.$this->faker->sentence(3), 'text' => '<p>'.$this->faker->sentence().'</p>'],
            ],
            'aside_panel_title' => $this->faker->sentence(2),
            'aside_panel_text' => $this->faker->sentence(),
            'aside_panel_button_text' => $this->faker->words(2, true),
            'aside_panel_button_url' => '/herny/',
            'aside_card_eyebrow' => $this->faker->sentence(2),
            'aside_card_title' => $this->faker->sentence(3),
            'aside_card_text' => $this->faker->sentence(),
            'aside_card_button_text' => $this->faker->words(2, true),
            'aside_card_button_url' => '/pravidla/',
            'faq_title' => $this->faker->sentence(3),
            'sort_order' => 0,
        ];
    }
}
