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
            'qteStock' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'qteStock.required' => 'La quantité en stock est obligatoire.',
            'qteStock.numeric' => 'La quantité en stock doit être un nombre.',
            'qteStock.min' => 'La quantité en stock doit être positive.'
        ];
    }
}
