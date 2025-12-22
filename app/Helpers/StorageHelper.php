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
        $disk = Storage::disk('public');
        if ($disk->exists($path)) {
            $url = $disk->url($path);

            // If APP_ASSET_VERSION is set in env, use it as a deploy-time cache buster (recommended)
            $assetVersion = env('APP_ASSET_VERSION');
            if (!empty($assetVersion)) {
                $sep = strpos($url, '?') === false ? '?' : '&';
                return $url . $sep . 'v=' . urlencode($assetVersion);
            }

            // Otherwise fall back to lastModified timestamp
            try {
                $mtime = $disk->lastModified($path);
                $sep = strpos($url, '?') === false ? '?' : '&';
                return $url . $sep . 'v=' . $mtime;
            } catch (\Throwable $e) {
                return $url;
            }
        }

        // Fallback to Laravel's Storage::url() if file not found on public disk
        return Storage::url($path);
    }
}
