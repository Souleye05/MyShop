<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\BulkUpdateArticleRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index(Request $request)
    {
        $disponible = $request->query('disponible');
    
        if ($disponible === 'oui') {
            $articles = Article::where('qteStock', '>', 0)->get();
        } elseif ($disponible === 'non') {
            $articles = Article::where('qteStock', '=', 0)->get();
        } else {
            $articles = Article::all();
        }
    
        if ($articles->isEmpty()) {
            return response()->json([
                'status' => 200,
                'data' => null,
                'message' => 'Aucun article trouvé',
            ], 200);
        }
    
        return response()->json([
            'status' => 200,
            'data' => $articles,
            'message' => 'Articles trouvés',
        ], 200);
    }
    
    public function store(StoreArticleRequest $request)
    {
        try {
            // Crée un nouvel article avec les données validées
            $article = Article::create($request->validated());

            return response()->json([
                'status' => 201,
                'data' => $article,
                'message' => 'Article enregistré avec succès',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data' => null,
                'message' => 'Une erreur est survenue lors de l\'enregistrement de l\'article',
            ], 500);
        }
    }

    public function showById($id)
{
    $article = Article::find($id);

    if ($article) {
        return response()->json([
            'status' => 200,
            'data' => $article,
            'message' => 'Article trouvé',
        ], 200);
    }

    return response()->json([
        'status' => 411,
        'data' => null,
        'message' => 'Objet non trouvé',
    ], 411);
}
public function showByLibelle(Request $request)
{
    $request->validate([
        'libelle' => 'required|string',
    ]);

    $article = Article::where('libelle', $request->input('libelle'))->first();

    if ($article) {
        return response()->json([
            'status' => 200,
            'data' => $article,
            'message' => 'Article trouvé',
        ], 200);
    }

    return response()->json([
        'status' => 411,
        'data' => null,
        'message' => 'Objet non trouvé',
    ], 411);
}

public function updateStock(StoreArticleRequest $request, $id)
{
    $validated = $request->validated();

    $article = Article::find($id);

    if (!$article) {
        return response()->json([
            'status' => 411,
            'data' => null,
            'message' => 'Objet non trouvé',
        ], 411);
    }

    $article->qteStock = $validated['qteStock'];
    $article->save();

    return response()->json([
        'status' => 200,
        'data' => $article,
        'message' => 'Quantité en stock mise à jour',
    ], 200);
}




public function bulkUpdateStock(BulkUpdateArticleRequest $request)
{
    $validated = $request->validated();

    $updatedArticles = [];
    $notFoundArticles = [];

    foreach ($validated['articles'] as $articleData) {
        $article = Article::find($articleData['id']);

        if ($article) {
            $article->qteStock = $articleData['qteStock'];
            $article->save();
            $updatedArticles[] = $article;
        } else {
            $notFoundArticles[] = $articleData['id'];
        }
    }

    return response()->json([
        'status' => 200,
        'data' => [
            'success' => $updatedArticles,
            'error' => $notFoundArticles,
        ],
        'message' => count($notFoundArticles) === 0 
            ? 'Tous les articles ont été mis à jour avec succès.'
            : 'Certains articles n\'ont pas pu être mis à jour car leurs ID n\'ont pas été trouvés.',
    ], 200);
}


}
