<?php

namespace App\Repositories;

use App\Services\Archive\ArchiveServiceInterface;
use App\Models\ArchiveDette;

class ArchiveDetteRepository
{
    protected $archiveService;

    public function __construct(ArchiveServiceInterface $archiveService)
    {
        $this->archiveService = $archiveService;
    }

    public function getAll()
    {
        return $this->archiveService->getAll();
    }

    public function getByClient($clientId)
    {
        return $this->archiveService->getByClient($clientId);
    }

    public function getById($id)
    {
        return $this->archiveService->getById($id);
    }

    public function restoreByDate($date)
    {
        return $this->archiveService->restoreByDate($date);
    }

    public function restoreById($id)
    {
        return $this->archiveService->restoreById($id);
    }

    public function restoreByClient($clientId)
    {
        return $this->archiveService->restoreByClient($clientId);
    }
}
