<?php

use App\Helpers\StorageHelper;

if (!function_exists('storage_url')) {
    /**
     * Generate public URL for stored files
     * Works with both local storage and S3 (Railway Object Storage)
     */
    function storage_url(?string $path): ?string
    {
        return StorageHelper::url($path);
    }
}
