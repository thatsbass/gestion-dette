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

    public function getAllUsers()
    {
        return User::with('role')->get();  // Charge aussi le rôle
    }

    public function getUsersByRole($roleName)
    {
        return User::whereHas('role', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();
    }

    public function getUsersByRoleAndStatus($roleName, $active)
    {
        $active = ($active === 'oui') ? 1 : 0;
        return User::where('is_active', $active)
            ->whereHas('role', function ($query) use ($roleName) {
                $query->where('name', $roleName);
            })->get();
    }

    public function getUsersByStatus($active)
    {
        $active = ($active === 'oui') ? 1 : 0;
        return User::where('is_active', $active)->get();
    }
}
