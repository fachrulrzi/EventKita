<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Filesystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register storage_url helper directly in ServiceProvider
        if (!function_exists('storage_url')) {
            function storage_url(?string $path): ?string
            {
                if (empty($path)) {
                    return null;
                }

                // Check if it's already a full URL
                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                    return $path;
                }

                // For Railway S3 - use AWS_URL directly
                $awsUrl = config('filesystems.disks.s3.url');
                if (!empty($awsUrl)) {
                    return rtrim($awsUrl, '/') . '/' . ltrim($path, '/');
                }

                // Fallback: construct from bucket name
                $bucket = config('filesystems.disks.s3.bucket');
                if (!empty($bucket)) {
                    return 'https://' . $bucket . '.storage.railway.app/' . ltrim($path, '/');
                }

                // Last resort: use Laravel Storage
                return \Illuminate\Support\Facades\Storage::url($path);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Pastikan symbolic link storage tersedia untuk file upload (icon kategori, dsb)
        $filesystem = new Filesystem();
        $publicStorage = public_path('storage');
        $storageAppPublic = storage_path('app/public');

        if (!$filesystem->exists($publicStorage)) {
            try {
                Artisan::call('storage:link');
            } catch (\Throwable $e) {
                // Abaikan jika gagal; akan memakai placeholder di UI
            }
        }
    }
}
