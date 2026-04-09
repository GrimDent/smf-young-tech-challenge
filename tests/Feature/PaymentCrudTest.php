<?php

namespace Tests\Feature;

use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_payment_for_invoice()
    {
        $invoice = Invoice::factory()->create();

        $paymentData = [
            'invoice_id' => $invoice->id,
            'amount' => 150.75,
            'method' => 'transfer',
            'due_date' => '2024-05-20',
        ];

        $response = $this->postJson('/api/payments', $paymentData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 150.75,
        ]);
    }

    public function test_cannot_create_payment_without_invoice_id()
    {
        $response = $this->postJson('/api/payments', [
            'amount' => 100,
        ]);

        $response->assertStatus(422);
    }
}
