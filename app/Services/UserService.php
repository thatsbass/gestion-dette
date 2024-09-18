<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Models\Client;
use Exception;
class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $userData): User
    {

        return $this->userRepository->create($userData);
    }

    public function updateUser(User $user, array $data): User
    {
        return $this->userRepository->update($user, $data);
    }

    public function createUserForClient(array $userData)
    {

        // dd($userData);
      
        $userData['role_id'] = 3;
        if (isset($userData['photo']) && is_array($userData['photo'])) {
            $userData['photo'] = $userData['photo']['url'] ?? null;
        }
        
        try {
            $user = $this->userRepository->create($userData);
            $client = Client::findOrFail($userData['client_id']);
            // dd($client);
            $client->user_id = $user->id;
            $client->save();

            return $user;
        } catch (Exception $e) {
          
            throw new Exception("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
        }
    }

  
    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function getUsersByRole($role)
    {
        return $this->userRepository->getUsersByRole($role);
    }

    public function getUsersByRoleAndStatus($role, $active)
    {
        return $this->userRepository->getUsersByRoleAndStatus($role, $active);
    }

    public function getUsersByStatus($active)
    {
        return $this->userRepository->getUsersByStatus($active);
    }
}
