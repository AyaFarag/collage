<?php

namespace App\Http\Requests\Api\Teacher\Session;

use App\Http\Requests\Api\AbstractRequest;

use Illuminate\Validation\Rule;

class SessionStartRequest extends AbstractRequest
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
            "semester_session_id" => [
                Rule::exists("semester_sessions", "id") -> where(function ($query) {
                    $query -> where("teacher_id", auth() -> user("teacher-api") -> id);
                })
            ]
        ];
    }

    public function requestAttributes() {
        return [
            "semester_session_id"
        ];
    }
}
