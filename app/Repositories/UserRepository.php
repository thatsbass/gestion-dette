<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        // dd($data);
        return User::create($data);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    // public function delete(User $user): void
    // {
    //     $user->delete();
    // }

    public function getAll()
    {
        return User::all();
    }
}
