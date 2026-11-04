<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department',
        'description',
        'image',
        'status',
        'contact_person',
        'location',
    ];

    /**
     * Stand has many products.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope query to only open stands.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Check if stand is open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}

