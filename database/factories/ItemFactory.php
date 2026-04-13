<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $company = Company::inRandomOrder()->first();
        $category = Category::inRandomOrder()->first();
        $subCategory = SubCategory::inRandomOrder()->first();
        $adjectives = ['Premium', 'Advanced', 'Deluxe', 'Standard', 'Eco', 'Pro', 'Ultra', 'Compact'];
        $nouns = ['Laptop', 'Chair', 'Table', 'Shampoo', 'Monitor', 'Printer', 'Notebook', 'Bottle', 'Keyboard', 'Bag'];

        $itemName = $this->faker->randomElement($adjectives) . ' ' . $this->faker->randomElement($nouns);
        return [
            'item_code' => 'ITM-' . strtoupper($this->faker->bothify('###??')),
            'item_name' =>  $itemName,
            'user_id' => $user?->id,
            'company_id' => $company?->id,
            'category_id' => $category?->id,
            'sub_category_id' => $subCategory?->id,
            'item_type' => $this->faker->randomElement(['Product', 'Service']),
            'status' => 1,

            // Pricing
            'selling_price' => $this->faker->randomFloat(2, 50, 500),
            'cost_price' => $this->faker->randomFloat(2, 20, 300),
            'commission_factor' => $this->faker->randomFloat(2, 1, 10),
            'tax_group' => $this->faker->word,

            // Inventory
            'uom' => $this->faker->randomElement(['pcs', 'kg', 'ltr']),
            'opening_stock' => $this->faker->numberBetween(10, 100),
            'reorder_level' => $this->faker->numberBetween(5, 20),
            'safety_stock' => $this->faker->numberBetween(1, 10),

            // Supplier
            'default_supplier' => $this->faker->company,
            'supplier_item' => $this->faker->word,
            'purchase_price' => $this->faker->randomFloat(2, 30, 250),

            // Logistics
            'warehouse' => $this->faker->word,
            'bin_location' => $this->faker->bothify('BIN-##??'),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),

            // Description
            'short_description' => $this->faker->sentence,
            'detail_description' => $this->faker->paragraph,
        ];
    }
}
