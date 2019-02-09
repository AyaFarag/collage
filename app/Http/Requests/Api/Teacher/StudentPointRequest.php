<?php

namespace App\Http\Requests\Api\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StudentPointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "session_id" => "required|exists:sessions,id",
            "student_id" => "required|exists:students,id",
            "points"     => "required|numeric|min:1",
            "reason"     => "required|string"
        ];
    }

    public function requestAttributes() {
        return [
            "session_id",
            "student_id",
            "points",
            "reason"
        ];
    }
}
