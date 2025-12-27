<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\CustomResponse;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SettlementDebtController extends BaseController {

    public function index(Request $request) {
        $access_control = "SETTLEMENT_DEBT.VIEW";
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

        $per_page = 20;
        if ($request->has("export_to_csv")) {
            $per_page = 99999999;
        }

        $form_params = [];
        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
        ];
        if ($start_date && $end_date) {
            $form_params["start_date"] = $start_date->format("Y-m-d");
            $form_params["end_date"] = $end_date->format("Y-m-d");
        }

        if ($customer_name) {
            $form_params["customer_name"] = $customer_name;
        }

        if ($ref_code) {
            $form_params["ref_code"] = $ref_code;
        }

        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_LIST_YUKK_CO, [
            "query" => $query_params,
            "form_params" => $form_params,
        ]);

        if (!$settlement_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }

        $result = $settlement_master_response->result;

        $settlement_debt_master_list = $result->data;

        if ($request->has("export_to_csv")) {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Settlement Debt List " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y") . ".csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = [
                'Ref Code',
                'Settlement Date',
                'Beneficiary ID',
                'Beneficiary Name',
                'Partner ID',
                'Partner Name',
                'Total Grand Total',
                'Total Merchant Portion',
                'Total Fee Partner',
                'Status Disb Beneficiary',
                'Status Disb Partner',
            ];

            $callback = function() use ($settlement_debt_master_list, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($settlement_debt_master_list as $settlement_debt_master) {
                    fputcsv($file, [
                        @$settlement_debt_master->ref_code,
                        @$settlement_debt_master->settlement_date,
                        @$settlement_debt_master->customer_id,
                        @$settlement_debt_master->customer->name,
                        @$settlement_debt_master->partner_id,
                        @$settlement_debt_master->partner->name,
                        @number_format($settlement_debt_master->total_grand_total, 2, ".", ""),
                        @number_format($settlement_debt_master->total_merchant_portion, 2, ".", ""),
                        @number_format($settlement_debt_master->total_fee_partner, 2, ".", ""),
                        @$settlement_debt_master->status_disbursement_beneficiary,
                        @$settlement_debt_master->status_disbursement_partner,
                    ]);
                }
                fclose($file);
            };
            return Response::stream($callback, 200, $headers);
        } else {
            $current_page = $result->current_page;
            $last_page = $result->last_page;

            return view("yukk_co.settlement_debt.list", [
                "settlement_debt_master_list" => $settlement_debt_master_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "start_time" => $start_date,
                "end_time" => $end_date,

                "customer_name" => $customer_name,
                "ref_code" => $ref_code,
            ]);
        }
    }


    public function show(Request $request, $settlement_debt_id) {
        $access_control = "SETTLEMENT_DEBT.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $settlement_debt_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_ITEM_YUKK_CO, [
            "form_params" => [
                "settlement_debt_master_id" => $settlement_debt_id,
            ],
        ]);

        if ($settlement_debt_response->is_ok) {
            $result = $settlement_debt_response->result;

            return view("yukk_co.settlement_debt.show", [
                "settlement_debt_master" => $result,
            ]);
        } else if ($settlement_debt_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_debt_response);
        }
    }

    public function downloadTransactionReport(Request $request) {
        $access_control = "SETTLEMENT_DEBT.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $settlement_debt_id = $request->get("settlement_debt_id");

        $settlement_debt_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_ITEM_YUKK_CO, [
            "form_params" => [
                "settlement_debt_master_id" => $settlement_debt_id,
            ],
        ]);

        //dd($settlement_debt_response->result->settlement_debt_details);
        $settlement_debt = $settlement_debt_response->result;

        if ($settlement_debt->type == "QRIS_DISPUTE") {
            $data = [["DATE", "RRN", "REF CODE", "COA MERCHANT", "NAMA MERCHANT", "PARTNER", "NOMINAL", "MDR TOTAL", "MDR DPP", "MDR PPN", "ADDITIONAL FEE TOTAL", "ADDITIONAL FEE DPP", "ADDITIONAL FEE PPN", "PARTNER FEE"]];
            foreach ($settlement_debt->settlement_debt_details as $settlement_debt_detail) {
                $yukk_portion = $settlement_debt_detail->yukk_portion;
                $yukk_portion_ppn = round($yukk_portion * 0.11, 2);
                $yukk_portion_dpp = $yukk_portion - $yukk_portion_ppn;
                $fee_yukk_additional = $settlement_debt_detail->fee_yukk_additional;
                $fee_yukk_additional_ppn = round($fee_yukk_additional * 0.11, 2);
                $fee_yukk_additional_dpp = $fee_yukk_additional - $fee_yukk_additional_ppn;

                $data[] = [
                    @$settlement_debt_detail->created_at,
                    @$settlement_debt_detail->transaction_ref_code,
                    @$settlement_debt->ref_code,
                    @"2201 " . $settlement_debt->customer_id,
                    @$settlement_debt->customer->name,
                    @$settlement_debt->partner->name,
                    @number_format($settlement_debt_detail->grand_total, 2, ".", ""),
                    @number_format($yukk_portion, 2, ".", ""),
                    @number_format($yukk_portion_dpp, 2, ".", ""),
                    @number_format($yukk_portion_ppn, 2, ".", ""),
                    @number_format($fee_yukk_additional, 2, ".", ""),
                    @number_format($fee_yukk_additional_dpp, 2, ".", ""),
                    @number_format($fee_yukk_additional_ppn, 2, ".", ""),
                    @number_format($settlement_debt_detail->fee_partner, 2, ".", ""),
                ];
            }

            //dd($data);
            return H::getStreamExcel("Settlement Debt Transaction Report " . $settlement_debt->ref_code, $data);
        } else {
            $data = [["SettlementRefCode", "CreatedAt", "FeePartner", "Type", "Notes"]];
            foreach ($settlement_debt->settlement_debt_details as $settlement_debt_detail) {
                $data[] = [
                    @$settlement_debt_detail->transaction_ref_code,
                    @$settlement_debt_detail->created_at,
                    @number_format($settlement_debt_detail->fee_partner * -1, 2, ".", ""),
                    @$settlement_debt_detail->type,
                    @$settlement_debt_detail->notes,
                ];
            }

            return H::getStreamExcel("Settlement Debt Sharing Profit " . $settlement_debt->ref_code, $data);
        }

        return abort(501, "Type undefined");
    }






    public function inputDisputeForm(Request $request) {
        $access_control = "SETTLEMENT_DEBT.CREATE_FROM_DISPUTE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        return view("yukk_co.settlement_debt.input_dispute_form");
    }

    public function inputDisputeSummaryXlsx(Request $request) {
        $access_control = "SETTLEMENT_DEBT.CREATE_FROM_DISPUTE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $transaction_list = collect([]);
        foreach ($request->file("disputes") as $file_dispute) {
            if (in_array($file_dispute->getMimeType(), ["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"]) && in_array(strtolower($file_dispute->getClientOriginalExtension()), ["xlsx"])) {
                $spreadsheet = $reader->load($file_dispute->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                foreach ($sheet->toArray() as $index => $row) {
                    if ($index == 0) {
                        continue;
                    }

                    if (isset($row[0])) {
                        $transaction_list[] = (object) ['rrn' => $row[0]];
                    }
                }
            }
        }

        $settlement_debt_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_GET_FROM_DISPUTE_YUKK_CO, [
            "json" => $transaction_list,
        ]);

        if (! $settlement_debt_response->is_ok) {
            return self::getApiResponseNotOkDefaultResponse($settlement_debt_response);
        }

        $transaction_payment_list = isset($settlement_debt_response->result->transaction_payment_list) ? collect($settlement_debt_response->result->transaction_payment_list) : collect([]);
        $rrn_list_not_found = $transaction_list->pluck("rrn")->diff($transaction_payment_list->pluck("transaction_code"))->flatten();
        $transaction_payment_grouped_by = collect([]);
        $rrn_list_double = $transaction_payment_list->countBy("transaction_code")->filter(function ($item) {
            return $item > 1;
        });

        $transaction_payment_already_disputed_list = collect($settlement_debt_response->result->transaction_payment_already_exists_ids);

        foreach ($transaction_payment_list as $transaction_payment) {
            $key_customer_partner = $transaction_payment->customer_id . "|" . $transaction_payment->transaction_payment_extra->partner_id;
            if (! isset($transaction_payment_grouped_by[$key_customer_partner])) {
                $transaction_payment_grouped_by[$key_customer_partner] = (object) [
                    "customer" => $transaction_payment->customer,
                    "partner" => $transaction_payment->transaction_payment_extra->partner,
                    "transaction_payment_list" => collect([]),
                    "sum_grand_total" => 0,
                    "sum_merchant_portion" => 0,
                    "sum_fee_partner" => 0,
                ];
            }
            $transaction_payment_grouped_by[$key_customer_partner]->transaction_payment_list[] = $transaction_payment;
            $transaction_payment_grouped_by[$key_customer_partner]->sum_grand_total += $transaction_payment->grand_total;
            $transaction_payment_grouped_by[$key_customer_partner]->sum_merchant_portion += $transaction_payment->transaction_payment_extra->merchant_portion;
            $transaction_payment_grouped_by[$key_customer_partner]->sum_fee_partner += $transaction_payment->transaction_payment_extra->fee_partner_fixed + $transaction_payment->transaction_payment_extra->fee_partner_percentage;
        }
        $transaction_payment_grouped_by = $transaction_payment_grouped_by->flatten();
        //dd($rrn_list_double);

        //dd($transaction_payment_already_disputed_list->pluck("transaction_id"));

        return view("yukk_co.settlement_debt.input_dispute_summary", [
            "transaction_payment_grouped_by" => $transaction_payment_grouped_by,
            "rrn_list_not_found" => $rrn_list_not_found,
            "rrn_list_double" => $rrn_list_double,
            "transaction_payment_already_disputed_list" => $transaction_payment_already_disputed_list,
        ]);
    }

    public function inputDisputeSubmit(Request $request) {
        $access_control = "SETTLEMENT_DEBT.CREATE_FROM_DISPUTE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $transaction_payment_ids = collect($request->get("transaction_payment_ids", []));
        $notes = collect($request->get("notes", []));

        if ($transaction_payment_ids->isEmpty()) {
            return view("global.default_error_message", ["status_message" => "No Transaction Selected"]);
            //return abort(400, "No Transaction Selected");
        }

        $data_sent = collect([]);
        foreach ($transaction_payment_ids as $index => $transaction_payment_id) {
            $data_sent[] = (object) [
                "transaction_payment_id" => $transaction_payment_id,
                "notes" => isset($notes[$index]) ? $notes[$index] : null,
            ];
        }

        $settlement_debt_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_SUBMIT_FROM_DISPUTE_YUKK_CO, [
            "json" => $data_sent,
        ]);

        if (! $settlement_debt_response->is_ok) {
            return self::getApiResponseNotOkDefaultResponse($settlement_debt_response);
        }

        $settlement_debt_list = $settlement_debt_response->result;

        return view("yukk_co.settlement_debt.input_dispute_finish", [
            "settlement_debt_list" => $settlement_debt_list,
        ]);
    }


    public function inputSharingProfitForm(Request $request) {
        $access_control = "SETTLEMENT_DEBT.CREATE_SHARING_PROFIT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        return view("yukk_co.settlement_debt.input_sharing_profit_form");
    }

    public function inputSharingProfitSummaryXlsx(Request $request) {
        $access_control = "SETTLEMENT_DEBT.CREATE_SHARING_PROFIT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $sharing_profit_list = collect([]);
        foreach ($request->file("sharing_profits") as $file_sharing_profit) {
            if (in_array($file_sharing_profit->getMimeType(), ["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"]) && in_array(strtolower($file_sharing_profit->getClientOriginalExtension()), ["xlsx"])) {
                $spreadsheet = $reader->load($file_sharing_profit->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                $max_row_index = $sheet->getHighestRow();
                for ($row_index = 1; $row_index <= $max_row_index; $row_index++) {
                    // Skip Header
                    if ($row_index == 1) {
                        continue;
                    }

                    if ($sheet->getCellByColumnAndRow(1, $row_index)->getValue() && $sheet->getCellByColumnAndRow(3, $row_index)->getValue() && $sheet->getCellByColumnAndRow(5, $row_index)->getValue() && $sheet->getCellByColumnAndRow(6, $row_index)->getValue()) {
                        $sharing_profit_list[] = (object) [
                            'partner_id' => $sheet->getCellByColumnAndRow(1, $row_index)->getValue(),
                            'customer_id' => $sheet->getCellByColumnAndRow(3, $row_index)->getValue(),
                            'fee_partner' => $sheet->getCellByColumnAndRow(5, $row_index)->getCalculatedValue(),
                            'type' => $sheet->getCellByColumnAndRow(6, $row_index)->getValue(),
                            'notes' => $sheet->getCellByColumnAndRow(7, $row_index)->getValue(),
                            'ref_code' => $sheet->getCellByColumnAndRow(8, $row_index)->getValue(),
                        ];
                    }
                }
            }
        }

        $settlement_debt_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_GET_SHARING_PROFIT_YUKK_CO, [
            "json" => $sharing_profit_list,
        ]);

        if (! $settlement_debt_response->is_ok) {
            return self::getApiResponseNotOkDefaultResponse($settlement_debt_response);
        }

        $found_sharing_profit_list = isset($settlement_debt_response->result->found_sharing_profit_list) ? collect($settlement_debt_response->result->found_sharing_profit_list) : collect([]);
        $not_found_sharing_profit_list = isset($settlement_debt_response->result->not_found_sharing_profit_list) ? collect($settlement_debt_response->result->not_found_sharing_profit_list) : collect([]);

        $found_sharing_profit_grouped = collect([]);
        foreach ($found_sharing_profit_list as $found_sharing_profit) {
            $partner_id = $found_sharing_profit->partner_id;
            if (! isset($found_sharing_profit_grouped[$partner_id])) {
                $found_sharing_profit_grouped[$partner_id] = (object) [
                    "partner" => $found_sharing_profit->partner,
                    "items" => collect([]),
                ];
            }
            $found_sharing_profit_grouped[$partner_id]->items[] = $found_sharing_profit;
        }

        $found_sharing_profit_grouped = $found_sharing_profit_grouped->flatten();

        return view("yukk_co.settlement_debt.input_sharing_profit_summary", [
            "found_sharing_profit_list" => $found_sharing_profit_list,
            "found_sharing_profit_grouped" => $found_sharing_profit_grouped,
            "not_found_sharing_profit_list" => $not_found_sharing_profit_list,
        ]);
    }

    public function inputSharingProfitSubmit(Request $request) {
        $access_control = "SETTLEMENT_DEBT.CREATE_SHARING_PROFIT";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $partner_ids = $request->get("partner_ids");
        $customer_ids = $request->get("customer_ids");
        $fee_partners = $request->get("fee_partners");
        $notes = $request->get("notes");
        $ref_codes = $request->get("ref_codes");
        $types = $request->get("types");
        $max_count = count($partner_ids);

        $sharing_profit_list = collect([]);
        for ($index = 0; $index < $max_count; $index++) {
            if (isset($partner_ids[$index]) && isset($customer_ids[$index]) && isset($fee_partners[$index]) && isset($notes[$index])) {
                $sharing_profit_list[] = (object) [
                    'partner_id' => $partner_ids[$index],
                    'customer_id' => $customer_ids[$index],
                    'fee_partner' => $fee_partners[$index],
                    'notes' => $notes[$index],
                    'ref_code' => $ref_codes[$index],
                    'type' => $types[$index],
                ];
            }
        }

        $settlement_debt_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_DEBT_SUBMIT_SHARING_PROFIT_YUKK_CO, [
            "json" => $sharing_profit_list,
        ]);

        if (! $settlement_debt_response->is_ok) {
            return self::getApiResponseNotOkDefaultResponse($settlement_debt_response);
        }

        $settlement_debt_list = $settlement_debt_response->result;

        return view("yukk_co.settlement_debt.input_sharing_profit_finish", [
            "settlement_debt_list" => $settlement_debt_list,
        ]);
    }
}
