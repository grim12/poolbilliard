<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_category_id' => DocumentCategory::factory(),
            'year' => 2026,
            'name' => $this->faker->sentence(3),
            'file' => 'documents/test.pdf',
            'sort_order' => 0,
        ];
    }
}
