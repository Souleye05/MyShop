<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDetteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'montant' => 'required|numeric|min:0',
            'clientId' => 'required|exists:clients,id',
            'articles' => 'required|array|min:1',
            'articles.*.articleId' => 'required|exists:articles,id',
            'articles.*.qteVente' => 'required|numeric|min:1',
            'articles.*.prixVente' => 'required|numeric|min:0',
            'paiement.montant' => 'nullable|numeric|lte:montant',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'montant.required' => 'Le montant est obligatoire.',
            'clientId.required' => 'Le client est obligatoire.',
            'articles.required' => 'Les articles sont obligatoires.',
            'articles.*.articleId.exists' => 'Un ou plusieurs articles n\'existent pas.',
            'articles.*.qteVente.required' => 'La quantité vendue est obligatoire.',
            'articles.*.qteVente.min' => 'La quantité vendue doit être supérieure à 0.',
            'paiement.montant.lte' => 'Le montant du paiement ne peut pas être supérieur au montant de la dette.',
         ];
    }
}
