<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkUpdateArticleRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Ajustez cette méthode selon vos besoins en matière d'autorisation
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la requête.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'articles' => 'required|array|min:1',
            'articles.*.id' => 'required|exists:articles,id',
            'articles.*.qteStock' => 'required|numeric|min:0',
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'articles.required' => 'Le tableau des articles est obligatoire.',
            'articles.array' => 'Les articles doivent être fournis sous forme de tableau.',
            'articles.min' => 'Le tableau doit contenir au moins un article.',
            'articles.*.id.required' => 'L\'ID de l\'article est requis.',
            'articles.*.id.exists' => 'L\'article avec cet ID n\'existe pas.',
            'articles.*.qteStock.required' => 'La quantité en stock est obligatoire.',
            'articles.*.qteStock.numeric' => 'La quantité en stock doit être un nombre.',
            'articles.*.qteStock.min' => 'La quantité en stock doit être positive.',
        ];
    }
}

