<?php

namespace Database\Factories;

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
            'text' => $this->faker->paragraph(),
            'tag_text' => $this->faker->boolean() ? $this->faker->words(2, true) : null,
            'meta_text' => $this->faker->boolean() ? $this->faker->words(4, true) : null,
            'button_text' => 'Detail akce',
            'button_url' => '#',
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
