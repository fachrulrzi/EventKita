<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Filesystem\Filesystem;
use App\Helpers\StorageHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Register global helper function for storage URLs
        if (!function_exists('storage_url')) {
            function storage_url(?string $path): ?string
            {
                return StorageHelper::url($path);
            }
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
