<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentPointResource extends JsonResource
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
            "reason"     => $this -> reason,
            "session_id" => $this -> session_id,
            "student_id" => $this -> student_id,
            "points"     => $this -> points
        ];
    }
}