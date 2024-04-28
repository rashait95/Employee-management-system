<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'project_name' => 'required',
            'description' => 'required |text',
           'start_date' =>'required |dateTime',
           'end_date' =>'required|dateTime',
           'employees_id'   => 'required|array',
           'employees_id.*' => 'exists:employees,id'
        ];
    }
}
