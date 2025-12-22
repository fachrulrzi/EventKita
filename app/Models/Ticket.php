<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'order_id',
        'event_ticket_category_id',
        'ticket_code',
        'attendee_name',
        'attendee_email',
        'attendee_phone',
        'price',
        'qr_code',
        'status',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    /**
     * Generate unique ticket code
     */
    public static function generateTicketCode(): string
    {
        return 'TIX-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
    }

    /**
     * Relasi ke Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke Ticket Category
     */
    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(EventTicketCategory::class, 'event_ticket_category_id');
    }

    /**
     * Check if ticket is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
