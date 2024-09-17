<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    public function attemptAuth(array $credentials);
    public function createToken($user, string $name);
    public function user();
    public function logout();
}
