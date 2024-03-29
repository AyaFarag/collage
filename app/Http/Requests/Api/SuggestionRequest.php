<?php

namespace App\Http\Requests\Api;


class SuggestionRequest extends AbstractRequest
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
            "title"       => "required|string",
            "description" => "required|string"
        ];
    }

    public function requestAttributes() {
        return ["title", "description"];
    }
}
