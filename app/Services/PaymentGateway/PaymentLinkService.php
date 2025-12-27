<?php

namespace App\Services\PaymentGateway;

use App\Services\APIService;
use Illuminate\Http\UploadedFile;

class PaymentLinkService extends APIService
{
    /**
     * Find payment link by id & params
     *
     * @param int $id
     * @param array $params Eg: ['merchant_branch_id' => ?]
     * @return \Illuminate\Http\Client\Response
     */
    public function find($id, array $params = [])
    {
        return $this->client()
            ->segment(['id' => 2])
            ->get("pg-payment-links/{$id}", $params);
    }

    /**
     * Delete payment link by id & params
     *
     * @param int $id
     * @param array $params Eg: ['merchant_branch_id' => ?]
     * @return \Illuminate\Http\Client\Response
     */
    public function delete($id, array $params = [])
    {
        return $this->client()
            ->segment(['id' => 2])
            ->delete("pg-payment-links/{$id}", $params);
    }

    /**
     * Import payment links
     *
     * @param int $id
     * @param array $params Eg: ['merchant_branch_id' => ?, 'email' => '?']
     * @return \Illuminate\Http\Client\Response
     */
    public function import(UploadedFile $file, array $params)
    {
        return $this->client()
            ->attach('file', $file->getContent(), $file->getClientOriginalName())
            ->post('pg-payment-links/bulk', $params);
    }
}
