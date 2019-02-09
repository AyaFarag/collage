<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionSnippetResource extends JsonResource
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
            "id"      => $this -> id,
            "teacher" => $this -> semesterSession -> teacher -> name,
            "date"    => $this -> created_at -> toDateString()
        ];
    }
}
