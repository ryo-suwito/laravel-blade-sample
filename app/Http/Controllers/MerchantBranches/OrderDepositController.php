<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 21-May-22
 * Time: 18:10
 */

namespace App\Http\Controllers\MerchantBranches;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OrderDepositController extends BaseController {


    public function index(Request $request) {
        $access_control = ["ORDER_DEPOSIT_PLATFORM"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);
            $date_range_string = $request->get("date_range", null);

            if ($date_range_string) {
                $date_range_exploded = explode(" - ", $date_range_string);
                try {
                    $start_time = Carbon::parse($date_range_exploded[0]);
                } catch (\Exception $e) {
                    $start_time = Carbon::now()->startOfDay()->subDay()->subMinutes(30);
                }
                try {
                    $end_time = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
                } catch (\Exception $e) {
                    $end_time = Carbon::now()->endOfDay()->subDay()->subMinutes(30);
                }
            } else {
                $start_time = Carbon::now()->startOfDay()->subDay()->subMinutes(30);
                $end_time = Carbon::now()->endOfDay()->subDay()->subMinutes(30);
            }

            $status = $request->get("status", "ACCEPTED");


            $per_page = 20;
            if ($request->has("export_to_csv")) {
                $per_page = 99999999;
            }
            $query_params = [
                "page" => $page,
                "per_page" => $per_page,
                "status" => $status,
            ];
            if($request->has('platform_id')) {
                $query_params['platform_id'] = $request->get('platform_id');
                $platform_id = $request->get('platform_id');
            } else {
                $platform_id = null;
            }
            if ($start_time && $end_time) {
                $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
                $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
            }

            $order_deposit_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_DEPOSIT_LIST_MERCHANT_BRANCH, [
                "query" => $query_params,
            ]);
            //dd($order_deposit_qoin_response);

            if ($order_deposit_response->is_ok) {
                $result = $order_deposit_response->result;

                $order_deposit_list = $result->data;

                $sum_raw_amount = isset($result->extras) ? (isset($result->extras->sum_raw_amount) ? $result->extras->sum_raw_amount : 0) : 0;
                $sum_total_amount = isset($result->extras) ? (isset($result->extras->sum_total_amount) ? $result->extras->sum_total_amount : 0) : 0;

                if ($request->has("export_to_csv")) {
                    $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=Report Order deposit " . $start_time->format("d/m/Y") . " - " . $end_time->format("d/m/Y") . ".csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

                    $columns = [
                        'OrderID',
                        'RefCode',
                        'RawAmount',
                        'TotalAmount',
                        'Status',
                    ];

                    $callback = function() use ($order_deposit_list, $columns, $sum_raw_amount, $sum_total_amount, $start_time, $end_time)
                    {
                        $file = fopen('php://output', 'w');
                        fputcsv($file, [
                            "Date Range",
                            @$start_time->format("d/m/Y H:i:s") . " - " . $end_time->format("d/m/Y H:i:s"),
                        ]);

                        fputcsv($file, [
                            "Sum Raw Amount",
                            "IDR " . @number_format($sum_raw_amount, 2, ",", "."),
                        ]);

                        fputcsv($file, [
                            "Sum Total Amount",
                            "IDR " . @number_format($sum_total_amount, 2, ",", "."),
                        ]);

                        fputcsv($file, [
                            "",
                        ]);

                        fputcsv($file, $columns);

                        foreach($order_deposit_list as $order_deposit) {
                            fputcsv($file, [
                                @$order_deposit->qoin_order_id,
                                @$order_deposit->ref_code,
                                "IDR " . @number_format($order_deposit->raw_amount, 2, ",", "."),
                                "IDR " . @number_format($order_deposit->total_amount, 2, ",", "."),
                                @$order_deposit->status,
                                @H::formatDateTime($order_deposit->created_at, "d/m/Y H:i:s"),
                            ]);
                        }
                        fclose($file);
                    };
                    return Response::stream($callback, 200, $headers);
                } else {
                    $current_page = $result->current_page;
                    $last_page = $result->last_page;
                    try {
                        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_ORDER_DEPOSIT_PLATFORM_LIST_MERCHANT_BRANCH, []);
                    } catch (\Exception $e) {
                        report($e);
                        $response = null;
                    }
                    if ($response && $response->is_ok) {
                        $platforms = $response->result;
                        if ($platforms && count($platforms) > 0) {
                            $platform_id = $platform_id ? [$platform_id] : [];
                        } else {
                            $platform_id = null;
                        }
                    } else {
                        $platforms = [];
                        $platform_id = null;
                    }

                    return view("merchant_branches.order_deposit.list", [
                        "order_deposit_list" => $order_deposit_list,
                        "current_page" => $current_page,
                        "last_page" => $last_page,
                        "start_time" => $start_time,
                        "end_time" => $end_time,
                        "status" => $status,
                        "sum_raw_amount" => $platform_id?$sum_raw_amount:0,
                        "sum_total_amount" => $platform_id?$sum_total_amount:0,
                        "platforms" => $platforms,
                        "platform_id" => $platform_id,
                    ]);
                }
            } else {
                return $this->getApiResponseNotOkDefaultResponse($order_deposit_response);
            }

        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}