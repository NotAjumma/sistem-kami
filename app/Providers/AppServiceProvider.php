<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

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
    public function boot()
    {
        $credentialsBase64 = env('GOOGLE_DRIVE_CREDENTIALS_BASE64');
        $credentialsPath = storage_path('app/google/credentials.json');

        if ($credentialsBase64 && !File::exists($credentialsPath)) {
            File::ensureDirectoryExists(dirname($credentialsPath));
            File::put($credentialsPath, base64_decode($credentialsBase64));
        }

        // Ensure required directories exist
        foreach ([
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('app/public'),
            storage_path('logs'),
        ] as $path) {
            File::ensureDirectoryExists($path);
        }

        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }
    }
}
