<?php

namespace App\Services;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Exception;

class uploaderPhoto
{
    public function __construct()
    {
        // Manually configure Cloudinary settings using separate credentials
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    /**
     * Upload photo to Cloudinary.
     *
     * @param  \Illuminate\Http\UploadedFile $photo
     * @return string $photoUrl
     * @throws Exception
     */
    public function uploadPhoto($photo)
    {
        try {
            // Upload the photo to Cloudinary using UploadApi
            $uploadResult = (new UploadApi())->upload($photo->getRealPath(), [
                'folder' => 'clients_photos', // You can set a folder where photos will be uploaded
                'use_filename' => true,
                'unique_filename' => false,
            ]);

            // Return the secure URL of the uploaded photo
            return $uploadResult['secure_url'];

        } catch (Exception $e) {
            throw new Exception('Erreur lors du téléchargement de la photo: ' . $e->getMessage());
        }
    }
    public function encodePhotoToBase64($filePath)
    {
        try {
            // Lire le contenu du fichier
            $fileData = file_get_contents($filePath);
    
            // Encoder le contenu du fichier en base64
            $base64Encoded = base64_encode($fileData);
    
            // Ajouter le préfixe MIME pour l'image (ex. "data:image/jpeg;base64,")
            $fileMimeType = mime_content_type($filePath); // Obtenez le type MIME à partir du fichier
            return 'data:' . $fileMimeType . ';base64,' . $base64Encoded;
    
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'encodage en base64: ' . $e->getMessage());
        }
    }
    
}
