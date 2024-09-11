<?php

namespace App\Repositories\Interfaces;

interface PaiementRepositoryInterface
{
    public function create(array $data);
    public function findById(int $id);
    public function update($paiement, array $data);
    public function delete($paiement);
    public function getByDette(int $detteId);
}
