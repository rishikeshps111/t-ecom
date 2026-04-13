<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\CompanyDirector;
use Illuminate\Database\Seeder;
use App\Models\CompanyShareholder;

class CompanyFullSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory(5)->create()->each(function ($company) {

            // Company user
            User::factory()->create([
                'company_id' => $company->id,
                'role' => 'Company User',
            ]);

            // Address
            CompanyAddress::factory()->create([
                'company_id' => $company->id,
            ]);

            // Directors
            CompanyDirector::factory(2)->create([
                'company_id' => $company->id,
            ]);

            // Shareholders
            CompanyShareholder::factory(2)->create([
                'company_id' => $company->id,
            ]);
        });
    }
}
