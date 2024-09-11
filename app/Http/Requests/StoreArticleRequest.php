<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Assurez-vous que l'utilisateur est autorisé à faire cette requête
    }

    public function rules(): array
    {
        return [
            'libelle' => 'required|unique:articles,libelle',
            'prix' => 'required|integer',
            'quantite' => 'required|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libelle est obligatoire.',
            'libelle.unique' => 'Le libelle doit être unique.',
            'prix.required' => 'Le prix est obligatoire.',
            'quantite.required' => 'La quantité est obligatoire.',
        ];
    }
}
