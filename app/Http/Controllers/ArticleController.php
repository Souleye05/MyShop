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

       return compact('articles');
    }

    public function store(StoreArticleRequest $request)
    {
        $article = $this->articleService->create($request->validated());

        return compact('article');
    }

    public function showById($id)
    {
        $article = $this->articleService->find($id);

        return compact('article');
    }

    public function showByLibelle(Request $request)
    {
        $article = $this->articleService->findByLibelle($request->input('libelle'));

        return compact('article');
    }

    public function updateStock(StoreArticleRequest $request, $id)
    {
        // $article = $this->articleService->update($id, $request->validated()['qteStock']);

        $article = $this->articleService->update($id, $request->validated());
        

       return compact('article');
    }

    public function bulkUpdateStock(BulkUpdateArticleRequest $request)
    {
        $validated = $request->validated();
    
        // Appel à la méthode `bulkUpdateStock` du service
        $result = $this->articleService->bulkUpdateStock($validated['articles']);
    
        return compact('result');
    }
    
}
