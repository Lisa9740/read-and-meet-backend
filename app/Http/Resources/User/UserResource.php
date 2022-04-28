<?php

namespace App\Http\Resources\User;

use App\Http\Resources\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'firstname'    => $this->firstname,
            'lastname'     => $this->lastname,
            'email'        => $this->email,
            'user_picture' => $this->user_picture,
            'gender'       => $this->gender,
            'age'          => $this->age,
            'profile'      => new ProfileResource($this->profile),
            'profile_id'   => $this->profile_id
        ];
    }
}
