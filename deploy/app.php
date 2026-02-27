<?php

/**
 * DEPLOYMENT TEMPLATE: bootstrap/app.php
 *
 * CARA PAKAI:
 * 1. Jika ini project baru, copy isi file ini ke 'bootstrap/app.php'.
 * 2. File ini sudah dikonfigurasi untuk 'Trust Proxies' agar HTTPS
 *    berjalan lancar di Cloudflare Tunnel.
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*'); // Tambahkan ini untuk production
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
