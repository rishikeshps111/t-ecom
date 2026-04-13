<?php

namespace Database\Factories;

use App\Models\PlannerDocument;
use App\Models\PlannerDocumentFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlannerDocumentFile>
 */
class PlannerDocumentFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PlannerDocumentFile::class;

    public function definition(): array
    {
        return [
            'planner_document_id' => PlannerDocument::inRandomOrder()->value('id'),
            'document' => 'documents/' . $this->faker->uuid . '.pdf',
        ];
    }
}
