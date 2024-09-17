<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
        'surnom' => 'required|string|unique:clients,surnom|max:255',
        'adresse' => 'required|string|max:255',
        'telephone' => 'required|string|unique:clients,telephone|max:15',
        'max_montant' => 'required|numeric|min:0',
        'user' => 'nullable|array',
        'user.nom' => 'required_with:user|string|max:255',
        'user.prenom' => 'required_with:user|string|max:255',
        'user.login' => 'required_with:user|string|unique:users,login|max:255',
        'user.password' => 'required_with:user|string|min:8',
        'user.photo' => 'nullable|image',
        'user.photo_status' => 'nullable|in:valid,invalid',
    ];
}

public function messages(): array
{
    return [
        'surnom.required' => 'Le surnom est requis',
        'surnom.unique' => 'Le surnom doit être unique',
        'surnom.max' => 'Le surnom ne doit pas dépasser 255 caractères',
        'adresse.required' => 'L\'adresse est requise',
        'adresse.max' => 'L\'adresse ne doit pas dépasser 255 caractères',
        'telephone.required' => 'Le numéro de telephone est requis',
        'telephone.unique' => 'Le numéro de telephone doit être unique',
        'max_montant.required' => 'Le montant maximum est requis',
        'max_montant.numeric' => 'Le montant maximum doit être un nombre',
        'max_montant.min' => 'Le montant maximum ne peut pas être négatif',
        'user.nom.required_with' => 'Le nom est requis',
        'user.prenom.required_with' => 'Le prenom est requis',
        'user.login.required_with' => 'Le login est requis',
        'user.password.required_with' => 'Le mot de passe est requis',
        'user.photo.required_with' => 'La photo est requise',
    ];
}

}
