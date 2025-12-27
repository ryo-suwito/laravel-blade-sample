<?php

namespace App\Actions\MerchantAcquisition;

use App\Services\API;
use App\Services\Paginator;
use Exception;

class ImportDeletePten
{
    protected $deletePten;

    public function __construct()
    {
        $this->deletePten = API::instance('merchant_acquisition', 'delete_pten');
    }

    public function importDelete($file)
    {
        $response = $this->deletePten->import($file);
        
        throw_if(!$response->ok(), new Exception("Invalid format data. please use the format from the template file"));

        $duplicates = collect($response->json('result'))
            ->groupBy('id')
            ->filter(fn ($items) => count($items) > 1)
            ->keys()
            ->toArray();
        
        return collect($response->json('result'))
            ->map(function($item) use($duplicates) {
                $item['duplicate'] = in_array($item['id'], $duplicates);

                return $item;
            });
    }
}