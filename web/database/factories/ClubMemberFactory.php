<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\ClubMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClubMember>
 */
class ClubMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'club_id' => Club::factory(),
            'name' => $this->faker->name(),
            'sort_order' => 0,
        ];
    }
}
