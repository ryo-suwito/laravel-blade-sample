<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class EdcController extends BaseController
{
    public function index(Request $request)
    {
        $access_control = ["QRIS_MENU.EDC.VIEW","QRIS_MENU.EDC.UPDATE"];
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "OR")) {
            $page = $request->get("page", 1);
            $per_page = $request->get("per_page", 10);

            $query_params = [
                "page" => $page,
                "per_page" => $per_page,
            ];

            $beneficiary = $request->get('beneficiary', null);
            $branch = $request->get('branch', null);
            $partner = $request->get('partner', null);
            $type = $request->get('type', null);
            $date_range = $request->get('date_range', null);
            if ($date_range) {
                $date_range_exploded = explode(" - ", $date_range);
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

            if ($beneficiary) $query_params['beneficiary'] = $beneficiary;
            if ($branch) $query_params['branch'] = $branch;
            if ($partner) $query_params['partner'] = $partner;
            if ($type) $query_params['type'] = $type;

            if ($start_time && $end_time) {
                $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
                $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
            }

            $excel = $request->get('export_to_excel');
            if ($excel) $query_params['excel'] = $excel;

            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_LIST_YUKK_CO, [
                'form_params' => $query_params
            ]);

            if (!$response->is_ok){
                return $this->getApiResponseNotOkDefaultResponse($response);
            }

            $edc_list = $response->result->edc_list->data;
            $partner_login = $response->result->partner_logins;  
            $access_control = json_decode(S::getUserRole()->role->access_control);

            if ($request->has('export_to_excel')) {
                $file_name = "Data QRIS Merchant";
                $spreadsheet = self::generateSpreadSheet($edc_list, $partner_login);

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                $writer->save('php://output');
                die();
            }
            $current_page = $response->result->edc_list->current_page;
            $last_page = $response->result->edc_list->last_page;

            return view("yukk_co.edcs.list", [
                "edc_list" => $edc_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "start_time" => $start_time,
                "end_time" => $end_time,
                "beneficiary" => $beneficiary,
                "branch" => $branch,
                "partner" => $partner,
                "type" => $type,
                "access_control" => $access_control,

                "per_page" => $per_page,

                "showing_data" => [
                    "from" => $response->result->edc_list->from,
                    "to" => $response->result->edc_list->to,
                    "total" => $response->result->edc_list->total,
                ],
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function detail(Request $request, $id)
    {
        $access_control = "QRIS_MENU.EDC.VIEW";
        if (!AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_DETAIL_YUKK_CO, [
            'form_params' => [
                'id' => $id
            ]
        ]);

        if ($response->is_ok){
            return view('yukk_co.edcs.detail', [
                'edc' => $response->result->edc,
                'customers' => $response->result->customer,
                'partner_fee_list' => $response->result->partner_fee_list,
                'event_list' => $response->result->event_list,
                'partner_login' => $response->result->partner_login,
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function edit(Request $request, $id)
    {
        $access_control = "QRIS_MENU.EDC.UPDATE";
        if (!AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_DETAIL_YUKK_CO, [
            'form_params' => [
                'id' => $id
            ]
        ]);

        $time_threshold_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_TIME_THRESHOLD_YUKK_CO, []);
        $time_threshold = '';
        $time_message = '';
        $time_threshold_benef = '';
        $time_message_benef = '';
    
        if ($time_threshold_response->is_ok) {
            if(!isset($time_threshold_response->result) ||
                !isset($time_threshold_response->result->time_threshold) ||
                !isset($time_threshold_response->result->time_threshold_benef)){
                $time_message = 'Failed to get time for schedule apply.';
                $time_threshold = ''; 
            }

            $time_threshold = $time_threshold_response->result->time_threshold;
            $time_threshold_benef = $time_threshold_response->result->time_threshold_benef;


            // Validate that the time_threshold is in the correct "HH:MM" format
            if (!preg_match('/^\d{2}:\d{2}$/', $time_threshold)) {
                $time_message = 'The partner_fee schedule time format is incorrect. Please use "HH:MM".';
                $time_threshold = ''; // Reset time_threshold to an empty string if format is incorrect
            }
            if (!preg_match('/^\d{2}:\d{2}$/', $time_threshold_benef)) {
                $time_message_benef = 'The benef schedule time format is incorrect. Please use "HH:MM".';
                $time_threshold_benef = ''; // Reset time_threshold to an empty string if format is incorrect
            }
        } else {
            // Handle API call failure
            $time_message = 'API call failed to retrieve time threshold.';
        }

        if ($response->is_ok){
            return view('yukk_co.edcs.edit', [
                'edc' => $response->result->edc,
                'customers' => $response->result->customer,
                'partner_fee_list' => $response->result->partner_fee_list,
                'event_list' => $response->result->event_list,
                'partner_login' => $response->result->partner_login,
                "time_threshold" => $time_threshold,
                "time_message" => $time_message,
                "time_threshold_benef" => $time_threshold_benef,
                "time_message_benef" => $time_message_benef
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function update(Request $request, $id){

        $access_control = "QRIS_MENU.EDC.UPDATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $schedule_date = $request->get('schedule_date');
            $schedule_time = $request->get('schedule_time');
            $schedule_at = null;
    
            if ($schedule_date && $schedule_time) {
                // Combine date and time into a single timestamp
                $schedule_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $schedule_date . ' ' . $schedule_time);
                $time_threshold_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_PARTNER_FEE_TIME_THRESHOLD_YUKK_CO, []);
                
                if ($time_threshold_response->is_ok) {
                    if(!isset($time_threshold_response->result) ||
                        !isset($time_threshold_response->result->time_threshold) ||
                        !isset($time_threshold_response->result->time_threshold_benef)){
                        $time_message = 'Failed to get time for schedule apply.';
                        $time_threshold = ''; 
                    }

                    $time_threshold = $time_threshold_response->result->time_threshold;
                    $time_threshold_benef = $time_threshold_response->result->time_threshold_benef;
                    if (!$time_threshold || !preg_match('/^\d{2}:\d{2}$/', $time_threshold ?? '')) {
                        H::flashFailed('Invalid time threshold format. Ask admin to provide a valid "HH:MM" format.', true);
                        return redirect(route('yukk_co.edc.edit', $id));
                    }
                    if (!$time_threshold_benef || !preg_match('/^\d{2}:\d{2}$/', $time_threshold_benef ?? '')) {
                        H::flashFailed('Invalid time threshold format. Ask admin to provide a valid "HH:MM" format.', true);
                        return redirect(route('yukk_co.edc.edit', $id));
                    }
                    // Check if schedule_time time matches the time in settings
                    if ($schedule_time !== $time_threshold && $schedule_time !== $time_threshold_benef) {
                        H::flashFailed('The scheduled time does not match the required time.', true);
                        return redirect(route('yukk_co.edc.edit', $id));
                    }
                } else {
                    H::flashFailed('Failed to get schedule time.', true);
                    return redirect(route('yukk_co.edc.edit', $id));
                }
            }

            $schedule_at = $schedule_at ? $schedule_at->format('Y-m-d H:i:s') : null;
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_ACTIVATION_SERVICE_EDC_UPDATE_DETAIL_YUKK_CO, [
               'form_params' => [
                   'id' => $id,
                   'merchant_branch_id' => $request->get('merchant_branch_id'),
                   'partner_login_id' => $request->get('partner_login_id'),
                   'beneficiary_id' => $request->get('customer_id'),
                   'partner_fee_id' => $request->get('partner_fee_id'),
                   'event_id' => $request->get('event_id'),
                   'grant_type' => $request->get('grant_type'),
                   'store_id' => $request->get('store_id'),
                   'client_id' => $request->get('snap_client_id'),
                   'client_secret' => $request->get('snap_client_secret'),
                   'payment_notify_mode' => $request->get('payment_notify_mode') == 'WEBHOOK_PG' ? 'WEBHOOK' : $request->get('payment_notify_mode'),
                   'is_payment_gateway' => $request->get('payment_notify_mode') == 'WEBHOOK_PG' ? 1 : 0,
                   'webhook_url' => $request->get('webhook_url'),
                   'schedule_at' => $schedule_at,
                   'status' => $request->get('status')
               ],
           ]);

           if ($response->is_ok){
               if($schedule_at)  H::flashSuccess("EDC data change scheduled for {$schedule_at}.", true);
               else H::flashSuccess($response->status_message, true);
               return redirect(route('yukk_co.edc.detail', $id));
           }else{
               H::flashFailed($response->status_message, true);
               return redirect(route('yukk_co.edc.edit', $id));
           }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public static function generateSpreadSheet($edcs, $partner_logins) {    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $border_style = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $sheet->setCellValue('A1', 'Beneficiary Name');
        $sheet->setCellValue('B1', 'Merchant Name');
        $sheet->setCellValue('C1', 'Branch Name');
        $sheet->setCellValue('D1', 'Partner Name');
        $sheet->setCellValue('E1', 'Partner Fee');
        $sheet->setCellValue('F1', 'Type of QRIS');
        $sheet->setCellValue('G1', 'Client ID');
        $sheet->setCellValue('H1', 'Client Secret');
        $sheet->setCellValue('I1', 'URL Notification Callback');
        $sheet->setCellValue('J1', 'Status');
        $sheet->setCellValue('K1', 'MPAN');
        $sheet->setCellValue('L1', 'MID');
        $sheet->setCellValue('M1', 'NMID');

        $sheet->getStyle('A1')->applyFromArray($border_style);
        $sheet->getStyle('B1')->applyFromArray($border_style);
        $sheet->getStyle('C1')->applyFromArray($border_style);
        $sheet->getStyle('D1')->applyFromArray($border_style);
        $sheet->getStyle('E1')->applyFromArray($border_style);
        $sheet->getStyle('F1')->applyFromArray($border_style);
        $sheet->getStyle('G1')->applyFromArray($border_style);
        $sheet->getStyle('H1')->applyFromArray($border_style);
        $sheet->getStyle('I1')->applyFromArray($border_style);  
        $sheet->getStyle('J1')->applyFromArray($border_style);  
        $sheet->getStyle('K1')->applyFromArray($border_style);   
        $sheet->getStyle('L1')->applyFromArray($border_style);   
        $sheet->getStyle('M1')->applyFromArray($border_style);   


        $row_index = 2;
        
        foreach ($edcs as $edc) {
            if($edc->type == 'STICKER'){
                $col_index = 1;

                $beneficiary_name = @$edc->customer ? $edc->customer->name : '';
                $merchant_name = @$edc->branch ? $edc->branch->merchant->name : '';
                $branch_name = @$edc->branch ? $edc->branch->name : '';
                $partner_name = @$edc->partner ? $edc->partner->name : '';
                $partner_fee = @$edc->partner_fee ? $edc->partner_fee->name : '';
                $qris_type = @$edc->type ?? "";
                $client_id = @$edc->client_id ?? "";
                $client_secret = @$edc->client_secret ?? "";
                $callback_url = @$edc->webhook_url ?? "";
                $mpan = @$edc->mpan ?? "";
                $mid = @$edc->mid ?? "";
                $nmid_pten = @$edc->nmid_pten ?? "";
                $status = @$edc->active == 1 ? 'Active' : 'Inactive';

                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $beneficiary_name);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $merchant_name);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $branch_name);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $partner_name);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $partner_fee);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $qris_type);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $client_id, DataType::TYPE_STRING2);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $client_secret, DataType::TYPE_STRING2);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $callback_url);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $status);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $mpan, DataType::TYPE_STRING2);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $mid, DataType::TYPE_STRING2);
                $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $nmid_pten, DataType::TYPE_STRING2);

                $row_index++;
                $col_index = 1;
            }
            if($edc->type == 'QRIS_DYNAMIC'){
                if(@$edc->partner_logins){
                    foreach($edc->partner_logins as $partner_login){
                        if($partner_login->grant_type == 'CLIENT_ID_SECRET' || $partner_login->grant_type == 'NONE'){
                            $col_index = 1;

                            $beneficiary_name = @$edc->customer ? @$edc->customer->name : '';
                            $branch_name = @$edc->branch ? @$edc->branch->name : '';
                            $merchant_name = @$edc->branch ? @$edc->branch->merchant->name : '';
                            $partner_name = @$edc->partner ? @$edc->partner->name : '';
                            $partner_fee = @$edc->partner_fee ? @$edc->partner_fee->name : '';
                            $qris_type = @$edc->type ?? "";
                            $client_id = @$partner_login->username ?? "";
                            $client_secret = @$partner_login->password ?? "";
                            $callback_url = @$partner_login->webhook_url ?? "";
                            $mpan = @$edc->mpan ?? "";
                            $mid = @$edc->mid ?? "";
                            $nmid_pten = @$edc->nmid_pten ?? "";
                            $status = @$edc->active == 1 ? 'Active' : 'Inactive';

                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $beneficiary_name);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $merchant_name);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $branch_name);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $partner_name);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $partner_fee);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $qris_type);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $client_id, DataType::TYPE_STRING2);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $client_secret, DataType::TYPE_STRING2);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $callback_url);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $status);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $mpan, DataType::TYPE_STRING2);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $mid, DataType::TYPE_STRING2);
                            $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                            $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $nmid_pten, DataType::TYPE_STRING2);

                            $row_index++;
                            $col_index = 1;
                        }
                    }
                }else{
                    $col_index = 1;

                    $beneficiary_name = @$edc->customer ? @$edc->customer->name : '';
                    $branch_name = @$edc->branch ? @$edc->branch->name : '';
                    $merchant_name = @$edc->branch ? @$edc->branch->merchant->name : '';
                    $partner_name = @$edc->partner ? @$edc->partner->name : '';
                    $partner_fee = @$edc->partner_fee ? @$edc->partner_fee->name : '';
                    $qris_type = @$edc->type ?? "";
                    $client_id = "";
                    $client_secret = "";
                    $callback_url = "";
                    $mpan = @$edc->mpan ?? "";
                    $mid = @$edc->mid ?? "";
                    $nmid_pten = @$edc->nmid_pten ?? "";
                    $status = @$edc->active == 1 ? 'Active' : 'Inactive';

                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $beneficiary_name);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $merchant_name);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $branch_name);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $partner_name);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $partner_fee);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $qris_type);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $client_id, DataType::TYPE_STRING2);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $client_secret, DataType::TYPE_STRING2);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $callback_url);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $status);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $mpan, DataType::TYPE_STRING2);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $mid, DataType::TYPE_STRING2);
                    $sheet->getStyleByColumnAndRow($col_index, $row_index)->applyFromArray($border_style);
                    $sheet->setCellValueExplicitByColumnAndRow($col_index++, $row_index, $nmid_pten, DataType::TYPE_STRING2);

                    $row_index++;
                    $col_index = 1;
                }
            }
        }

        return $spreadsheet;
    }
}
