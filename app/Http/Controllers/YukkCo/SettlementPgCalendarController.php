<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 12-Apr-22
 * Time: 12:06
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettlementPgCalendarController extends BaseController {

    public function index(Request $request) {
        $access_control = "SETTLEMENT_PG_CALENDAR_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);

            $date_range_string = $request->get("date_range", null);

            if ($date_range_string) {
                $date_range_exploded = explode(" - ", $date_range_string);
                try {
                    $start_date = Carbon::parse($date_range_exploded[0]);
                } catch (\Exception $e) {
                    $start_date = Carbon::now()->startOfMonth();
                }
                try {
                    $end_date = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
                } catch (\Exception $e) {
                    $end_date = Carbon::now()->endOfMonth();
                }
            } else {
                $start_date = Carbon::now()->startOfMonth();
                $end_date = Carbon::now()->endOfMonth();
            }


            $provider_id = $request->get("provider_id", null);
            $payment_channel_id = $request->get("payment_channel_id", null);

            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_LIST_YUKK_CO, [
                "per_page" => 100000,
            ]);

            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_YUKK_CO, [
                "per_page" => 100000,
            ]);

            if ($provider_response->is_ok && $payment_channel_response->is_ok) {
                $provider_list = $provider_response->result->data;
                $payment_channel_list = $payment_channel_response->result->data;

                $settlement_pg_calendar_list = null;
                $current_page = 1;
                $last_page = 1;
                if ($provider_id && $payment_channel_id) {
                    $query_params = [
                        "provider_id" => $provider_id,
                        "payment_channel_id" => $payment_channel_id,
                        "page" => $page,
                        "per_page" => 10000,
                    ];

                    if ($start_date && $end_date) {
                        $query_params["start_date"] = $start_date->format("Y-m-d");
                        $query_params["end_date"] = $end_date->format("Y-m-d");
                    }

                    $settlement_pg_calendar_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_LIST_YUKK_CO, [
                        "query" => $query_params,
                    ]);

                    if (! $settlement_pg_calendar_response->is_ok) {
                        return $this->getApiResponseNotOkDefaultResponse($settlement_pg_calendar_response);
                    } else {
                        $result = $settlement_pg_calendar_response->result;

                        $settlement_pg_calendar_list = $result->data;

                        $current_page = $result->current_page;
                        $last_page = $result->last_page;
                    }
                }

                return view("yukk_co.settlement_pg_calendars.list", [
                    "provider_id" => $provider_id,
                    "payment_channel_id" => $payment_channel_id,
                    "provider_list" => $provider_list,
                    "payment_channel_list" => $payment_channel_list,
                    "settlement_pg_calendar_list" => $settlement_pg_calendar_list,

                    "start_time" => $start_date,
                    "end_time" => $end_date,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                ]);
            } else if (! $provider_response->is_ok) {
                return $this->getApiResponseNotOkDefaultResponse($provider_response);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function show(Request $request, $settlement_pg_calendar_id) {
        $access_control = "SETTLEMENT_PG_CALENDAR_VIEW";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $settlement_pg_calendar_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_ITEM_YUKK_CO, [
                "form_params" => [
                    "settlement_pg_calendar_id" => $settlement_pg_calendar_id,
                ],
            ]);

            if ($settlement_pg_calendar_response->is_ok) {
                $result = $settlement_pg_calendar_response->result;

                return view("yukk_co.settlement_pg_calendars.show", [
                    "settlement_pg_calendar" => $result,
                ]);
            } else if ($settlement_pg_calendar_response->status_code == 7014) {
                return abort(404);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($settlement_pg_calendar_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function create(Request $request) {
        $access_control = "SETTLEMENT_PG_CALENDAR_CREATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {

            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_LIST_YUKK_CO, [
                "per_page" => 100000,
            ]);

            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_YUKK_CO, [
                "per_page" => 100000,
            ]);

            if ($provider_response->is_ok && $payment_channel_response->is_ok) {
                $provider_list = $provider_response->result->data;
                $payment_channel_list = $payment_channel_response->result->data;

                return view("yukk_co.settlement_pg_calendars.create", [
                    "provider_list" => $provider_list,
                    "payment_channel_list" => $payment_channel_list,
                ]);
            } else if (! $provider_response->is_ok) {
                return $this->getApiResponseNotOkDefaultResponse($provider_response);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function store(Request $request) {
        $access_control = "SETTLEMENT_PG_CALENDAR_CREATE";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "provider_id" => "required|integer",
                "payment_channel_id" => "required|integer",
                "settlement_date" => "required",
                "start_time_transaction" => "required",
                "end_time_transaction" => "required",
            ]);
            $validator->validate();

            $provider_id = $request->get("provider_id");
            $payment_channel_id = $request->get("payment_channel_id");
            $settlement_date_string = $request->get("settlement_date");
            $is_skip = $request->has("is_skip");
            $start_time_transaction_string = $request->get("start_time_transaction");
            $end_time_transaction_string = $request->get("end_time_transaction");


            try {
                $settlement_date = Carbon::parse($settlement_date_string);
            } catch (\Exception $e) {
                $settlement_date = Carbon::now()->startOfDay();
            }

            try {
                $start_time_transaction = Carbon::parse($start_time_transaction_string);
            } catch (\Exception $e) {
                $start_time_transaction = Carbon::now()->startOfDay();
            }

            try {
                $end_time_transaction = Carbon::parse($end_time_transaction_string);
            } catch (\Exception $e) {
                $end_time_transaction = Carbon::now()->startOfDay();
            }

            $form_params = [
                "provider_id" => $provider_id,
                "payment_channel_id" => $payment_channel_id,
                "settlement_date" => $settlement_date->format("Y-m-d"),
                "is_skip" => $is_skip,
                "start_time_transaction" => $start_time_transaction->format("Y-m-d H:i:s"),
                "end_time_transaction" => $end_time_transaction->format("Y-m-d H:i:s"),
            ];

            $settlement_pg_calendar_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_CREATE_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            if ($settlement_pg_calendar_response->is_ok) {
                H::flashSuccess($settlement_pg_calendar_response->status_message, true);

                return redirect(route("cms.yukk_co.settlement_pg_calendar.item", @$settlement_pg_calendar_response->result->id));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($settlement_pg_calendar_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function edit(Request $request, $settlement_pg_calendar_id) {
        $access_control = "SETTLEMENT_PG_CALENDAR_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {

            $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PROVIDER_LIST_YUKK_CO, [
                "per_page" => 100000,
            ]);

            $payment_channel_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_SERVICE_PAYMENT_CHANNEL_LIST_YUKK_CO, [
                "per_page" => 100000,
            ]);

            if ($provider_response->is_ok && $payment_channel_response->is_ok) {
                $provider_list = $provider_response->result->data;
                $payment_channel_list = $payment_channel_response->result->data;

                $settlement_pg_calendar_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_ITEM_YUKK_CO, [
                    "form_params" => [
                        "settlement_pg_calendar_id" => $settlement_pg_calendar_id,
                    ],
                ]);

                if ($settlement_pg_calendar_response->is_ok) {
                    $result = $settlement_pg_calendar_response->result;

                    return view("yukk_co.settlement_pg_calendars.edit", [
                        "settlement_pg_calendar" => $result,
                        "provider_list" => $provider_list,
                        "payment_channel_list" => $payment_channel_list,
                    ]);
                } else if ($settlement_pg_calendar_response->status_code == 7014) {
                    return abort(404);
                } else {
                    return $this->getApiResponseNotOkDefaultResponse($settlement_pg_calendar_response);
                }
            } else if (! $provider_response->is_ok) {
                return $this->getApiResponseNotOkDefaultResponse($provider_response);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($payment_channel_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function update(Request $request, $settlement_pg_calendar_id) {
        $access_control = "SETTLEMENT_PG_CALENDAR_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "provider_id" => "required|integer",
                "payment_channel_id" => "required|integer",
                "settlement_date" => "required",
                "start_time_transaction" => "required",
                "end_time_transaction" => "required",
            ]);
            $validator->validate();

            $provider_id = $request->get("provider_id");
            $payment_channel_id = $request->get("payment_channel_id");
            $settlement_date_string = $request->get("settlement_date");
            $is_skip = $request->has("is_skip");
            $start_time_transaction_string = $request->get("start_time_transaction");
            $end_time_transaction_string = $request->get("end_time_transaction");


            try {
                $settlement_date = Carbon::parse($settlement_date_string);
            } catch (\Exception $e) {
                $settlement_date = Carbon::now()->startOfDay();
            }

            try {
                $start_time_transaction = Carbon::parse($start_time_transaction_string);
            } catch (\Exception $e) {
                $start_time_transaction = Carbon::now()->startOfDay();
            }

            try {
                $end_time_transaction = Carbon::parse($end_time_transaction_string);
            } catch (\Exception $e) {
                $end_time_transaction = Carbon::now()->startOfDay();
            }

            $form_params = [
                "settlement_pg_calendar_id" => $settlement_pg_calendar_id,
                "provider_id" => $provider_id,
                "payment_channel_id" => $payment_channel_id,
                "settlement_date" => $settlement_date->format("Y-m-d"),
                "is_skip" => $is_skip,
                "start_time_transaction" => $start_time_transaction->format("Y-m-d H:i:s"),
                "end_time_transaction" => $end_time_transaction->format("Y-m-d H:i:s"),
            ];

            $settlement_pg_calendar_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_EDIT_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            if ($settlement_pg_calendar_response->is_ok) {
                H::flashSuccess($settlement_pg_calendar_response->status_message, true);

                return redirect(route("cms.yukk_co.settlement_pg_calendar.edit", @$settlement_pg_calendar_response->result->id));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($settlement_pg_calendar_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function delete(Request $request, $settlement_pg_calendar_id) {
        $access_control = "SETTLEMENT_PG_CALENDAR_EDIT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $validator = Validator::make($request->all(), [
                "settlement_pg_calendar_id" => "required|integer",
            ]);
            $validator->validate();

            if ($request->get("settlement_pg_calendar_id") == $settlement_pg_calendar_id) {
                $form_params = [
                    "settlement_pg_calendar_id" => $settlement_pg_calendar_id,
                ];

                $settlement_pg_calendar_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_PG_CALENDAR_DELETE_YUKK_CO, [
                    "form_params" => $form_params,
                ]);

                if ($settlement_pg_calendar_response->is_ok) {
                    H::flashSuccess($settlement_pg_calendar_response->status_message, true);

                    return redirect(route("cms.yukk_co.settlement_pg_calendar.list"));
                } else {
                    return $this->getApiResponseNotOkDefaultResponse($settlement_pg_calendar_response);
                }
            } else {
                return abort(400);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

}