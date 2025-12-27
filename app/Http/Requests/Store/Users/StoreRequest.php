<?php

namespace App\Http\Requests\Store\Users;

use App\Helpers\H;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function prepareForValidation() {
        $roles = $this->get('roles') ?? [];
        foreach ($roles as $index => $role) {
            $role = json_decode($role, true);
            $roles[$index] = $role;

            if ($role['target_type'] == 'PARTNER') {
                $roles[$index]['targets'] = $this->get('target_partner'); 
            } else if ($role['target_type'] == 'MERCHANT_BRANCH') {
                $roles[$index]['targets'] = $this->get('target_merchant_branch'); 
            } else if ($role['target_type'] == 'CUSTOMER' || $role['target_type'] == 'BENEFICIARY') {
                $roles[$index]['targets'] = $this->get('target_beneficiary'); 
            } else {
                $roles[$index]['targets'] = [];
            }
        }

        $this->merge(['roles' => []]);
        $this->merge([
            '_roles' => $roles
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
