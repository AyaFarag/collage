<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedResource extends JsonResource
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
            "id"    => $this -> id,
            "title" => $this -> title,
            "sub_title" => $this -> {"sub-title"},
            "image"   => url($this -> getFirstMediaUrl("images")),
            "details" => $this -> details
        ];
    }
}
