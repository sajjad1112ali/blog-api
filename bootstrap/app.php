<?php


use App\Helpers\ResponseHelper;
use App\Http\Middleware\JsonMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'json' => JsonMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });
        // $exceptions->render(function (ResourceAccessNotAllowedException | TokenException $e) {
        //     return response()->json([
        //         'success' => false,
        //         'status' => 401,
        //         'message' => $e->getMessage(),
        //         'error' => 'Unauthenticated.',
        //     ], 401);
        // });
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return ResponseHelper::errorResponse('Unauthenticated', 401);
            }
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return ResponseHelper::errorResponse('Not Found!', 404);
            }
        });
    })->create();
