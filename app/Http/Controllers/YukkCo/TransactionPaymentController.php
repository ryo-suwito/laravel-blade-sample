<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 27-Mar-23
 * Time: 12:08
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionPaymentController extends BaseController {


    public function index(Request $request) {
        $access_control = "TRANSACTION_PAYMENT_VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $date_range_string = $request->get("date_range", null);
        $rrn = $request->get("rrn", "");
        $grand_total = $request->get("grand_total", "");
        $per_page = $request->get("per_page", 10);

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_time = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_time = null;
            }
            try {
                $end_time = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_time = null;
            }
        } else {
            $start_time = null;
            $end_time = null;
        }


        if ($rrn) {
            $query_params["rrn"] = $rrn;
        }
        if ($grand_total) {
            $query_params["grand_total"] = $grand_total;
        }

        if ($start_time == null && $end_time == null && $rrn == null && $grand_total == null) {
            $start_time = Carbon::now()->startOfDay();
            $end_time = Carbon::now()->endOfDay();
        }

        if ($start_time && $end_time) {
            $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
            $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
        }

        if ($request->has("export_to_csv")) {
            $per_page = 1000000;
        }

        $query_params["page"] = $page;
        $query_params['per_page'] = $per_page;

        // Get Transaction Payment List
        $transaction_payment_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PAYMENT_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if (! $transaction_payment_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($transaction_payment_response);
        }

        $result = $transaction_payment_response->result;

        $transaction_payment_list = $result->data;

        if ($request->has("export_to_csv")) {

        } else {
            $current_page = $result->current_page;
            $last_page = $result->last_page;

            return view("yukk_co.transaction_payments.list", [
                "transaction_payment_list" => $transaction_payment_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "showing_data" => [
                    "from" => $result->from,
                    "to" => $result->to,
                    "total" => $result->total,
                ],

                "start_time" => $start_time,
                "end_time" => $end_time,
                "rrn" => $rrn,
                "grand_total" => $grand_total,
            ]);
        }
    }

    public function show(Request $request, $transaction_payment_id) {
        $access_control = "TRANSACTION_PAYMENT_VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }


        // Get Payment Channel Item
        $transaction_payment_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PAYMENT_ITEM_YUKK_CO, [
            "form_params" => [
                "transaction_payment_id" => $transaction_payment_id,
            ],
        ]);

        if (! $transaction_payment_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($transaction_payment_response);
        }

        $transaction_payment = $transaction_payment_response->result;

        $partner_webhook_log_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_PARTNER_WEBHOOK_LOG_LIST_YUKK_CO, [
            "form_params" => [
                "transaction_payment_id" => $transaction_payment_id,
            ],
        ]);


        if (! $partner_webhook_log_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($partner_webhook_log_response);
        }

        $partner_webhook_log_list = $partner_webhook_log_response->result;

        return view("yukk_co.transaction_payments.show", [
            "transaction_payment" => $transaction_payment,
            "partner_webhook_log_list" => $partner_webhook_log_list,
        ]);
    }

    public static function generateCurlPartnerWebhookLog($partner_webhook_log) {
        $curl = "curl --request POST --url " . $partner_webhook_log->request_url;
        $request_headers = (array)json_decode($partner_webhook_log->request_headers);
        foreach ($request_headers as $key => $value) {
            $curl .= " --header '$key: $value'";
        }
        $curl .= " --data '" . $partner_webhook_log->request_parameters . "'";

        return $curl;
    }

}