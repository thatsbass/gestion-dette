<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generateUserPdf($user, $photoPath, $qrCodePath)
    {
        try {
            $pdf = Pdf::loadView('pdf.user', [
                'user' => $user,
                'photoPath' => $photoPath,
                'qrCodePath' => $qrCodePath
            ]);
    
            $pdfPath = storage_path('app/pdfs/' . uniqid() . '.pdf');
            $pdf->save($pdfPath);
    
            Log::info('PDF generated at path: ' . $pdfPath); // Ajoutez ceci pour loguer le chemin du fichier
    
            return $pdfPath;
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
