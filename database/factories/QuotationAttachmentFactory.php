<?php

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\QuotationAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationAttachment>
 */
class QuotationAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = QuotationAttachment::class;

    public function definition(): array
    {
        return [
            'quotation_id' => Quotation::inRandomOrder()->first()?->id,
            'file' => 'uploads/quotations/' . fake()->uuid . '.pdf',
            'alt' => fake()->sentence(3),
        ];
    }
}
