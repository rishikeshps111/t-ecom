<?php

namespace Database\Factories;

use App\Models\ChatCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatCategory>
 */
class ChatCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ChatCategory::class;

    public function definition()
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(90),
        ];
    }
}
