<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\PlannerDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlannerDocument>
 */
class PlannerDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PlannerDocument::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::inRandomOrder()->value('id'),
            'title' => $this->faker->sentence(3),
            'start_date' => $this->faker->optional()->date(),
            'end_date' => $this->faker->optional()->date(),
            'description' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
