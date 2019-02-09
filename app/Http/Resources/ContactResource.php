<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            "lat"             => $this -> lat,
            "lng"             => $this -> lng,
            "social_networks" => $this -> social_networks,
            "phone_numbers"   => $this -> phone_numbers
        ];
    }
}
