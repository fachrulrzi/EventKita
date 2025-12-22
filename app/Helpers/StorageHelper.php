<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * Generate public URL for stored files
     * Works with both local storage and S3 (Railway Object Storage)
     */
    public static function url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Check if it's already a full URL (e.g., from seeder with external URLs)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Always use the 'public' disk for generating URLs.
        return Storage::disk('public')->url($path);
    }
}
