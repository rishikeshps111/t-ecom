<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\BillerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BillerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Customer::inRandomOrder()->first();
        $company = Company::inRandomOrder()->first();

        if (!$company || !$customer) {
            return;
        }

        BillerProfile::create([
            'company_id' => $company->id,
            'total_group_id'   => $customer->id,
            'invoice_header'   => 'Thank you for choosing our business',
            'invoice_footer'   => 'System generated invoice',
            'quotation_header' => 'Quotation Details',
            'quotation_footer' => 'Valid for 15 days',
            'receipt_header'   => 'Payment Receipt',
            'receipt_footer'   => 'No signature required',
        ]);
    }
}
