<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'code'   => strtoupper(Str::slug($name, '_')) . '_' . fake()->unique()->numberBetween(100, 999),
            'name'   => ucwords($name),
            'status' => fake()->boolean,
        ];
    }
}
