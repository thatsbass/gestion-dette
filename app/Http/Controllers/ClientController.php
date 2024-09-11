<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ClientCollection;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use App\Services\PhotoService;
use App\Services\UserService;
use App\Http\Requests\CreateClientUserRequest;

class ClientController extends Controller
{
    protected $clientService;
    protected $userService;
    protected $photoService;

    public function __construct(ClientService $clientService, PhotoService $photoService, UserService $userService)
    {
        $this->clientService = $clientService;
        $this->photoService = $photoService;
        $this->userService = $userService;
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
  
        $clientData = $request->only(['surnom', 'adresse', 'telephone']);
        $userData = $request->input('user', null);
        $photo = $request->file('user.photo'); 
    
        if ($photo) {
            $photoData = $this->photoService->uploadPhoto($photo);
            $userData['photo'] = $photoData['url'];
            $userData['photo_status'] = $photoData['status']; 
        }
        
        
        $client = $this->clientService->createClient($clientData, $userData);
        return response()->json(new ClientResource($client), 201);
    }


    public function createUserForClient(CreateClientUserRequest $request): JsonResponse
    {
       
        $userData = $request->only(['nom', 'prenom', 'login', 'password', 'role_id', 'client_id']);
        $photo = $request->file('photo');
       
        if ($photo) {
            $photoData = $this->photoService->uploadPhoto($photo);
            $userData['photo'] = $photoData['url'];
            $userData['photo_status'] = $photoData['status']; 
        }

        $user = $this->userService->createUserForClient($userData);

        return response()->json(new UserResource($user), 201);
    }
    

    public function update(StoreClientRequest $request, $id): JsonResponse
    {
        $client = $this->clientService->updateClient($id, $request->validated());

        return response()->json(new ClientResource($client), 200);
    }


    public function index(): JsonResponse
    {
        $clients = $this->clientService->getAllClients();

        return response()->json(new ClientCollection($clients), 200);
    }
}
