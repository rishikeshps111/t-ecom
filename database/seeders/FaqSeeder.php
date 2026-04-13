<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\ChatCategory;
use App\Models\KnowledgeBase;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChatCategory::factory(5)->create();
        KnowledgeBase::factory(20)->create();
        Faq::factory(10)->create();
    }
}
