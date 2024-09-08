<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiResponseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Vérifier si la réponse est une instance de JsonResponse
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);

            // Si les données ne sont pas déjà dans une structure définie
            if (!isset($data['status']) || !isset($data['data']) || !isset($data['message'])) {
                $data = [
                    'status' => $response->status(),
                    'data' => $data,
                    'message' => $this->getMessageFromStatus($response->status()),
                ];

                $response->setData($data);
            }
        }

        return $response;
    }

    private function getMessageFromStatus($status)
    {
        switch ($status) {
            case 200:
                return 'Requête réussie';
            case 201:
                return 'Ressource créée avec succès';
            case 400:
                return 'Erreur dans la requête';
            case 404:
                return 'Ressource non trouvée';
            case 500:
                return 'Erreur serveur';
            default:
                return 'Statut inconnu';
        }
    }
}
