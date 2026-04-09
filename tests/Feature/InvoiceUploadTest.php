<?php

namespace Tests\Feature;

use App\Services\AiAgentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class InvoiceUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_upload_creates_contractor_and_invoice()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('faktura.jpg');

        $response = $this->postJson('/api/invoices/upload', [
            'invoice_file' => $file,
        ]);
        $response->assertStatus(201);

        $this->assertDatabaseCount('contractors', 1);

        $this->assertDatabaseCount('invoices', 1);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'invoice' => ['id', 'invoice_number', 'total_amount', 'contractor'],
            ],
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(AiAgentService::class, function (MockInterface $mock) {
            $mock->shouldReceive('processInvoiceText')
                ->andReturn([
                    'nip' => '1234567890',
                    'vendor_name' => 'Testowa Firma',
                    'number' => 'FV/TEST/001',
                    'total' => 100.50,
                    'date' => '2024-01-01',
                    'currency' => 'PLN',
                ]);
        });
    }
}
