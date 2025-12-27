<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 13-Jun-22
 * Time: 11:04
 */

namespace App\Http\Controllers\Customers;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SettlementPgMasterController extends BaseController {

    public function downloadExcel(Request $request) {
        $access_control = "DISBURSEMENT_CUSTOMER_VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $settlement_pg_master_id = $request->get("settlement_pg_master_id");

        $settlement_pg_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_PG_MASTER_ITEM_CUSTOMER, [
            "form_params" => [
                "settlement_pg_master_id" => $settlement_pg_master_id,
            ],
        ]);

        if (! $settlement_pg_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($settlement_pg_master_response);
        }

        $settlement_pg_master = $settlement_pg_master_response->result;
        $spreadsheet = self::generateSpreadSheet($settlement_pg_master);

        $filename = "Transaction_Report_" . Carbon::parse($settlement_pg_master->settlement_date)->format("Y_m_d");

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        $writer->save('php://output');
        die();
    }

    public static function generateSpreadSheet($settlement_pg_master) {
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
        $sheet->setCellValue('F7', 'Payment Channel');
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
        foreach ($settlement_pg_master->settlement_pg_detail_list as $settlement_pg_detail) {
            $col_index = 1;

            $merchant_name = @$settlement_pg_detail->transaction->merchant_branch->name ?? "";
            $customer_name = @$settlement_pg_detail->transaction->customer_name ?? "";
            $transaction_code = @$settlement_pg_detail->transaction->code ?? "";
            $transaction_time = @$settlement_pg_detail->transaction->request_at ?? "";
            $order_id = @$settlement_pg_detail->transaction->order_id ?? "";
            $payment_channel_name = @$settlement_pg_detail->transaction->payment_channel->name ?? "";
            $grand_total = @$settlement_pg_detail->transaction->grand_total ?? "";
            $mdr = @($settlement_pg_detail->transaction->mdr_external_fixed + $settlement_pg_detail->transaction->mdr_external_percentage + $settlement_pg_detail->transaction->fee_partner_fixed + $settlement_pg_detail->transaction->fee_partner_percentage) ?? "";
            $merchant_portion = @$settlement_pg_detail->transaction->grand_total - ($settlement_pg_detail->transaction->mdr_external_fixed + $settlement_pg_detail->transaction->mdr_external_percentage + $settlement_pg_detail->transaction->fee_partner_fixed + $settlement_pg_detail->transaction->fee_partner_percentage) ?? "";

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
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $payment_channel_name);
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
        $sheet->setCellValueByColumnAndRow($col_index, $row_index, $settlement_pg_master->total_merchant_portion);
        $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);

        $row_index++;
        $col_index = 1;
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, "Beneficiary Name:");
        $sheet->setCellValueByColumnAndRow($col_index, $row_index, @$settlement_pg_master->customer->name ?? "");


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
