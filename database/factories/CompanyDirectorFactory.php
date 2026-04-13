<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyDirector;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyDirector>
 */
class CompanyDirectorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CompanyDirector::class;

    public function definition()
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'name' => $this->faker->name,
            'identification_type' => 'Passport',
            'identification_number' => strtoupper($this->faker->bothify('??######')),
            'nationality' => 'Malaysian',
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-30 years'),
            'email' => $this->faker->safeEmail,
            'mobile' => $this->faker->phoneNumber,
            'position' => 'Director',
            'appointment_date' => now()->subYears(3),
        ];
    }
}
