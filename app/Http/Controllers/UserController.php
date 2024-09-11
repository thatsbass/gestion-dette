<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Services\UserService;
use App\Services\PhotoService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;
    protected $photoService;

    public function __construct(UserService $userService, PhotoService $photoService)
    {
        $this->userService = $userService;
        $this->photoService = $photoService;
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $userData = $request->only(['nom', 'prenom', 'login', 'password', 'role_id']);
        $photo = $request->file('photo');

        if ($photo) {
            $photoData = $this->photoService->uploadPhoto($photo);
            $userData['photo'] = $photoData['url'];
            $userData['photo_status'] = $photoData['status'];
        }
        
        $user = $this->userService->createUser($userData);

        return response()->json(new UserResource($user), 201);
    }

    public function update(StoreUserRequest $request, $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return response()->json(new UserResource($user), 200);
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return response()->json(new UserCollection($users), 200);
    }
}
