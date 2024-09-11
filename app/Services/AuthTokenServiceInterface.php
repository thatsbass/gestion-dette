<?php

namespace App\Services;

interface AuthTokenServiceInterface
{
    public function createToken($user);
    public function revokeToken($token);
    public function validateToken($token);
    public function refreshToken($refreshToken);
}
