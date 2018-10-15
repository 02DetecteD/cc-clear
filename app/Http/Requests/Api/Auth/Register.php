<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
{
    private
        $phone_regex = '/^\d{10,17}$/';

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
            'code' => 'required|integer|min:10000|max:99999',
            'phone' => "required|integer|regex:{$this->phone_regex}"
        ];
    }
}
