<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
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

        foreach ([
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('app/public'),
            storage_path('logs'),
        ] as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }
    }
}
