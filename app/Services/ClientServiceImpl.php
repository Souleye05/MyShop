<?php
namespace App\Services;

use App\Exceptions\ClientNotFoundException;
use App\Models\Client;
use App\Models\User;
use App\Repository\ClientRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class ClientServiceImpl implements ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getAll(Request $filters)
    {
        $clients = $this->clientRepository->getAll($filters);
        
        return [
            'status' => 200,
            'clients' => $clients,
            'message' => 'Liste des clients récupérée avec succès',
        ];
    }

    public function findByPhone(string $telephone)
    {
        $client = $this->clientRepository->findByPhone($telephone);

        if ($client) {
            return [
                'status' => 200,
                'client' => $client,
                'message' => 'Client trouvé'
            ];
        }

        return [
            'status' => 404,
            'client' => null,
            'message' => 'Client non trouvé'
        ];
    }

    public function listDettes($id)
    {
        $client = $this->clientRepository->find($id);

        if ($client && $client->dettes) {
            return [
                'status' => 200,
                'dettes' => $client->dettes,
                'message' => 'Dettes récupérées avec succès'
            ];
        }

        return [
            'status' => 404,
            'dettes' => null,
            'message' => 'Client ou dettes non trouvées'
        ];
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            // Créez le client
            $client = $this->clientRepository->create($data);

            // Si des données d'utilisateur sont fournies, créer un utilisateur lié au client
            if (isset($data['user'])) {
                $user = User::create([
                    'name' => $data['user']['name'],
                    'last_name' => $data['user']['last_name'] ?? '',
                    'password' => bcrypt($data['user']['password']),
                    'login' => $data['user']['login'],
                    'status' => true,
                ]);

                // Lier l'utilisateur au client
                $client->user_id = $user->id;
                $client->save();
            }

            DB::commit();

            return [
                'status' => 201,
                'client' => $client->load('user'),
                'message' => 'Client créé avec succès'
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Erreur lors de la création du client: ' . $e->getMessage());
        }
    }

    public function find(int $id)
    {
        $client = $this->clientRepository->find($id);

        if ($client) {
            return [
                'status' => 200,
                'client' => $client,
                'message' => 'Client trouvé'
            ];
        }

        return [
            'status' => 404,
            'client' => null,
            'message' => 'Client non trouvé'
        ];
    }

    public function update(int $id, array $data)
    {
        $client = $this->clientRepository->update($id, $data);

        if ($client) {
            return [
                'status' => 200,
                'client' => $client,
                'message' => 'Client mis à jour avec succès'
            ];
        }

        return [
            'status' => 404,
            'client' => null,
            'message' => 'Client non trouvé'
        ];
    }

    public function delete(int $id)
    {
        $deleted = $this->clientRepository->delete($id);

        if ($deleted) {
            return [
                'status' => 200,
                'message' => 'Client supprimé avec succès'
            ];
        }

        return [
            'status' => 404,
            'message' => 'Client non trouvé'
        ];
    }
}
