<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiAgentService
{
    protected $model = 'llama3';

    public function processInvoiceText(string $rawText): array
    {
        $prompt = "Jesteś asystentem księgowym. Z poniższego tekstu z OCR faktury wyodrębnij dane:
        1. NIP wystawcy (tylko cyfry),
        2. Numer faktury,
        3. Kwota brutto (jako liczba),
        4. Data wystawienia (format RRRR-MM-DD).
        Zwróć TYLKO czysty JSON bez żadnego dodatkowego tekstu.
        Format: {\"nip\": \"\", \"number\": \"\", \"total\": 0.00, \"date\": \"\"}

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
