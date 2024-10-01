<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreDetteRequest;
use App\Http\Requests\AddPaymentRequest;
use App\Http\Resources\DetteResource;
use App\Http\Resources\PaiementResource;
use App\Http\Resources\DetteCollection;
use App\Services\DetteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Dette;

class DetteController extends Controller
{
    protected $detteService;

    public function __construct(DetteService $detteService)
    {
        $this->detteService = $detteService;
    }

    public function store(StoreDetteRequest $request): JsonResponse
    {
        try {
            $dette = $this->detteService->createDette($request->all());
            return response()->json(new DetteResource($dette), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur de validation', 'errors' => $e->getMessage()], 411);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $status = $request->query('statut');
        if ($status) {
            $dettes = $this->detteService->getDettesByStatus($status);
        } else {
            $dettes = $this->detteService->getAllDettes();
        }
        return response()->json(new DetteCollection($dettes), 200);
    }

    public function show($id): JsonResponse
    {
        $dette = $this->detteService->getDetteById($id);
        if ($dette) {
            return response()->json(new DetteResource($dette), 200);
        } else {
            return response()->json(['message' => 'Objet non trouvé'], 411);
        }
    }

    public function addPayment($id, AddPaymentRequest $request): JsonResponse
    {
        try {
            $dette = $this->detteService->addPayment($id, $request->input('montant'));
            return response()->json(new DetteResource($dette), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur', 'errors' => $e->getMessage()], 411);
        }
    }

    public function getArticles($id): JsonResponse
    {
        try {
            $articles = $this->detteService->getArticlesByDette($id);
            return response()->json(['data' => $articles], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur', 'errors' => $e->getMessage()], 411);
        }
    }

    public function getPayments($id): JsonResponse
    {
        try {
            $payments = $this->detteService->getPaymentsByDette($id);
            return response()->json(['data' => PaiementResource::collection($payments)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur', 'errors' => $e->getMessage()], 411);
        }
    }

    // Nouvelle méthode pour consulter les demandes de dettes
    public function indexDemandes(): JsonResponse
    {
        $client = auth()->user()->client;
        $demandes = $client->demandes()->where('etat', 'En cours')->get();
        return response()->json($demandes);
    }

   
    public function relance($id): JsonResponse
    {
        $dette = Dette::findOrFail($id);
        if ($dette->etat != 'Annule') {
            return response()->json(['message' => 'La demande n\'est pas annulée'], 400);
        }

        // Envoyer la relance après 2 jours
        $dette->update(['etat' => 'En cours']);
        
        
        //  NotificationService::sendRelance($dette);

        return response()->json(['message' => 'Relance envoyée'], 200);
    }

    // Nouvelle méthode pour obtenir les notifications de demandes
    public function notificationsClient(): JsonResponse
    {
        $client = auth()->user()->client;
        $notifications = $client->notifications; // Assurez-vous que vous avez une relation définie dans le modèle Client
        return response()->json($notifications);
    }
}
