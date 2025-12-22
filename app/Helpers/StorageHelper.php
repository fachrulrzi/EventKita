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

        // For S3/Railway Object Storage
        if (config('filesystems.default') === 's3') {
            $url = config('filesystems.disks.s3.url');
            $endpoint = config('filesystems.disks.s3.endpoint');
            $bucket = config('filesystems.disks.s3.bucket');
            
            // If AWS_URL is set, use it
            if (!empty($url)) {
                return rtrim($url, '/') . '/' . ltrim($path, '/');
            }
            
            // For Railway Object Storage: construct URL from bucket name
            // Railway format: https://{bucket}.storage.railway.app/{path}
            if (!empty($bucket) && str_contains($bucket, '-')) {
                return 'https://' . $bucket . '.storage.railway.app/' . ltrim($path, '/');
            }
            
            // Otherwise use endpoint
            if (!empty($endpoint)) {
                return rtrim($endpoint, '/') . '/' . ltrim($path, '/');
            }
        }

        // Fallback to Laravel's Storage::url()
        return Storage::url($path);
    }
}
