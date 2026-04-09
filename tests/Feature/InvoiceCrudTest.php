<?php

namespace Tests\Feature;

use App\Models\Contractor;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_invoices()
    {
        Invoice::factory()->count(3)->create();

        $response = $this->getJson('/api/invoices');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_show_single_invoice_details()
    {
        $invoice = Invoice::factory()->create();

        $response = $this->getJson("/api/invoices/{$invoice->id}");

        $response->assertStatus(200)
            ->assertJsonPath('invoice_number', $invoice->invoice_number);
    }

    public function test_can_get_all_invoices()
    {
        Invoice::factory()->count(3)->create();

        $this->getJson('/api/invoices')
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_invoice_manually()
    {
        $contractor = Contractor::factory()->create();

        $data = [
            'contractor_id' => $contractor->id,
            'invoice_number' => 'INV-2024-001',
            'total_amount' => 1230.50,
            'currency' => 'PLN',
            'issue_date' => '2024-01-01',
            'file_path' => 'manual/test_invoice.pdf',
        ];

        $this->postJson('/api/invoices', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('invoices', ['invoice_number' => 'INV-2024-001']);
    }
}
