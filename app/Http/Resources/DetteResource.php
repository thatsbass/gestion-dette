<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Calculate remaining amount
        $montantRestant = $this->montant - $this->paiements->sum('montant');
        
        return [
            'id' => $this->id,
            'montant' => $this->montant,
            'date' => $this->date,
            'client' => new ClientResource($this->whenLoaded('client')),
            'articles' => $this->whenLoaded('articles')->map(function ($article) {
                return [
                    'articleId' => $article->id,
                    'libelle' => $article->libelle,
                    'prix' => $article->pivot->price,
                    'quantite' => $article->pivot->quantity,
                ];
            }),
            'paiements' => $this->whenLoaded('paiements')->map(function ($paiement) {
                return [
                    'id' => $paiement->id,
                    'montant' => $paiement->montant,
                    'date' => $paiement->created_at,
                ];
            }),
            'montantRestant' => $montantRestant,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
