<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $clients = QueryBuilder::for(Client::class)
                ->allowedFilters(['surnom', 'telephone', 'adresse'])
                ->allowedSorts('surnom', 'telephone')
                ->when($request->has('comptes'), function ($query) use ($request) {
                    if ($request->input('comptes') === 'oui') {
                        $query->whereNotNull('user_id');
                    } elseif ($request->input('comptes') === 'non') {
                        $query->whereNull('user_id');
                    }
                })
                ->when($request->has('active'), function ($query) use ($request) {
                    if ($request->input('active') === 'oui') {
                        $query->whereHas('user', function ($query) {
                            $query->where('status', true);
                        });
                    } elseif ($request->input('active') === 'non') {
                        $query->whereHas('user', function ($query) {
                            $query->where('status', false);
                        });
                    }
                })
                ->get();

            if ($clients->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'data' => null,
                    'message' => 'Pas de clients'
                ], 200);
            }

            return response()->json([
                'status' => 200,
                'data' => $clients,
                'message' => 'Liste des clients'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data' => null,
                'message' => 'Erreur lors de la récupération des clients: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trouver un client par numéro de téléphone.
     */
    public function findByPhone(Request $request)
    {
        $request->validate([
            'telephone' => 'required|string|size:9' // Assurez-vous que la validation correspond à vos critères
        ]);

        $client = Client::where('telephone', $request->input('telephone'))->first();

        if ($client) {
            return response()->json([
                'status' => 200,
                'data' => $client,
                'message' => 'Client trouvé'
            ], 200);
        }

        return response()->json([
            'status' => 404,
            'data' => null,
            'message' => 'Client non trouvé'
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): JsonResponse
{
    // Démarrer une transaction
    DB::beginTransaction();

    try {
        // Créez le client
        $client = Client::create($request->only(['surnom', 'telephone', 'adresse']));

        // Vérifiez si les attributs de l'utilisateur sont présents dans la requête
        if ($request->has('user')) {
            // Créez l'utilisateur
            $user = User::create([
                'name' => $request->input('user.name'),
                'last_name' => $request->input('user.last_name', ''), // Peut-être null ou une valeur par défaut
                'password' => bcrypt($request->input('user.password')),
                'login' => $request->input('user.login'),
                'status' => true,
            ]);

            // Lier l'utilisateur au client
            $client->user_id = $user->id;
            $client->save();
        }

        // Confirmer la transaction
        DB::commit();

        return response()->json([
            'status' => 201,
            'data' => $client->load('user'),
            'message' => 'Client enregistré avec succès',
        ], 201);

    } catch (\Exception $e) {
        // Annuler la transaction en cas d'erreur
        DB::rollBack();

        return response()->json([
            'status' => 500,
            'data' => null,
            'message' => 'Erreur lors de l\'enregistrement du client: ' . $e->getMessage(),
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::find($id);

        if ($client) {
            return response()->json([
                'status' => 200,
                'data' => $client->load('user'),
                'message' => 'Client trouvé',
            ], 200);
        }

        return response()->json([
            'status' => 404,
            'data' => null,
            'message' => 'Client non trouvé',
        ], 404);
    }

    public function listDettes($id){
        $client = Client::with('dettes')->find($id);

        if ($client) {
            $dettes = $client->dettes;

            return response()->json([
                'status' => 200,
                'data' => $dettes->isEmpty() ? null : ['client' => $client, 'dettes' => $dettes],
                'message' => 'Client trouvé'
            ], 200);
        }

        return response()->json([
            'status' => 404,
            'data' => null,
            'message' => 'Client non trouvé'
        ], 404);
    }

    public function showWithUser($id)
    {
        $client = Client::with('user')->find($id);

        if ($client && $client->user) {
            return response()->json([
                'status' => 200,
                'data' => ['client' => $client, 'user' => $client->user],
                'message' => 'Client trouvé'
            ], 200);
        }

        return response()->json([
            'status' => 411,
            'data' => null,
            'message' => 'Objet non trouvé'
        ], 411);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
