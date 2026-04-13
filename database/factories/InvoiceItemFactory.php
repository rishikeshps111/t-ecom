<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = InvoiceItem::class;

    public function definition()
    {
        $invoices = Invoice::pluck('id')->toArray();
        $items = Item::pluck('id')->toArray();

        $quantity = fake()->numberBetween(1, 10);
        $unit_price = fake()->randomFloat(2, 50, 500);
        $tax_percentage = fake()->numberBetween(0, 18);
        $tax_amount = ($unit_price * $quantity * $tax_percentage) / 100;
        $discount_amount = fake()->randomFloat(2, 0, 50);
        $total_amount = ($unit_price * $quantity) + $tax_amount - $discount_amount;

        return [
            'invoice_id'      => fake()->randomElement($invoices),
            'item_id'         => fake()->randomElement($items),
            'quantity'        => $quantity,
            'unit_price'      => $unit_price,
            'tax_percentage'  => $tax_percentage,
            'tax_amount'      => $tax_amount,
            'discount_amount' => $discount_amount,
            'total_amount'    => $total_amount,
        ];
    }
}
