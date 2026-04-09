<?php

namespace Database\Factories;

use App\Models\Contractor;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contractor_id' => Contractor::factory(),
            'invoice_number' => 'FV/'.$this->faker->unique()->numberBetween(1000, 9999),
            'total_amount' => $this->faker->randomFloat(2, 100, 5000),
            'currency' => 'PLN',
            'issue_date' => now()->format('Y-m-d'),
            'file_path' => 'invoices/fake.pdf',
        ];
    }
}
