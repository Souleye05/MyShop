<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthentificationSanctum implements AuthenticationServiceInterface
{
    public function authenticate( $credentials)
    {
        if (Auth::guard('sanctum')->attempt($credentials)) {
            return Auth::guard('sanctum')->user()->createToken('API Token')->plainTextToken;
        }

        return null;
    }

    public function logout()
    {
        // Révocation des tokens avec Sanctum
        Auth::guard('sanctum')->user()->tokens()->delete();
    }
}
