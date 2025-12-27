<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Http\Requests\Partner\StoreRequest;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Actions\MerchantAcquisition\InternalBlackList;

class   PartnerActivationController extends BaseController
{
    protected $bank_account;

    public function __construct()
    {
        $this->bank_account = api('core_api', 'bank_account');
    }

    public function index(Request $request)
    {
        $access_control = ["MASTER_DATA.PARTNER.VIEW", "MASTER_DATA.PARTNER.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $page = $request->get("page", 1);
            $partner = $request->get('partner', null);
            $code = $request->get('code', null);
            $per_page = $request->get('per_page', 10);
            $partner_type = $request->get('partner_type', null);

            $query_params = [
                "per_page" => $per_page,
                "page" => $page,
                "search" => $partner,
                "code" => $code,
                "partner_type" => $partner_type,
            ];

            $access_control = json_decode(S::getUserRole()->role->access_control);

            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_LIST_YUKK_CO, [
                'query' =>  $query_params,
            ]);

            if ($partner_response->is_ok) {
                $partner_list = $partner_response->result->data;

                $current_page = $partner_response->result->current_page;
                $last_page = $partner_response->result->last_page;
                return view("yukk_co.partner_activation.list", [
                    "partner_list" => $partner_list,

                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "partner" => $partner,
                    "code" => $code,

                    "showing_data" => [
                        "from" => $partner_response->result->from,
                        "to" => $partner_response->result->to,
                        "total" => $partner_response->result->total,
                    ],

                    "access_control" => $access_control,
                    "per_page" => $per_page,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function detail(Request $request, $partner_id){
        $access_control = ["MASTER_DATA.PARTNER.VIEW", "MASTER_DATA.PARTNER.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_EDIT_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                ],
            ]);

            $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, []);
            $bank_list = $banks->http_status_code == 200 ? $banks->result : null;

            if ($partner_response->is_ok) {
                $partner = $partner_response->result->partner;
                $bank_account_list = $partner_response->result->bank_account;

                $owner = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_ITEM, [
                    "query" => [
                        'id' => $partner->owner_id
                    ],
                ]);

                return view("yukk_co.partner_activation.detail", [
                    "partner" => $partner,
                    "owner" => $owner->result,
                    "banks" => $bank_list,
                    "bank_account_list" => $bank_account_list,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $partner_id)
    {
        $access_control = "MASTER_DATA.PARTNER.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_EDIT_YUKK_CO, [
                "form_params" => [
                    "partner_id" => $partner_id,
                ],
            ]);

            $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, []);
            $rek_parkings = $this->bank_account->paginated($request->all());
            $parking_accounts = $rek_parkings->json('status_code') == '6000' ? $rek_parkings->json('result') : null;
            $bank_list = $banks->http_status_code == 200 ? $banks->result : null;

            if (is_null($bank_list)) {
                H::flashFailed($banks->status_message, true);
                return back();
            }

            if ($partner_response->is_ok) {
                $partner = $partner_response->result->partner;
                $bank_account_list = $partner_response->result->bank_account;

                $owner = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_GET_LIST, [
                    "query" => [
                        "fields" => "id,name",
                    ],
                ]);

                return view("yukk_co.partner_activation.edit", [
                    "partner" => $partner,
                    "bank_account_list" => $bank_account_list,
                    "banks" => $bank_list,
                    "owner" => $owner->result,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($partner_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $partner_id)
    {
        $access_control = "MASTER_DATA.PARTNER.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $rules = [
                "owner_id" => "required",
                "name" => "required",
                "partner_type" => "required",
                "description" => "required",
                "short_description" => "required",
                "fee_in_percentage" => "required|numeric|between:0,100",
                "fee_in_idr" => "required|numeric|min:0",
                "fee_yukk_in_percentage" => "required|numeric|between:0,100",
                "fee_yukk_in_idr" => "required|numeric|min:0",
                "minimum_nominal" => "required|numeric|min:0",
                "pic_email" => "nullable|email",
                "pic_phone" => "nullable|regex:/^[0-9]*$/",
                "transfer_type" => "required",
                "city_name" => "required",
                "disbursement_fee" => "required|numeric|between:0,1000000",
                "partner_parking_account_number" => "required",
                "disbursement_interval" => "required",
            ];

            if ($request->has('is_snap_enabled')) {
                $rules += [
                    "snap_client_id" => "required|max:32",
                    "snap_client_secret" => "required|max:40",
                    "snap_public_key" => "required",
                    "qr_access_token_notify_base_url" => "required",
                    "qr_access_token_notify_relative_path" => "required",
                    "qr_mpm_notify_base_url" => "required",
                    "qr_mpm_notify_relative_path" => "required",
                    "snap_notify_client_id" => "required",
                    "snap_notify_client_secret" => "required",
                    "channel_id" => "required|size:5",
                ];
            }

            if(!$request->has('owner_fill_rekening')){
                $rules += [
                    "account_number" => "required",
                    "account_name" => "required",
                    "bank_id" => "required",
                    "branch_name" => "required",
                ];
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                H::flashFailed($validator->errors()->first(), true);
                $validator->validate();
                return back();
            }

            
            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_UPDATE_YUKK_CO, [
                "form_params" => [
                    'id' => $partner_id,
                    'owner_id' => $request->get('owner_id'),
                    'code' => $request->get('code'),
                    'name' => $request->get('name'),
                    'partner_type' => $request->get('partner_type'),
                    'description' => $request->get('description'),
                    'short_description' => $request->get('short_description'),
                    'fee_partner_percentage' => $request->get('fee_in_percentage') ,
                    'fee_partner_fixed' => $request->get('fee_in_idr'),
                    'fee_yukk_additional_percentage' => $request->get('fee_yukk_in_percentage') ,
                    'fee_yukk_additional_fixed' => $request->get('fee_yukk_in_idr'),
                    'minimum_nominal' => $request->get('minimum_nominal'),

                    'is_pic_details_using_owner' => $request->has('owner_fill_information'),
                    'pic_name' => $request->get('pic_name'),
                    'pic_email' => $request->get('pic_email'),
                    'pic_phone' => $request->get('pic_phone'),
                    'email_list' => $request->get('email_list'),

                    'account_number' => $request->get('account_number'),
                    'account_name' => $request->get('account_name'),
                    'bank_id'=> $request->get('bank_id'),
                    'bank_type' => $request->get('bank_type'),
                    'account_branch_name' => $request->get('branch_name'),
                    'account_city_name' => $request->get('city_name'),
                    'transfer_type' => $request->get('transfer_type'),
                    'disbursement_fee' => $request->get('disbursement_fee'),
                    'auto_disbursement_interval' => $request->get('disbursement_interval'),

                    'is_bank_details_using_owner' => $request->has('owner_fill_rekening'),
                    'bank_account_id' => $request->get('partner_parking_account_number'),
                    'rek_parking_account_number' => $request->get('partner_owner_number'),
                    'rek_parking_account_name' => $request->get('partner_owner_name'),

                    'snap_enabled' => $request->get('is_snap_enabled'),

                    'snap_client_id' => $request->get('snap_client_id'),
                    'snap_client_secret' => $request->get('snap_client_secret'),
                    'snap_public_key' => $request->get('snap_public_key'),
                    'qr_access_token_notify_base_url' => $request->get('qr_access_token_notify_base_url'),
                    'qr_access_token_notify_relative_path' => $request->get('qr_access_token_notify_relative_path'),
                    'qr_mpm_notify_base_url' => $request->get('qr_mpm_notify_base_url'),
                    'qr_mpm_notify_relative_path' => $request->get('qr_mpm_notify_relative_path'),
                    'snap_notify_client_id' => $request->get('snap_notify_client_id'),
                    'snap_notify_client_secret' => $request->get('snap_notify_client_secret'),
                    'channel_id' => $request->get('channel_id'),
                    'webhook_url_api_qris_registration' => $request->get('webhook_url_api_qris_registration'),
                ],
            ]);

            if ($partner_response->http_status_code !== 201) {
                H::flashFailed($partner_response->status_message, true);
                return redirect(route('yukk_co.partner.edit', $partner_id));
            }else{
                H::flashSuccess("Data changes are successfully saved and are in the process of being reviewed first", true);
                return redirect(route('yukk_co.partner.detail', $partner_id));
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function create(Request $request)
    {
        $access_control = "MASTER_DATA.PARTNER.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $banks = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_BANK_GET_LIST, []);
    
            $rek_parkings = $this->bank_account->paginated($request->all());
            $parking_accounts = $rek_parkings->json('status_code') == '6000' ? $rek_parkings->json('result') : null;
            $bank_list = $banks->http_status_code == 200 ? $banks->result : null;

            if (is_null($parking_accounts)) {
                H::flashFailed($rek_parkings->json('status_message'), true);
                return back();
            }
            if (is_null($bank_list)) {
                H::flashFailed($banks->status_message, true);
                return back();
            }

            $owner = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_OWNER_GET_LIST, [
                "query" => [
                    "fields" => "id,name",
                ],
            ]);

            return view('yukk_co.partner_activation.create', ([
                'owner' => $owner->result,
                'banks'=> $bank_list,
                'parking_accounts' => $parking_accounts
            ]));
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function store(Request $request){
        $access_control = "MASTER_DATA.PARTNER.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $rules = [
                "name" => "required",
                "partner_type" => "required",
                "description" => "required",
                "short_description" => "required",
                "fee_in_percentage" => "required|numeric|between:0,100",
                "fee_in_idr" => "required|numeric|min:0",
                "fee_yukk_in_percentage" => "required|numeric|between:0,100",
                "fee_yukk_in_idr" => "required|numeric|min:0",
                "minimum_nominal" => "required|numeric|min:0",
                "owner_id" => "required",
                "pic_name" => "required",
                "pic_email" => "required|email",
                "pic_phone" => "required|regex:/^[0-9]*$/",
                "city_name" => "required",
                "transfer_type" => "required",
                "disbursement_fee" => "required|numeric|between:0,1000000",
                "partner_parking_account_number" => "required",
                "disbursement_interval" => "required",
                "coa_number_hutang" => "required",
            ];

            if ($request->has('is_snap_enabled')) {
                $rules += [
                    "snap_client_id" => "required|max:32",
                    "snap_client_secret" => "required|max:40",
                    "snap_public_key" => "required",
                    "qr_access_token_notify_base_url" => "required",
                    "qr_access_token_notify_relative_path" => "required",
                    "qr_mpm_notify_base_url" => "required",
                    "qr_mpm_notify_relative_path" => "required",
                    "snap_notify_client_id" => "required",
                    "snap_notify_client_secret" => "required",
                    "channel_id" => "required|size:5",
                ];
            }

            if(!$request->has('owner_fill_rekening')){
                $rules += [
                    "account_number" => "required",
                    "account_name" => "required",
                    "bank_id" => "required",
                    "branch_name" => "required",
                ];
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                H::flashFailed($validator->errors()->first(), true);
                $validator->validate();
                return back()->withInput();
            }


            $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_STORE_YUKK_CO, [
                "form_params" => [
                    'owner_id' => $request->get('owner_id'),
                    'name' => $request->get('name'),
                    'partner_type' => $request->get('partner_type'),
                    'description' => $request->get('description'),
                    'short_description' => $request->get('short_description'),

                    'fee_partner_percentage' => $request->get('fee_in_percentage') ,
                    'fee_partner_fixed' => $request->get('fee_in_idr'),
                    'fee_yukk_additional_percentage' => $request->get('fee_yukk_in_percentage') ,
                    'fee_yukk_additional_fixed' => $request->get('fee_yukk_in_idr'),
                    'minimum_nominal' => $request->get('minimum_nominal'),
                    
                    'is_pic_details_using_owner' => $request->has('owner_fill_information'),
                    'pic_name' => $request->get('pic_name'),
                    'pic_email' => $request->get('pic_email'),
                    'pic_phone' => $request->get('pic_phone'),
                    'email_list' => $request->get('email_list'),
                    
                    'is_bank_details_using_owner' => $request->has('owner_fill_rekening'),
                    'bank_id' => $request->get('bank_id'),
                    'account_number' => $request->get('account_number'),
                    'account_name' => $request->get('account_name'),
                    'coa_number_hutang' => $request->get("coa_number_hutang"),

                    'bank_type' => ($request->get('bank_id') == 1) ? 'BCA' : 'NON_BCA',
                    'branch_name' => $request->get('branch_name'),
                    'city_name' => $request->get('city_name'),

                    'transfer_type' => $request->get('transfer_type'),
                    'disbursement_fee' => $request->get('disbursement_fee'),
                    'rek_parking_account_number' => $request->get('partner_parking_account_number'),
                    'auto_disbursement_interval' => $request->get('disbursement_interval'),
                    'partner_parking_account_number' => $request->get('partner_parking_account_number'),
                    'partner_owner_name' => $request->get('partner_owner_name'),
                    'partner_account_number' => $request->get('partner_account_number'),
    
                    'snap_enabled' => $request->get('is_snap_enabled'),
                    'snap_client_id' => $request->get('snap_client_id'),
                    'snap_client_secret' => $request->get('snap_client_secret'),
                    'snap_public_key' => $request->get('snap_public_key'),
                    'qr_access_token_notify_base_url' => $request->get('qr_access_token_notify_base_url'),
                    'qr_access_token_notify_relative_path' => $request->get('qr_access_token_notify_relative_path'),
                    'qr_mpm_notify_base_url' => $request->get('qr_mpm_notify_base_url'),
                    'qr_mpm_notify_relative_path' => $request->get('qr_mpm_notify_relative_path'),
                    'snap_notify_client_id' => $request->get('snap_notify_client_id'),
                    'snap_notify_client_secret' => $request->get('snap_notify_client_secret'),
                    'channel_id' => $request->get('channel_id'),
                    'webhook_url_api_qris_registration' => $request->get('webhook_url_api_qris_registration'),
                ],
            ]);
    
            if ($partner_response->http_status_code == '201'){
                H::flashSuccess($partner_response->status_message, true);
                return redirect(route('yukk_co.partner.detail', $partner_response->result->id));
            } elseif($partner_response->http_status_code == '422'){
                H::flashFailed($partner_response->status_message, true);
                return back()->withErrors(['account_number' => $partner_response->status_message])->withInput();
            }else {
                H::flashFailed($partner_response->status_message, true);
                return back()->withInput();
            }
        }else{
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function downloadPublicKey(Request $request)
    {
        $access_control = "MASTER_DATA.PARTNER.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $public_key = env('YUKK_SNAP_QRIS_PUBLIC_KEY_PATH', 'storage/app/keys/rsa_public_key.pem');
            // if not found, return back with error message
            if (!file_exists(base_path($public_key))) {
                H::flashFailed("Public Key not found", true);
                return back();
            }
            $file = file_get_contents(base_path($public_key));
            $filename = basename(base_path($public_key));
            return response($file, 200)->header('Content-Type', 'application/x-pem-file')->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

    }

    public function getBankAccountsJsonSelect2(Request $request)
    {
        $data = $this->bank_account->paginated($request->all());

        if ($data->json('status_code') == '6000'){
            $result = $data->json('result');
            $data = $result['data'];

            foreach ($data as $item) {
                $response[] = array(
                    "id" => $item['account_number'],
                    "text" => $item['account_number'] . ' - ' .$item['name'],
                    "coa_parking" => $item['coa_code_bank'],
                    "account_name" => $item['account_name'],
                    "bank_account_id" => $item['id']
                );
            }
        }

        return response()->json([
            'response' => $response,
            'total' => $result['total']
        ]);
    }

    public function listJson(Request $request) {
        $access_control = ["MASTER_DATA.PARTNER.VIEW","MASTER_DATA.PARTNER.UPDATE"];

        if (!AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            return response()->json([], 401);
        }

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_LIST_YUKK_CO, [
            'form_params' => [
                'page' => $request->get('page', 1),
                'search' => $request->get('search', ''),
                'per_page' => $request->get('per_page'),
                'flag' => $request->get('flag'),
            ]
        ]);

        if ($response->is_ok) {
            return response()->json([
                'result' => $response->result->data,
                'more' => $response->result->next_page_url != null ? true : false,
                'page' => $response->result->current_page,
            ]);
        } else {
            return response()->json([], 400);
        }
    }
}
