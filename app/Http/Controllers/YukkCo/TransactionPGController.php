<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 17-Nov-21
 * Time: 12:26
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TransactionPGController extends BaseController {

    public function index(Request $request) {
        $access_control = "TRANSACTIONS_PC_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);
            $date_range_string = $request->get("date_range", null);
            $paid_at_date_range_string = $request->get("paid_at_date_range", null);
            $paid_at_null = $request->has("paid_at_null");
            $code = $request->get("code", "");
            $statuses = $request->get("statuses", ["SUCCESS", "SUCCESS.BELOW_AMOUNT", "WAITING", "PENDING", "FAILED", "CANCELED", "EXPIRED"]);
            $payment_channel_id = $request->get("payment_channel_id", null);
            $provider_id = $request->get("provider_id", null);
            $is_settle = $request->get("is_settle", "ALL");
            $provider_trx_id = $request->get("provider_trx_id", "");
            $per_page = $request->get("per_page", 10);

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

            $start_time = $start_time->startOfDay();
            $end_time = $end_time->endOfDay();

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

            $paid_at_start_time = $paid_at_start_time->startOfDay();
            $paid_at_end_time = $paid_at_end_time->endOfDay();

            $query_params = [];
            if ($start_time && $end_time) {
                $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
                $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
            }
            if ($paid_at_null == false && $paid_at_start_time && $paid_at_end_time) {
                $query_params["paid_at_start_time"] = $paid_at_start_time->format("Y-m-d H:i:s");
                $query_params["paid_at_end_time"] = $paid_at_end_time->format("Y-m-d H:i:s");
            }
            if ($code) {
                $query_params["code"] = $code;
            }
            if ($statuses) {
                $query_params["status_list"] = $statuses;
            }
            if ($payment_channel_id) {
                $query_params["payment_channel_id"] = $payment_channel_id;
            }
            if ($provider_id) {
                $query_params["provider_id"] = $provider_id;
            }
            if ($is_settle == "YES" || $is_settle == "NO") {
                $query_params["is_settle"] = $is_settle == "YES" ? 1 : 0;
            }
            if($provider_trx_id){
                $query_params["provider_trx_id"] = $provider_trx_id;
            }

            // Get Payment Channel List
            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_YUKK_CO, [
                "query" => [
                    "per_page" => 10000,
                ],
            ]);

            if ($payment_channel_response->is_ok) {
                $payment_channel_list = $payment_channel_response->result->data;
            } else {
                $payment_channel_list = [];
            }

            // Get Provider List
            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_LIST_YUKK_CO, [
                "query" => [
                    "per_page" => 10000,
                ],
            ]);

            if ($provider_response->is_ok) {
                $provider_list = $provider_response->result->data;
            } else {
                $provider_list = [];
            }

            if ($request->has("export_to_csv")) {
                $query_params["page"] = $page;
                $query_params["per_page"] = 10000000000;
            } else {
                $query_params["page"] = $page;
                $query_params["per_page"] = $per_page;
            }

            $transaction_pg_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PG_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            if ($transaction_pg_response->is_ok) {
                $result = $transaction_pg_response->result;

                $transaction_pg_list = $result->data;

                if ($request->has("export_to_csv")) {
                    $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=Transaction PG " . $start_time->format("d-M-Y") . " - " . $end_time->format("d-M-Y") . ".csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

                    $columns = ['branch_name', 'partner', 'payment_method', 'nominal', 'paid_at', 'reference_code', 'status'];

                    $callback = function() use ($transaction_pg_list, $columns)
                    {
                        $file = fopen('php://output', 'w');
                        fputcsv($file, $columns);

                        foreach($transaction_pg_list as $transaction_pg) {
                            fputcsv($file, [
                                @$transaction_pg->merchant_branch->name,
                                @$transaction_pg->partner->name,
                                @$transaction_pg->payment_channel->name,
                                @number_format($transaction_pg->grand_total, 2, ".", ""),
                                (@isset($transaction_pg->paid_at) && @$transaction_pg->paid_at) ? @H::formatDateTime($transaction_pg->paid_at) : "-",
                                @$transaction_pg->code,
                                @$transaction_pg->status,
                            ]);
                        }
                        fclose($file);
                    };
                    return Response::stream($callback, 200, $headers);
                } else {
                    $current_page = $result->current_page;
                    $last_page = $result->last_page;

                    return view("yukk_co.transaction_pg.list", [
                        "transaction_pg_list" => $transaction_pg_list,
                        "current_page" => $current_page,
                        "last_page" => $last_page,
                        "start_time" => $start_time,
                        "end_time" => $end_time,
                        "showing_data" => [
                            "from" => $result->from,
                            "to" => $result->to,
                            "total" => $result->total,
                        ],

                        "paid_at_start_time" => $paid_at_start_time,
                        "paid_at_end_time" => $paid_at_end_time,
                        "paid_at_null" => $paid_at_null,
                        "code" => $code,
                        "statuses" => $statuses,

                        "payment_channel_list" => $payment_channel_list,
                        "payment_channel_id" => $payment_channel_id,

                        "provider_list" => $provider_list,
                        "provider_id" => $provider_id,

                        "is_settle" => $is_settle,

                        "provider_trx_id" => $provider_trx_id,
                    ]);
                }
            } else {
                return $this->getApiResponseNotOkDefaultResponse($transaction_pg_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $transaction_id) {
        $access_control = "TRANSACTIONS_PC_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $transaction_pg_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PG_ITEM_YUKK_CO, [
                "form_params" => [
                    "transaction_id" => $transaction_id,
                ],
            ]);

            if ($transaction_pg_response->is_ok) {
                $result = $transaction_pg_response->result;

                $transaction_pg = $result;
                if(isset($transaction_pg->detail) && is_array($transaction_pg->detail)) {
                    foreach ($transaction_pg->detail as $detail) {
                        if ($detail->name == "billing") {
                            // try to decode json detail value
                            try {
                                $transaction_pg->detail = json_decode($detail->value);
                                // check and concate address, city, state, country and zip code
                                if (isset($transaction_pg->detail->address)) {
                                    $address = $transaction_pg->detail->address;
                                    $city = isset($transaction_pg->detail->city) ? $transaction_pg->detail->city : "";
                                    $state = isset($transaction_pg->detail->state) ? $transaction_pg->detail->state : "";
                                    $country = isset($transaction_pg->detail->country) ? $transaction_pg->detail->country : "";
                                    $postal_code = isset($transaction_pg->detail->postal_code) ? $transaction_pg->detail->postal_code : "";
                                    $transaction_pg->detail->address = "-";
                                    // check and concate vars if empty skip without comma
                                    if ($address) {
                                        $transaction_pg->detail->address = $address;
                                        if ($city) {
                                            $transaction_pg->detail->address .= ", " . $city;
                                        }
                                        if ($state) {
                                            $transaction_pg->detail->address .= ", " . $state;
                                        }
                                        if ($country) {
                                            $transaction_pg->detail->address .= ", " . $country;
                                        }
                                        if ($postal_code) {
                                            $transaction_pg->detail->address .= ", " . $postal_code;
                                        }
                                    }

                                }
                            } catch (\Exception $e) {
                                \Log::Error("Failed to decode billing detail value", [
                                    "transaction_id" => $transaction_id,
                                    "detail" => $detail,
                                    "exception" => $e,
                                ]);
                                $transaction_pg->detail= null;
                            }
                            break;
                        }
                    }
                }
                return view("yukk_co.transaction_pg.show", [
                    "transaction_pg" => $transaction_pg,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($transaction_pg_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}
