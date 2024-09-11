<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'login' => $this->login,
            'photo' => $this->photo,
            'photo_status' => $this->photo_status,
            'role_id' => $this->role_id,
            'is_active' => $this->is_active,
        ];
    }
}
