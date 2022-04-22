<?php

namespace App\Http\Resources\Collection;

use App\Http\Resources\BookResource;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return MessageResource::collection($this->collection);
    }
}
