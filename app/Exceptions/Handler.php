<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PhpParser\Node\Expr\AssignOp\Mod;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        $this->reportable(function (Throwable $e) {
            
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' =>  $e->getMessage()
            ],Response::HTTP_NOT_FOUND);
                }
        // Xử lý lỗi NotFoundHttpException (404 - Trang không tìm thấy)
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Trang không tìm thấy!'
            ], Response::HTTP_NOT_FOUND);
        }

        // Nếu không phải các lỗi trên, trả về lỗi mặc định
        return parent::render($request, $e);
    }
}


