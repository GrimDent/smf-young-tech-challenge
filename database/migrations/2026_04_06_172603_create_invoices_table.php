<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('contractor_id')->constrained('contractors')->onDelete('cascade');
        $table->string('invoice_number')->nullable();
        $table->string('file_path');
        $table->longText('raw_text')->nullable(); // OCR result
        $table->date('issue_date')->nullable();
        $table->decimal('total_amount', 15, 2);
        $table->string('currency', 3)->default('PLN');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
