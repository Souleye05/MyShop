<?php

namespace App\Exceptions;

use Exception;

class ClientNotFoundException extends Exception
{
    //
    protected $message = 'Client non trouvé';
    protected $status = 404;

    public function render($request)
    {
        return response()->json([
            'status' => $this->status,
            'data' => null,
            'message' => $this->message,
        ], $this->status);
    }
}
