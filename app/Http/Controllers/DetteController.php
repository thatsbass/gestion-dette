<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDetteRequest;
use App\Http\Requests\AddPaymentRequest;
use App\Services\DetteService;
use Illuminate\Http\JsonResponse;

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
            return response()->json($dette, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur de validation', 'errors' => $e->getMessage()], 411);
        }
    }

    public function index(): JsonResponse
    {
        $dettes = $this->detteService->getAllDettes();
        return response()->json(['data' => $dettes, 'message' => 'Liste des dettes'], 200);
    }

    public function show($id): JsonResponse
    {
        $dette = $this->detteService->getDetteById($id);
        if ($dette) {
            return response()->json(['data' => $dette, 'message' => 'Dette trouvée'], 200);
        } else {
            return response()->json(['message' => 'Objet non trouvé'], 411);
        }
    }

    public function addPayment($id, AddPaymentRequest $request): JsonResponse
    {
        try {
            $dette = $this->detteService->addPayment($id, $request->input('montant'));
            return response()->json(['data' => $dette, 'message' => 'Paiement ajouté'], 200);
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
            return response()->json(['data' => $payments], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur', 'errors' => $e->getMessage()], 411);
        }
    }
}
