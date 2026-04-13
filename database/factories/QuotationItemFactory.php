<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationItem>
 */
class QuotationItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = QuotationItem::class;

    public function definition()
    {
        $qty = fake()->numberBetween(1, 10);
        $unitPrice = fake()->randomFloat(2, 100, 1000);
        $taxPercent = fake()->randomElement([5, 12, 18]);

        $subTotal = $qty * $unitPrice;
        $taxAmount = ($subTotal * $taxPercent) / 100;

        return [
            'quotation_id' => Quotation::inRandomOrder()->first()?->id,
            'item_id' => Item::inRandomOrder()->first()?->id,
            'quantity' => $qty,
            'tax_percentage' => $taxPercent,
            'unit_price' => $unitPrice,
            'tax_amount' => $taxAmount,
            'discount_amount' => fake()->randomFloat(2, 0, 100),
            'total_amount' => $subTotal + $taxAmount,
        ];
    }
}
