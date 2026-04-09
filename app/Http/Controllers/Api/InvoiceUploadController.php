<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\Invoice;
use App\Services\AiAgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;
use thiagoalessio\TesseractOCR\TesseractOCR;

class InvoiceUploadController extends Controller
{
    #[OA\Post(
        path: '/api/invoices/upload',
        summary: 'Prześlij plik faktury do przetworzenia przez OCR i AI oraz zapisz w bazie',
        tags: ['Invoices'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'invoice_file',
                            type: 'string',
                            format: 'binary',
                            description: 'Plik obrazu (jpg, png) lub PDF'
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Faktura została przetworzona i zapisana pomyślnie'
            ),
            new OA\Response(
                response: 400,
                description: 'Nieprawidłowy plik lub błąd walidacji'
            ),
            new OA\Response(
                response: 500,
                description: 'Błąd procesowania OCR/AI lub błąd bazy danych'
            ),
        ]
    )]
    public function upload(Request $request, AiAgentService $aiAgent)
    {
        $request->validate([
            'invoice_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('invoice_file')->store('invoices', 'public');
        $fullPath = storage_path('app/public/'.$path);

        try {
            if (app()->environment('testing')) {
                $rawText = 'Tekst testowy z faktury NIP 1234567890';
            } else {
                $ocr = new TesseractOCR($fullPath);
                $rawText = $ocr->run();
            }

            $extractedData = $aiAgent->processInvoiceText($rawText);

            $invoice = DB::transaction(function () use ($extractedData, $path, $rawText) {
                $contractor = Contractor::firstOrCreate(
                    ['tax_id' => $extractedData['nip'] ?? '0000000000'],
                    ['name' => $extractedData['vendor_name'] ?? 'Nieznany Kontrahent', 'address' => 'Dane do uzupełnienia']
                );

                return Invoice::create([
                    'contractor_id' => $contractor->id,
                    'invoice_number' => $extractedData['number'] ?? 'BRAK_'.time(),
                    'total_amount' => $extractedData['total'] ?? 0,
                    'currency' => $extractedData['currency'] ?? 'PLN',
                    'issue_date' => $extractedData['date'] ?? now()->format('Y-m-d'),
                    'file_path' => $path,
                    'raw_text' => $rawText,
                ]);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Faktura została przetworzona i zapisana pomyślnie',
                'data' => [
                    'invoice' => $invoice->load('contractor'), // Ładujemy relację kontrahenta
                    'ai_raw_output' => $extractedData,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Błąd OCR: '.$e->getMessage(),
                'file_path' => $path,
            ], 500);
        }
    }
}
