<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            "branch_id"   => "required|exists:branches,id",
            "parent_id"   => "required|exists:parents,id",
            "class_id"    => "required|exists:classes,id",
            "year_id"     => "required|exists:years,id",
            "seat_number" => "required|string",
            "ssn"         => "required|string",
            "image"       => "required|image",
            "address"     => "required",
            "password"    => "required|min:6|confirmed",
            "name"        => "required|string",
            "phone"       => "required|string|unique:students,phone"
                . ($this -> isMethod("put") ? ",{$this -> student -> id}" : ""),
            "gender"      => "required|in:other,male,female",
            "birth_date"  => "required|date_format:Y-m-d",
            "nationality" => "required|string"
        ];

        if ($this -> isMethod("put")) {
            $rules["phone"] = "required|unique:students,phone," . $this -> student -> id;
            $rules["password"] = "nullable|min:6|confirmed";
            $rules["image"] = "nullable|image";
        }
        return $rules;
    }
}
