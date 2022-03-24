<?php

namespace App\Http\Resources;

use App\Models\Localisation;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPostResource extends JsonResource
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
            'localisation'  => new LocalisationResource($this->localisation),
            'description'   => $this->description,
            'is_visible'    => $this->is_visible,
            'created_at'    => $this->created_at->format('d/m/Y'),
            'updated_at'    => $this->updated_at->format('d/m/Y'),
        ];
    }
}
