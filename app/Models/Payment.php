<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'status',
        'paid_at',
        'proof_image',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Payment belongs to an Order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Formatted amount in IDR.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Friendly payment method label.
     */
    public function getMethodLabelAttribute(): string
    {
        return match (strtolower($this->payment_method)) {
            'cash', 'bayar di kasir' => 'Bayar di Kasir (Tunai)',
            'transfer', 'transfer bank' => 'Transfer Bank',
            'qris' => 'QRIS (E-Wallet / Bank)',
            default => ucfirst($this->payment_method),
        };
    }
}

