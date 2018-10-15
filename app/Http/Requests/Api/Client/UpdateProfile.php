<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
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
            'gender' => "integer|in:0,1",
            'home_call' => 'integer|in:0,1',
            'address' => 'string|max:250',
            'about' => 'string|max:250',
            'first_name' => 'string|max:250',
            'surname' => 'string|max:250',
            'avatar' => 'mimes:jpeg,jpg,png|max:1024'
        ];
    }
}
