<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPaymentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'montant' => 'required|numeric|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.gt' => 'Le montant doit être supérieur à zéro.',
        ];
    }
}
