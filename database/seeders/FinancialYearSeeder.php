<?php

namespace Database\Seeders;

use App\Models\FinancialYear;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FinancialYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($year = 2005; $year <= 2026; $year++) {
            FinancialYear::updateOrCreate(
                ['year' => (string) $year],
                [
                    'is_active' => $year === date('Y') ? 1 : 0
                ]
            );
        }
    }
}
