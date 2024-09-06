<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);
            return $this->sendResponse($response);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            // \Log::error($e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getMessage());
    
            // Return a JSON error response
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Format the response as JSON if necessary.
     *
     * @param  mixed  $response
     * @param  string $message
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResponse($response, $message = 'Operation successful', $status = 200)
    {
        // Si la réponse est une instance de JsonResponse, on la renvoie telle quelle
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response;
        }
    
        // Si la réponse est un objet ou un tableau, formatez-le en JSON
        if (is_array($response) || is_object($response)) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $response
            ], $status);
        }
    
        // Sinon, encapsulez toute autre réponse dans un format JSON
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => (string) $response
        ], $status);
    }
    
}
