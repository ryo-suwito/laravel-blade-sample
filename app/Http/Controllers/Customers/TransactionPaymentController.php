<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 20-Sep-22
 * Time: 16:36
 */

namespace App\Http\Controllers\Customers;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class TransactionPaymentController extends BaseController {

    public function index(Request $request) {
        //Remove this asap
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", 0);
        
        $access_control = "BENEFICIARY_TRANSACTION_QRIS.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $page = $request->get("page", 1);
        $per_page = $request->get("per_page", 10);

        $rrn = $request->get("rrn",null);
        $order_id = $request->get("order_id",null);
        $status = $request->get("status",null);
        $date_range_string = $request->get("date_range", null);
        $merchant_branch_name = $request->get("merchant_branch_name", null);

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

        if ($rrn) {
            $query_params['rrn'] = $rrn;
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

        //dd($query_params);
        $transaction_payment_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PAYMENT_LIST_CUSTOMER, [
            "query" => $query_params,
        ]);


        if (! $transaction_payment_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($transaction_payment_response);
        }

        $result = $transaction_payment_response->result;

        $transaction_payment_list = $result->data;

        if ($request->has("export_to_xls")) {
            $spreadsheet = self::generateSpreadSheet($transaction_payment_list);

            $filename = "transaction_payment";

            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            $writer->save('php://output');
            die();
        } else {
            $current_page = $result->current_page;
            $last_page = $result->last_page;
            return view("customers.transaction_payments.list", [
                "transaction_payment_list" => $transaction_payment_list,

                "rrn" => $rrn,
                "merchant_branch_name" => $merchant_branch_name,
                "status" => $status,
                "order_id" => $order_id,

                "start_time" => $start_time,
                "end_time" => $end_time,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "per_page" => $per_page,
            ]);
        }
    }

    public function generateSpreadSheet($transaction_payment_list) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            "order_id",
            "transaction_code",
            "transaction_time",
            "yukk_id",
            "branch_name",
            "issuer_name",
            "acquirer_name",
            "customer_data",
            "status",
            "amount_before_tax",
            "discount",
            "service",
            "tax",
            "grand_total",
            "yukk_cash",
            "yukk_points",
            "yukk_portion",
        ];
        $row_index = 1;
        $col_index = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$header);
        }
        $row_index++;
        foreach ($transaction_payment_list as $transaction_payment) {
            $col_index = 1;

            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_payment->partner_order_order_id ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_payment->transaction_code ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @H::formatDateTime($transaction_payment->transaction_time, "Y-m-d H:i:s") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_payment->user->yukk_id ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_payment->merchant_branch->name ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_payment->issuer_name ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_payment->acquirer_name ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_payment->customer_data ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @$transaction_payment->status ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_payment->price_before_tax, 2, ".", ",") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_payment->discount, 2, ".", ",") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_payment->service, 2, ".", ",") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_payment->tax, 2, ".", ",") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_payment->grand_total, 2, ".", ",") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_payment->yukk_p, 0, "", "") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_payment->yukk_e, 0, "", "") ?? "");
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, @number_format($transaction_payment->transaction_payment_extra->yukk_portion, 2, ".", ",") ?? "");

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
        $spreadsheet->getActiveSheet()->getColumnDimension("L")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("M")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("N")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("O")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("P")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("Q")->setWidth(15);

        return $spreadsheet;
    }

    public function show(Request $request, $transaction_payment_id) {
        $access_control = "BENEFICIARY_TRANSACTION_QRIS.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $transaction_payment_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_ORDER_TRANSACTION_PAYMENT_ITEM_CUSTOMER, [
            "form_params" => [
                "transaction_payment_id" => $transaction_payment_id,
            ],
        ]);

        if ($transaction_payment_response->is_ok) {
            $result = $transaction_payment_response->result;

            return view("customers.transaction_payments.show", [
                "transaction_payment" => $result,
            ]);
        } else if ($transaction_payment_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($transaction_payment_response);
        }
    }


}