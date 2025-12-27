<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class QoinCreditLogController extends BaseController
{
    public function index(Request $request) {
        $page = $request->get("page", 1);
        $date_range_string = $request->get("date_range", null);

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
            $per_page = 10000;
        }

        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
            'search' => $search = $request->get('search'),
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_QOIN_CREDIT_LOG_LIST_YUKK_CO, [
            'query' => $query_params
        ]);

        if ($response->is_ok) {
            $result = $response->result;
            $logs = $result->data;

            [$current_page, $last_page] = [$result->current_page, $result->last_page];

            // For getting last row
            $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_QOIN_CREDIT_LOG_LIST_YUKK_CO, [
                "per_page" => 1,
            ]);

            $last_end_credit = @$response->result->data[0]->end_credit;
            $start_credit = number_format(@$response->result->data[0]->end_credit, 0, null, '.');

            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Report Order deposit " . $start_date->format("d/m/Y") . " - " . $end_date->format("d/m/Y") . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'RefCode',
                    'Object_ID',
                    'Title',
                    'Description',
                    'Start_Credit',
                    'Value',
                    'End_Credit',
                    'Type',
                    'Created_At',
                ];

                $callback = function() use ($logs, $columns, $last_end_credit, $start_date, $end_date)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, [
                        "Date Range",
                        @$start_date->format("d/m/Y H:i:s") . " - " . $end_date->format("d/m/Y H:i:s"),
                    ]);

                    fputcsv($file, [
                        "Last End Credit",
                        "IDR " . @number_format($last_end_credit, 2, ",", "."),
                    ]);

                    fputcsv($file, [
                        "",
                    ]);

                    fputcsv($file, $columns);

                    foreach($logs as $credit_log) {
                        fputcsv($file, [
                            @$credit_log->ref_code,
                            @$credit_log->object_id,
                            @$credit_log->title,
                            @$credit_log->description,
                            "IDR " . @number_format($credit_log->start_credit, 2, ",", "."),
                            "IDR " . @number_format($credit_log->value, 2, ",", "."),
                            "IDR " . @number_format($credit_log->end_credit, 2, ",", "."),
                            @$credit_log->type,
                            @H::formatDateTime($credit_log->created_at, "d/m/Y H:i:s"),
                        ]);
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            }
            return view("yukk_co.qoin_credit_logs.list", [
                "logs" => $logs,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "start_credit" => $start_credit,

                "start_time" => $start_date,
                "end_time" => $end_date,
                "search" => $search,

                "last_end_credit" => $last_end_credit,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function store(Request $request) {
        $body = array_merge([
            'created_by' => S::getUser()->id,
        ], $request->only(['object_id', 'title', 'description', 'start_credit', 'value', 'end_credit', 'type']));

        $body['value'] = str_replace(".", "", $body['value']);

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_QOIN_CREDIT_LOG_CREATE_YUKK_CO, [
            'json' => $body
        ]);

        if ($response->is_ok) {
            H::flashSuccess($response->status_message);
            return redirect(route('cms.yukk_co.transaction_qoin.credit_logs.index'));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }
}
