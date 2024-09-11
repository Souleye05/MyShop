<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ClientCreatedEvent;
use App\Jobs\SendFidelityCardJob;
use App\Jobs\UploadPhotoJob;
use Illuminate\Support\Facades\Log;

class HandleClientCreation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClientCreatedEvent $event): void
    {
        //

        $client = $event->client;

         // Vérifier si un utilisateur est lié au client
         if ($client->user) {
            // Dispatcher le job pour l'upload de la photo
            if ($client->user->photo) {
            Log::info($client->user->photo);
                UploadPhotoJob::dispatch($client->user->photo, $client);
            }

            // Générer la carte de fidélité et le QR code
            $qrCodePath = app('App\Services\QRCodeService')->generateQRCodeForClient($client);
            $loyaltyCardPDF = app('App\Services\QRCodeService')->generateLoyaltyCardPDF($client);

            // Dispatcher le job pour l'envoi de l'email
            SendFidelityCardJob::dispatch($client, $loyaltyCardPDF, $qrCodePath);
        }
    }
}
