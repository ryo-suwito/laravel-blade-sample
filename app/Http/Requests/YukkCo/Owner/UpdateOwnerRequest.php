<?php

namespace App\Http\Requests\YukkCo\Owner;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;

class UpdateOwnerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city_id' => 'required|integer',
            'bank_id' => 'integer|nullable',
            'bank_account_number' => 'string|nullable|max:255',
            'bank_account_name' => 'string|nullable|max:255',
            'bank_account_branch' => 'string|nullable|max:255',
            'merchant_type' => 'required|string|max:255|in:INDIVIDU,BADAN_HUKUM',
            'id_card_number' => 'string|nullable|digits:16',
            'id_card_nationality' => 'string|nullable|max:255',
            'id_card_gender' => 'string|nullable|max:255',
            'id_card_marital_status' => 'string|nullable|max:255',
            'id_card_name' => 'string|nullable|max:255',
            'id_card_job' => 'string|nullable|max:255',
            'id_card_nationality' => 'string|nullable|max:255',
            'id_card_address' => 'string|nullable|max:255',
            'id_card_date_of_birth' => 'string|nullable|max:255',
            'id_card_place_of_birth' => 'string|nullable|max:255',
            'file_ktp' => 'nullable|file|max:2048',
            'file_selfie' => 'nullable|file|max:4096',
            'file_npwp' => 'nullable|file|max:2048',
            'npwp_number' => 'nullable|string',
            'active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'phone.required' => 'The phone field is required.',
            'address.required' => 'The address field is required.',
            'city_id.required' => 'The city field is required.',
            'bank_id.required' => 'The bank field is required.',
            'bank_account_number.required_if' => 'The bank account number field is required.',
            'bank_account_name.required_if' => 'The bank account name field is required.',
            'bank_account_branch.required_if' => 'The bank account branch field is required.',
            'merchant_type.required' => 'The merchant type field is required.',
            'npwp_number.required' => 'The npwp number field is required.',
            'id_card_number.required' => 'The id card number field is required.',
            'id_card_name.required' => 'The id card name field is required.',
            'id_date_of_birth.required' => 'The id date of birth field is required.',
            'file_selfie.max' => 'The selfie image must be less than 4MB.',
            'file_npwp.max' => 'The npwp image must be less than 2MB.',
            'file_ktp.max' => 'The ktp image must be less than 2MB.',
            'file_selfie.required' => 'The selfie image field is required.',
            'file_npwp.required' => 'The npwp image field is required.',
            'file_ktp.required' => 'The ktp image field is required.',
            'active.required' => 'The active field is required.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'timestamp' => round(microtime(true) - LARAVEL_START, 3),
                'status_code' => 42201,
                'status_message' => 'Validation failed',
                'result' => $validator->errors()
            ], 422)
        );
    }
}

