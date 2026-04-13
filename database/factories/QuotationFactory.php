<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Company;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    public function definition()
    {
        $quotationDate = fake()->dateTimeBetween('-10 days', 'now');

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'customer_id' => User::inRandomOrder()->first()?->id,
            'company_id' => Company::inRandomOrder()->first()?->id,
            'contact_person' => fake()->name,
            'quotation_date' => $quotationDate,
            'validity_date' => now()->addDays(15),
            'validity_in_days' => 15,
            'sub_total' => fake()->randomFloat(2, 1000, 5000),
            'tax_total' => fake()->randomFloat(2, 100, 900),
            'discount_total' => fake()->randomFloat(2, 0, 300),
            'grant_total' => fake()->randomFloat(2, 1000, 6000),
            'payment_terms' => fake()->sentence,
            'notes' => fake()->paragraph,
            'terms' => fake()->paragraph,
            'status' => fake()->randomElement([
                'draft',
                'submitted',
                'approved',
                'rejected'
            ]),
        ];
    }

    /**
     * AFTER creating the quotation, generate quotation number
     */
    public function configure()
    {
        return $this->afterCreating(function (Quotation $quotation) {
            $quotation->update([
                'quotation_number' =>
                Quotation::generateQuotationNumber($quotation->id),
            ]);

            foreach (range(1, 4) as $level) {
                $quotation->approvals()->create([
                    'level' => $level,
                    'status' => $quotation->status === 'approved' ? 'approved' : 'pending',
                ]);
            }
        });
    }
}
