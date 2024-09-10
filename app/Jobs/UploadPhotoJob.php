<?php

namespace App\Jobs;

use App\Services\UploaderPhoto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadPhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $photo;
    protected $client;

    public function __construct($photo, $client)
    {
        $this->photo = $photo;
        $this->client = $client;
    }

    public function handle(UploaderPhoto $uploaderPhoto)
    {
        // Upload de la photo
        $photoUrl = $uploaderPhoto->uploadPhoto($this->photo);

        // Mise à jour du client avec l'URL de la photo
        $this->client->user->photo = $photoUrl;
        $this->client->save();
    }
}
