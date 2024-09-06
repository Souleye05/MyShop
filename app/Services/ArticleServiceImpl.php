<?php

namespace App\Services;

use App\Repository\ArticleRepositoryImpl;
use App\Exceptions\ArticleNotFoundException;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleServiceImpl implements ArticleService
{
    protected $repo;


    public function __construct(ArticleRepositoryImpl $repo)
    {
        $this->repo = $repo;
      
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function find($id)
    {
        $article = $this->repo->find($id);

        if (!$article) {
            throw new ArticleNotFoundException("Article not found");
        }

        return $article;
    }

    public function update($id, array $data)
    {
        $article = $this->find($id);
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function findByLibelle($libelle)
    {
        $article = $this->repo->findByLibelle($libelle);
        if (!$article) {
            throw new ArticleNotFoundException("Article with libelle $libelle not found");
        }
        return $article;
    }

    public function findByEtat(?string $disponible)
    {
        // Démarrer une nouvelle requête sur le modèle Article
        $query = Article::query();

        // Filtrer par disponibilité
        if ($disponible) {
            if ($disponible === 'oui') {
                $query->where('qteStock', '>', 0); // Articles disponibles
            } elseif ($disponible === 'non') {
                $query->where('qteStock', '=', 0); // Articles non disponibles
            }
        }

        // Récupérer tous les articles filtrés
        return $query->get();
    }
    

    public function bulkUpdateStock(array $articlesData)
{
    $updatedArticles = [];
    $notFoundArticles = [];

    foreach ($articlesData as $articleData) {
        $article = $this->repo->find($articleData['id']);

        if ($article) {
            $article->qteStock = $articleData['qteStock'];
            $this->repo->update($articleData['id'], ['qteStock' => $articleData['qteStock']]);
            $updatedArticles[] = $article;
        } else {
            $notFoundArticles[] = $articleData['id'];
        }
    }

    return ['updated' => $updatedArticles, 'not_found' => $notFoundArticles];
}

}
