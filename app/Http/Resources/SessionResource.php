<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $studentOfDay = $this -> studentsOfDayPivot -> first();

        return [
            "id"                      => $this -> id,
            "students"                => StudentSnippetResource::collection($this -> semesterSession -> class -> students),
            "absent_students_ids"     => $this -> absencesPivot -> map(function ($item) {
                return ["has_permission" => $item -> has_permission, "id" => $item -> student_id];
            }),
            "students_of_the_day_id"  => $studentOfDay ? $studentOfDay -> student_id : null
        ];
    }
}
