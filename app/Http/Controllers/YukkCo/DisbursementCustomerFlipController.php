<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 14-Mar-23
 * Time: 12:43
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DisbursementCustomerFlipController extends BaseController {

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

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_date = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_date = Carbon::now()->subMonth()->startOfMonth()->startOfDay();
            }
            try {
                $end_date = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_date = Carbon::now()->subMonth()->endOfMonth()->endOfDay();
            }
        } else {
            $start_date = Carbon::now()->subMonth()->startOfMonth()->startOfDay();
            $end_date = Carbon::now()->subMonth()->endOfMonth()->endOfDay();
        }

        $query_params = [
            "page" => $page,
            "per_page" => $request->has("export_to_csv") ? 9999999 : 100,
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }

        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_CUSTOMER_FLIP_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        //dd($disbursement_customer_response);
        if ($disbursement_customer_response->is_ok) {
            $result = $disbursement_customer_response->result;

            $summary = $result->extras;

            $disbursement_customer_list = $result->data;

            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Disbursement Customer Transfer List " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y") . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'BeneficiaryName',
                    'DisbursementDate',
                    'PartnerName',
                    'RefCode',
                    'MerchantPortion',
                    'DisbursementFee',
                    'Rounding',
                    'DisbursementAmount',
                    'UniqueCode',
                    'StatusFlip',
                ];

                $callback = function() use ($disbursement_customer_list, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($disbursement_customer_list as $disbursement_customer_transfer) {
                        fputcsv($file, [
                            @$disbursement_customer_transfer->customer->name,
                            @$disbursement_customer_transfer->disbursement_date,
                            @$disbursement_customer_transfer->partner->name,
                            @$disbursement_customer_transfer->ref_code,
                            @number_format($disbursement_customer_transfer->total_merchant_portion, 2, ",", ""),
                            @number_format($disbursement_customer_transfer->disbursement_fee, 2, ",", ""),
                            @number_format($disbursement_customer_transfer->rounding, 2, ",", ""),
                            @number_format($disbursement_customer_transfer->total_disbursement, 2, ",", ""),
                            @number_format($disbursement_customer_transfer->flip_unique_code, 2, ",", ""),
                            @$disbursement_customer_transfer->status_flip,
                        ]);
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            } else {
                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_payment_response);
                return view("yukk_co.disbursement_customer_flip.list", [
                    "disbursement_customer_list" => $disbursement_customer_list,
                    "summary" => $summary,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                ]);
            }
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }

}