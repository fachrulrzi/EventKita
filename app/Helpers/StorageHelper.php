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

        // Auto-detect Railway environment - check if AWS credentials exist
        $hasS3Config = !empty(config('filesystems.disks.s3.bucket')) 
                    && !empty(config('filesystems.disks.s3.endpoint'));
        
        // For Railway or any S3 storage (auto-detect)
        if (config('filesystems.default') === 's3' || $hasS3Config) {
            $url = config('filesystems.disks.s3.url');
            $endpoint = config('filesystems.disks.s3.endpoint');
            $bucket = config('filesystems.disks.s3.bucket');
            
            // Priority 1: If AWS_URL is set, use it
            if (!empty($url)) {
                return rtrim($url, '/') . '/' . ltrim($path, '/');
            }
            
            // Priority 2: For Railway Object Storage - construct URL from bucket name
            // Railway format: https://{bucket}.storage.railway.app/{path}
            if (!empty($bucket)) {
                // Railway bucket names are in format: word-word-randomstring
                if (preg_match('/^[a-z]+-[a-z]+-[a-z0-9]+$/i', $bucket)) {
                    return 'https://' . $bucket . '.storage.railway.app/' . ltrim($path, '/');
                }
            }
            
            // Priority 3: Use endpoint directly
            if (!empty($endpoint)) {
                return rtrim($endpoint, '/') . '/' . ltrim($path, '/');
            }
        }

        // Fallback to Laravel's Storage::url() for local disk
        return Storage::url($path);
    }
}
