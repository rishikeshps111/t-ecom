<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Invoice::class;

    public function definition()
    {
        $users = User::pluck('id')->toArray();
        $customers = Customer::pluck('id')->toArray();
        $grantTotal = fake()->randomFloat(2, 100, 1200);

        return [
            'created_by'     => fake()->randomElement($users),
            'customer_id'    => fake()->randomElement($customers),
            'company_id' => Company::inRandomOrder()->first()?->id,
            'quotation_id' => Quotation::where('status', 'accepted')->inRandomOrder()->first()?->id ?? null,
            'invoice_date'   => fake()->date(),
            'due_date'       => fake()->date(),
            'invoice_number' => 'INV-' . fake()->unique()->numerify('#####'),
            'payment_terms'  => fake()->randomElement(['Net 7', 'Net 15', 'Net 30']),
            'currency'       => fake()->randomElement(['INR', 'MYR', 'USD']),
            'sub_total'      => fake()->randomFloat(2, 100, 1000),
            'tax_total'      => fake()->randomFloat(2, 10, 100),
            'discount_total' => fake()->randomFloat(2, 5, 50),
            'grant_total'    => $grantTotal,
            'balance_amount'    => $grantTotal,
            'status'         => fake()->randomElement(['draft', 'submitted', 'approved', 'rejected']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Invoice $invoice) {
            $invoice->update([
                'invoice_number' =>
                Invoice::generateInvoiceNumber($invoice->id),
            ]);

            foreach (range(1, 4) as $level) {
                $invoice->approvals()->create([
                    'level' => $level,
                    'status' => $invoice->status === 'approved' ? 'approved' : 'pending',
                ]);
            }
        });
    }
}
