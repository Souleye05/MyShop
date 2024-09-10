<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Log as FacadesLog;

class QRCodeService
{
    public function generateQRCode(string $qrContent): string
    {
        // Generate the QR code
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($qrContent)
            ->size(150)  // Set the size of the QR code
            ->margin(10)
            ->build();

        // Save the QR code to a temporary file
        $qrCodePath = public_path('qrcodes/qr_code_temp.png');
        $qrCode->saveToFile($qrCodePath);

        return $qrCodePath;
    }

    public function generateLoyaltyCardPDF($client): string
    {
        // Generate the QR code
        $qrCodePath = $this->generateQRCode("{$client->telephone} {$client->user->login}");

        // Prepare the data for the PDF
        $data = [
            'client' => $client,
            'qrCodePath' => $qrCodePath
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('pdf.loyalty_card', $data);

        // Save the PDF to a specific path
        $pdfFilePath = public_path("loyalty_cards/loyalty_card_{$client->id}.pdf");
        $pdf->save($pdfFilePath);

        return $pdfFilePath;
    }
    protected function encodePhotoToBase64($photoUrl)
{
    if ($photoUrl) {
        try {
            $imageData = file_get_contents($photoUrl);
            $imageExtension = pathinfo(parse_url($photoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
            return 'data:image/' . $imageExtension . ';base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            FacadesLog::error('Erreur lors de l\'encodage de la photo en base64 : ' . $e->getMessage());
            return null;
        }
    }
    return null;
}
}
