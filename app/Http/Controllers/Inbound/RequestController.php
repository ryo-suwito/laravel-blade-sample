<?php

namespace App\Http\Controllers\Inbound;

use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Actions\MerchantAcquisition\InternalBlackList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class RequestController extends BaseController
{
    public function ocrKTP(Request $request)
    {
        $multiparts = [];
        if ($request->hasFile('file_ktp')) {
            $ktp = $request->file('file_ktp');
            $fileKtp['name'] = 'file';
            $fileKtp['contents'] =  $ktp->getContent();
            $fileKtp['filename'] =  $request->input('filename') ? $request->input('filename'): $ktp->getClientOriginalName();
            $multiparts[] = $fileKtp;
            // filename is separated from the file itself because the filename is needed for the cache key
        }
        $multiparts[] = [
            "name" => "targetType",
            "contents" => $request->input('targetType') ?? 'REQUEST'
        ];
        $multiparts[] = [
            "name" => "requestId",
            // convert request id to numeric because it is expected to be a number
            "contents" => (int) $request->input('requestId')
        ];
        $response_kym = null;
        try {
            $response_kym = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_KYM_INBOUND_OCR, [
                "multipart" => $multiparts
            ]);

            Cache::put('response_kym', $response_kym, now()->addMinutes(10));
        } catch (\Throwable $th) {
            \Log::error($th);
            // Handle the exception, maybe return an error response
            return new JsonResponse(['error' => 'An error occurred while processing the request'], 500);
        }
        // Return a JSON response with the data received from the API
        return new JsonResponse($response_kym, 200);
    }

    public function index(Request $request){
        $per_page = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $status = $request->get('filter_status');
        $search = $request->get('search');
        $source = $request->get('source');

        $query = [
            'per_page' => $per_page,
            'page' => $page,
            'filter_status' => $status,
            'source' => $source,
            'search' => $search,
        ];

        $requestResponse = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_INBOUND_GET_LIST_TABLE, [
            'query'=> $query
        ]);

        if ($requestResponse->is_ok){
            $result = $requestResponse->result;

            $datas = $result->item;
            $current_page = $result->current_page;
            $last_page = $result->last_page;

            return view('inbound.list', [
                'datas' => $datas,

                'per_page' => $per_page,
                'status' => $status,
                'search' => $search,
                'source' => $source,

                "showing_data" => [
                    "from" => $result->from,
                    "to" => $result->to,
                    "total" => $result->total,
                ],

                'current_page' => $current_page,
                'last_page' => $last_page
            ]);
        }else {
            return $this->getApiResponseNotOkDefaultResponse($requestResponse);
        }
    }

    public function show(Request $request, $id, $type)
    {
        $requestResponse = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_INBOUND_SHOW_REQUEST, [
            'form_params' => [
                'id' => $id,
                'type' => $type,
            ],
        ]);

        if ($requestResponse->is_ok){
            $response = $requestResponse->result;

            $data = $response->data;
            try {
                // getKycLogs
                $responseKymLog = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_KYM_INBOUND_KYC_DATA, [
                    'form_params' => [
                        'request_id' => $id,
                        'identity_number' => isset($data->owner->ktp_number) ? $data->owner->ktp_number : ''
                    ],
                ]);
            } catch (\Throwable $th) {
                $responseKymLog = null;
            }

            if($responseKymLog && $responseKymLog->result){
                $kymResult = isset($responseKymLog->result->result) ? $responseKymLog->result->result : null;
                $verihubsData = $kymResult->verihubs && isset($kymResult->verihubs->status) ? $kymResult->verihubs->status: null;
                $dttotData = $kymResult->dttot && isset($kymResult->dttot->status) ? $kymResult->dttot->status  : null;
            } else {
                $verihubsData = null;
                $dttotData = null;
            }

            if ($data->status == 'APPROVED' && $type == 'edit'){
                return abort(404);
            }

            $mcc = $response->mcc;
            $province = $response->province;
            $ownerCities = $response->ownerCities;
            $merchantCities = $response->merchantCities;
            $merchantRegions = $response->merchantRegions;
            $merchant_type = [
                [
                    'label' => 'INDIVIDU',
                    'value' => 'INDIVIDU',
                ],
                [
                    'label' => 'BADAN HUKUM',
                    'value' => 'BADANHUKUM'
                ]
            ];
            $criteria = [
                [
                    'code' =>'UKE',
                    'name' => 'UKE (Usaha Kecil)'
                ],
                [
                    'code' =>'UMI',
                    'name' => 'UMI (Usaha Mikro)'
                ],
                [
                    'code' =>'UBE',
                    'name' => 'UBE (Usaha Besar)'
                ],
                [
                    'code' =>'URE',
                    'name' => 'URE (Usaha Reguler)'
                ],
                [
                    'code' => 'BLU',
                    'name' => 'BLU (Badan Layanan Umum)'
                ],
                [
                    'code' => 'PSO',
                    'name' => 'PSO (Public Service Obligation)'
                ]
            ];
            $categories = $response->categories;
            $mdrFees = $response->mdrFees;
            $banks = $response->banks;
            $disbursement = [
                [
                    'label' => 'Setiap Hari',
                    'value' => 'DAILY',
                ],
                [
                    'label' => '1 minggu sekali (setiap jumat)',
                    'value' => 'WEEKLY',
                ]
            ];

            $m_type = $response->merchant_type->merchant_type ?? '';

            return view('inbound.detail-edit', [
                'data' => $data,
                'id' => $id,
                'type' => $type,
                'mccs' => $mcc,
                'provinces' => $province,
                'ownerCities' => $ownerCities,
                'merchantCities' => $merchantCities,
                'merchantRegions' => $merchantRegions,
                'criterias' => $criteria,
                'categories' => $categories,
                'mdrFees' => $mdrFees,
                'banks' => $banks,
                'disbursements' => $disbursement,
                'merchant_types' => $merchant_type,
                'm_type' => $m_type,

                'ktp_path' => $response->documents->ktp ?? '',
                'npwp_path' => $response->documents->npwp ?? '',
                'book_account_cover_path' => $response->documents->account_book_cover ?? '',
                'logo_path' => $response->documents->logo ?? '',
                'store_path' => $response->documents->store_photo ?? '',
                'platform_screenshot_path' => $response->documents->platform_screenshot ?? '',
                'thumbnail_path' => $response->documents->thumbnail ?? '',
                'face_photo_path' => $response->documents->face_photo ?? '',

                'verihubsData' => $verihubsData,
                'dttotData' => $dttotData
            ]);
        }else {
            return $this->getApiResponseNotOkDefaultResponse($requestResponse);
        }
    }

    public function update(Request $request, $id)
    {
        $data = [
            [
                "name" => "id",
                "contents" => $id,
            ],
            [
                "name" => "changed_by",
                "contents" => S::getUser()->username,
            ],
            [
                "name" => "merchantMcc",
                "contents" => $request->get('merchantMcc'),
            ],
            [
                "name" => "merchantProvince",
                "contents" => $request->get('merchantProvince'),
            ],
            [
                "name" => "merchantCity",
                "contents" => $request->get('merchantCity'),
            ],
            [
                "name" => "merchantRegion",
                "contents" => $request->get('merchantRegion'),
            ],
            [
                "name" => "merchantMcc",
                "contents" => $request->get('merchantMcc'),
            ],
            [
                "name" => "merchantCategory",
                "contents" => $request->get('merchantCategory'),
            ],
            [
                "name" => "merchantType",
                "contents" => $request->get('merchantType'),
            ],
            [
                "name" => "detailMerchantType",
                "contents" => $request->get('detailMerchantType'),
            ],
            [
                "name" => "merchantName",
                "contents" => $request->get('merchantName'),
            ],
            [
                "name" => "merchantCriteria",
                "contents" => $request->get('merchantCriteria'),
            ],
            [
                "name" => "facebook",
                "contents" => $request->get('facebook'),
            ],
            [
                "name" => "instagram",
                "contents" =>$request->get('instagram'),
            ],
            [
                "name" => "website",
                "contents" => $request->get('website'),
            ],
            [
                "name" => "other",
                "contents" => $request->get('other'),
            ],
            [
                "name" => "merchantBranchTotal",
                "contents" => $request->get('merchantBranchTotal'),
            ],
            [
                "name" => "merchantAddress",
                "contents" => $request->get('merchantAddress'),
            ],
            [
                "name" => "merchantRt",
                "contents" => $request->get('merchantRt'),
            ],
            [
                "name" => "merchantRw",
                "contents" => $request->get('merchantRw'),
            ],
            [
                "name" => "merchantVillage",
                "contents" =>$request->get('merchantVillage'),
            ],
            [
                "name" => "merchantPostalCode",
                "contents" => $request->get('merchantPostalCode'),
            ],
            [
                "name" => "merchantLatitude",
                "contents" => $request->get('merchantLatitude'),
            ],
            [
                "name" => "merchantLongitude",
                "contents" => $request->get('merchantLongitude'),
            ],
            [
                "name" => "merchantBranchName",
                "contents" =>$request->get('merchantBranchName'),
            ],
            [
                "name" => "company_id",
                "contents" => $request->get('company_id'),
            ],
            [
                "name" => "start_date",
                "contents" => $request->get('start_date'),
            ],
            [
                "name" => "end_date",
                "contents" => $request->get('end_date'),
            ],
            [
                "name" => "ownerProvince",
                "contents" => $request->get('ownerProvince'),
            ],
            [
                "name" => "ownerCity",
                "contents" => $request->get('ownerCity'),
            ],
            [
                "name" => "ownerRegion",
                "contents" => $request->get('ownerRegion'),
            ],
            [
                "name" => "ownerName",
                "contents" => $request->get('ownerName'),
            ],
            [
                "name" => "ownerKTP",
                "contents" => $request->get('ownerKTP'),
            ],
            [
                "name" => "ownerNPWP",
                "contents" => $request->get('ownerNPWP'),
            ],
            [
                "name" => "ownerKK",
                "contents" => $request->get('ownerKK'),
            ],
            [
                "name" => "ownerEmail",
                "contents" => $request->get('ownerEmail'),
            ],
            [
                "name" => "ownerPhoneNumber",
                "contents" => $request->get('ownerPhoneNumber'),
            ],
            [
                "name" => "ownerAddress",
                "contents" => $request->get('ownerAddress'),
            ],
            [
                "name" => "ownerRt",
                "contents" =>$request->get('ownerRt'),
            ],
            [
                "name" => "ownerRw",
                "contents" => $request->get('ownerRw'),
            ],
            [
                "name" => "ownerVillage",
                "contents" => $request->get('ownerVillage'),
            ],
            [
                "name" => "ownerPostalCode",
                "contents" => $request->get('ownerPostalCode'),
            ],
            [
                "name" => "ownerDescription",
                "contents" =>$request->get('ownerDescription'),
            ],
            [
                "name" => "bank",
                "contents" => $request->get('bank'),
            ],
            [
                "name" => "accountHolderName",
                "contents" => $request->get('accountHolderName'),
            ],
            [
                "name" => "accountNumber",
                "contents" => $request->get('accountNumber'),
            ],
            [
                "name" => "bankBranch",
                "contents" =>$request->get('bankBranch'),
            ],
            [
                "name" => "locationBankBranch",
                "contents" => $request->get('locationBankBranch'),
            ],
            [
                "name" => "disbursementInterval",
                "contents" => $request->get('disbursementIntervalList'),
            ],
            [
                "name" => "disbursementFee",
                "contents" => $request->get('disbursementFee'),
            ],
            [
                "name" => "mdrFee",
                "contents" =>$request->get('mdrFee'),
            ],
            [
                "name" => "isWhitelist",
                "contents" => $request->get('isWhitelist'),
            ]
        ];

        // verify identity
        $verifyData = [];

        $file_ktp = null;
        $ktp_filename = null;

        if ($request->hasFile('file_ktp')){
            $file_ktp = $request->file('file_ktp');
            $fileKtp['name'] = 'file_ktp';
            $fileKtp['contents'] =  $file_ktp->getContent();
            $fileKtp['filename'] =  $file_ktp->getClientOriginalName();
            $ktp_filename = $file_ktp->getClientOriginalName();

            $oldFileKtp['name'] = 'file_ktp';
            $oldFileKtp['contents'] =  $file_ktp->getContent();
            $oldFileKtp['filename'] =  $file_ktp->getClientOriginalName();
            $data[] = $fileKtp;
            $data[] = $oldFileKtp;

            $fileKtp['name'] = 'identity_image';
            $verifyData[] = $fileKtp;
        }

        if ($request->hasFile('file_npwp')){
            $file_npwp = $request->file('file_npwp');
            $fileNPWP['name'] = 'file_npwp';
            $fileNPWP['contents'] =  $file_npwp->getContent();
            $fileNPWP['filename'] =  $file_npwp->getClientOriginalName();
            $data[] = $fileNPWP;
            $fileNPWP['name'] = 'tax_payer_image';
            $verifyData[] = $fileNPWP;

        }

        if ($request->hasFile('file_account_book_cover')){
            $file_account_book_cover = $request->file('file_account_book_cover');
            $fileAccountBookCover['name'] = 'file_account_book_cover';
            $fileAccountBookCover['contents'] =  $file_account_book_cover->getContent();
            $fileAccountBookCover['filename'] =  $file_account_book_cover->getClientOriginalName();
            $data[] = $fileAccountBookCover;
        }

        if ($request->hasFile('file_logo')){
            $file_logo = $request->file('file_logo');
            $fileLogo['name'] = 'file_ktp';
            $fileLogo['contents'] =  $file_logo->getContent();
            $fileLogo['filename'] =  $file_logo->getClientOriginalName();
            $data[] = $fileLogo;
        }

        if ($request->hasFile('file_store_photo')){
            $file_store_photo = $request->file('file_store_photo');
            $fileStorePhoto['name'] = 'file_store_photo';
            $fileStorePhoto['contents'] =  $file_store_photo->getContent();
            $fileStorePhoto['filename'] =  $file_store_photo->getClientOriginalName();
            $data[] = $fileStorePhoto;
        }

        if ($request->hasFile('file_platform_screenshot')){
            $file_platform_screenshot = $request->file('file_platform_screenshot');
            $filePlatformScreenshot['name'] = 'file_platform_screenshot';
            $filePlatformScreenshot['contents'] =  $file_platform_screenshot->getContent();
            $filePlatformScreenshot['filename'] =  $file_platform_screenshot->getClientOriginalName();
            $data[] = $filePlatformScreenshot;
        }

        if ($request->hasFile('file_thumbnail')){
            $file_thumbnail = $request->file('file_thumbnail');
            $fileThumbnail['name'] = 'file_thumbnail';
            $fileThumbnail['contents'] =  $file_thumbnail->getContent();
            $fileThumbnail['filename'] =  $file_thumbnail->getClientOriginalName();
            $data[] = $fileThumbnail;
        }

        if ($request->hasFile('file_face_photo')){
            $file_face_photo = $request->file('file_face_photo');
            $fileFacePhoto['name'] = 'file_face_photo';
            $fileFacePhoto['contents'] =  $file_face_photo->getContent();
            $fileFacePhoto['filename'] =  $file_face_photo->getClientOriginalName();
            $data[] = $fileFacePhoto;
            $fileFacePhoto['name'] = 'selfie_image';
            $verifyData[] = $fileFacePhoto;
        }

        $verifyData[] = [
            "name" => "identity_number",
            "contents" => $request->get('nik')
        ];
        $verifyData[] = [
            "name" => "tax_payer_number",
            "contents" => $request->get('ownerNPWP')
        ];
        $verifyData[] = [
            "name" => "identity_name",
            "contents" => $request->get('identity_name')
        ];
        $verifyData[] = [
            "name" => "birthdate",
            "contents" => $request->get('birthdate')
        ];
        $verifyData[] = [
            "name" => "email",
            "contents" => $request->get('ownerEmail')
        ];
        $verifyData[] = [
            "name" => "phone",
            "contents" => $request->get('ownerPhoneNumber')
        ];
        $verifyData[] = [
            "name" => "requestId",
            "contents" => $id
        ];
        $verifyData[] = [
            "name" => "owner_name",
            "contents" => $request->get('ownerName')
        ];
        $verifyData[] = [
            "name" => "bank_account_name",
            "contents" => $request->get('accountHolderName')
        ];

        if($ktp_filename){
            $verifyData[] = [
                'name' => 'filename',
                'contents' => $ktp_filename
            ];
        }

        $response_kym = null;

        //internal blacklist
        $response = (new InternalBlacklist)($data, null, $entityType = 'dataVerif');
        if($response->status_code == 200){
            if ($request->has('file_ktp') || $request->has('file_npwp') || $request->has('file_account_book_cover') || $request->hasFile('file_logo')
            || $request->hasFile('file_store_photo') || $request->hasFile('file_platform_screenshot') || $request->hasFile('file_face_photo')){
            
                $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_INBOUND_UPDATE, [
                    'multipart' => $data
                ]);
            }else{
                $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_INBOUND_UPDATE, [
                    'form_params' => [
                        'id' => $id,
                        'changed_by' => S::getUser()->username,
                        'isWhitelist' => $request->get('isWhitelist'),
                        'merchantMcc' => $request->get('merchantMcc'),
                        'merchantProvince' => $request->get('merchantProvince'),
                        'merchantCity' => $request->get('merchantCity'),
                        'merchantRegion' => $request->get('merchantRegion'),
                        'merchantCategory' => $request->get('merchantCategory'),
                        'merchantType' => $request->get('merchantType'),
                        'detailMerchantType' => $request->get('detailMerchantType'),
                        'merchantName' => $request->get('merchantName'),
                        'merchantCriteria'=> $request->get('merchantCriteria'),

                        'facebook' => $request->get('facebook'),
                        'instagram' => $request->get('instagram'),
                        'website' => $request->get('website'),
                        'other' => $request->get('other'),

                        'merchantBranchTotal' => $request->get('merchantBranchTotal'),
                        'merchantAddress' => $request->get('merchantAddress'),
                        'merchantRt' => $request->get('merchantRt'),
                        'merchantRw' => $request->get('merchantRw'),
                        'merchantVillage' => $request->get('merchantVillage'),
                        'merchantPostalCode' => $request->get('merchantPostalCode'),
                        'merchantLatitude' => $request->get('merchantLatitude'),
                        'merchantLongitude' => $request->get('merchantLongitude'),
                        'merchantBranchName' => $request->get('merchantBranchName'),
                        'company_id' => $request->get('company_id'),
                        'start_date' => $request->get('start_date'),
                        'end_date' => $request->get('end_date'),

                        'ownerProvince' => $request->get('ownerProvince'),
                        'ownerCity' => $request->get('ownerCity'),
                        'ownerRegion' => $request->get('ownerRegion'),
                        'ownerName' => $request->get('ownerName'),
                        'ownerKTP' => $request->get('ownerKTP'),
                        'ownerNPWP' => $request->get('ownerNPWP'),
                        'ownerKK' => $request->get('ownerKK'),
                        'ownerPhoneNumber' => $request->get('ownerPhoneNumber'),
                        'ownerAddress' => $request->get('ownerAddress'),
                        'ownerRt' => $request->get('ownerRt'),
                        'ownerRw' => $request->get('ownerRw'),
                        'ownerEmail' => $request->get('ownerEmail'),
                        'ownerVillage' => $request->get('ownerVillage'),
                        'ownerPostalCode' => $request->get('ownerPostalCode'),
                        'ownerDescription' => $request->get('ownerDescription'),

                        'bank' => $request->get('bank'),
                        'accountHolderName' => $request->get('accountHolderName'),
                        'accountNumber' => $request->get('accountNumber'),
                        'bankBranch' => $request->get('bankBranch'),
                        'locationBankBranch' => $request->get('locationBankBranch'),
                        'disbursementInterval' => $request->get('disbursementIntervalList'),
                        'disbursementFee' => $request->get('disbursementFee'),
                        'mdrFee' => $request->get('mdrFee'),
                    ]
                ]);
            }
        }else{
            $responseErrors = [];
            $responseData = json_decode($response->body_string, true);
            $ktpBlacklist = $responseData['ktp_blacklist'] ?? null;
            $cekRekening = $responseData['cek_rekening'] ?? null;
            $ktp_no = $request->get('ownerKTP');
            if ($ktpBlacklist) {
                $responseErrors['ktp_blacklist'] = [
                    'status_message' => 'KTP Number '. $ktp_no .' is blacklisted. Please use a different KTP',
                ];
            }
            if ($cekRekening){
                $responseErrors['cek_rekening'] = [
                    'status_message' => $cekRekening['status_message'],
                ];
            }

            return response()->json($responseErrors, 409);
        }

        if ($response->http_status_code != '200'){
            return response()->json([
                'message' => $response->status_message
            ], $response->http_status_code);
        }

        if($request->has('isWhitelist') && $request->get('isWhitelist')){
            return response()->json([
                'message' => 'Success',
                'redirect_url' => route('yukk_co.data_verification.list')
            ], 200);
        }

        try {
            $response_kym = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_KYM_INBOUND_VERIFY, [
                "multipart" => $verifyData
            ]);
        } catch (\Throwable $th) {
            $response_kym = null;
        }

        $responseErrorKym = null;
        $responseErrorKymCode = null;
        if(!$response_kym){
            return response()->json([
                'message' => 'Failed to verify KYM data'
            ], 500);
        } else if ($response_kym->http_status_code != '200'){
            $responseErrorKym = $response_kym->status_message;
            $responseErrorKymCode = $response_kym->http_status_code;
        }
        if ($response->http_status_code == '200' && $request->get('isWhitelist')){
            H::flashSuccess('Success', true);
            return response()->json([
                'redirect_url' => route('yukk_co.data_verification.list')
            ], 200);
        }else if($response->http_status_code == '200' && !$request->get('isWhitelist')){
            return response()->json([
                $message = $responseErrorKym ? 'Failed to verify KYM data : ' . $responseErrorKym . ' (' . $responseErrorKymCode . ')' : 'Success',
                'message' => $message,
                'redirect_url' =>route('yukk_co.data_verification.detail', ['id' => $id, 'type' => 'show'])
            ], 200);
        }else {
            return response()->json([
                'message' => $response->status_message
            ], $response->http_status_code);
        }
    }

    public function changeStatus(Request $request, $id, $type){
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_INBOUND_CHANGE_STATUS, [
            'form_params' => [
                'id' => $id,
                'type' => $type,
                'user' => S::getUser()->username,
                'changed_by' => S::getUser()->id,
            ]
        ]);

        if ($type == 'Approve') {
            // CONDITION IF STATUS IS APPROVE
            if ($response->result){
                $dataResult = $response->result;
                $customer = $dataResult->customer;
                $requestParam = [
                    'customer_id' => $customer->id,
                    'identity_number' => $customer->ktp_no,
                    'requestId' => $id
                ];
                try{
                    $responseBindKymData = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_KYM_INBOUND_BIND_KYM_DATA, [
                        'form_params' => $requestParam
                    ]);
                    \Log::info("Bind KYM Data: " . json_encode($responseBindKymData));
                    if(!$responseBindKymData){
                        \Log::error('Failed to bind KYM data');
                        return $this->getApiResponseNotOkDefaultResponse($response);
                    }
                } catch (\Throwable $th) {
                    \Log::error($th);
                }
            }else{
                return $this->getApiResponseNotOkDefaultResponse($response);
            }
        }

        if ($response->http_status_code == '200'){
            H::flashSuccess('Success', true);

            return redirect(route('yukk_co.data_verification.list'));
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function getCity(Request $request)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_INBOUND_GET_CITY_LIST, [
            'form_params' => [
                'province_id' => $request->get('province_id')
            ],
        ]);

        if ($response->is_ok){
            return response()->json($response->result);
        }else{
            Log::error('Tidak Masuk: ' . $response->body_string);
            return response()->json([],400);
        }
    }

    public function getRegion(Request $request)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_INBOUND_GET_REGION_LIST, [
            'form_params' => [
                'city_id' => $request->get('city_id')
            ],
        ]);

        if ($response->is_ok){
            return response()->json($response->result);
        }else{
            return response()->json([],400);
        }
    }

    public function getImageIndex()
    {
        abort(404);
    }

    public function getImage($path)
    {
        // Fetch the image data from the external URL
        $response = Http::get(config('inbound.base_url').'/'.config('inbound.end_point.proxy_image').$path);

        // Check if the request was successful
        if ($response->successful()) {
            // Get the content type of the response
            $contentType = $response->header('content-type');

            // Return a response with the fetched image data and appropriate headers
            return response($response->body())
                ->header('Content-Type', $contentType);
        } else {
            // If the request was not successful, return an error response
            return response()->json(['error' => 'Failed to fetch image'], $response->status());
        }
    }
    // get kyc logs by request id
    public function getKycLogs(Request $request)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_KYM_INBOUND_KYC_DATA, [
            'form_params' => [
                'request_id' => $request->get('request_id'),
                'identity_number' => $request->get('identity_number')
            ],
        ]);

        \Log::info("Get KYC Logs: " . json_encode($response));
        $result = [
            'status' => 'error',
            'message' => 'Failed to get KYC logs'
        ];
        if ($response->http_status_code == '200'){
            $result = [
                'status' => 'success',
                'message' => 'Success',
                'data' => $response->result
            ];
            return response()->json($result);
        }else{
            return response()->json($result, $response->http_status_code);
        }
    }
}
