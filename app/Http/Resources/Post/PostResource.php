<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Collection\Book\BookCollection;
use App\Http\Resources\LocalisationResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'user'          => new UserResource($this->user),
            'localisation'  => new LocalisationResource($this->localisation),
            'description'   => $this->description,
            'is_visible'    => $this->is_visible,
            'books'         => new BookCollection($this->books),
            'created_at'    => $this->created_at->format('d/m/Y'),
            'updated_at'    => $this->updated_at->format('d/m/Y'),
        ];
    }
}
