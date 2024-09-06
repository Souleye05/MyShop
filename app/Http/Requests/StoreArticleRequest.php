<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'qteStock' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'libelle.required' => 'Le libelle est obligatoire.',
            'libelle.string' => 'Le libelle doit être une chaîne de caractères.',
            'libelle.max' => 'Le libelle ne doit pas dépasser 255 caractères.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix doit être supérieur à 0.',
            'qteStock.required' => 'La quantité en stock est obligatoire.',
            'qteStock.numeric' => 'La quantité en stock doit être un nombre.',
            'qteStock.min' => 'La quantité en stock doit être positive.'
        ];
    }
}
