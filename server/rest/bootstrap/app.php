<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php', // Utiliser le fichier api.php pour les routes API
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Ajouter le middleware spécifique pour les routes API
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
        // Vous pouvez ajouter d'autres middlewares ici selon vos besoins
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Gestion des exceptions (si nécessaire pour l'API)
    })
    ->create();
