<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Psr\Log\LogLevel;

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

    protected $levels = [
        DataNotFoundException::class => LogLevel::INFO,
        NoContentException::class => LogLevel::ALERT,
        ServerException::class => LogLevel::CRITICAL,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (DataNotFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                $json = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];

                return response()->json($json, 404);
            } else {
                return abort(404, $e->getMessage());
            }
        });

        $this->renderable(function (ServerException $e, Request $request) {
            if ($request->expectsJson()) {
                $json = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];

                return response()->json($json, 500);
            } else {
                return abort(500, $e->getMessage());
            }
        });

        $this->renderable(function (DataAcceptedException $e, Request $request) {
            if ($request->expectsJson()) {
                $json = [
                    'success' => true,
                    'error' => $e->getMessage(),
                ];

                return response()->json($json, 202);
            } else {
                return abort(202, $e->getMessage());
            }
        });

        $this->renderable(function (NoContentException $e, Request $request) {
            if ($request->expectsJson()) {
                $json = [
                    'success' => true,
                    'error' => $e->getMessage(),
                ];

                return response()->json($json, 204);
            } else {
                return abort(204, $e->getMessage());
            }
        });
    }
}
