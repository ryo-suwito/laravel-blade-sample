<?php

namespace App\Http\Requests\Store\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function prepareForValidation() {
        $roles = $this->get('roles') ?? [];

        $userRoles = [];
        foreach ($roles as $index => $role) {
            [$roleId, $name, $targetType] = explode("|", $role);

            $userRoles[$index] = [
                'id' => $roleId,
                'target_type' => $targetType
            ];
            if ($targetType == 'PARTNER') {
                $userRoles[$index]['targets'] = $this->get('target_partner'); 
            } else if ($targetType == 'MERCHANT_BRANCH') {
                $userRoles[$index]['targets'] = $this->get('target_merchant_branch'); 
            } else if ($targetType == 'CUSTOMER' || $targetType == 'BENEFICIARY') {
                $userRoles[$index]['targets'] = $this->get('target_beneficiary'); 
            } else {
                $userRoles[$index]['targets'] = [];
            }
        }

        $this->merge(['roles' => []]);
        $this->merge([
            '_roles' => $userRoles
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
