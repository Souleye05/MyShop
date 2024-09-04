<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Méthode pour créer un utilisateur
    public function store(StoreUserRequest $request)
    {
        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->input('name'),
            'last_name' => $request->input('last_name'),
            'login' => $request->input('login'),
            'password' => Hash::make($request->input('password')), // Hachage du mot de passe
            'role_id' => $request->input('role_id'),
            'status' => $request->input('status', true) // Par défaut, actif
        ]);

        // Réponse JSON
        return response()->json([
            'status' => 201,
            'data' => $user,
            'message' => 'Utilisateur créé avec succès'
        ], 201);
    }

       // Méthode pour lister les utilisateurs
       public function index(Request $request)
       {
           // Initialisation de la requête
           $query = User::query();
   
           // Application des filtres si disponibles
           if ($request->filled('role')) {
               $query->whereHas('role', function($q) use ($request) {
                   $q->where('name', $request->input('role'));
               });
           }
   
           if ($request->filled('status')) {
               $query->where('status', $request->input('status'));
           }
   
           // Exécution de la requête
           $users = $query->get();
   
           // Réponse JSON
           return response()->json([
               'status' => 200,
               'data' => $users->isEmpty() ? null : $users,
               'message' => $users->isEmpty() ? 'Aucun utilisateur trouvé' : 'Liste des utilisateurs'
           ], 200);
       }
       
}

