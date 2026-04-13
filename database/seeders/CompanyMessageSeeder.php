<?php

namespace Database\Seeders;

use App\Models\CompanyMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyMessage::factory()->count(20)->create();
    }
}
