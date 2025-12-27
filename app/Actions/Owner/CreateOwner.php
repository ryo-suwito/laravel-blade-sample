<?php

namespace App\Actions\Owner;

use App\Actions\Action;
use App\Helpers\ApiHelper;
use Illuminate\Http\UploadedFile;

class CreateOwner
{
    public function save(array $inputs)
    {
        $response = api('merchant_acquisition', 'owners')->store($inputs);

        return $response;
    }

    public function verify(array $inputs) {
        $response = api('merchant_acquisition', 'owners')->verify($inputs);

        return $response;
    }
}
