<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\Interfaces\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function findById(int $id): ?Client
    {
        return Client::find($id);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client;
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }

    public function getAll()
    {
        return Client::all();
    }

    // Filtrer les clients avec ou sans compte utilisateur
    public function getClientsByAccountStatus(bool $hasAccount)
    {
        return Client::whereHas('user', function ($query) use ($hasAccount) {
            if (!$hasAccount) {
                $query->whereNull('user_id');
            }
        })->get();
    }

    // Filtrer les clients par statut actif/inactif de leur compte utilisateur
    public function getClientsByActiveStatus(bool $isActive)
    {
        return Client::whereHas('user', function ($query) use ($isActive) {
            $query->where('active', $isActive);
        })->get();
    }

    // Trouver un client par son numéro de téléphone
    public function findByTelephone(string $telephone)
    {
        return Client::where('telephone', $telephone)->first();
    }
}
