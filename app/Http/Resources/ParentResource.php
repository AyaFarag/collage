<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
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
            "id"           => $this -> id,
            "name"         => $this -> name,
            "email"        => $this -> email,
            "phone"        => $this -> phone,
            "image"        => url($this -> getFirstMediaUrl("images")),
            "api_token"    => $this -> api_token,
            "device_token" => $this -> device_token
        ];
    }
}
