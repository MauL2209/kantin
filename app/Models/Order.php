<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_nis',
        'customer_class',
        'customer_room',
        'customer_phone',
        'total_price',
        'payment_method',
        'status',
        'customer_note',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
        ];
    }

    /**
     * Order belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order has many details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Order has one payment.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Formatted total price in IDR.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Numerical step for the order progress (1 to 4).
     */
    public function getStatusStepAttribute(): int
    {
        return match ($this->status) {
            'Pesanan Dibuat' => 1,
            'Sedang Diproses' => 2,
            'Siap Diambil' => 3,
            'Selesai' => 4,
            default => 0, // Dibatalkan or other
        };
    }

    /**
     * Badge CSS classes for order status.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'Pesanan Dibuat' => ['bg' => 'bg-amber-100 text-amber-800 border-amber-300', 'dot' => 'bg-amber-500'],
            'Sedang Diproses' => ['bg' => 'bg-blue-100 text-blue-800 border-blue-300', 'dot' => 'bg-blue-500'],
            'Siap Diambil' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-300', 'dot' => 'bg-emerald-500'],
            'Selesai' => ['bg' => 'bg-slate-100 text-slate-800 border-slate-300', 'dot' => 'bg-slate-500'],
            'Dibatalkan' => ['bg' => 'bg-rose-100 text-rose-800 border-rose-300', 'dot' => 'bg-rose-500'],
            default => ['bg' => 'bg-gray-100 text-gray-800 border-gray-300', 'dot' => 'bg-gray-500'],
        };
    }
}

