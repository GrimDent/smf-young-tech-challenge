# PL Young Tech Challenge 🚀
english version below

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

### 📖 Dokumentacja API
Dokumentacja Swagger jest dostępna pod adresem:

http://localhost:8000/api/documentation

### 🧪 Testy
Projekt posiada zestaw testów automatycznych. Aby je uruchomić, użyj:

php artisan test

## Architektura systemu
### Warstwy
1. Controller Layer: Obsługuje żądania HTTP, waliduje dane wejściowe i zwraca odpowiedzi JSON.
2. Service Layer: logika integracji z AI (Ollama).
3. Model Layer: Reprezentuje strukturę bazy danych i relacje (Contractor ↔ Invoice ↔ Payment).

### Integracja z zewnętrznymi narzędziami
1. Tesseract OCR: Wykorzystywany do ekstrakcji surowego tekstu z plików JPG, PNG, PDF.
2. Ollama (Llama 3): Agent AI interpretujący tekst i zamieniający go na ustrukturyzowany format JSON.

### Schemat Bazy Danych
1. Contractors: Dane firm (NIP, nazwa, adres).
2. Invoices: Dane finansowe (numer, kwota, waluta, data wystawienia) + ścieżka do pliku i surowy tekst z OCR.
3. Payments: Historia płatności (kwota, metoda, termin) powiązana z konkretną fakturą.

### Przepływ Danych
1. Użytkownik wysyła plik przez endpoint /api/invoices/upload.
2. Plik jest zapisywany w storage/app/public/invoices.
3. TesseractOCR odczytuje tekst z fizycznego pliku.
4. AiAgentService wysyła tekst do lokalnego modelu Llama 3 z odpowiednim "promptem".

### Mocking
Użycie agenta AI i TesseractOCR jest mockowane w trakcie testów automatycznych by przyspieszyć ich działanie.


# ENG Young Tech Challenge 🚀

An invoice management system integrated with an AI Agent that automatically extracts data from documents using OCR (Tesseract) and the Llama 3 language model (Ollama).

## 🛠 Features
- **OCR & AI Extraction**: Upload invoice scans (JPG, PNG, PDF) with automatic recognition of Tax ID (NIP), amount, date, and invoice number.
- **Contractor Management**: Automatic creation of contractors in the database based on AI-extracted data.
- **Payments**: Recording and tracking payments linked to specific invoices.
- **API Documentation**: Full Swagger/OpenAPI documentation.
- **Automated Tests**: A complete suite of Feature and Unit tests (CRUD, Upload, AI Mocking).

## 📋 System Requirements
- PHP >= 8.2
- Composer
- Node.js & NPM (optional for frontend)
- **Tesseract OCR**: Installed on the system (default path: C:\Program Files\Tesseract-OCR\tesseract.exe)
- **Ollama**: Installed and running with the llama3 model.

Download Tesseract OCR: [Link](https://github.com/tesseract-ocr/tessdoc?tab=readme-ov-file#5xx)
Download Ollama: [Link](https://ollama.com/download)

## 🚀 Setup Instructions

### 1. Clone the repository
git clone https://github.com/GrimDent/smf-young-tech-challenge.git

### 2. Install dependencies
composer install

npm install && npm run dev

### 3. Environment configuration
cp .env.example .env

php artisan key:generate

### 4. Database migration
php artisan migrate

### 5. Start the application
php artisan serve

### 📖 API Documentation
Swagger documentation is available at:

http://localhost:8000/api/documentation

### 🧪 Testing
The project includes a suite of automated tests. To run them, use:

php artisan test

## System Architecture
### Layers
1. Controller Layer: Handles HTTP requests, validates input data, and returns JSON responses.
2. Service Layer: Contains business logic and AI integration (Ollama).
3. Model Layer: Represents the database structure and relationships (Contractor ↔ Invoice ↔ Payment).

### Integration with External Tools
1. Tesseract OCR: Used to extract raw text from JPG, PNG, and PDF files.

2. Ollama (Llama 3): An AI Agent that interprets the raw text and converts it into a structured JSON format.

### Database Schema
1. Contractors: Company data (Tax ID/NIP, name, address).
2. nvoices: Financial data (number, amount, currency, issue date) + file path and raw OCR text.
3. Payments: Payment history (amount, method, deadline) linked to a specific invoice.

### Data Flow
1. The user sends a file via the /api/invoices/upload endpoint.
2. The file is saved in storage/app/public/invoices.
3. TesseractOCR reads the text from the physical file.
4. AiAgentService sends the extracted text to the local Llama 3 model with a specific prompt.

### Mocking
The AI Agent and TesseractOCR are mocked during automated testing to ensure faster execution and independence from external services.

## Licencja: MIT 
(nie dotyczy przykładowej faktury, źródło: [Link](https://www.rafsoft.net/wzor-faktury-vat/))
