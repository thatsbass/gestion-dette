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
use Illuminate\Http\Request;

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

    // Récupérer tous les clients avec des filtres optionnels
    public function index(Request $request): JsonResponse
    {
        $hasAccount = $request->query('comptes'); // oui | non
        $isActive = $request->query('active'); // oui | non

        if ($hasAccount) {
            $clients = $this->clientService->getClientsByAccountStatus($hasAccount);
        } elseif ($isActive) {
            $clients = $this->clientService->getClientsByActiveStatus($isActive);
        } else {
            $clients = $this->clientService->getAllClients();
        }

        return response()->json([
            'status' => 200,
            'data' => $clients->isEmpty() ? null : new ClientCollection($clients),
            'message' => $clients->isEmpty() ? 'Pas de clients trouvés' : 'Liste des clients'
        ], 200);
    }

    // Recherche client par téléphone
    public function findByTelephone(Request $request): JsonResponse
    {
        $telephone = $request->input('telephone');
        $client = $this->clientService->findByTelephone($telephone);

        if ($client) {
            return response()->json([
                'status' => 200,
                'data' => new ClientResource($client),
                'message' => 'Client trouvé'
            ], 200);
        }

        return response()->json([
            'status' => 411,
            'data' => null,
            'message' => 'Client non trouvé'
        ], 411);
    }
    
}



