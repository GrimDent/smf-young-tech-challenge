<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PaymentController extends Controller
{
    #[OA\Get(
        path: '/api/payments',
        summary: 'Pobierz listę płatności',
        tags: ['Payments'],
        responses: [new OA\Response(response: 200, description: 'Sukces')]
    )]
    public function index()
    {
        return response()->json(Payment::with('invoice')->get());
    }

    #[OA\Post(
        path: '/api/payments',
        summary: 'Zarejestruj płatność',
        tags: ['Payments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['invoice_id', 'amount', 'method', 'due_date'],
                properties: [
                    new OA\Property(property: 'invoice_id', type: 'integer', example: 1),
                    new OA\Property(property: 'amount', type: 'number', example: 100.00),
                    new OA\Property(property: 'method', type: 'string', example: 'transfer'),
                    new OA\Property(property: 'due_date', type: 'string', format: 'date', example: '2024-02-01'),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Zarejestrowano')]
    )]
    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'due_date' => 'required|date',
        ]);

        return response()->json(Payment::create($data), 201);
    }
}
