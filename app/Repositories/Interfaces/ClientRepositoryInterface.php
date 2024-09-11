<?php

namespace App\Repositories\Interfaces;

use App\Models\Client;

interface ClientRepositoryInterface
{
    public function create(array $data): Client;
    public function findById(int $id): ?Client;
    public function update(Client $client, array $data): Client;
    public function getAll();

    public function getClientsByAccountStatus(bool $hasAccount);

    public function getClientsByActiveStatus(bool $isActive);

    public function findByTelephone(string $telephone);

}
