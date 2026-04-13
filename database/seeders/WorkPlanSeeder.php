<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\WorkPlan;
use App\Models\CompanyType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['approved', 'pending', 'cancelled'];

        for ($i = 1; $i <= 10; $i++) {
            WorkPlan::create([
                'company_id'       => Company::inRandomOrder()->value('id'),
                'company_type_id'  => CompanyType::inRandomOrder()->value('id'),
                'total_group_id'   => Customer::inRandomOrder()->value('id'),
                'planner_id'       => User::role('Planner')->inRandomOrder()->value('id'),
                'workplan_number'  => 'WP2026' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'date'             => now()->subDays(rand(0, 30)),
                'description'      => 'Work plan description ' . $i,
                'status'           => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
