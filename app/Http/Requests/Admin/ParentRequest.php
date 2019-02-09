<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ParentRequest extends FormRequest
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
        $rules = [
            "name"     => "required",
            "email"    => "required|unique:parents",
            "phone"    => "required|unique:parents",
            "password" => "required|confirmed",
            "image"       => "required|image",
        ];

        if ($this -> isMethod("put")) {
            $rules["phone"] = "required|unique:parents,phone," . $this -> parent -> id;
            $rules["email"] = "required|unique:parents,email," . $this -> parent -> id;
            $rules["password"] = "nullable|min:6|confirmed";
        }

        return $rules;
    }
}
