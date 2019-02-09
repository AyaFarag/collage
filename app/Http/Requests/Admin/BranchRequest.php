<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name' => 'required',
            'address' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'phone_numbers' => 'required|array|min:1',
            'phone_numbers.*' => 'required|string',
            'social_networks' => 'required|array',
            'social_networks.facebook' => 'required|url',
            'social_networks.twitter' => 'required|url',
            'social_networks.instagram' => 'required|url',
            'social_networks.linkedin' => 'required|url',
        ];
    }
}
