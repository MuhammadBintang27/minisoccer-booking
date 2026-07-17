<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/midtrans',
        ]);

        // Railway (dan PaaS sejenis) men-terminate TLS di reverse proxy lalu meneruskan
        // request ke container lewat HTTP biasa. Tanpa ini, Laravel menganggap semua
        // request masuk sebagai http:// (mengabaikan header X-Forwarded-Proto), yang
        // membuat signed URL (mis. guest.pemesanan.show) selalu gagal validasi karena
        // skema saat generate ("https") beda dengan skema saat validasi ("http").
        // IP proxy Railway dinamis/tidak diketahui di muka, jadi "*" dipercaya di sini
        // seperti direkomendasikan Laravel untuk load balancer/PaaS.
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_AWS_ELB);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
