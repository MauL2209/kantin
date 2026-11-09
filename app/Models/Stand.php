<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;

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

    /**
     * Stand has one penjaga (user with role penjaga).
     */
    public function penjaga(): HasOne
    {
        return $this->hasOne(User::class)->where('role', 'penjaga');
    }

    /**
     * Stand has many penjaga users (users with role penjaga).
     */
    public function penjagaUsers(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'penjaga');
    }
}

