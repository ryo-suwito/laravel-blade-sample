<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            "name" => "required",
            "description" => "required",
            "short_description" => "required",

            "fee_in_percentage" => "required|numeric|min:0|max:100",
            "fee_in_idr" => "required|numeric|min:0",
            "fee_yukk_in_percentage" => "required|numeric|min:0|max:100",
            "fee_yukk_in_idr" => "required|numeric|min:0",
            "minimum_nominal" => "required|numeric|min:0",

            "pic_email" => "nullable|email",
            "pic_phone" => "nullable|regex:/^[0-9]*$/",

            "transfer_type" => "required",
            "partner_parking_account_number" => "required",

            "city_name" => "required",
            "disbursement_fee" => "required|numeric|min:0|regex:/^[0-9]*$/|max:1000000",
            "disbursement_interval" => "required",

            "coa_number_hutang" => "required",
        ];
    }
}
