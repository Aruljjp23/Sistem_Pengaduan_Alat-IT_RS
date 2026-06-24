<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Percayai semua header dari reverse proxy (Nginx Laragon) ──
        // Ini membuat Laravel generate URL https:// bukan http://
        // sehingga asset, redirect, dan session cookie bekerja benar via HTTPS.
        Request::setTrustedProxies(
            ['127.0.0.1', '192.168.1.5'],
            Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
        );

        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}