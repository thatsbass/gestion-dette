<?php

namespace App\Services\Archive;

use App\Models\Dette;

interface ArchiveServiceInterface
{
    public function archiveDette(Dette $dette): void;
}
