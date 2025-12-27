<?php

namespace App\Actions\Owner;

use Illuminate\Http\UploadedFile;

class OCR
{
    /**
     * Scan KTP
     * 
     * @param UploadedFile $file
     * @return \Illuminate\Http\Client\Response
     */
    public function scanKtp(UploadedFile $file)
    {
        $response = api('merchant_acquisition', 'owners')->scanKtp($file);

        return $response;
    }
}   