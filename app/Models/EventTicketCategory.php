<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicketCategory extends Model
{
    protected $fillable = [
        'event_id',
        'category_name',
        'price',
        'stock',
        'sold',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'sold' => 'integer',
    ];

    /**
     * Relasi ke Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Check if ticket still available
     */
    public function isAvailable()
    {
        if (is_null($this->stock)) {
            return true; // Unlimited stock
        }
        return $this->sold < $this->stock;
    }

    /**
     * Get remaining stock
     */
    public function getRemainingStockAttribute()
    {
        if (is_null($this->stock)) {
            return null; // Unlimited
        }
        return max(0, $this->stock - $this->sold);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }
}
