<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attachment = $this -> getFirstMediaUrl("attachments");
        return [
            "id"         => $this -> id,
            "points"     => $this -> points ?? 0,
            "is_awarded" => !is_null($this -> points),
            "attachment" => $attachment ? url($attachment) : null,
            "student"    => new StudentSnippetResource($this -> student)
        ];
    }
}
