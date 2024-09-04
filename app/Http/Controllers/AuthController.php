<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Enregistrement d'un utilisateur
    public function register(Request $request)
    {
        $request->validate([
            'login' => 'required|unique:users',
            'password' => 'required|min:5|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'client_id' => 'required|exists:clients,id',
            'photo' => 'nullable|image|max:1024',
        ]);

        $user = User::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'role_id' => 2, // Role Boutiquier
            'client_id' => $request->client_id,
            'photo' => $request->file('photo')->store('photos', 'public'),
        ]);

        return response()->json([
            'status' => 201,
            'data' => $user,
            'message' => 'Client enregistré avec succès'
        ], 201);
    }

    // Authentification de l'utilisateur
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['login' => $request->login, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'login' => ['Les informations d\'identification sont incorrectes.'],
            ]);
        }

        $token = $request->user()->createToken('API Token')->accessToken;

        return response()->json([
            'status' => 200,
            'data' => ['token' => $token],
            'message' => 'Authentification réussie'
        ], 200);
    }
}
