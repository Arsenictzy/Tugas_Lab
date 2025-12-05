<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Mendefinisikan alias untuk Middleware kustom
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'hrd' => \App\Http\Middleware\HRDMiddleware::class,
            'leader' => \App\Http\Middleware\LeaderMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
        ]);
        
        // Perbaikan: Menghapus referensi ke HandleInertiaRequests karena menggunakan stack 'blade'
        $middleware->web(append: [
            // \App\Http\Middleware\HandleInertiaRequests::class Dihapus
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi penanganan pengecualian (Exceptions)
    })->create();