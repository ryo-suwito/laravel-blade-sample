<?php

namespace App\Http\Requests\Store\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'description' => 'nullable',
            'target_type' => 'required',
            'active' => 'required|in:0,1',
            'access_control' => 'required|array',
            'access_control.*' => 'required',
        ];
    }
}
