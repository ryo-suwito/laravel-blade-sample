<?php

namespace App\Actions\MerchantAcquisition;

use App\Helpers\ApiHelper;


class InternalBlackList {
    public function __invoke($request, $id, $entityType)
    {        
        if ($entityType == 'partner') {
            $payload = [
                'id' => $id,
                'name' => $request->get('name'),
                'pic_email' => $request->get('pic_email'),
                'pic_phone' => $request->get('pic_phone'),
                'account_number' => $request->get('account_number'),
                'account_name' => $request->get('account_name'),
                'transfer_type' => $request->get('transfer_type'),
                'bank_id' => $request->get('bank_id'),
                'bank_type' => $request->get('bank_type'),
                'city_name' => $request->get('city_name'),
                'branch_name' => $request->get('branch_name'),
                'partner_parking_account_number' => $request->get('partner_parking_account_number'),
                'snap_client_id' => $request->get('snap_client_id'),
                'snap_client_secret' => $request->get('snap_client_secret'),
            ];

            $data = [];

            $data[] = [
                'name' => 'type',
                'contents' => $entityType
            ];
            $data[] = [
                'name' => 'id',
                'contents' => $id
            ];
            
            foreach ($payload as $key => $value) {
                $data[] = [
                    'name' => $key,
                    'contents' => $value
                ];
            }
        }
        elseif ($entityType == 'customer') {
            $data = array_merge([
                [
                    'name' => 'type',
                    'contents' => $entityType
                ],
                [
                    'name' => 'id',
                    'contents' => $id
                ]
            ],$request);
        }elseif($entityType == 'dataVerif'){
            $data = $request;

            $customerIsNull = false;

            $data[] = [
                'name' => 'type',
                'contents' => 'customer'
            ];

            foreach ($data as &$item) {
                if ($item['name'] === 'ownerName' && $item['contents'] === null) {
                    $customerIsNull = true;
                    break;
                }
                if ($item['name'] === 'ownerName') {
                    $item['name'] = 'name';
                }
                if ($item['name'] === 'ownerEmail') {
                    $item['name'] = 'email';
                }
                if ($item['name'] === 'ownerKTP') {
                    $item['name'] = 'ktp_no';
                }
                if ($item['name'] === 'ownerNPWP') {
                    $item['name'] = 'npwp_no';
                }
                if ($item['name'] === 'ownerPhoneNumber') {
                    $item['name'] = 'contact_no';
                }
                if ($item['name'] === 'ownerCity') {
                    $item['name'] = 'city_id';
                }
                if ($item['name'] === 'accountHolderName') {
                    $item['name'] = 'account_name';
                }
                if ($item['name'] === 'accountNumber') {
                    $item['name'] = 'account_number';
                }
                if ($item['name'] === 'bank') {
                    $item['name'] = 'bank_id';
                }
                if ($item['name'] === 'bankBranch') {
                    $item['name'] = 'branch_name';
                }
            }

            if ($customerIsNull) {
                if ($customerIsNull) {
                    $response = response()->json([
                        'message' => 'ownerName is null, request aborted.',
                    ], 200);
                    $response->status_code = 200;
                    return $response;
                };
            };
            
        } else {
            throw new \Exception('Data must contain either customer or partner information.x');
        }
        $response = ApiHelper::requestGeneral('POST', ApiHelper::END_POINT_INTERNAL_BLACKLIST, [
            'multipart' => $data,
        ]);

        return $response;
    }
}