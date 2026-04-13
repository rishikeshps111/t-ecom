<?php

namespace Database\Seeders;

use App\Models\PlannerDocument;
use Illuminate\Database\Seeder;
use App\Models\PlannerDocumentFile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlannerDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlannerDocument::factory()
            ->count(10)
            ->create()
            ->each(function ($document) {
                PlannerDocumentFile::factory()
                    ->count(rand(1, 5))
                    ->create([
                        'planner_document_id' => $document->id,
                    ]);
            });
    }
}
