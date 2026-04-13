<?php

namespace Database\Seeders;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Database\Seeder;
use App\Models\QuotationAttachment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Quotation::factory(10)->create()->each(function ($quotation) {

            // Items
            QuotationItem::factory(rand(2, 5))->create([
                'quotation_id' => $quotation->id,
            ]);

            // Attachments
            QuotationAttachment::factory(rand(1, 3))->create([
                'quotation_id' => $quotation->id,
            ]);
        });
    }
}
