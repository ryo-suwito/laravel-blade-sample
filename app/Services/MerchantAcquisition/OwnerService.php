<?php

namespace App\Services\MerchantAcquisition;

use App\Services\APIService;
use Illuminate\Http\UploadedFile;

class OwnerService extends APIService
{
    /**
     * @param UploadedFile $file
     * @return \Illuminate\Http\Client\Response
     */
    public function scanKtp(UploadedFile $file) {
        $client = $this->client()->attach('file_ktp', $file->get(), $file->getClientOriginalName());

        return $client->post('merchant-acquisition/owner/scan/ktp');
    }

    /**
     * @param array $inputs{
     *  'file_ktp' => UploadedFile
     *  'file_selfie' => UploadedFile
     *  'file_npwp' => UploadedFile
     *  'name' => string
     *  'phone' => string
     *  'email' => string
     *  'address' => string
     *  'birth_date' => string
     *  'birth_place' => string
     *  'gender' => string
     * }
     * @return \Illuminate\Http\Client\Response
     */
    public function verify(array $inputs, array $options = [])
    {
        $client = $this->client();

        if (isset($inputs['file_ktp'])) {
            $client = $client->attach('file_ktp', $inputs['file_ktp']->get(), $inputs['file_ktp']->getClientOriginalName());
        }

        if (isset($inputs['file_selfie'])) {
            $client = $client->attach('file_selfie', $inputs['file_selfie']->get(), $inputs['file_selfie']->getClientOriginalName());
        }

        if (isset($inputs['file_npwp'])) {
            $client = $client->attach('file_npwp', $inputs['file_npwp']->get(), $inputs['file_npwp']->getClientOriginalName());
        }

        return $client->post('merchant-acquisition/owner/verify', array_merge([
            'id' => data_get($options, 'id', null),
        ], $inputs));
    }

    /**
     * @param array $inputs{
     *  'file_ktp' => UploadedFile
     *  'file_selfie' => UploadedFile
     *  'file_npwp' => UploadedFile
     *  'name' => string
     *  'phone' => string
     *  'email' => string
     *  'address' => string
     *  'birth_date' => string
     *  'birth_place' => string
     *  'gender' => string
     * }
     * @return \Illuminate\Http\Client\Response
     */
    public function store(array $inputs)
    {
        if (isset($inputs['id_card_date_of_birth'])) {
            $inputs['id_card_date_of_birth'] = date('Y-m-d', strtotime($inputs['id_card_date_of_birth']));
        }

        $client = $this->client();

        if (isset($inputs['file_ktp'])) {
            $client = $client->attach('file_ktp', $inputs['file_ktp']->get(), $inputs['file_ktp']->getClientOriginalName());
        }

        if (isset($inputs['file_selfie'])) {
            $client = $client->attach('file_selfie', $inputs['file_selfie']->get(), $inputs['file_selfie']->getClientOriginalName());
        }

        if (isset($inputs['file_npwp'])) {
            $client = $client->attach('file_npwp', $inputs['file_npwp']->get(), $inputs['file_npwp']->getClientOriginalName());
        }

        unset($inputs['file_ktp']);
        unset($inputs['file_selfie']);
        unset($inputs['file_npwp']);

        return $client->post('merchant-acquisition/owner/store', $inputs);
    }

    public function update(int $id, array $inputs)
    {
        if (isset($inputs['id_card_date_of_birth'])) {
            $inputs['id_card_date_of_birth'] = date('Y-m-d', strtotime($inputs['id_card_date_of_birth']));
        }

        $client = $this->client()->segment([
            'id' => 3,
        ]);

        if (isset($inputs['file_ktp'])) {
            $client = $client->attach('file_ktp', $inputs['file_ktp']->get(), $inputs['file_ktp']->getClientOriginalName());
        }

        if (isset($inputs['file_selfie'])) {
            $client = $client->attach('file_selfie', $inputs['file_selfie']->get(), $inputs['file_selfie']->getClientOriginalName());
        }

        if (isset($inputs['file_npwp'])) {
            $client = $client->attach('file_npwp', $inputs['file_npwp']->get(), $inputs['file_npwp']->getClientOriginalName());
        }

        unset($inputs['file_ktp']);
        unset($inputs['file_selfie']);
        unset($inputs['file_npwp']);

        return $client->post("merchant-acquisition/owner/{$id}", $inputs);
    }
}
