<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Http\Requests\UpdateMultipleArticleStockRequest;
use App\Http\Requests\UpdateArticleStockRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle($request->validated());

        return response()->json([
            'status' => 201,
            'data' => new ArticleResource($article),
            'message' => 'Article enregistré avec succès'
        ], 201);
    }

    public function index(): JsonResponse
    {
        $articles = $this->articleService->getAllArticles();

        return response()->json([
            'status' => 200,
            'data' => new ArticleCollection($articles),
            'message' => ''
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $article = $this->articleService->getArticle($id);

        if ($article) {
            return response()->json([
                'status' => 200,
                'data' => $article,
                'message' => 'Article trouvé'
            ], 200);
        }

        return response()->json([
            'status' => 411,
            'data' => null,
            'message' => 'Objet non trouvé'
        ], 411);
    }


    public function showByLibelle(Request $request): JsonResponse
    {
        $valid_data = $request->validate([
            'libelle' => 'required|string|unique:articles,libelle',
        ]);
        
        $libelle = $valid_data['libelle'];
        $article = $this->articleService->getArticleByLibelle($libelle);

        if ($article) {
            return response()->json([
                'status' => 200,
                'data' => $article,
                'message' => 'Article trouvé'
            ], 200);
        }

        return response()->json([
            'status' => 411,
            'data' => null,
            'message' => 'Objet non trouvé'
        ], 411);
    }

    public function update(StoreArticleRequest $request, $id): JsonResponse
    {
        $article = $this->articleService->updateArticle($id, $request->validated());

        return response()->json([
            'status' => 200,
            'data' => new ArticleResource($article),
            'message' => 'Article mis à jour avec succès'
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $this->articleService->deleteArticle($id);

        return response()->json([
            'status' => 200,
            'data' => null,
            'message' => 'Article supprimé avec succès'
        ], 200);
    }

    public function updateStock($id, UpdateArticleStockRequest $request): JsonResponse
{
   
    $data_valid = $request->validated();
    

    $article = $this->articleService->updateArticleStock($id, $data_valid['qteStock']);

    if ($article) {
        return response()->json([
            'status' => 200,
            'data' => new ArticleResource($article),
            'message' => 'Quantité stock mise à jour'
        ], 200);
    }

    return response()->json([
        'status' => 411,
        'data' => null,
        'message' => 'Article non trouvé'
    ], 411);
}

public function updateMultipleStock(UpdateMultipleArticleStockRequest $request): JsonResponse
{
    $data_valid = $request->validated();

    $result = $this->articleService->updateMultipleArticleStock($data_valid['articles']);

    return response()->json([
        'status' => 200,
        'data' => $result,
        'message' => 'Stock des articles mis à jour'
    ], 200);
}

}
