<?php
namespace App\Services;

use App\Exceptions\ClientNotFoundException;
use App\Jobs\SendFidelityCardJob;
use App\Jobs\UploadPhotoJob;
use App\Models\Client;
use App\Models\User;
use App\Mail\SendFidelityCard;
use Illuminate\Support\Facades\Mail;
use App\Services\uploaderPhoto;
use App\Services\QRCodeService;
use App\Repository\ClientRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class ClientServiceImpl implements ClientService
{
    protected $clientRepository;
    protected $uploaderPhoto;
    protected $qrcode;

    public function __construct(ClientRepository $clientRepository, uploaderPhoto $uploaderPhoto, QRCodeService $qrcode)
    {
        $this->clientRepository = $clientRepository;
        $this->uploaderPhoto = $uploaderPhoto;
        $this->qrcode = $qrcode;
    }

    public function getAll(Request $filters)
{
    // Récupérer tous les clients à partir du repository
    $clients = $this->clientRepository->getAll($filters);

    // Parcourir chaque client et encoder la photo en base64
    foreach ($clients as $client) {
        // Assurez-vous que le client a une photo
        if (isset($client->photo)) {
            // Chemin de la photo (ajustez selon votre implémentation)
            $photoPath = $client->user->photo;

            // Vérifier si le fichier photo existe localement
            if (file_exists($photoPath)) {
                // Lire le fichier et l'encoder en base64
                $client->photo_base64 = $this->uploaderPhoto->encodePhotoToBase64($photoPath);
            } else {
                // Si la photo n'existe pas, on peut gérer l'erreur ou définir une valeur par défaut
                $client->photo_base64 = null;
            }
        }
    }

    // Retourner la réponse avec les clients et leur photo encodée en base64
    return [
        'status' => 200,
        'clients' => $clients,
        'message' => 'Liste des clients récupérée avec succès',
    ];
}

public function findByPhone($telephone)
{
    // Récupérer le client à partir du repository par numéro de téléphone
    $client = $this->clientRepository->findByPhone($telephone);

    // Vérifier si le client a été trouvé
    if ($client) {
        // Si le client a une photo, l'encoder en base64
        $photoPath = $client->user->photo;
        
        // Vérifier si la photo existe et encoder en base64
        if (file_exists($photoPath)) {
            $photobase64 = $this->uploaderPhoto->encodePhotoToBase64($photoPath);
            dd($photobase64);
            return [
                'status' => 200,
                'client' => $client,
                'base64' => $photobase64,
                'message' => 'Client trouvé'
            ];
        } else {
            return [
                'status' => 404,
                'client' => $client,
                'message' => 'Photo non trouvée'
            ];
        }
    }

    // Si le client n'a pas été trouvé
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

    public function create(Request $data)
    {
        DB::beginTransaction();
    
        try {
            // Créer le client
            $client = $this->clientRepository->create($data->only(['surnom', 'telephone', 'adresse']));
    
            // Si des données d'utilisateur sont fournies, créer un utilisateur lié au client
            if (isset($data['user'])) {
                // Gestion de l'upload de la photo
                if ($data->hasFile('user.photo')) {
                    $photo = $data->file('user.photo');
                    // Upload de la photo avant le job
                    $photoUrl = $this->uploaderPhoto->uploadPhoto($photo);
                }
    
                // Créer l'utilisateur avec la photo déjà uploadée
                $user = User::create([
                    'name' => $data['user']['name'],
                    'last_name' => $data['user']['last_name'] ?? '',
                    'password' => bcrypt($data['user']['password']),
                    'login' => $data['user']['login'],
                    'photo' => $photoUrl ?? null, // URL de la photo uploadée
                    'status' => true,
                ]);
    
                // Lier l'utilisateur au client
                $client->user()->associate($user);
                $client->save();
    
                // Générer le QR code et la carte de fidélité en PDF pour le client
                $qrCodePath = $this->qrcode->generateQRCode($client);
                $loyaltyCardPDF = $this->qrcode->generateLoyaltyCardPDF($client);
    
                // Envoi de la carte de fidélité par mail dans un job (sans sérialiser le fichier uploadé)
            SendFidelityCardJob::dispatch($client, $loyaltyCardPDF, $qrCodePath);
            }
    
            DB::commit();
    
            return [
                'status' => 201,
                'client' => $client->load('user'),
                'message' => 'Client créé avec succès et carte de fidélité envoyée par email'
            ];
    
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Erreur lors de la création du client: ' . $e->getMessage());
        }
    }
    
    
    
    public function findWithBase64Photo(int $id)
{
    $client = $this->clientRepository->find($id);

    if ($client && $client->user && $client->user->photo) {
        // Encoder l'image en base64
        $photoBase64 = $this->uploaderPhoto->encodePhotoToBase64($client->user->photo);
        $client->user->photo_base64 = $photoBase64; // Ajouter l'image encodée en base64 aux données du client
    }

    return $client;
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

        public function findWithUser($id)
 {
     // Find the client and eager load the associated user
     $client = $this->clientRepository->find($id);
 
     if ($client) {
         if ($client->user) {
             return [
                 'status' => 200,
                 'client' => $client,
                 'message' => 'Client et son compte utilisateur trouvés'
             ];
         } else {
             return [
                 'status' => 200,
                 'client' => $client,
                 'message' => 'Client trouvé, mais aucun compte utilisateur associé'
             ];
         }
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
