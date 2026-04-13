<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Project::class;

    public function definition()
    {
        return [
            'project_category_id' => ProjectCategory::factory(),
            'company_id'          => Company::factory(),
            'name'                => fake()->sentence(3),
            'description'         => fake()->paragraph(),
            'start_date'          => fake()->date(),
            'end_date'            => fake()->optional()->date(),
            'status'              => fake()->randomElement([
                'open',
                'in_progress',
                'completed',
                'on_hold'
            ]),
        ];
    }
}
