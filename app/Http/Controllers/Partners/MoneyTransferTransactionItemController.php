<?php

namespace App\Http\Controllers\Partners;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MoneyTransferTransactionItemController extends BaseController {

    public function index(Request $request) {
        if (! AccessControlHelper::checkCurrentAccessControl("MONEY_TRANSFER.TRANSACTION_ITEM.VIEW")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => "MONEY_TRANSFER.TRANSACTION_ITEM.VIEW",
            ]));
        }

        ini_set("memory_limit", -1);
        ini_set("max_execution_time", 0);

        $page = $request->get("page", 1);
        $date_range_string = $request->get("date_range", null);
        $code = $request->get("code");
        $account_number = $request->get("account_number");
        $recipient_name = $request->get("recipient_name");

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
        if ($code) {
            $query_params["code"] = $code;
        }
        if ($account_number) {
            $query_params["account_number"] = $account_number;
        }
        if ($recipient_name) {
            $query_params["recipient_name"] = $recipient_name;
        }
        if ($start_time && $end_time) {
            $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
            $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
        }
        $transaction_item_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_MT_TRANSACTION_ITEM_LIST_PARTNER, [
            "query" => $query_params,
        ]);

        if (! $transaction_item_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($transaction_item_response);
        }

        $result = $transaction_item_response->result;

        $transaction_item_list = $result->data;

        if ($request->has("export_to_csv")) {
            $headers = ["Code", "Amount", "Fee", "Total", "Bank", "Account Number", "Recipient Name", "Beneficiary Email", "Created At", "Status", "Remark"];
            $data = [];
            foreach ($transaction_item_list as $transaction_item) {
                $data[] = [$transaction_item->code, $transaction_item->amount, $transaction_item->fee, $transaction_item->total, $transaction_item->bank->name, $transaction_item->account_number, $transaction_item->recipient_name, $transaction_item->beneficiary_email, $transaction_item->created_at, $transaction_item->status, $transaction_item->remark];
            }
            return H::getStreamCsv("Transaction Items " . $start_time->format("d-M-Y His") . " - " . $end_time->format("d-M-Y His"), $headers, $data);
        } else {
            $current_page = $result->current_page;
            $last_page = $result->last_page;
            //dd($transaction_payment_response);
            return view("partners.money_transfers.transaction_items.list", [
                "transaction_item_list" => $transaction_item_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "code" => $code,
                "account_number" => $account_number,
                "recipient_name" => $recipient_name,
                "start_time" => $start_time,
                "end_time" => $end_time,
            ]);
        }
    }

    public function show(Request $request, $transaction_item_id) {
        if (! AccessControlHelper::checkCurrentAccessControl("MONEY_TRANSFER.TRANSACTION_ITEM.VIEW")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => "MONEY_TRANSFER.TRANSACTION_ITEM.VIEW",
            ]));
        }

        $transaction_item_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_MT_TRANSACTION_ITEM_ITEM_PARTNER, [
            "form_params" => [
                "transaction_item_id" => $transaction_item_id,
            ],
        ]);

        if (! $transaction_item_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($transaction_item_response);
        }

        return view("partners.money_transfers.transaction_items.show", [
            "transaction_item" => $transaction_item_response->result,
        ]);
    }

}
