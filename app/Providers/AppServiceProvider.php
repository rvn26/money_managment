<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        if (request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        $targetPath = storage_path('app/firebase/service-account.json');

        // Jika file belum ada di server, dan string Base64 tersedia di .env
        if (!file_exists($targetPath) && env('FIREBASE_CREDENTIALS_BASE64')) {
            // Buat foldernya jika belum ada
            if (!is_dir(dirname($targetPath))) {
                mkdir(dirname($targetPath), 0755, true);
            }

            // Decode isi Base64 dan tulis menjadi file .json fisik kembali
            $jsonContent = base64_decode(env('FIREBASE_CREDENTIALS_BASE64'));
            file_put_contents($targetPath, $jsonContent);
        }
    }
}
