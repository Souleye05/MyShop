<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthenticationServiceInterface;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    protected $authService;

    // Injecter le service d'authentification
    public function __construct(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
    }
    
     // Authentification de l'utilisateur
     public function login(LoginRequest $request)
     {
      // Authentifier via le service (Sanctum ou Passport)
         $token = $this->authService->authenticate($request->only('login', 'password'));
         return response()->json([
             'status' => 200,
             'data' => ['token' => $token],
             'message' => 'Authentification réussie'
         ], 200);
     }
 
     // Deconnexion de l'utilisateur
     public function logout(Request $request)
     {
         // Déconnexion via le service
         $this->authService->logout();
 
         return response()->json([
             'status' => 200,
             'data' => null,
             'message' => 'Déconnexion réussie'
         ], 200);
     }
  
}
