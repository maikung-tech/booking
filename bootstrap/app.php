<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (ValidationValidationException $exception, Request $request) {
            return response()->json(['message' => $exception->errors()], 422);
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            return response()->json(['message' => $exception->getMessage()], 404);
        });

        $exceptions->render(function (AuthorizationException $exception, Request $request) {
            return response()->json(['message' => $exception->getMessage()], 403);
        });

        $exceptions->render(function (AccessDeniedHttpException $exception, Request $request) {
            return response()->json(['message' => $exception->getMessage()], 403);
        });

        $exceptions->render(function (QueryException $exception, Request $request) {
            return response()->json(['message' => $exception->getMessage()], 500);

        });

        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            return response()->json(['message' => $exception->getMessage()], 401);
        });
    })->create();
