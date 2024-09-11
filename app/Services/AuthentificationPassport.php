<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthentificationPassport implements AuthenticationServiceInterface
{
    public function authenticate( $request)
    {
     
      if (Auth::attempt($request)) {
        $user = User::find(Auth::user()->id);
        $token = $user->createToken('LaravelPassportToken')->accessToken;

        return response()->json(['token' => $token,'user' =>$user], 200);
    } else {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    }

    public function logout()
    {
        // Révocation des tokens avec Passport
        Auth::guard('passport')->user()->token()->revoke();
    }
}
