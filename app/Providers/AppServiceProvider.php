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

        // Register Blade directive for storage URLs
        Blade::directive('storageUrl', function ($expression) {
            return "<?php echo app('App\Helpers\StorageHelper')::url($expression); ?>";
        });

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
