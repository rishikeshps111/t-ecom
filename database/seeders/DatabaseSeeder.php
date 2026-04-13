<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
            CategorySeeder::class,
            StateSeeder::class,
            LocationSeeder::class,
            UserSeeder::class,
            PrefixSeeder::class,
            CompanyFullSeeder::class,
            ItemSeeder::class,
            QuotationSeeder::class,
            CustomerSeeder::class,
            InvoiceSeeder::class,
            ProjectCategorySeeder::class,
            ProjectSeeder::class,
            DocumentSeeder::class,
            FaqSeeder::class,
            PlannerDocumentSeeder::class,
            CustomerUserSeeder::class,
            MessageSeeder::class,
            AnnouncementSeeder::class,
            FinancialYearSeeder::class,
            WorkPlanSeeder::class
        ]);
    }
}
