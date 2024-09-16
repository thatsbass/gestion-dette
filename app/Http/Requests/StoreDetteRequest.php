<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDetteRequest extends FormRequest
{
    public function rules()
    {
        return [
            "montant" => "required|numeric|gt:0",
            "dealine" => ,
            "client_id" => "required|exists:clients,id",
            "articles" => "required|array|min:1",
            "articles.*.articleId" => "required|exists:articles,id",
            "articles.*.qteVente" => "required|numeric|min:1",
            "articles.*.prixVente" => "required|numeric|gt:0",
            "paiement.montant" => "nullable|numeric|lte:montant",
        ];
    }

    public function messages()
    {
        return [
            "montant.required" => "Le montant est obligatoire.",
            "montant.numeric" => "Le montant doit être un nombre.",
            "montant.gt" => "Le montant doit être supérieur à zéro.",
            "clientId.required" => 'L\'ID du client est obligatoire.',
            "clientId.exists" => 'Le client spécifié n\'existe pas.',
            "articles.required" => "Les articles sont obligatoires.",
            "articles.array" => "Les articles doivent être un tableau.",
            "articles.min" => "Vous devez ajouter au moins un article.",
            "articles.*.articleId.required" =>
                'L\'ID de l\'article est obligatoire.',
            "articles.*.articleId.exists" =>
                'L\'article spécifié n\'existe pas.',
            "articles.*.qteVente.required" =>
                "La quantité vendue est obligatoire.",
            "articles.*.qteVente.numeric" =>
                "La quantité vendue doit être un nombre.",
            "articles.*.qteVente.min" =>
                "La quantité vendue doit être au moins 1.",
            "articles.*.prixVente.required" =>
                "Le prix de vente est obligatoire.",
            "articles.*.prixVente.numeric" =>
                "Le prix de vente doit être un nombre.",
            "articles.*.prixVente.gt" =>
                "Le prix de vente doit être supérieur à zéro.",
            "paiement.montant.numeric" =>
                "Le montant du paiement doit être un nombre.",
            "paiement.montant.lte" =>
                "Le montant du paiement ne peut pas être supérieur au montant de la dette.",
        ];
    }
}
