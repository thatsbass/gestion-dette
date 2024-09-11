<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\Interfaces\ArticleRepositoryInterface;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function create(array $data)
    {
        return Article::create($data);
    }

    public function getAll()
    {
        return Article::all();
    }

    public function find($id)
    {
        return Article::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $article = $this->find($id);
        $article->update($data);
        return $article;
    }

    public function delete($id)
    {
        $article = $this->find($id);
        $article->delete();
    }

    public function findById($id)
{
    return Article::find($id);
}

public function findByLibelle($libelle)
{
    return Article::where('libelle', $libelle)->first();
}

}
