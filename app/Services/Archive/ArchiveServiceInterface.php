<?php
namespace App\Services\Archive;

use App\Models\Dette;

interface ArchiveServiceInterface
{
    public function archiveDette(Dette $dette);
    public function getAll();
    public function getByDate($date);
    public function getByClient($clientId);
    public function getById($id);
    public function deleteById($id);
}