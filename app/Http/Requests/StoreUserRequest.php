<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'login' => 'required|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:5',
                'regex:/[a-z]/',      // au moins une lettre minuscule
                'regex:/[A-Z]/',      // au moins une lettre majuscule
                'regex:/[0-9]/',      // au moins un chiffre
                'regex:/[@$!%*?&]/'   // au moins un caractère spécial
            ],
            'role_id' => 'required|exists:roles,id',
            'status' => 'boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'last_name.required' => 'Le prénom est obligatoire.',
            'login.required' => 'Le login est obligatoire.',
            'login.unique' => 'Ce login est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 5 caractères.',
            'password.regex' => 'Le mot de passe doit contenir des lettres majuscules, des lettres minuscules, des chiffres et des caractères spéciaux.',
            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle sélectionné est invalide.',
            'status.boolean' => 'Le statut doit être vrai ou faux.',
        ];
    }

}
