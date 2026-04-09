# Young Tech Challenge 🚀

System do zarządzania fakturami zintegrowany z Agentem AI, który automatycznie wyciąga dane z dokumentów za pomocą OCR (Tesseract) i modelu językowego Llama 3 (Ollama).

## 🛠 Funkcjonalności
- **OCR & AI Extraction**: Przesyłanie skanów faktur (JPG, PNG, PDF) i automatyczne rozpoznawanie NIP, kwoty, daty i numeru faktury.
- **Zarządzanie Kontrahentami**: Automatyczne tworzenie kontrahentów w bazie na podstawie danych z AI.
- **Płatności**: Rejestrowanie i śledzenie płatności powiązanych z fakturami.
- **API Documentation**: Pełna dokumentacja Swagger/OpenAPI.
- **Automated Tests**: Komplet testów Feature i Unit (CRUD, Upload, Mocking AI).

## 📋 Wymagania systemowe
- PHP >= 8.2
- Composer
- Node.js & NPM (opcjonalnie dla frontendu)
- **Tesseract OCR**: Zainstalowany w systemie (domyślna ścieżka: `C:\Program Files\Tesseract-OCR\tesseract.exe`)
- **Ollama**: Zainstalowana i uruchomiona z modelem `llama3`.

Link do pobrania Teseract OCR: https://github.com/tesseract-ocr/tessdoc?tab=readme-ov-file#5xx
Link do pobrania Ollama: https://ollama.com/download

## 🚀 Instrukcja uruchomienia

### 1. Klonowanie repozytorium
git clone https://github.com/GrimDent/smf-young-tech-challenge.git

### 2. Instalacja zależności
composer install
npm install && npm run dev

### 3. Konfiguracja środowiska
cp .env.example .env
php artisan key:generate

### 4. Migracja bazy danych
php artisan migrate

### 5. Uruchomienie aplikacji
php artisan serve

📖 Dokumentacja API
Dokumentacja Swagger jest dostępna pod adresem:
http://localhost:8000/api/documentation

🧪 Testy
Projekt posiada zestaw testów automatycznych. Aby je uruchomić, użyj:
php artisan test

## Licencja: MIT
