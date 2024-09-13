<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DetteCollection extends ResourceCollection
{
    /**
     * Transforme la collection en tableau de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($dette) {
                return new DetteResource($dette);
            }),
        ];
    }
}
