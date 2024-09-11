<?php

namespace App\Services;

use Laravel\Sanctum\PersonalAccessToken;

class SanctumAuthTokenService implements AuthTokenServiceInterface
{
    public function createToken($user)
    {
        return [
            'access_token' => $user->createToken('API Token')->plainTextToken,
            'refresh_token' => bin2hex(random_bytes(40)),
        ];
    }

    public function revokeToken($token)
    {
        $personalToken = PersonalAccessToken::findToken($token);
        $personalToken->delete();
    }

    public function refreshToken($refreshToken)
    {
        
        $user = auth()->user(); 

        return $this->createToken($user);
    }

    public function validateToken($token)
    {
        return PersonalAccessToken::findToken($token) !== null;
    }
}
