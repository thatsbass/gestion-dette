<?php

// app/Services/Interfaces/ClientServiceInterface.php

namespace App\Services\Interfaces;

use App\Models\Client;
use App\Models\User;

interface ClientServiceInterface
{
    public function createClientWithOrWithoutUser(array $clientData, ?array $userData = null): Client;
}
