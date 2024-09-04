<?php

namespace App\Traits;

trait HasValidationMessages
{
    public function validationMessages()
    {
        return [
            'required' => 'Ce champ est obligatoire.',
            'unique' => 'Cette valeur est déjà utilisée.',
            'exists' => 'Cette valeur n\'existe pas.',
            'min' => 'Ce champ doit avoir au moins :min caractères.',
            'regex' => 'Le format de ce champ est invalide.',
            'boolean' => 'Ce champ doit être vrai ou faux.',
        ];
    }
}
