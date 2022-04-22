<?php

namespace App\Http\Resources\Collection\Post;

use App\Http\Resources\Post\PostWithoutUserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostWithoutUserCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return PostWithoutUserResource::collection($this->collection);
    }
}
