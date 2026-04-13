<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'user_id' => User::role('Customer')->inRandomOrder()->value('id'),
            'subject' => $this->faker->sentence(5),
            'priority' => fake()->randomElement(['high', 'medium', 'low'])
        ];
    }
}
