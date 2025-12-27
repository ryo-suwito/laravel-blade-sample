<?php

namespace App\Http\Requests\Store\User;

use Illuminate\Foundation\Http\FormRequest;

class ImportFileRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    if ($value->getClientOriginalExtension() != 'csv') {
                        $fail('The ' . $attribute . 'is invalid.');
                    }
                },
            ],
        ];
    }
}
