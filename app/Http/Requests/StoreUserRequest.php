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
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'login' => 'required|string|unique:users,login|max:255',
            'password' => 'required|string',
            'photo' => 'nullable|image|max:4048', // 4Mo
            'photo_status' => 'nullable|in:success,failed',
            'role_id' => 'required|exists:roles,id',
        ];


    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le champ nom est obligatoire.',
            'nom.string' => 'Le champ nom doit être une chaîne de caractères.',
            'nom.max' => 'Le champ nom ne doit pas dépasser 255 caractères.',

            'prenom.required' => 'Le champ prenom est obligatoire.',
            'prenom.string' => 'Le champ prenom doit être une chaîne de caractères.',
            'prenom.max' => 'Le champ prenom ne doit pas dépasser 255 caractères.',

            'login.required' => 'Le champ login est obligatoire.',
            'login.string' => 'Le champ login doit être une chaîne de caractères.',
            'login.max' => 'Le champ login ne doit pas dépasser 255 caractères.',
            'login.unique' => 'Le login existe déjà.',

            'password.required' => 'Le champ mot de passe est obligatoire.',
            'password.string' => 'Le champ mot de passe doit être une chaîne de caractères.',

            'photo.nullable' => 'Le champ photo est facultatif.',
            'photo.image' => 'Le champ photo doit être une image.',
            'photo.max' => 'Le champ photo est trop volumineux.',

            'photo_status.nullable' => 'Le champ photo_status est facultatif.',
            'photo_status.in' => 'Le champ photo_status n\'a pas une valeur valide.',

            'role_id.required' => 'Le champ role_id est obligatoire.',
            'role_id.exists' => 'Le rôle n\'existe pas.',
        ];
    }
}
