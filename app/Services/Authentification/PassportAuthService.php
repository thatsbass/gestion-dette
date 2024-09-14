<?php

namespace App\Services\Authentification;

use App\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;

class PassportAuthService implements AuthServiceInterface
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function attempt(array $credentials)
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
        $this->user()->token()->revoke();
    }
}
