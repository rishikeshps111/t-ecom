<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::factory(10)->create()->each(function ($invoice) {
            // Each invoice has 2-5 items
            InvoiceItem::factory(rand(2, 5))->create([
                'invoice_id' => $invoice->id
            ]);
        });
    }
}
