<?php

namespace App\Services;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Services\Interfaces\ArticleServiceInterface;


class ArticleService implements ArticleServiceInterface
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function createArticle(array $data)
    {
        return $this->articleRepository->create($data);
    }

    public function getAllArticles()
    {
        return $this->articleRepository->getAll();
    }

    public function getArticle($id)
    {
        return $this->articleRepository->find($id);
    }

    public function updateArticle($id, array $data)
    {
        return $this->articleRepository->update($id, $data);
    }

    public function deleteArticle($id)
    {
        $this->articleRepository->delete($id);
    }
    public function updateArticleStock($id, $qteStock)
{
    $article = $this->articleRepository->findById($id);
    if ($article) {
        $article->quantite = $qteStock;
        $article->save();
        return $article;
    }
    return null;
}

public function updateMultipleArticleStock(array $articles)
{
    $updated = [];
    $errors = [];

    foreach ($articles as $articleData) {
        $article = $this->articleRepository->findById($articleData['id']);
        if ($article) {
            $article->quantite = $articleData['qteStock'];
            $article->save();
            $updated[] = $article;
        } else {
            $errors[] = $articleData['id'];
        }
    }

    return [
        'success' => $updated,
        'error' => $errors
    ];

    
}


public function getArticleByLibelle($libelle)
{
    return $this->articleRepository->findByLibelle($libelle);
}

}
