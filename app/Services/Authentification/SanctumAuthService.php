<?php

namespace App\Services\Authentification;

use App\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;

class SanctumAuthService implements AuthServiceInterface
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function attemptAuth(array $credentials)
    {
        return Auth::attempt($credentials);
    }

    public function createToken($user, string $name)
    {
        return $user->createToken(
            $name,
            ["*"],
            now()->addMinutes($this->config["expiration"])
        );
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        $this->user()->tokens()->delete();
    }
}
