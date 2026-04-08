<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ContractorController extends Controller
{
    #[OA\Get(
        path: '/api/contractors',
        summary: 'Pobierz listę kontrahentów',
        tags: ['Contractors'],
        responses: [
            new OA\Response(response: 200, description: 'Lista pobrana pomyślnie'),
        ]
    )]
    public function index()
    {
        return response()->json(Contractor::all());
    }

    #[OA\Post(
        path: '/api/contractors',
        summary: 'Utwórz nowego kontrahenta',
        tags: ['Contractors'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Jan Kowalski'),
                    new OA\Property(property: 'address', type: 'string', example: 'ul. Prosta 1, Warszawa'),
                    new OA\Property(property: 'tax_id', type: 'string', example: '1234567890'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Kontrahent utworzony'),
            new OA\Response(response: 422, description: 'Błąd walidacji'),
        ]
    )]
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
        ]);

        return response()->json(Contractor::create($validatedData), 201);
    }

    #[OA\Get(
        path: '/api/contractors/{id}',
        summary: 'Pobierz konkretnego kontrahenta',
        tags: ['Contractors'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Sukces'),
            new OA\Response(response: 404, description: 'Nie znaleziono'),
        ]
    )]
    public function show(string $id)
    {
        return response()->json(Contractor::findOrFail($id));
    }

    #[OA\Put(
        path: '/api/contractors/{id}',
        summary: 'Aktualizuj kontrahenta',
        tags: ['Contractors'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Nowa Nazwa'),
                    new OA\Property(property: 'address', type: 'string', example: 'Nowy Adres'),
                    new OA\Property(property: 'tax_id', type: 'string', example: '0987654321'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Zaktualizowano'),
            new OA\Response(response: 404, description: 'Nie znaleziono'),
        ]
    )]
    public function update(Request $request, string $id)
    {
        $contractor = Contractor::findOrFail($id);
        $contractor->update($request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
        ]));

        return response()->json($contractor);
    }

    #[OA\Delete(
        path: '/api/contractors/{id}',
        summary: 'Usuń kontrahenta',
        tags: ['Contractors'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Usunięto'),
            new OA\Response(response: 404, description: 'Nie znaleziono'),
        ]
    )]
    public function destroy(string $id)
    {
        Contractor::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
