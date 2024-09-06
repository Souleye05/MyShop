<?php

namespace App\Exceptions;

use Exception;

class ArticleNotFoundException extends Exception
{
    public function __construct($message = "Article non trouvé", $code = 404)
    {
        parent::__construct($message, $code);
    }

    // Vous pouvez également personnaliser la méthode de rendu
    public function render($request)
    {
        return response()->json([
            'status' => 404,
            'message' => $this->getMessage(),
        ], 404);
    }
}
