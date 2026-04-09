<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiAgentService
{
    protected $model = 'llama3';

    public function processInvoiceText(string $rawText): array
    {
        $prompt = "Jesteś asystentem księgowym. Z poniższego tekstu z OCR faktury wyodrębnij dane:
        1. nip (tylko cyfry),
        2. vendor_name (pełna nazwa firmy sprzedawcy),
        3. number (numer faktury),
        4. total (kwota brutto jako liczba),
        5. date (data wystawienia RRRR-MM-DD),
        6. currency (waluta, np. PLN, EUR).

        Zwróć TYLKO czysty JSON.
        Format: {\"nip\": \"\", \"vendor_name\": \"\", \"number\": \"\", \"total\": 0.00, \"date\": \"\", \"currency\": \"\"}

        Tekst faktury:
        {$rawText}";

        try {
            $response = Http::timeout(30)->post('http://localhost:11434/api/generate', [
                'model' => $this->model,
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json',
            ]);

            return json_decode($response->json('response'), true) ?? [];
        } catch (\Exception $e) {
            return ['error' => 'Model AI nie odpowiada. Sprawdź czy Ollama działa.'];
        }
    }
}
