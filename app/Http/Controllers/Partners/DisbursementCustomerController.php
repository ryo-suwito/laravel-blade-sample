<?php

namespace App\Http\Controllers\Partners;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DisbursementCustomerController extends BaseController {

    public function index(Request $request) {
        $access_control = "DISBURSEMENT_CUSTOMER.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $date_range_string = $request->get("date_range", null);
        $customer_name = $request->get("customer_name", null);
        $ref_code = $request->get("ref_code", null);

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
            "per_page" => $request->has("export_to_csv") ? 9999999 : 100,
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


        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_LIST_PARTNER, [
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
                    'RefCode',
                    'MerchantPortion',
                    'DisbursementFee',
                    'Rounding',
                    'DisbursementAmount',
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
                            @$disbursement_customer_master->ref_code,
                            @number_format($disbursement_customer_master->total_merchant_portion, 2, ".", ""),
                            @number_format($disbursement_customer_master->disbursement_fee, 2, ".", ""),
                            @number_format($disbursement_customer_master->rounding, 2, ".", ""),
                            @number_format($disbursement_customer_master->total_disbursement, 2, ".", ""),
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
                return view("partners.disbursement_customer.list", [
                    "disbursement_customer_list" => $disbursement_customer_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "start_time" => $start_date,
                    "end_time" => $end_date,

                    "customer_name" => $customer_name,
                    "ref_code" => $ref_code,
                ]);
            }
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }


    public function show(Request $request, $disbursement_customer_id) {
        $access_control = "DISBURSEMENT_CUSTOMER.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_ITEM_PARTNER, [
            "form_params" => [
                "disbursement_customer_master_id" => $disbursement_customer_id,
            ],
        ]);

        if ($disbursement_customer_response->is_ok) {
            $result = $disbursement_customer_response->result;

            return view("partners.disbursement_customer.show", [
                "disbursement_customer" => $result,
            ]);
        } else if ($disbursement_customer_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }

}
