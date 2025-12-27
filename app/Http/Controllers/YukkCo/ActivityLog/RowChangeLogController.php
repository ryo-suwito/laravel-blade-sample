<?php

namespace App\Http\Controllers\YukkCo\ActivityLog;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RowChangeLogController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 10);
        $type = $request->get('type', null);
        $menu = $request->get('menu', null);
        $submenu = $request->get('submenu', null);     
        $search = $request->get('search', null);
        $date_range_string = $request->get("date_range", null);

        if ($date_range_string) {
            // dd($date_range_string);
            $date_range_exploded = explode(" - ", $date_range_string);
            // dd($date_range_exploded);
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

        $query_params = [
            'per_page' => $per_page,    
            'page' => $request->get('page'),
            'type' => $type,
            'menu' => $menu,
            'submenu' => $submenu,
            'search' => $search
        ];
        
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->startOfDay()->format("Y-m-d H:i:s");
            $query_params["end_date"] = $end_date->endOfDay()->format("Y-m-d H:i:s");
        }

        if ($search) {
            $query_params["search"] = $search;
        }
        
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_ACTIVITY_LOG_LIST_YUKK_CO, [
            'query' => $query_params
        ]);
        // dd($response);

        // return $response->body_string;   

        if ($response->is_ok) {
            $result = $response->result;
            $from = $result->from;
            $to = $result->to;
            $total = $result->total;
            
            return view('activity_log.index', [
                'list' => $result->data,
                'per_page' => $per_page,    
                'from' => $from,
                'to' => $to,
                'type' => $type,
                'menu' => $menu,
                'submenu' => $submenu,
                'total' => $total,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'search' => $search,
                'current_page' => $result->current_page,
                'last_page' => $result->last_page,
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [

            ]));
        }
    }

    public function detail(Request $request, $id)
    {
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_ACTIVITY_LOG_DETAIL_YUKK_CO, [
            'query' => [
                'id' => $id
            ],
        ]);

        if ($response->is_ok) {
            $logs = collect($response->result);

            return view('activity_log.detail', [
                'logs' => $logs,
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", []));
        }
    }

}
