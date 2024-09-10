<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFidelityCard extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $pdfFilePath;
    public $qrCodePath;

    /**
     * Create a new message instance.
     *
     * @param $client
     * @param $pdfFilePath
     * @param $qrCodePath
     */
    public function __construct($client, $pdfFilePath, $qrCodePath)
    {
        $this->client = $client;
        $this->pdfFilePath = $pdfFilePath;
        $this->qrCodePath = $qrCodePath;  // Pass the QR code path here
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Votre carte de fidélité')
                    ->view('emails.fidelity_card')
                    ->with([
                        'qrCodePath' => $this->qrCodePath,  // Pass the QR code path to the view
                    ])
                    ->attach($this->pdfFilePath, [
                        'as' => 'loyalty_card.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
