<?php

namespace App\Http\Controllers;

use App\Services\UserServiceInterface;
use App\Services\UserServiceImpl;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'status' => 201,
            'data' => $user,
            'message' => 'Utilisateur créé avec succès'
        ], 201);
    }

    public function index(Request $request)
    {
        // Utilisation du service pour gérer le filtrage
        $users = $this->userService->getFilteredUsers($request->only(['role', 'status']));

        return response()->json([
            'status' => 200,
            'data' => $users->isEmpty() ? null : $users,
            'message' => $users->isEmpty() ? 'Aucun utilisateur trouvé' : 'Liste des utilisateurs'
        ], 200);
    }
}
