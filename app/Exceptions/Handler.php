<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {

        $this->reportable(function (InsufficientAccountBalanceException $e) {
            return false;
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'details' => [
                        'message' => 'Item not found.',
                    ],
                    'code' => 'ERR_101'
                ], 404);
            }
            return false;
        });

        $this->renderable(function (UnauthorizedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'details' => [
                        'message' => 'Unauthenticated!',
                    ],
                    'code' => 'ERR_102'
                ], 401);
            }
            return false;
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'details' => [
                        'message' => 'Access denied.',
                    ],
                    'code' => 'ERR_103'
                ], 403);
            }
            return false;
        });

        $this->renderable(function (BadRequestException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'details' => [
                        'message' => 'bad request.',
                    ],
                    'code' => 'ERR_104'
                ], 400);
            }
            return false;
        });
    }
}
