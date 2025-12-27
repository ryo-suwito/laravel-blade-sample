<?php

namespace App\Actions\Owner;

use App\Actions\Action;
use App\Helpers\ApiHelper;
use Illuminate\Http\UploadedFile;

class UpdateOwner
{
    public function update(int $id, array $inputs)
    {
        $response = api('merchant_acquisition', 'owners')->update($id, $inputs);

        return $response;
    }
}
