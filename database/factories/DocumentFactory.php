<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Project;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Document::class;

    public function definition()
    {
        // $type = fake()->randomElement(['general', 'planner']);

        return [
            // 'company_id'    => $type === 'general'
            //     ? Company::factory()
            //     : null,
            // 'project_id'    => $type === 'planner'
            //     ? Project::factory()
            //     : null,
            'company_id' => Company::inRandomOrder()->value('id'),
            'title'         => fake()->sentence(1),
            'type'          => 'general',
            'document_type' => fake()->randomElement(['pdf', 'word', 'image']),
            'document'      => fake()->uuid . '.pdf',
            'valid_from'    => fake()->optional()->date(),
            'valid_to'      => fake()->optional()->date(),
            'status'        => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
