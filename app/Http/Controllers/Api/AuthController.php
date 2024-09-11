<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

use App\Services\AuthTokenServiceInterface;

class AuthController extends Controller
{
    protected $authTokenService;

    public function __construct(AuthTokenServiceInterface $authTokenService)
    {
        $this->authTokenService = $authTokenService;
    }

    public function login(Request $request)
    {
        if (auth()->attempt($request->only('login', 'password'))) {
            $user = auth()->user();
            $token = $this->authTokenService->createToken($user);
            
            return response()->json([
                'data' => new UserResource($user),
                'token' => $token
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        $this->authTokenService->revokeToken($token);

        return response()->json(['message' => 'Logged out successfully'], 200);
    }


    public function refreshToken(Request $request)
{
    $refreshToken = $request->input('refresh_token');
    
    $newToken = $this->authTokenService->refreshToken($refreshToken);

    if (!$newToken) {
        return response()->json(['message' => 'Invalid refresh token'], 400);
    }

    return response()->json($newToken, 200);
}

}