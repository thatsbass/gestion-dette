<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($client) {
                return new ClientResource($client);
            }),
            'links' => [
                'self' => $request->url(),
            ],
        ];
    }
}
