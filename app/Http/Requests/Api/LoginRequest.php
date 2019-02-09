<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\AbstractRequest;

class LoginRequest extends AbstractRequest
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

    public function rules()
    {
        return [
            "phone"        => "nullable",
            "ssn"          => "nullable",
            "password"     => "required",
            "device_token" => "nullable"
        ];
    }

    public function requestAttributes() {
        return [
            "phone",
            "ssn",
            "password",
            "device_token"
        ];
    }
}
