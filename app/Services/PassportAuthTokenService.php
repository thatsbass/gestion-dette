<?php

namespace App\Services;

use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class PassportAuthTokenService implements AuthTokenServiceInterface
{
    public function createToken($user)
    {
        $tokenResult = $user->createToken('API Token');
        $accessToken = $tokenResult->accessToken;
        $refreshToken = $tokenResult->token->id; 

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    public function revokeToken($token)
    {
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($token->id);
    }

    public function refreshToken($refreshToken)
    {
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $token = $refreshTokenRepository->find($refreshToken);

        if (!$token || !$token->isValid()) {
            return null;
        }

        return $this->createToken($token->user);
    }

    public function validateToken($token)
    {
        return $token->isValid();
    }
}
