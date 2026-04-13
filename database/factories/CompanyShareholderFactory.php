<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyShareholder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyShareholder>
 */
class CompanyShareholderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CompanyShareholder::class;

    public function definition()
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'type' => $this->faker->randomElement(['individual', 'corporate']),
            'name' => $this->faker->name,
            'identification' => strtoupper($this->faker->bothify('??######')),
            'nationality' => 'Malaysian',
            'shares' => rand(100, 10000),
            'ownership' => rand(10, 100) . '%',
            'share_class' => 'Ordinary',
        ];
    }
}
