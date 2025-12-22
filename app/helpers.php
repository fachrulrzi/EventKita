<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('storage_url')) {
    /**
     * Generate public URL for stored files
     * Works with both local storage and S3 (Railway Object Storage)
     */
    function storage_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Check if it's already a full URL (e.g., from seeder with external URLs)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // For Railway S3 - use AWS_URL directly (PRIORITY!)
        $awsUrl = env('AWS_URL');
        if (!empty($awsUrl)) {
            return rtrim($awsUrl, '/') . '/' . ltrim($path, '/');
        }

        // Fallback: use config
        $awsUrlConfig = config('filesystems.disks.s3.url');
        if (!empty($awsUrlConfig)) {
            return rtrim($awsUrlConfig, '/') . '/' . ltrim($path, '/');
        }

        // Fallback: construct from bucket name
        $bucket = env('AWS_BUCKET') ?: config('filesystems.disks.s3.bucket');
        if (!empty($bucket)) {
            return 'https://' . $bucket . '.storage.railway.app/' . ltrim($path, '/');
        }

        // Last resort: use Laravel Storage
        return Storage::url($path);
    }
}
