<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleStockRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Assurez-vous que les utilisateurs sont authentifiés
    }

    public function rules()
    {
        return [
            'qteStock' => 'required|numeric|min:0',
        ];
    }
}
