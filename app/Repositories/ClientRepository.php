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
}
