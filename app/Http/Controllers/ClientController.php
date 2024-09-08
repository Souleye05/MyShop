<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\ClientServiceFacade;
use App\Http\Requests\FindByPhoneClientRequest;
use App\Http\Requests\StoreClientRequest;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = ClientServiceFacade::getAll($request);
        return compact('clients');
    }

    /**
     * Trouver un client par numéro de téléphone.
     */
    public function findByPhone(FindByPhoneClientRequest $request)
    {
        $request->validated();

        $response = ClientServiceFacade::findByPhone($request->validated());

        return compact('response');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        try {
            // Logique déléguée au service via la façade
            $response = ClientServiceFacade::create($request->validated());
    
            return compact('response');
    
        } catch (\Exception $e) {
            // Gestion des exceptions personnalisées
            return response()->json([
                'status' => 500,
                'client' => null,
                'message' => 'Erreur lors de l\'enregistrement du client: ' . $e->getMessage(),
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $response = ClientServiceFacade::find($id);

        return compact('response');
    }

    /**
     * Lister les dettes d’un client.
     */
    public function listDettes(int $id)
    {
        $response = ClientServiceFacade::listDettes($id);

        return compact('response');
    }

    /**
     * Display the client with its associated user.
     */
    public function showWithUser(int $id)
    {
        $response = ClientServiceFacade::showWithUser($id);

        return compact('response');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $response = ClientServiceFacade::update($id, $request->all());

        return compact('response');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $response = ClientServiceFacade::delete($id);

        return compact('response');
    }
}
