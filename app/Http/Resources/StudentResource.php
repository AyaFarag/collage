<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            "ssn"          => $this -> ssn,
            "phone"        => $this -> phone,
            "gender"       => $this -> gender,
            "birth_date"   => $this -> birth_date -> toDateString(),
            "nationality"  => $this -> nationality,
            "address"      => $this -> address,
            "image"        => url($this -> getFirstMediaUrl("images")),
            "api_token"    => $this -> api_token,
            "device_token" => $this -> device_token
        ];
    }
}
