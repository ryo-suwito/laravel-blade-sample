<?php

namespace App\Http\Controllers\Partners;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SettlementMasterController extends BaseController {

    public function downloadExcel (Request $request) {
        $access_control = "DISBURSEMENT_CUSTOMER.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $settlement_master_id = $request->get("settlement_master_id");

        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_ITEM_PARTNER, [
            "form_params" => [
                "settlement_master_id" => $settlement_master_id,
            ],
        ]);

        if (! $settlement_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }

        $settlement_master = $settlement_master_response->result;
        $spreadsheet = self::generateSpreadSheet($settlement_master);

        $filename = "Transaction Report " . $settlement_master->ref_code . " " . Carbon::parse($settlement_master->settlement_date)->format("Y_m_d");

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        $writer->save('php://output');
        die();
    }


    public static function generateSpreadSheet($settlement_master) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('D2', 'PT. YUKK KREASI INDONESIA');
        $sheet->setCellValue('A3', 'Alam Sutera - Jl. Jalur Sutera');
        $sheet->setCellValue('A4', 'Ruko De Mansion Blok D No. 17, Banten 15144');
        $sheet->setCellValue('A5', 'Telp : 021 31108040 | Website : www.yukk.co.id');


        $border_style = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];


        $sheet->setCellValue('A7', 'Merchant Name');
        $sheet->setCellValue('B7', 'Customer Name');
        $sheet->setCellValue('C7', 'Transaction Code');
        $sheet->setCellValue('D7', 'Transaction Time');
        $sheet->setCellValue('E7', 'OrderID');
        $sheet->setCellValue('F7', 'Source');
        $sheet->setCellValue('G7', 'Transaction Amount');
        $sheet->setCellValue('H7', 'MDR');
        $sheet->setCellValue('I7', 'Total');

        $sheet->getStyle('A7')->applyFromArray($border_style);
        $sheet->getStyle('B7')->applyFromArray($border_style);
        $sheet->getStyle('C7')->applyFromArray($border_style);
        $sheet->getStyle('D7')->applyFromArray($border_style);
        $sheet->getStyle('E7')->applyFromArray($border_style);
        $sheet->getStyle('F7')->applyFromArray($border_style);
        $sheet->getStyle('G7')->applyFromArray($border_style);
        $sheet->getStyle('H7')->applyFromArray($border_style);
        $sheet->getStyle('I7')->applyFromArray($border_style);

        $row_index = 8;
        foreach ($settlement_master->settlement_detail_list as $settlement_detail) {
            $col_index = 1;

            $merchant_name = @$settlement_detail->transaction_payment->merchant_branch->name ?? "";
            $customer_name = @$settlement_detail->transaction_payment->customer_data ?? "";
            $transaction_code = @$settlement_detail->transaction_payment->transaction_code ?? "";
            $transaction_time = @$settlement_detail->transaction_payment->transaction_time ?? "";
            $order_id = @$settlement_detail->transaction_payment->partner_order_order_id ?? "";
            $source = @$settlement_detail->transaction_payment->type == "CROSS_BORDER" ? trans("cms.CROSS BORDER") : trans("cms.DOMESTIC");
            $grand_total = @$settlement_detail->transaction_payment->grand_total ?? "";
            $mdr = @($settlement_detail->transaction_payment->transaction_payment_extra->yukk_portion + $settlement_detail->transaction_payment->transaction_payment_extra->fee_partner_percentage + $settlement_detail->transaction_payment->transaction_payment_extra->fee_partner_fixed + $settlement_detail->transaction_payment->transaction_payment_extra->fee_yukk_additional_percentage + $settlement_detail->transaction_payment->transaction_payment_extra->fee_yukk_additional_fixed) ?? "";
            $merchant_portion = @$settlement_detail->transaction_payment->transaction_payment_extra->merchant_portion ?? "";

            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $merchant_name);
            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $customer_name);
            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $transaction_code);
            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $transaction_time);
            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $order_id);
            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $source);
            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $grand_total);
            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $mdr);
            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $merchant_portion);

            $row_index++;
            $col_index = 1;
        }

        // Create Grand Total
        $col_index = 7;
        $sheet->setCellValueByColumnAndRow($col_index, $row_index, "Grand Total");
        $sheet->mergeCellsByColumnAndRow($col_index, $row_index, $col_index+1, $row_index);
        $sheet->getStyleByColumnAndRow($col_index, $row_index, $col_index+1, $row_index)->applyFromArray($border_style);
        $col_index += 2;
        $sheet->setCellValueByColumnAndRow($col_index, $row_index, $settlement_master->total_merchant_portion);
        $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);

        $row_index++;
        $col_index = 1;
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, "Beneficiary Name:");
        $sheet->setCellValueByColumnAndRow($col_index, $row_index, @$settlement_master->customer->name ?? "");

        $spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension("B")->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension("C")->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension("D")->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension("E")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("F")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("G")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("H")->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension("I")->setWidth(15);

        return $spreadsheet;
    }

}
