<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 16-Sep-21
 * Time: 09:59
 */

namespace App\Http\Controllers\Partners;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DisbursementPartnerController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("DISBURSEMENT_PARTNER_VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "DISBURSEMENT_PARTNER_VIEW",
                ]));
            }
        });
    }


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

        $query_params = [
            "page" => $page,
            "per_page" => 100,
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }
        $disbursement_partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_PARTNER_LIST_PARTNER, [
            "query" => $query_params,
        ]);

        if ($disbursement_partner_response->is_ok) {
            $result = $disbursement_partner_response->result;

            $disbursement_partner_list = $result->data;

            $current_page = $result->current_page;
            $last_page = $result->last_page;
            //dd($transaction_payment_response);
            return view("partners.disbursement_partner.list", [
                "disbursement_partner_list" => $disbursement_partner_list,
                "current_page" => $current_page,
                "last_page" => $last_page,
                "start_time" => $start_date,
                "end_time" => $end_date,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_partner_response);
        }
    }

    public function show(Request $request, $disbursement_partner_id) {
        $disbursement_customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_DISBURSEMENT_PARTNER_ITEM_PARTNER, [
            "form_params" => [
                "disbursement_partner_master_id" => $disbursement_partner_id,
            ],
        ]);

        if ($disbursement_customer_response->is_ok) {
            $result = $disbursement_customer_response->result;

            return view("partners.disbursement_partner.show", [
                "disbursement_partner" => $result,
            ]);
        } else if ($disbursement_customer_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($disbursement_customer_response);
        }
    }


}
