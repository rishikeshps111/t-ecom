<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Location;
use App\Models\CompanyAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyAddress>
 */
class CompanyAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CompanyAddress::class;

    public function definition()
    {
        $city = Location::inRandomOrder()->first();
        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'address1' => $this->faker->streetAddress,
            'city_id' => $city->id,
            'state_id' => $city->state_id,
            'postcode' => $this->faker->postcode,
            'country' => 'Malaysia',
            'office_phone' => $this->faker->phoneNumber,
            'office_email' => $this->faker->companyEmail,
        ];
    }
}
