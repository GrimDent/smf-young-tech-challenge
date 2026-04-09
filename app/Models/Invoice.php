<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_id',
        'invoice_number',
        'file_path',
        'raw_text',
        'issue_date',
        'total_amount',
        'currency',
    ];

    /**
     * Get the contractor that owns the invoice.
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    /**
     * Get the items for the invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the payment associated with the invoice.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
