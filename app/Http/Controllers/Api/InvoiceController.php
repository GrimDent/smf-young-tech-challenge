<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class InvoiceController extends Controller
{
    #[OA\Get(
        path: '/api/invoices',
        summary: 'Pobierz listę faktur',
        tags: ['Invoices'],
        responses: [new OA\Response(response: 200, description: 'Sukces')]
    )]
    public function index()
    {
        return response()->json(Invoice::with('contractor')->get());
    }

    #[OA\Post(
        path: '/api/invoices',
        summary: 'Utwórz nową fakturę',
        tags: ['Invoices'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['contractor_id', 'invoice_number', 'total_amount', 'currency', 'issue_date'],
                properties: [
                    new OA\Property(property: 'contractor_id', type: 'integer', example: 1),
                    new OA\Property(property: 'invoice_number', type: 'string', example: 'FV/2024/001'),
                    new OA\Property(property: 'issue_date', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'total_amount', type: 'number', example: 123.45),
                    new OA\Property(property: 'currency', type: 'string', example: 'PLN'),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Utworzono')]
    )]
    public function store(Request $request)
    {
        $data = $request->validate([
            'contractor_id' => 'required|exists:contractors,id',
            'invoice_number' => 'required|string',
            'total_amount' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'issue_date' => 'required|date',
            'file_path' => 'nullable|string',
        ]);

        return response()->json(Invoice::create($data), 201);
    }

    #[OA\Delete(
        path: '/api/invoices/{id}',
        summary: 'Usuń fakturę',
        tags: ['Invoices'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [new OA\Response(response: 204, description: 'Usunięto')]
    )]
    public function destroy(string $id)
    {
        Invoice::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
