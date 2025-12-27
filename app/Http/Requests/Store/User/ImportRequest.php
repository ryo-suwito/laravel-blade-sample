<?php

namespace App\Http\Requests\Store\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
            'users' => 'required|array',
            'users.*.username' => 'required|email',
            'users.*.full_name' => 'required',
            'users.*.email' => 'required|email',
            'users.*.phone' => 'required',
            'users.*.gender' => 'required|in:MALE,FEMALE',
            'users.*.description' => 'nullable',
            'users.*.active' => 'required|in:1,0',
            'users.*.roles' => 'array',
            'users.*.roles.*.id' => 'required',
            'users.*.roles.*.target_type' => 'required',
        ];

        foreach ($this->get('users') as $i => $user) {
            foreach ($user['roles'] as $j => $role) {
                if ($role['target_type'] == 'YUKK_CO') {
                    continue;
                }

                $rules["users.$i.roles.$j.targets"] = 'required|array';
            }
        }

        return $rules;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        abort(response()->json([
            'status_code' => 422,
            'status_message' => __('cms.Invalid data'),
            'result' => $validator->errors(),
        ], 422));
    }
}
