<?php

namespace App\Jobs;

use App\Mail\SendFidelityCard;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// use Mail;

class SendFidelityCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $loyaltyCardPDF;
    protected $qrCodePath;

    public function __construct($client, $loyaltyCardPDF, $qrCodePath)
    {
        $this->client = $client;
        $this->loyaltyCardPDF = $loyaltyCardPDF;
        $this->qrCodePath = $qrCodePath;
    }

    public function handle()
    {
        // Envoi du mail avec la carte de fidélité et le QR code
        Mail::to($this->client->user->login)->send(new SendFidelityCard($this->client, $this->loyaltyCardPDF, $this->qrCodePath));
    }
}


