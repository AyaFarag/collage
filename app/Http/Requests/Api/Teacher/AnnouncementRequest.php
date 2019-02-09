<?php

namespace App\Http\Requests\Api\Teacher;

use App\Http\Requests\Api\AbstractRequest;

class AnnouncementRequest extends AbstractRequest
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
            "title"      => "required|string",
            "content"    => "required|string",
            "attachment" => "required|url"
        ];
    }

    public function requestAttributes() {
        return [
            "title",
            "content",
            "attachment"
        ];
    }
}
