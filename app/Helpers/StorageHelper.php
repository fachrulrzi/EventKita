<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Kalau sudah full URL, langsung return
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Pakai default disk (S3 di Railway)
        return Storage::url($path);
    }
}