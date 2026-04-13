<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Models\Location;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'city_id' => Location::inRandomOrder()->value('id'),
            'state_id' => State::inRandomOrder()->value('id'),
            'company_id' => Company::inRandomOrder()->value('id'),
            'customer_code' => 'CUST-' . strtoupper(fake()->bothify('###??')),
            'customer_name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'phone' => fake()->numerify('9#########'),
            'alternate_phone' => fake()->numerify('8#########'),
            'country' => 'malaysia',
            'billing_address' => $billing = fake()->address,
            'shipping_address' => fake()->boolean(70) ? $billing : fake()->address,
            'gst' => fake()->optional()->bothify('##AAAAA####A#Z#'),
            'tax_id' => fake()->optional()->numerify('##########'),
            'is_active' => true,
        ];
    }
}
