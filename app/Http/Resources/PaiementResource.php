<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaiementResource extends JsonResource
{
    /**
     * Transforme le modèle en tableau de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "montant" => $this->montant,
            "dette_id" => $this->dette_id,
            "client_id" => $this->client_id,
        ];
    }
}
