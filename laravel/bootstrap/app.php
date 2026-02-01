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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\LanguageManager::class,
        ]);

        // Ödəniş sistemlərinin (Cryptomus) geri dönüş (callback) sorğuları üçün CSRF qorumasını söndürürük
        $middleware->validateCsrfTokens(except: [
            'payment/callback',
            'payment/webhook', // Ehtiyat üçün
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
