<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Asegurar que cualquier excepción se registre (por si no llega al handler por defecto)
        $exceptions->reportable(function (\Throwable $e): void {
            Log::error('Exception reported', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });

        // Peticiones a Livewire update: no devolver 500 HTML; devolver 200 + redirect con flash para mostrar el error en el formulario
        $exceptions->renderable(function (\Throwable $e, $request) {
            if (!$request->is('livewire/update')) {
                return null;
            }
            $message = 'Error al procesar. Prueba con otra imagen (PNG o JPG) o un tamaño menor.';
            if (config('app.debug')) {
                $message = $e->getMessage();
            }
            try {
                $request->session()->flash('error', $message);
                $redirect = $request->header('Referer', route('advertisers.index'));
                return response()->json([
                    'effects' => [
                        'redirect' => $redirect,
                    ],
                ], 200);
            } catch (\Throwable) {
                return response()->json(['message' => $message], 200);
            }
        });
    })->create();
