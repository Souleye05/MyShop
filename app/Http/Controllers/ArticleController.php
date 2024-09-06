<?php
 
namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\BulkUpdateArticleRequest;
use App\Services\ArticleServiceImpl;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleServiceImpl $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
       
        $articles = $this->articleService->findByEtat($request->query('disponible'));

        return response()->json([
            'data' => $articles,
            'message' => $articles->isEmpty() ? 'Aucun article trouvé' : 'Articles trouvés',
        ]);
    }

    public function store(StoreArticleRequest $request)
    {
        $article = $this->articleService->create($request->validated());

        return response()->json([
            'data' => $article,
            'message' => 'Article enregistré avec succès',
        ]);
    }

    public function showById($id)
    {
        $article = $this->articleService->find($id);

        return response()->json([
            'data' => $article,
            'message' => 'Article trouvé',
        ]);
    }

    public function showByLibelle(Request $request)
    {
        $article = $this->articleService->findByLibelle($request->input('libelle'));

        return response()->json([
            'data' => $article,
            'message' => 'Article trouvé',
        ]);
    }

    public function updateStock(StoreArticleRequest $request, $id)
    {
        // $article = $this->articleService->update($id, $request->validated()['qteStock']);

        $article = $this->articleService->update($id, $request->validated());
        

        return response()->json([
            'data' => $article,
            'message' => 'Quantité en stock mise à jour',
        ]);
    }

    public function bulkUpdateStock(BulkUpdateArticleRequest $request)
    {
        $validated = $request->validated();
    
        // Appel à la méthode `bulkUpdateStock` du service
        $result = $this->articleService->bulkUpdateStock($validated['articles']);
    
        return response()->json([
            'status' => 200,
            'data' => [
                'success' => $result['updated'],
                'error' => $result['not_found'],
            ],
            'message' => count($result['not_found']) === 0 
                ? 'Tous les articles ont été mis à jour avec succès.'
                : 'Certains articles n\'ont pas pu être mis à jour car leurs ID n\'ont pas été trouvés.',
        ], 200);
    }
    
}
