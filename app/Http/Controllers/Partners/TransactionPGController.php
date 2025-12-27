<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 21-Dec-21
 * Time: 11:12
 */

namespace App\Http\Controllers\Partners;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TransactionPGController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("TRANSACTION_PG_LIST", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "TRANSACTION_PG_LIST",
                ]));
            }
        });
    }

    public function index(Request $request) {
        $page = $request->get("page", 1);
        $order_id = $request->get("order_id", null);
        $date_range_string = $request->get("date_range", null);
        $payment_channel_id = $request->get("payment_channel_id", null);
        $paid_at_date_range_string = $request->get("paid_at_date_range", null);
        $paid_at_null = $request->has("paid_at_null");
        $is_settle = $request->get("is_settle", "ALL");

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_time = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_time = Carbon::now()->startOfDay();
            }
            try {
                $end_time = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_time = Carbon::now()->endOfDay();
            }
        } else {
            $start_time = Carbon::now()->startOfDay();
            $end_time = Carbon::now()->endOfDay();
        }


        if ($paid_at_date_range_string) {
            $date_range_exploded = explode(" - ", $paid_at_date_range_string);
            try {
                $paid_at_start_time = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $paid_at_start_time = Carbon::now()->startOfDay();
            }
            try {
                $paid_at_end_time = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $paid_at_end_time = Carbon::now()->endOfDay();
            }
        } else {
            $paid_at_start_time = Carbon::now()->startOfDay();
            $paid_at_end_time = Carbon::now()->endOfDay();
        }

        // Per page 99999999 because want to SUM grand_total and count(*) on the fly (from CMS)
        $per_page = 99999999;
        /*if ($request->has("export_to_csv")) {
            $per_page = 99999999;
        }*/
        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
        ];
        if ($order_id) {
            $query_params["order_id"] = $order_id;
        }
        if ($start_time && $end_time) {
            $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
            $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
        }
        if ($payment_channel_id) {
            $query_params["payment_channel_id"] = $payment_channel_id;
        }
        if ($paid_at_null == false && $paid_at_start_time && $paid_at_end_time) {
            $query_params["paid_at_start_time"] = $paid_at_start_time->format("Y-m-d H:i:s");
            $query_params["paid_at_end_time"] = $paid_at_end_time->format("Y-m-d H:i:s");
        }
        if ($is_settle == "YES" || $is_settle == "NO") {
            $query_params["is_settle"] = $is_settle == "YES" ? 1 : 0;
        }

        $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_PARTNER, [
            "query" => [
                "per_page" => 10000,
            ],
        ]);
        if ($payment_channel_response->is_ok) {
            $payment_channel_list = $payment_channel_response->result->data;
        } else {
            $payment_channel_list = [];
        }

        $transaction_pg_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PG_LIST_PARTNER, [
            "query" => $query_params,
        ]);
        //dd($transaction_pg_response);

        if ($transaction_pg_response->is_ok) {
            $result = $transaction_pg_response->result;

            $transaction_pg_list = $result->data;
            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Transaction PG List " . $start_time->format("d-M-Y His") . " - " . $end_time->format("d-M-Y His") . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'Merchant_Branch',
                    'Payment_Method',
                    'Nominal',
                    'Bank Fee',
                    'Order ID',
                    'Transaction_Time',
                    'Paid Time',
                    'Status',
                    'Status Settlement',
                ];

                $callback = function() use ($transaction_pg_list, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($transaction_pg_list as $transaction_pg) {
                        fputcsv($file, [
                            @$transaction_pg->merchant_branch->name,
                            @$transaction_pg->payment_channel->name,
                            @number_format($transaction_pg->grand_total, 2, ".", ""),
                            @number_format($transaction_pg->bank_fee, 2, ".", ""),
                            @$transaction_pg->order_id,
                            @\App\Helpers\H::formatDateTime($transaction_pg->request_at),
                            @$transaction_pg->paid_at ? @\App\Helpers\H::formatDateTime($transaction_pg->paid_at) : "",
                            @$transaction_pg->status,
                            @$transaction_pg->is_settle ? "Settlement" : "Not Settlement",
                        ]);
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            } else {
                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_pg_response);
                return view("partners.transaction_pg.list", [
                    "transaction_pg_list" => $transaction_pg_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "order_id" => $order_id,
                    "start_time" => $start_time,
                    "end_time" => $end_time,
                    "paid_at_start_time" => $paid_at_start_time,
                    "paid_at_end_time" => $paid_at_end_time,
                    "paid_at_null" => $paid_at_null,
                    "is_settle" => $is_settle,
                    "payment_channel_id" => $payment_channel_id,
                    "payment_channel_list" => $payment_channel_list,
                ]);
            }
        } else {
            return $this->getApiResponseNotOkDefaultResponse($transaction_pg_response);
        }
    }

    public function show(Request $request, $transaction_pg_id) {
        $transaction_payment_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PG_ITEM_PARTNER, [
            "form_params" => [
                "transaction_pg_id" => $transaction_pg_id,
            ],
        ]);

        if ($transaction_payment_response->is_ok) {
            $result = $transaction_payment_response->result;

            return view("partners.transaction_pg.show", [
                "transaction_pg" => $result,
            ]);
        } else if ($transaction_payment_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($transaction_payment_response);
        }
    }
}