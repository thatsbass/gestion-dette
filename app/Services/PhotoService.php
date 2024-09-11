<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Exception;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary();
    }

    public function uploadPhoto($photo)
    {
        // Stockage local de la photo
        $localPath = $photo->store('photos');

        try {
            // Tentative d'upload sur Cloudinary
            $photoUrl = $this->uploadToCloudinary($photo);
            $photoStatus = 'success';
        } catch (Exception $e) {
            $photoUrl = Storage::url($localPath);
            $photoStatus = 'failed';
        }

        return ['url' => $photoUrl, 'status' => $photoStatus];
    }

    protected function uploadToCloudinary($photo)
    {
        $uploadResult = $this->cloudinary->uploadApi()->upload($photo->getRealPath(), [
            'folder' => 'users'
        ]);

        return $uploadResult['secure_url'];
    }
}
