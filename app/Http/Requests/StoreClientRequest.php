<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette demande.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * les règles de validation qui s'appliquent à la requête.
     */
    public function rules()
    {
        return [
            'surnom' => 'required|string|unique:clients,surnom',
            'telephone' => [
                'required',
                'string',
                'unique:clients,telephone',
                'regex:/^(77|78|76|70|75)[0-9]{7}$/'
            ],
            'adresse' => 'nullable|string',
            'user.login' => 'nullable|required_with:user|string|unique:users,login',
            'user.password' => [
                'nullable',
                'required_with:user',
                'string',
                'min:5',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{5,}$/'
            ],
        ];
    }

    /**
     * les messages d'erreur.
     */
    public function messages()
    {
        return [
            'surnom.required' => 'Le nom est obligatoire.',
            'surnom.unique' => 'Ce nom est déjà pris.',
            'telephone.required' => 'Le téléphone est obligatoire.',
            'telephone.unique' => 'Ce téléphone est déjà utilisé.',
            'telephone.regex' => 'Le téléphone doit être un numéro valide au Sénégal.',
            'user.login.required_with' => 'Le login est obligatoire si un utilisateur est fourni.',
            'user.login.unique' => 'Ce login est déjà utilisé.',
            'user.password.required_with' => 'Le mot de passe est obligatoire si un utilisateur est fourni.',
            'user.password.regex' => 'Le mot de passe doit contenir au moins 5 caractères, dont des majuscules, des minuscules, des chiffres et des caractères spéciaux.',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new \Illuminate\Validation\ValidationException($validator);
    }
}

