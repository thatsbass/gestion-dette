<?php

namespace App\Services;

use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Exception;

class ClientService 
{
    protected $clientRepository;
    protected $photoService;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function createClient(array $clientData, ?array $userData = null): Client
    {
        return DB::transaction(function () use ($clientData, $userData) {
            $client = $this->clientRepository->create($clientData);

            if ($userData) {
                $userData['role_id'] = 3; 
                $user = app(UserService::class)->createUser($userData);
                $client->user_id = $user->id;
                $client->save();
            }

            return $client;
        });
    }

    public function updateClient(Client $client, array $data): Client
    {
        return $this->clientRepository->update($client, $data);
    }


    public function getAllClients()
    {
        return $this->clientRepository->getAll();
    }

 
}
