<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ModelNotFoundException) {
                    $model = strtolower(class_basename($e->getModel()));
                    return response()->json([
                        'status' => 'error',
                        'message' => ucfirst($model) . ' not found'
                    ], 404);
                }

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $e->errors()
                    ], 422);
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthenticated'
                    ], 401);
                }

                // Catch all other exceptions
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'code' => $statusCode
                ], $statusCode);
            }
        });
    }
}