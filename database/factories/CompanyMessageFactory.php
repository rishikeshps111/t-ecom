<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyMessage>
 */
class CompanyMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::inRandomOrder()->value('id'), // Random existing user
            'subject' => $this->faker->sentence(5),
            'message' => $this->faker->paragraph(2),
            'priority' => fake()->randomElement(['high', 'medium', 'low'])
        ];
    }
}
