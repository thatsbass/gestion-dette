<?php

namespace App\Repositories\Interfaces;

interface DetteRepositoryInterface
{
    public function create(array $data);
    public function findById(int $id);
    public function update($dette, array $data);
    public function delete($dette);
    public function getAll();
    public function getByStatus($status);
}
