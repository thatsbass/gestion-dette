<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeRequest extends FormRequest
{

    public function authorize(){
        return true;
    }
    public function rules()
    {
        return [
            "montant" => "required|numeric|min:0",
            "etat" => "nullable|string",
            "articles" => "required|array",
            "articles.*.id" => "required|exists:articles,id",
            "articles.*.quantity" => "required|integer|min:1",
        ];
    }

    public function messages(){
        return [
            "articles.*.id.exists" => "L'article n'existe pas.",
            "articles.*.quantity.min" => "La quantité doit être supérieure ou égale à 1.",
            "articles.*.id.required" => "L'article est obligatoire.",
            "articles.*.quantity.required" => "La quantité est obligatoire.",
            "articles.*.quantity.integer" => "La quantité doit être un entier.",
            "montant.numeric" => "Le montant doit être un nombre.",
            "montant.min" => "Le montant doit être supérieur ou égal à 0.",
            "etat.required" => "L'état est obligatoire.",
            "montant.required" => "Le montant est obligatoire.",

        ];
    }
    
}
