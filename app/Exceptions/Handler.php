<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ArticleNotFoundException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
