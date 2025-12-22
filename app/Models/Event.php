<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'city_id',
        'description',
        'location',
        'city',
        'date',
        'time_start',
        'time_end',
        'price_min',
        'price_max',
        'image_path',
        'is_featured',
        'status',
        'tags',
        'website_url',
    ];

    protected $casts = [
        'date' => 'date',
        'is_featured' => 'boolean',
    ];

    /**
     * Relasi ke Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke City
     */
    public function cityRelation()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Relasi ke Discussions
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    /**
     * Relasi Many-to-Many ke Users (Favorited By)
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Relasi ke Ticket Categories
     */
    public function ticketCategories()
    {
        return $this->hasMany(EventTicketCategory::class);
    }

    /**
     * Automatically generate unique slug from title
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = static::generateUniqueSlug($event->title);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('title')) {
                $event->slug = static::generateUniqueSlug($event->title, $event->id);
            }
        });
    }

    /**
     * Generate unique slug, add suffix if duplicate exists
     */
    protected static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = static::where('slug', $slug);
            
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get formatted price from ticket categories
     */
    public function getFormattedPriceAttribute()
    {
        // Load ticket categories if not loaded
        if (!$this->relationLoaded('ticketCategories')) {
            $this->load('ticketCategories');
        }

        // Check if has ticket categories
        if ($this->ticketCategories->isEmpty()) {
            return 'Gratis';
        }

        $prices = $this->ticketCategories->pluck('price')->filter();
        
        if ($prices->isEmpty()) {
            return 'Gratis';
        }

        $minPrice = $prices->min();
        $maxPrice = $prices->max();

        if ($minPrice == $maxPrice) {
            return 'Rp' . number_format($minPrice, 0, ',', '.');
        }

        return 'Rp' . number_format($minPrice, 0, ',', '.') . ' - Rp' . number_format($maxPrice, 0, ',', '.');
    }

    /**
     * Check if event has paid tickets
     */
    public function hasPaidTickets()
    {
        if (!$this->relationLoaded('ticketCategories')) {
            $this->load('ticketCategories');
        }

        return $this->ticketCategories->where('price', '>', 0)->isNotEmpty();
    }
}
