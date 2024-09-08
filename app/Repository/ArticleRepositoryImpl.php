<?php
namespace App\Repository;

use App\Exceptions\ArticleNotFoundException;
use App\Models\Article;
use App\Repository\ArticleRepository;
use Illuminate\Http\Request;

class ArticleRepositoryImpl implements ArticleRepository
{
    public function all()
    {
        return Article::all();
    }

    public function create(array $data)
    {
        return Article::create($data);
    }

    public function find($id)
    {
        $article = Article::find($id);
        if (!$article) {
            throw new ArticleNotFoundException();
        }
        return $article;
    }

    public function update($id, array $data)
    {
        $article = $this->find($id); // Utilisation de la méthode `find` qui lance une exception si l'article n'est pas trouvé
        $article->update($data);
        return $article;
    }

    public function delete($id)
    {
        $article = $this->find($id);
        $article->delete();
        return true;
    }

    public function findByLibelle($libelle)
    {
        return Article::where('libelle', $libelle)->first();
    }

    public function findByEtat(Request $request)
    {
        // Récupérer la valeur du paramètre 'disponible' dans la requête
        $disponible = $request->query('disponible');
    
        // Vérifier si 'disponible' est 'oui' ou 'non' et ajuster la requête
        if ($disponible === 'oui') {
            // Rechercher les articles qui sont disponibles (qteStock > 0)
            $articles = Article::where('qteStock', '>', 0)->get();
        } elseif ($disponible === 'non') {
            // Rechercher les articles qui ne sont pas disponibles (qteStock <= 0)
            $articles = Article::where('qteStock', '<=', 0)->get();
        } else {
            // Si 'disponible' n'est pas spécifié ou mal renseigné, retourner tous les articles
            $articles = Article::all();
        }
    
        return response()->json($articles);
    }
}