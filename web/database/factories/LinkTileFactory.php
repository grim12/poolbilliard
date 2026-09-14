<?php

namespace Database\Factories;

use App\Models\LinkTile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LinkTile>
 */
class LinkTileFactory extends Factory
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
            'url' => '/'.$this->faker->slug(),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
