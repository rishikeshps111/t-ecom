<?php

namespace Database\Factories;

use App\Models\ChatCategory;
use App\Models\KnowledgeBase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KnowledgeBase>
 */
class KnowledgeBaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KnowledgeBase::class;

    public function definition()
    {
        return [
            'chat_category_id' => ChatCategory::inRandomOrder()->first()?->id ?? ChatCategory::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(2, true),
            'keywords' => fake()->words(5),
            'status' => fake()->randomElement(['draft', 'published', 'unpublished']),
        ];
    }
}
