<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users,login',
            'password' => 'required|string',
            'role_id' => 'exists:roles,id',
            'client_id' => 'required|exists:clients,id',
            'photo' => 'nullable|image|max:2048', 
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le champ nom est obligatoire.',
            'nom.string' => 'Le champ nom doit être une chaîne de caractères.',
            'nom.max' => 'Le champ nom ne doit pas depasser 255 caractères.',
            'prenom.required' => 'Le champ prenom est obligatoire.',
            'prenom.string' => 'Le champ prenom doit être une chaîne de caractères.',
            'prenom.max' => 'Le champ prenom ne doit pas depasser 255 caractères.',
            'login.required' => 'Le champ login est obligatoire.',
            'login.string' => 'Le champ login doit être une chaîne de caractères.',
            'login.max' => 'Le champ login ne doit pas depasser 255 caractères.',
            'login.unique' => 'Le login existe déja.',
            'password.required' => 'Le champ mot de passe est obligatoire.',
            'password.string' => 'Le champ mot de passe doit être une chaîne de caractères.',
            'role_id.exists' => 'Le role n\'existe pas.',
            'client_id.required' => 'Le champ client_id est obligatoire.',
            'client_id.exists' => 'Le client n\'existe pas.',
            'photo.image' => 'Le fichier n\'est pas une image.',
            'photo.max' => 'Le fichier est trop volumineux.',
        ];
    }
}
