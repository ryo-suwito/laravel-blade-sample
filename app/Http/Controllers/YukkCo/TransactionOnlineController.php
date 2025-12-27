<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionOnlineController extends BaseController
{
    public function index(Request $request)
    {
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

        $query = [
            'search' => $request->get('search'),
            'per_page' => $request->get('per_page'),
            'status' => $request->get('status'),
            'start_date' => $start_date->format("Y-m-d"),
            'end_date' => $end_date->format("Y-m-d"),
            'page' => $request->get('page')
        ];

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_TRANSACTION_ONLINE_GET_TRANSACTION_LIST, [
            "query" => $query,
        ]);

        if ($response->is_ok){
            $result = $response->result;

            return view('yukk_co.transaction_merchant_online.index', [
                'transaction_list' => $result->data,
                'from' => $result->from,
                'to' => $result->to,
                'total' => $result->total,
                'current_page' => $result->current_page,
                'last_page' => $result->last_page,
                'per_page' => $query['per_page'],
                'start_time' => $start_date,
                'end_time' => $end_date,
                'status' => $query['status'],
                'search' => $query['search']
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function detail(Request $request, $id)
    {
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_TRANSACTION_ONLINE_GET_TRANSACTION_DETAIL, [
            'query' => [
                'id' => $id,
            ],
        ]);

        if ($response->is_ok){
            $result = $response->result;

            return view('yukk_co.transaction_merchant_online.detail', [
                'transaction' => $result,
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }
}
