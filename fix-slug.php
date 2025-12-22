<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Update all events without slug
DB::table('events')->whereNull('slug')->orWhere('slug', '')->get()->each(function($event) {
    DB::table('events')
        ->where('id', $event->id)
        ->update(['slug' => Str::slug($event->title)]);
});

echo "Slug updated successfully!\n";
