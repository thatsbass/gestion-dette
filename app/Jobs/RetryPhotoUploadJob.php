<?php

namespace App\Jobs;

use App\Services\PhotoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class RetryPhotoUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $photoPath;
    protected $userId;
    public $tries = 5;

    public function __construct($photoPath, $userId)
    {
        $this->photoPath = $photoPath;
        $this->userId = $userId;
    }

    public function handle(PhotoService $photoService)
    {
        try {
            $photo_url = $photoService->uploadToCloudinary($this->photoPath);

            if ($photo_url) {
                $user = User::find($this->userId);
                if ($user) {
                    $user->photo = $photo_url;
                    $user->photo_status = 'success';
                    $user->save();
                }
            }
            Log::info("Photo uploaded successfully for user ID {$this->userId} with URL: {$photo_url}", $user->toArray());
        } catch (Exception $e) {
            Log::error("Failed to upload photo for user ID {$this->userId}: " . $e->getMessage());
            $this->release(600); 
        }
    }

    // Méthode appelée après épuisement des tentatives
    public function failed(Exception $exception)
    {
        Log::error("Job failed after multiple attempts for user ID {$this->userId}: " . $exception->getMessage());
    }
}
