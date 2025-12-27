<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 04-Aug-21
 * Time: 00:07
 */

namespace App\Http\Controllers\Partners;



use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TransactionPaymentController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("TRANSACTION_PAYMENT_LIST", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "TRANSACTION_PAYMENT_LIST",
                ]));
            }
        });
    }

    public function index(Request $request) {
        //Remove this asap
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", 0);

        $page = $request->get("page", 1);
        $order_id = $request->get("order_id", null);
        $transaction_code = $request->get("transaction_code", null);
        $date_range_string = $request->get("date_range", null);

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

        $per_page = 10;
        if ($request->has("export_to_csv")) {
            $per_page = 99999999;
        }
        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
        ];
        if ($order_id) {
            $query_params["order_id"] = $order_id;
        }
        if ($transaction_code) {
            $query_params["transaction_code"] = $transaction_code;
        }
        if ($start_time && $end_time) {
            $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
            $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
        }
        $transaction_payment_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PAYMENT_LIST_PARTNER, [
            "query" => $query_params,
        ]);

        if ($transaction_payment_response->is_ok) {
            $result = $transaction_payment_response->result;

            $transaction_payment_list = $result->data;
            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Transaction Payment List " . $start_time->format("d-M-Y His") . " - " . $end_time->format("d-M-Y His") . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'Branch Name',
                    'Customer Name',
                    'Issuer Name',
                    'Grand Total',
                    'Order ID',
                    'Transaction Time',
                    'Type',
                    'RRN',
                ];

                $callback = function() use ($transaction_payment_list, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($transaction_payment_list as $transaction_payment) {
                        fputcsv($file, [
                            @trim($transaction_payment->merchant_branch->name),
                            @$transaction_payment->customer_data,
                            @$transaction_payment->issuer_name,
                            @number_format($transaction_payment->grand_total, 2, ".", ""),
                            @$transaction_payment->partner_order_order_id,
                            @\App\Helpers\H::formatDateTime($transaction_payment->transaction_time),
                            @$transaction_payment->qris_qr_type,
                            @$transaction_payment->transaction_code,
                        ]);
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            } else {
                $current_page = $result->current_page;
                $last_page = $result->last_page;
                //dd($transaction_payment_response);
                return view("partners.transaction_payments.list", [
                    "transaction_payment_list" => $transaction_payment_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "order_id" => $order_id,
                    "transaction_code" => $transaction_code,
                    "start_time" => $start_time,
                    "end_time" => $end_time,
                ]);
            }
        } else {
            return $this->getApiResponseNotOkDefaultResponse($transaction_payment_response);
        }
    }

    public function show(Request $request, $transaction_payment_id) {
        $transaction_payment_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PAYMENT_ITEM_PARTNER, [
            "form_params" => [
                "transaction_payment_id" => $transaction_payment_id,
            ],
        ]);

        if ($transaction_payment_response->is_ok) {
            $transaction_payment = $transaction_payment_response->result;

            if (AccessControlHelper::checkCurrentAccessControl("TRANSACTION_WEBHOOK_LIST", "AND")) {
                $partner_webhook_log_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_PARTNER_WEBHOOK_LOG_LIST_PARTNER, [
                    "form_params" => [
                        "transaction_payment_id" => $transaction_payment_id,
                    ],
                ]);

                if ($partner_webhook_log_response->is_ok) {
                    $partner_webhook_list = $partner_webhook_log_response->result;
                } else {
                    return $this->getApiResponseNotOkDefaultResponse($partner_webhook_log_response);
                }
            } else {
                $partner_webhook_list = null;
            }

            return view("partners.transaction_payments.show", [
                "transaction_payment" => $transaction_payment,
                "partner_webhook_list" => $partner_webhook_list,
            ]);
        } else if ($transaction_payment_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($transaction_payment_response);
        }
    }

    public function resendWebhook(Request $request) {
        $access_control = "TRANSACTION_WEBHOOK_LIST";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            if ($request->has("transaction_payment_id")) {
                $transaction_payment_id = $request->get("transaction_payment_id", null);

                $partner_webhook_log_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_PARTNER_WEBHOOK_LOG_RESEND_PARTNER, [
                    "form_params" => [
                        "transaction_payment_id" => $transaction_payment_id,
                    ],
                ]);

                //dd($partner_webhook_log_response);
                if ($partner_webhook_log_response->is_ok) {
                    H::flashSuccess($partner_webhook_log_response->status_message, true);
                    return redirect(route("cms.partner.transaction_payment.show", $transaction_payment_id));
                } else {
                    return $this->getApiResponseNotOkDefaultResponse($partner_webhook_log_response);
                }
            } else {
                return abort(400);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}
