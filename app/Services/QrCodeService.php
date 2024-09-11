<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateQrCode($phoneNumber)
    {
        $qrCode = QrCode::size(200)->generate($phoneNumber);

        $filePath = storage_path('app/qrcodes/' . uniqid() . '.png');
        file_put_contents($filePath, $qrCode);

        return $filePath;
    }
}
