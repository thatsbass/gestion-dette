<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findById(int $id): ?User;
    public function update(User $user, array $data): User;
    // public function delete(User $user): void;
    public function getAllUsers(); 
    public function getUsersByRole($role);
    public function getUsersByRoleAndStatus($role, $active);
    public function getUsersByStatus($active);
    

}
