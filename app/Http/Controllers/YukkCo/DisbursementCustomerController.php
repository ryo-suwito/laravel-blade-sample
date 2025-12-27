<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 14-Sep-21
 * Time: 10:46
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\S;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DisbursementCustomerController extends BaseController {


    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("DISBURSEMENT_CUSTOMER_VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "DISBURSEMENT_CUSTOMER_VIEW",
                ]));
            }
        });
    }


    public function index(Request $request) {
        $page = $request->get("page", 1);
        $date_range_string = $request->get("date_range", null);
        $customer_name = $request->get("customer_name", null);
        $ref_code = $request->get("ref_code", null);
        $per_page = $request->get("per_page", 10);
        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_date = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_date = Carbon::now()->startOfDay();
            }
            try {
                $end_date = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_date = Carbon::now()->endOfDay();
            }
        } else {
            $start_date = Carbon::now()->startOfDay();
            $end_date = Carbon::now()->endOfDay();
        }

        $query_params = [
            "page" => $page,
            "per_page" => $request->has("export_to_csv") ? 9999999 : $per_page,
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }

        if ($customer_name) {
            $query_params["customer_name"] = $customer_name;
        }

        if ($ref_code) {
            $query_params["ref_code"] = $ref_code;
        }


        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        //dd($disbursement_customer_response);
        if ($disbursement_customer_response->is_ok) {
            $result = $disbursement_customer_response->result;

            $disbursement_customer_list = $result->data;

            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Disbursement Customer List " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y") . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'BeneficiaryID',
                    'BeneficiaryName',
                    'DisbursementDate',
                    'PartnerName',
                    'RefCode',
                    'MerchantPortion',
                    'DisbursementFee',
                    'Rounding',
                    'DisbursementAmount',
                    'Transfer Using',
                    'BeneficiaryBankType',
                    'BankName',
                    'AccountNumber',
                    'AccountName',
                    'Status',
                ];

                $callback = function() use ($disbursement_customer_list, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($disbursement_customer_list as $disbursement_customer_master) {
                        fputcsv($file, [
                            @$disbursement_customer_master->customer_id,
                            @$disbursement_customer_master->customer->name,
                            @$disbursement_customer_master->disbursement_date,
                            @$disbursement_customer_master->partner->name,
                            @$disbursement_customer_master->ref_code,
                            @number_format($disbursement_customer_master->total_merchant_portion, 2, ".", ""),
                            @number_format($disbursement_customer_master->disbursement_fee, 2, ".", ""),
                            @number_format($disbursement_customer_master->rounding, 2, ".", ""),
                            @number_format($disbursement_customer_master->total_disbursement, 2, ".", ""),
                            @$disbursement_customer_master->transfer_using,
                            @$disbursement_customer_master->bank_type,
                            @$disbursement_customer_master->bank->name,
                            @$disbursement_customer_master->customer_account_number,
                            @$disbursement_customer_master->customer_account_name,
                            @$disbursement_customer_master->status,
                        ]);
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            } else {
                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_payment_response);
                return view("yukk_co.disbursement_customer.list", [
                    "disbursement_customer_list" => $disbursement_customer_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "start_time" => $start_date,
                    "end_time" => $end_date,
                    "showing_data" => [
                        "from" => $result->from,
                        "to" => $result->to,
                        "total" => $result->total,
                    ],

                    "customer_name" => $customer_name,
                    "ref_code" => $ref_code,
                ]);
            }
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }

    public function show(Request $request, $disbursement_customer_id) {
        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_ITEM_YUKK_CO, [
            "form_params" => [
                "disbursement_customer_master_id" => $disbursement_customer_id,
            ],
        ]);

        if ($disbursement_customer_response->is_ok) {
            $result = $disbursement_customer_response->result;

            return view("yukk_co.disbursement_customer.show", [
                "disbursement_customer" => $result,
            ]);
        } else if ($disbursement_customer_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }

    public function resendEmail(Request $request) {
        $access_control = "DISBURSEMENT_CUSTOMER.RESEND_EMAIL";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $disbursement_customer_master_id = $request->get("disbursement_customer_master_id");

        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_RESEND_EMAIL_YUKK_CO, [
            "form_params" => [
                "disbursement_customer_master_id" => $disbursement_customer_master_id,
            ],
        ]);

        if ($disbursement_customer_response->is_ok) {
            $result = $disbursement_customer_response->result;

            S::flashSuccess(trans("cms.Email Resend. Please wait a moment and then refresh this page."), true);
            return redirect(route("cms.yukk_co.disbursement_customer.item", $result->id));
        } else if ($disbursement_customer_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }


}
