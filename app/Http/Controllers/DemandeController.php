<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDemandeRequest;
use App\Models\Demande;
use App\Services\DemandeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Jobs\NotifyBoutiquiersJob;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    protected $demandeService;

    public function __construct(DemandeService $demandeService)
    {
        $this->demandeService = $demandeService;
    }

    // Créer une nouvelle demande pour un client connecté
    public function store(StoreDemandeRequest $request)
    {
        $validatedData = $request->validated();

        $client = Auth::user();

        $demande = $this->demandeService->createDemande(
            $client,
            $validatedData
        );

        NotifyBoutiquiersJob::dispatch($demande);

        return response()->json($demande, 201);
    }

    // Rechercher les demandes d'un client connecté en fonction d'un état
    public function index(Request $request): JsonResponse
    {
        $client = auth()->user()->client;
        $etat = $request->query('etat');
        $demandes = $this->demandeService->getDemandesByClient(
            $client->id,
            $etat
        );

        return response()->json($demandes);
    }

    // Voir les notifications des demandes de dettes soumises
    public function getNotifications(): JsonResponse
    {
        // Logique pour récupérer les notifications des demandes de dettes
        $notifications = $this->demandeService->getNotifications();
        return response()->json($notifications);
    }

    // Voir toutes les demandes de dettes avec l'état "Encours" ou filtrer par état
    public function getAllDemandes(Request $request): JsonResponse
    {
        $etat = $request->query('etat');
        $demandes = $this->demandeService->getAllDemandes($etat);
        return response()->json($demandes);
    }


    public function checkDisponibilite($id): JsonResponse
{
    // Récupérer la demande par son ID
    $demande = $this->demandeService->findDemandeById($id);
    
    if (!$demande) {
        return response()->json(['message' => 'Demande non trouvée.'], 404);
    }

    $quantitesDisponibles = $this->demandeService->verifierQuantitesDisponibles($demande);

    return response()->json($quantitesDisponibles);
}

public function validerOuAnnuler(Request $request, $id): JsonResponse
{
    $validatedData = $request->validate([
        'action' => 'required|in:Valider,Annuler',
        'motif' => 'required_if:action,Annuler|string',
    ]);

    $demande = $this->demandeService->findDemandeById($id);

    if (!$demande) {
        return response()->json(['message' => 'Demande non trouvée.'], 404);
    }

    if ($validatedData['action'] === 'Annuler') {
        // Annuler la demande
        $this->demandeService->annulerDemande($demande, $validatedData['motif']);
    } else {
        // Valider la demande
        $this->demandeService->validerDemande($demande);
    }

    return response()->json(['message' => 'Demande traitée avec succès.']);
}


}
