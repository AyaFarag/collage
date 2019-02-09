<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentSnippetResource extends JsonResource
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
            "name"  => $this -> name,
            "image" => url($this -> getFirstMediaUrl("images")),
            "class" => new ClassResource($this -> whenLoaded("class"))
        ];
    }
}
