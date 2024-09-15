<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Services\UserService;
use App\Services\PhotoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    protected $photoService;

    public function __construct(
        UserService $userService,
        PhotoService $photoService
    ) {
        $this->userService = $userService;
        $this->photoService = $photoService;
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $userData = $request->only([
            "nom",
            "prenom",
            "login",
            "password",
            "role_id",
        ]);
        $photo = $request->file("photo");
        // A gerer pour le temps d'execution
        if ($photo) {
            $photoData = $this->photoService->uploadPhoto($photo);
            $userData["photo"] = $photoData["url"];
            $userData["photo_status"] = $photoData["status"];
        }

        $user = $this->userService->createUser($userData);

        return response()->json(new UserResource($user), 201);
    }

    public function update(StoreUserRequest $request, $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return response()->json(new UserResource($user), 200);
    }

    // public function index(): JsonResponse
    // {
    //     $users = $this->userService->getAllUsers();

    //     return response()->json(new UserCollection($users), 200);
    // }
    public function index(Request $request): JsonResponse
    {
        $role = $request->query("role");
        $active = $request->query("active");

        if ($role && $active) {
            $users = $this->userService->getUsersByRoleAndStatus(
                $role,
                $active
            );
        } elseif ($role) {
            $users = $this->userService->getUsersByRole($role);
        } elseif ($active) {
            $users = $this->userService->getUsersByStatus($active);
        } else {
            $users = $this->userService->getAllUsers();
        }

        return response()->json(
            [
                "status" => 200,
                "data" => $users->isEmpty() ? null : $users,
                "message" => $users->isEmpty()
                    ? "Aucun utilisateur trouvé"
                    : "Liste des utilisateurs",
            ],
            200
        );
    }
}
