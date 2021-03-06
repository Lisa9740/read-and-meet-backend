<?php

namespace App\Http\Resources;

use App\Http\Resources\Collection\MessageCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'users' => $this->users,
            'messages' => new MessageCollection($this->messages),
            'created_at' => $this->created_at
        ];
    }
}
