<?php

namespace Database\Factories;

use App\Enums\BannerColor;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'text' => '<p>'.$this->faker->paragraph().'</p>',
            'tag_text' => $this->faker->boolean() ? $this->faker->words(2, true) : null,
            'meta_text' => $this->faker->boolean() ? $this->faker->words(4, true) : null,
            'color' => $this->faker->randomElement(BannerColor::cases()),
            'buttons' => [
                ['text' => 'Detail akce', 'url' => '#', 'variant' => 'solid'],
            ],
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
