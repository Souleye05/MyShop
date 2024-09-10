<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreDetteRequest;
use App\Models\Dette;
use App\Models\Client;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DetteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDetteRequest $request): JsonResponse
    {
        // Démarrer une transaction
        DB::beginTransaction();

        try {
            // Créez la dette
            $dette = Dette::create([
                'montant' => $request->input('montant'),
                'client_id' => $request->input('clientId'),
            ]);

            // Ajouter les articles à la dette
            $articles = $request->input('articles');
            foreach ($articles as $article) {
                $articleModel = Article::find($article['articleId']);
                if ($articleModel->qte_stock < $article['qteVente']) {
                    throw new \Exception('Quantité insuffisante pour l\'article ID ' . $article['articleId']);
                }
                $dette->articles()->attach($article['articleId'], [
                    'qte_vente' => $article['qteVente'],
                    'prix_vente' => $article['prixVente']
                ]);
                // Mettre à jour la quantité en stock
                $articleModel->qte_stock -= $article['qteVente'];
                $articleModel->save();
            }

            // Gérer le paiement si présent
            if ($request->has('paiement')) {
                $paiementMontant = $request->input('paiement.montant');
                if ($paiementMontant > $dette->montant) {
                    throw new \Exception('Le montant du paiement ne peut pas être supérieur au montant de la dette.');
                }
                // Logique pour enregistrer le paiement ici
            }

            // Confirmer la transaction
            DB::commit();

            return response()->json([
                'status' => 201,
                'data' => $dette->load('articles', 'client'),
                'message' => 'Dette enregistrée avec succès',
            ], 201);

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            return response()->json([
                'status' => 411,
                'data' => null,
                'message' => 'Erreur de validation : ' . $e->getMessage(),
            ], 411);
        }
    }
}
