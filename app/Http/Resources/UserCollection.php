<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($user) {
                return new UserResource($user);
            }),
            'links' => [
                'self' => $request->url(),
            ],
        ];
    }
}
