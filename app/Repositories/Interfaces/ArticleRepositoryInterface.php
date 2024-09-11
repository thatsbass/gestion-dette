<?php

namespace App\Repositories\Interfaces;

interface ArticleRepositoryInterface
{
    public function create(array $data);
    public function getAll();
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function findById(int $id);
    public function findByLibelle($libelle);
}
