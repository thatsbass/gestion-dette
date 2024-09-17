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

class PhotoUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $photoPath;
    protected $userId;

    public $tries = 3; 

    public function __construct($photoPath, $userId)
    {
        $this->photoPath = $photoPath;
        $this->userId = $userId;
    }

    public function handle(PhotoService $photoService)
    {
        try {
            $photoData = $photoService->uploadPhoto($this->photoPath);

            if ($photoData['status'] === 'success') {
                $user = User::find($this->userId);
                if ($user) {
                    $user->photo = $photoData['url'];
                    $user->photo_status = 'success';
                    $user->save();
                }
            }
        } catch (Exception $e) {
            Log::error("Failed to upload photo for user ID {$this->userId}: " . $e->getMessage());
            $this->release(600);
        }
    }

    public function failed(Exception $exception)
    {
        Log::error("Job failed after multiple attempts for user ID {$this->userId}: " . $exception->getMessage());
    }
}
