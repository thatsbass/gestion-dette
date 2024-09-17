<?php

namespace App\Http\Controllers\Api;
use App\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only("login", "password");

        if ($this->authService->attemptAuth($credentials)) {
            $user = $this->authService->user();
            $token = $this->authService->createToken($user, "authToken")
                ->accessToken;

            return response()->json(
                [
                    "token" => $token,
                    "Data" => $user,
                ],
                200
            );
        }

        return response()->json(
            ["message" => "login ou mot de passe incorrect"],
            401
        );
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(["message" => "Logged out successfully"]);
    }
}