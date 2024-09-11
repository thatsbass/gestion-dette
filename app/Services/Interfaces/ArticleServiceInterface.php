<?php

namespace App\Services\Interfaces;

interface ArticleServiceInterface
{
    public function createArticle(array $data);
    public function getAllArticles();
    public function getArticle($id);
    public function updateArticle($id, array $data);
    public function deleteArticle($id);
    public function updateArticleStock($id, $qteStock);
    public function updateMultipleArticleStock(array $articles);
    public function getArticleByLibelle($libelle);

}
