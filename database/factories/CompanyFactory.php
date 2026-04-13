<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Company::class;

    public function definition()
    {
        return [
            'category_id' => 1, // make sure categories exist
            'sub_category_id' => 1,
            'company_code' => strtoupper($this->faker->bothify('??')),
            'company_type' => $this->faker->randomElement(['SDN BHD', 'LLP']),
            'company_name' => $this->faker->company,
            'industry' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'status' => 'active',
            'ssm_number' => $this->faker->numerify('##########'),
            'incorporation_date' => now()->subYears(5),
            'commencement_date' => now()->subYears(4),
            'paid_up_capital' => 100000,
            'authorized_capital' => 500000,
            'employees' => rand(5, 100),
            'primary_contact_name' => $this->faker->name,
            'designation' => 'Manager',
            'mobile_no' => $this->faker->phoneNumber,
            'email_address' => $this->faker->companyEmail,
            'company_website' => $this->faker->url,
        ];
    }
}
