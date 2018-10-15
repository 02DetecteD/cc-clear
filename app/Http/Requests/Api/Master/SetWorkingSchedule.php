<?php

namespace App\Http\Requests\Api\Master;

use App\Models\WorkingSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetWorkingSchedule extends FormRequest
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
            'date' => ['string', 'regex:/(^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])$)/'],
            'hour' => ['required', 'integer', Rule::in(WorkingSchedule::HOURS_WORKING)]
        ];
    }
}
