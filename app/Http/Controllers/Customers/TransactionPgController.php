<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 21-Sep-22
 * Time: 15:00
 */

namespace App\Http\Controllers\Customers;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class TransactionPgController extends BaseController {

    public function index(Request $request) {
        $access_control = "BENEFICIARY_TRANSACTION_PG.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $per_page = $request->get("per_page", 10);

        $ref_code = $request->get("ref_code",null);
        $order_id = $request->get("order_id",null);
        $status = $request->get("status",null);
        $date_range_string = $request->get("date_range", null);
        $merchant_branch_name = $request->get("merchant_branch_name", null);
        $partner_name = $request->get("partner_name", null);
        $payment_channel_name = $request->get("payment_channel_name", null);
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


        $_per_page = $per_page;
        if ($request->has("export_to_xls")) {
            $_per_page = 99999999;
        }

        $query_params = [
            "page" => $page,
            "per_page" => $_per_page,
        ];

        if ($start_time && $end_time) {
            $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
            $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
        }

        if ($ref_code) {
            $query_params['ref_code'] = $ref_code;
        }

        if ($order_id) {
            $query_params['order_id'] = $order_id;
        }

        if ($status && $status != "ALL") {
            $query_params['status'] = $request->get("status");
        }

        if ($merchant_branch_name) {
            $query_params['merchant_branch_name'] = $merchant_branch_name;
        }
        if ($partner_name) {
            $query_params['partner_name'] = $partner_name;
        }
        if ($payment_channel_name) {
            $query_params['payment_channel_name'] = $payment_channel_name;
        }
        if ($paid_at_null == false && $paid_at_start_time && $paid_at_end_time) {
            $query_params["paid_at_start_time"] = $paid_at_start_time->format("Y-m-d H:i:s");
            $query_params["paid_at_end_time"] = $paid_at_end_time->format("Y-m-d H:i:s");
        }
        if ($is_settle == "YES" || $is_settle == "NO") {
            $query_params["is_settle"] = $is_settle == "YES" ? 1 : 0;
        }


        //dd($query_params);
        $transaction_pg_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PG_LIST_CUSTOMER, [
            "query" => $query_params,
        ]);
        //dd($transaction_pg_response);

        if (! $transaction_pg_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($transaction_pg_response);
        }

        $result = $transaction_pg_response->result;

        $transaction_pg_list = $result->data;

        if ($request->has("export_to_xls")) {
            $spreadsheet = self::generateSpreadSheet($transaction_pg_list);

            $filename = "transaction_payment_pg";

            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            $writer->save('php://output');
            die();
        } else {
            $current_page = $result->current_page;
            $last_page = $result->last_page;
            return view("customers.transaction_pg.list", [
                "transaction_pg_list" => $transaction_pg_list,

                "ref_code" => $ref_code,
                "merchant_branch_name" => $merchant_branch_name,
                "status" => $status,
                "order_id" => $order_id,
                "partner_name" => $partner_name,
                "payment_channel_name" => $payment_channel_name,

                "start_time" => $start_time,
                "end_time" => $end_time,
                "paid_at_start_time" => $paid_at_start_time,
                "paid_at_end_time" => $paid_at_end_time,
                "paid_at_null" => $paid_at_null,
                "is_settle" => $is_settle,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "per_page" => $per_page,
            ]);
        }
    }

    public function generateSpreadSheet($transaction_pg_list) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            "order_id",
            "ref_code",
            "request_time",
            "paid_time",
            "branch_name",
            "partner_name",
            "payment_channel",
            "grand_total",
            "Bank Fee",
            "Status",
            "Status Settlement",
        ];
        $row_index = 1;
        $col_index = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$header);
        }
        $row_index++;
        foreach ($transaction_pg_list as $transaction_pg) {
            $col_index = 1;

            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_pg->order_id ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_pg->code ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @H::formatDateTime($transaction_pg->request_at, "Y-m-d H:i:s") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @H::formatDateTime($transaction_pg->paid_at, "Y-m-d H:i:s") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_pg->merchant_branch->name ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_pg->partner->name ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_pg->payment_channel->name ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_pg->grand_total, 2, ".", ",") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_pg->bank_fee, 2, ".", ",") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_pg->status ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_pg->is_settle ? "Settlement" : "Not Settlement");
            $row_index++;
            $col_index = 1;
        }


        $spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension("B")->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension("C")->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension("D")->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension("E")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("F")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("G")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("H")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("I")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("J")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("K")->setWidth(15);

        return $spreadsheet;
    }

    public function show(Request $request, $transaction_pg_id) {
        $access_control = "BENEFICIARY_TRANSACTION_PG.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $transaction_pg_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PG_ITEM_CUSTOMER, [
            "form_params" => [
                "transaction_pg_id" => $transaction_pg_id,
            ],
        ]);

        if ($transaction_pg_response->is_ok) {
            $result = $transaction_pg_response->result;

            return view("customers.transaction_pg.show", [
                "transaction_pg" => $result,
            ]);
        } else if ($transaction_pg_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($transaction_pg_response);
        }
    }
}