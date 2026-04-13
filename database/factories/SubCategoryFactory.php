<?php

namespace Database\Factories;

use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubCategoryFactory extends Factory
{
    protected $model = SubCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'code'        => 'SUB_' . strtoupper(Str::slug($name, '_')) . '_' . fake()->unique()->numberBetween(100, 999),
            'name'        => ucwords($name),
            'category_id' => Category::inRandomOrder()->first()->id,
            'status'      => fake()->boolean(90),
        ];
    }
}
