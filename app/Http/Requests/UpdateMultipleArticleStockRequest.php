<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultipleArticleStockRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Assurez-vous que les utilisateurs sont authentifiés
    }

    public function rules()
    {
        return [
            'articles' => 'required|array|min:1',
            'articles.*.id' => 'required|integer',
            'articles.*.qteStock' => 'required|numeric|min:0',
        ];
    }
}
