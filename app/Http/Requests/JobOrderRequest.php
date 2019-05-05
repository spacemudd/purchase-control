<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
        // TODO: Maybe we can check for permissions of the user
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'cost_center_id' => 'required|exists:departments,id',
            'ext' => 'required',
            'requested_through_type' => 'required',
            'job_description' => 'required|max:255',
            'status' => 'required',
            'remark' => 'required',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
            'location_id' => 'required|exists:locations,id',
        ];
    }
}
