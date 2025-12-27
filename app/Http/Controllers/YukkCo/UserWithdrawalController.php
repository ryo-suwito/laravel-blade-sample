<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 02-Feb-22
 * Time: 15:24
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class UserWithdrawalController extends BaseController {

    public function index(Request $request) {
        $access_control = "CASHOUT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $page = $request->get("page", 1);
            $date_range_string = $request->get("date_range", null);
            $keyword_ref_code = $request->get("ref_code", null);
            $keyword_bank_name = $request->get("bank_name", null);
            $status = $request->get("status", "ALL");
            $bank_type = $request->get("bank_type", "ALL");
            $yukk_id = $request->get("yukk_id", null);

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

            $start_time = $start_time->startOfDay();
            $end_time = $end_time->endOfDay();

            if ($bank_type) {
                if ($bank_type == "ALL" || $bank_type == "NON_BCA") {
                    // do nothing
                } else {
                    $bank_type = "BCA";
                }
            }
            if ($yukk_id) {
                if (empty($yukk_id)) {
                    $yukk_id = null;
                }
            }


            $query_params = [];
            if ($start_time && $end_time) {
                $query_params["start_date"] = $start_time->format("Y-m-d");
                $query_params["end_date"] = $end_time->format("Y-m-d ");
            }

            $query_params["page"] = $page;
            if ($request->has("export_to_csv")) {
                $query_params["per_page"] = 10000000;
            } else {
                $query_params["per_page"] = 20;
            }

            if ($keyword_ref_code) {
                $query_params["ref_code"] = $keyword_ref_code;
            }
            if ($keyword_bank_name) {
                $query_params["bank_name"] = $keyword_bank_name;
            }
            if ($status) {
                $query_params["status"] = $status;
            }
            if ($bank_type) {
                if ($bank_type == "ALL") {
                    // do nothing if ALL
                } else {
                    $query_params["bank_type"] = $bank_type;
                }
            }
            if ($yukk_id) {
                $query_params["yukk_id"] = $yukk_id;
            }

            $user_withdrawal_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_USER_WITHDRAWAL_LIST_YUKK_CO, [
                "query" => $query_params,
            ]);

            if ($user_withdrawal_response->is_ok) {
                $result = $user_withdrawal_response->result;

                $user_withdrawal_list = $result->data;

                if ($request->has("export_to_csv")) {
                    $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=User Withdrawal " . $start_time->format("d-M-Y") . " - " . $end_time->format("d-M-Y") . ".csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

                    $columns = [
                        'yukk_id',
                        'ref_code',
                        'bank_name',
                        'bank_type',
                        'yukk_cash',
                        'nominal_transfer',
                        'mdr_internal',
                        'status',
                        'created_at',
                    ];

                    $callback = function() use ($user_withdrawal_list, $columns)
                    {
                        $file = fopen('php://output', 'w');
                        fputcsv($file, $columns);

                        foreach($user_withdrawal_list as $user_withdrawal) {
                            fputcsv($file, [
                                @$user_withdrawal->user->yukk_id,
                                @$user_withdrawal->ref_code,
                                @$user_withdrawal->bank_name,
                                @$user_withdrawal->bank_type,
                                @H::formatNumber($user_withdrawal->yukk_p, 2),
                                @H::formatNumber($user_withdrawal->amount, 2),
                                @H::formatNumber(($user_withdrawal->fee_internal_fixed + $user_withdrawal->fee_internal_percentage), 2),
                                @$user_withdrawal->status,
                                @H::formatDateTime($user_withdrawal->created_at, "Y-m-d H:i:s"),
                            ]);
                        }
                        fclose($file);
                    };
                    return Response::stream($callback, 200, $headers);
                } else {
                    $current_page = $result->current_page;
                    $last_page = $result->last_page;
                    //dd($transaction_payment_response);
                    return view("yukk_co.user_withdrawals.list", [
                        "user_withdrawal_list" => $user_withdrawal_list,
                        "current_page" => $current_page,
                        "last_page" => $last_page,
                        "start_time" => $start_time,
                        "end_time" => $end_time,
                        "keyword_ref_code" => $keyword_ref_code,
                        "keyword_bank_name" => $keyword_bank_name,
                        "status" => $status,
                        "bank_type" => $bank_type,
                        "yukk_id" => $yukk_id,
                    ]);
                }
            } else {
                return $this->getApiResponseNotOkDefaultResponse($user_withdrawal_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }


    public function show(Request $request, $user_withdrawal_id) {
        $access_control = "CASHOUT";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            $user_withdrawal_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_USER_WITHDRAWAL_ITEM_YUKK_CO, [
                "form_params" => [
                    "user_withdrawal_id" => $user_withdrawal_id,
                ],
            ]);

            if ($user_withdrawal_response->is_ok) {
                $result = $user_withdrawal_response->result;

                $user_withdrawal = $result;

                $user_withdrawal_updated_at_ = isset($user_withdrawal->updated_at) ? $user_withdrawal->updated_at : null;
                $user_withdrawal_updated_at = Carbon::now();
                try {
                    if ($user_withdrawal_updated_at_) {
                        $user_withdrawal_updated_at = Carbon::parse($user_withdrawal_updated_at_, "Asia/Jakarta");
                    }
                } catch (\Exception $e) { $e->getTrace(); }
                //dump($user_withdrawal_updated_at, Carbon::now());
                //dd(Carbon::now()->subSeconds(0)->diffInSeconds($user_withdrawal_updated_at, false));
                if (Carbon::now()->diffInSeconds($user_withdrawal_updated_at, false) * -1 > env("USER_WITHDRAWAL_BUTTON_VISIBLE_IN_SECONDS", "120")) {
                    $button_actions_visible = true;
                } else {
                    $button_actions_visible = false;
                }

                return view("yukk_co.user_withdrawals.show", [
                    "user_withdrawal" => $user_withdrawal,
                    "button_actions_visible" => $button_actions_visible,
                ]);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($user_withdrawal_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }

    public function action(Request $request, $user_withdrawal_id) {
        $access_control = "CASHOUT_ADM";
        if (AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            //dd($request->all());
            $user_withdrawal_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_USER_WITHDRAWAL_ACTION_YUKK_CO, [
                "form_params" => [
                    "user_withdrawal_id" => $user_withdrawal_id,
                    "status" => $request->get("status", null),
                    "bank_type" => $request->get("bank_type", null),
                    "action" => strtoupper($request->get("action", null)),
                ],
            ]);

            if ($user_withdrawal_response->is_ok) {
                H::flashSuccess($user_withdrawal_response->status_message, true);
                return redirect(route("cms.yukk_co.user_withdrawal.item", $user_withdrawal_id));
            } else {
                return $this->getApiResponseNotOkDefaultResponse($user_withdrawal_response);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
    }
}