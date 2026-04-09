<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiAgentService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use thiagoalessio\TesseractOCR\TesseractOCR;

class InvoiceUploadController extends Controller
{
    #[OA\Post(
        path: '/api/invoices/upload',
        summary: 'Prześlij plik faktury do przetworzenia OCR',
        tags: ['Invoices'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'invoice_file', type: 'string', format: 'binary', description: 'Plik obrazu (jpg, png) lub PDF'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Tekst został wyodrębniony pomyślnie'),
            new OA\Response(response: 400, description: 'Błąd pliku'),
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
            $ocr = new TesseractOCR($fullPath);
            $ocr->executable('C:\Program Files\Tesseract-OCR\tesseract.exe');
            $rawText = $ocr->run();

            // return response()->json([
            //     'message' => 'Plik zapisany i przetworzony',
            //     'file_path' => $path,
            //     'extracted_text' => $rawText,
            // ]);
            $extractedData = $aiAgent->processInvoiceText($rawText);

            return response()->json([
                'message' => 'Faktura przetworzona przez Agenta AI',
                'file_path' => $path,
                'ai_extracted_data' => $extractedData,
                'raw_text_preview' => substr($rawText, 0, 200).'...',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Błąd OCR: '.$e->getMessage(),
                'file_path' => $path,
            ], 500);
        }
    }
}
