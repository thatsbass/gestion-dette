<?php

namespace App\Services\Archive;

use App\Models\Dette;

interface ArchiveServiceInterface
{
    public function archiveDette(Dette $dette): void;
    public function getAll();
    public function getByClient($clientId);
    public function getByDate($date);
    public function getById($id);
    public function deleteById($id);
}
