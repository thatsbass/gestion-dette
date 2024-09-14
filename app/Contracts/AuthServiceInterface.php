<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    public function attempt(array $credentials);
    public function createToken($user, string $name);
    public function user();
    public function logout();
}
