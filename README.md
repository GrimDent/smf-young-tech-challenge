# PL Young Tech Challenge 🚀
english version below

System do zarządzania fakturami zintegrowany z Agentem AI, który automatycznie wyciąga dane z dokumentów za pomocą OCR (Tesseract) i modelu językowego Llama 3 (Ollama).

## 🛠 Funkcjonalności
- **OCR & AI Extraction**: Przesyłanie skanów faktur (JPG, PNG, PDF) i automatyczne rozpoznawanie NIP, kwoty, daty i numeru faktury.
- **Zarządzanie Kontrahentami**: Automatyczne tworzenie kontrahentów w bazie na podstawie danych z AI.
- **Płatności**: Rejestrowanie i śledzenie płatności powiązanych z fakturami.
- **API Documentation**: Pełna dokumentacja Swagger/OpenAPI.
- **Automated Tests**: Komplet testów Feature i Unit (CRUD, Upload, Mocking AI).

## 🚀 Instrukcja uruchomienia z Dockerem

### 1. Klonowanie repozytorium
git clone https://github.com/GrimDent/smf-young-tech-challenge.git

### 2. Zbudowanie obrazu
docker-compose up -d --build

### 3. Zainstalowanie zależności wewnątrz wewnątrz dockera
docker-compose exec app composer install

### 4. Generowanie klucza
php artisan key:generate     

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
2. Ollama (qwen3.5:0.8b): Agent AI interpretujący tekst i zamieniający go na ustrukturyzowany format JSON.

### Schemat Bazy Danych
1. Contractors: Dane firm (NIP, nazwa, adres).
2. Invoices: Dane finansowe (numer, kwota, waluta, data wystawienia) + ścieżka do pliku i surowy tekst z OCR.
3. Payments: Historia płatności (kwota, metoda, termin) powiązana z konkretną fakturą.

### Przepływ Danych
1. Użytkownik wysyła plik przez endpoint /api/invoices/upload.
2. Plik jest zapisywany w storage/app/public/invoices.
3. TesseractOCR odczytuje tekst z fizycznego pliku.
4. AiAgentService wysyła tekst do modelu qwen3.5:0.8b wewnątrz kontenera z odpowiednim promptem.

### Mocking
Użycie agenta AI i TesseractOCR jest mockowane w trakcie testów automatycznych by przyspieszyć ich działanie.

## Licencja: MIT 
(nie dotyczy przykładowej faktury, źródło: [Link](https://www.rafsoft.net/wzor-faktury-vat/))
