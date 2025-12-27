<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 30-Aug-21
 * Time: 16:03
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SettlementMasterController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("SETTLEMENT_MASTER_VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "SETTLEMENT_MASTER_VIEW",
                ]));
            }
        });
    }
    public function index(Request $request) {
        $page = $request->get("page", 1);
        $date_range_string = $request->get("date_range", null);
        $per_page = $request->get("per_page", 10);
        $ref_code = $request->get("ref_code", null);
        $customer_name = $request->get("customer_name", null);
        $partner_name = $request->get("partner_name", null);
        $customer_id = $request->get("customer_id", null);

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

        if ($request->has("export_to_csv")) {
            $per_page = 99999999;
        }

        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
        ];
        $form_params = [];
        if ($start_date && $end_date) {
            $form_params["start_date"] = $start_date->format("Y-m-d");
            $form_params["end_date"] = $end_date->format("Y-m-d");
        }

        if ($ref_code) {
            $form_params['ref_code'] = $ref_code;
        }
        if ($customer_name) {
            $form_params['customer_name'] = $customer_name;
        }
        if ($customer_id) {
            $form_params['customer_id'] = $customer_id;
        }
        if ($partner_name) {
            $form_params['partner_name'] = $partner_name;
        }

        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_LIST_YUKK_CO, [
            "query" => $query_params,
            "form_params" => $form_params,
        ]);

        if ($settlement_master_response->is_ok) {
            $result = $settlement_master_response->result;

            $settlement_master_list = $result->data;

            if ($request->has("export_to_csv")) {
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Settlement List " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y") . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                $columns = [
                    'Beneficiary ID',
                    'Beneficiary Name',
                    'Beneficiary Bank Type',
                    'Partner Name',
                    'Partner Bank Type',
                    'Settlement Date',
                    'Ref Code',
                    'Total YUKK Cash',
                    'Total YUKK Points',
                    'Total Other Currency',
                    'Merchant Portion',
                    'Partner Portion',
                    'Status',
                ];

                $callback = function() use ($settlement_master_list, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($settlement_master_list as $settlement_master) {
                        fputcsv($file, [
                            @$settlement_master->customer_id,
                            @$settlement_master->customer->name,
                            @$settlement_master->customer->bank_type,
                            @$settlement_master->partner->name,
                            @$settlement_master->partner->bank_type,
                            @$settlement_master->settlement_date,
                            @$settlement_master->ref_code,
                            @number_format($settlement_master->total_yukk_cash, 2, ".", ""),
                            @number_format($settlement_master->total_yukk_points, 2, ".", ""),
                            @number_format($settlement_master->total_other_currency, 2, ".", ""),
                            @number_format($settlement_master->total_merchant_portion, 2, ".", ""),
                            @number_format($settlement_master->total_fee_partner, 2, ".", ""),
                            @$settlement_master->status,
                        ]);
                    }
                    fclose($file);
                };
                return Response::stream($callback, 200, $headers);
            } else {
                $current_page = $result->current_page;
                $last_page = $result->last_page;

                //dd($transaction_payment_response);
                return view("yukk_co.settlement_masters.list", [
                    "settlement_master_list" => $settlement_master_list,
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "start_time" => $start_date,
                    "end_time" => $end_date,

                    "ref_code" => $ref_code,
                    "customer_name" => $customer_name,
                    "partner_name" => $partner_name,
                    "customer_id" => $customer_id,

                    "showing_data" => [
                        "from" => $result->from,
                        "to" => $result->to,
                        "total" => $result->total,
                    ],

                ]);
            }
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }
    }

    public function listSwitching(Request $request) {
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

        $form_params = [];
        if ($start_date && $end_date) {
            $form_params["start_date"] = $start_date->format("Y-m-d");
            $form_params["end_date"] = $end_date->format("Y-m-d");
        }
        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_SWITCHING_YUKK_CO, [
            "form_params" => $form_params,
        ]);

        if ($settlement_master_response->is_ok) {
            $total_yukk_cash_switching = 0;
            $total_yukk_points_switching = 0;

            $total_issuer_qr_nominal = 0;
            $total_issuer_qr_fee = 0;
            $total_issuer_refund_nominal = 0;
            $total_issuer_refund_fee = 0;
            $total_acquirer_qr_nominal = 0;
            $total_acquirer_qr_fee = 0;
            $total_acquirer_refund_nominal = 0;
            $total_acquirer_refund_fee = 0;

            try {
                $total_yukk_cash_switching = @$settlement_master_response->result->yukk_cash_points->yukk_cash;
            } catch (\Exception $e) { $e->getTrace(); }
            try {
                $total_yukk_points_switching = @$settlement_master_response->result->yukk_cash_points->yukk_points;
            } catch (\Exception $e) { $e->getTrace(); }

            try {
                $total_issuer_qr_nominal = @$settlement_master_response->result->issuer_qr->nominal;
            } catch (\Exception $e) { $e->getTrace(); }
            try {
                $total_issuer_qr_fee = @$settlement_master_response->result->issuer_qr->fee;
            } catch (\Exception $e) { $e->getTrace(); }
            try {
                $total_issuer_refund_nominal = @$settlement_master_response->result->issuer_refund->nominal;
            } catch (\Exception $e) { $e->getTrace(); }
            try {
                $total_issuer_refund_fee = @$settlement_master_response->result->issuer_refund->fee;
            } catch (\Exception $e) { $e->getTrace(); }

            try {
                $total_acquirer_qr_nominal = @$settlement_master_response->result->acquirer_qr->nominal;
            } catch (\Exception $e) { $e->getTrace(); }
            try {
                $total_acquirer_qr_fee = @$settlement_master_response->result->acquirer_qr->fee;
            } catch (\Exception $e) { $e->getTrace(); }
            try {
                $total_acquirer_refund_nominal = @$settlement_master_response->result->acquirer_refund->nominal;
            } catch (\Exception $e) { $e->getTrace(); }
            try {
                $total_acquirer_refund_fee = @$settlement_master_response->result->acquirer_refund->fee;
            } catch (\Exception $e) { $e->getTrace(); }


            /*dd([
                $settlement_master_list_non_switching->sum("total_merchant_portion"),
                $settlement_master_list_non_switching->sum("total_yukk_cash") + $settlement_master_list_non_switching->sum("total_yukk_points"),
                $total_other_currency_hak,
                ($total_other_currency_hak - $total_fee_switching_hak) - ($total_grand_total_kewajiban - $total_fee_yukk_kewajiban),
            ]);*/
            //dd($transaction_payment_response);

            // Additional Data
            $settlement_master_list_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_LIST_YUKK_CO, [
                "form_params" => array_merge($form_params, [
                    "customer_is_switching" => 1,
                ]),
                "query" => [
                    "page" => 1,
                    "per_page" => 10000,
                ],
            ]);
            $settlement_master_list = [];
            if ($settlement_master_list_response->is_ok) {
                @$settlement_master_list = $settlement_master_list_response->result->data;
            } else {
                $settlement_master_list = [];
            }

            return view("yukk_co.settlement_masters.list_switching", [
                "settlement_master_list" => $settlement_master_list,
                "total_issuer_qr_nominal" => $total_issuer_qr_nominal,
                "total_issuer_qr_fee" => $total_issuer_qr_fee,
                "total_issuer_refund_nominal" => $total_issuer_refund_nominal,
                "total_issuer_refund_fee" => $total_issuer_refund_fee,

                "total_acquirer_qr_nominal" => $total_acquirer_qr_nominal,
                "total_acquirer_qr_fee" => $total_acquirer_qr_fee,
                "total_acquirer_refund_nominal" => $total_acquirer_refund_nominal,
                "total_acquirer_refund_fee" => $total_acquirer_refund_fee,

                "total_yukk_cash_switching" => $total_yukk_cash_switching,
                "total_yukk_points_switching" => $total_yukk_points_switching,

                "start_time" => $start_date,
                "end_time" => $end_date,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }
    }

    public function listSwitchingOld(Request $request) {
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
        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);

        if ($settlement_master_response->is_ok) {
            $result = $settlement_master_response->result;

            $settlement_master_list = $result->data;

            $settlement_master_list_switching = collect($settlement_master_list)->filter(function($item, $key) {
                return $item->customer_is_switching;
            });

            $settlement_master_list_non_switching = collect($settlement_master_list)->filter(function($item, $key) {
                return ! $item->customer_is_switching;
            });

            $total_grand_total_kewajiban = $settlement_master_list_switching->sum("total_grand_total");
            $total_fee_yukk_kewajiban = $settlement_master_list_switching->sum("total_fee_yukk");

            $total_other_currency_hak = $settlement_master_list_non_switching->sum("total_other_currency");
            $total_fee_switching_hak = $settlement_master_list_non_switching->sum("total_fee_switching");

            $total_yukk_cash_switching = $settlement_master_list_switching->sum("total_yukk_cash");
            $total_yukk_points_switching = $settlement_master_list_switching->sum("total_yukk_points");

            /*dd([
                $settlement_master_list_non_switching->sum("total_merchant_portion"),
                $settlement_master_list_non_switching->sum("total_yukk_cash") + $settlement_master_list_non_switching->sum("total_yukk_points"),
                $total_other_currency_hak,
                ($total_other_currency_hak - $total_fee_switching_hak) - ($total_grand_total_kewajiban - $total_fee_yukk_kewajiban),
            ]);*/
            //dd($transaction_payment_response);
            return view("yukk_co.settlement_masters.list_switching", [
                //"settlement_master_list" => $settlement_master_list,
                "total_grand_total_kewajiban" => $total_grand_total_kewajiban,
                "total_fee_yukk_kewajiban" => $total_fee_yukk_kewajiban,
                "total_other_currency_hak" => $total_other_currency_hak,
                "total_fee_switching_hak" => $total_fee_switching_hak,

                "total_yukk_cash_switching" => $total_yukk_cash_switching,
                "total_yukk_points_switching" => $total_yukk_points_switching,

                "start_time" => $start_date,
                "end_time" => $end_date,
            ]);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }
    }

    public function show(Request $request, $settlement_master_id) {
        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_ITEM_YUKK_CO, [
            "form_params" => [
                "settlement_master_id" => $settlement_master_id,
                "rrn" => $request->get("rrn", null),
            ],
            "query" => [
                "page" => $request->get("page", "1"),
                "per_page" => $request->get("per_page", "50"),
            ],
        ]);

        //dd($settlement_master_response);

        if ($settlement_master_response->is_ok) {
            $result = $settlement_master_response->result;

            return view("yukk_co.settlement_masters.show", [
                "settlement_master" => $result,

                "current_page" => $request->get("page", "1"),
                "last_page" => $request->get("page", "1")+3,

                "rrn" => $request->get("rrn", null),
            ]);
        } else if ($settlement_master_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }
    }

    public function exportCsvTransaction(Request $request, $settlement_master_id) {
        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_ITEM_YUKK_CO, [
            "form_params" => [
                "settlement_master_id" => $settlement_master_id,
            ],
            "query" => [
                "per_page" => 1000000,
            ],
        ]);

        if ($settlement_master_response->is_ok) {
            $settlement_master = $settlement_master_response->result;

            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Settlement Transaction List " . @$settlement_master->ref_code . " " . @H::formatDateTime($settlement_master->settlement_date, "d-M-Y") . " " . substr(@$settlement_master->customer->name, 0, 24) . ".csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = [
                'Merchant Branch Name',
                'Ref Code',
                'Transaction Time',
                'Type',
                'Source',
                'YUKK ID',
                'User ID',
                'YUKK Cash',
                'YUKK Points',
                'Other Currency',
                'Grand Total',
                'YUKK Portion Fixed',
                'Merchant Portion',
            ];

            $callback = function() use ($settlement_master, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($settlement_master->transaction_payments as $transaction_payment) {
                    fputcsv($file, [
                        @$transaction_payment->merchant_branch_name,
                        @$transaction_payment->transaction_code,
                        @$transaction_payment->transaction_time,
                        @$transaction_payment->yukk_as == "ISSUER" ? trans("cms.YUKK") : trans("cms.NON YUKK"),
                        @$transaction_payment->type == "CROSS_BORDER" ? trans("cms.CROSS BORDER") : trans("cms.DOMESTIC"),
                        @$transaction_payment->user->yukk_id,
                        @$transaction_payment->user_id,
                        @number_format($transaction_payment->yukk_as == "ACQUIRER" ? 0 : $transaction_payment->yukk_p, 2, ".", ""),
                        @number_format($transaction_payment->yukk_as == "ACQUIRER" ? 0 : $transaction_payment->yukk_e, 2, ".", ""),
                        @number_format($transaction_payment->yukk_as == "ACQUIRER" ? $transaction_payment->yukk_p : 0, 2, ".", ""),
                        @number_format($transaction_payment->grand_total, 2, ".", ""),
                        @number_format($transaction_payment->yukk_portion, 2, ".", ""),
                        @number_format($transaction_payment->merchant_portion, 2, ".", ""),
                    ]);
                }
                fclose($file);
            };
            return Response::stream($callback, 200, $headers);
        } else if ($settlement_master_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }
    }


    public function action(Request $request, $settlement_master_id) {
        $access_control = "SETTLEMENT_MASTER.ACTION";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "status" => "required",
            "action" => "required",
        ]);
        $validator->validate();

        $settlement_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_SETTLEMENT_MASTER_ACTION_YUKK_CO, [
            "form_params" => [
                "settlement_master_id" => $settlement_master_id,
                "status" => $request->get("status"),
                "action" => $request->get("action"),
            ],
        ]);

        if (! $settlement_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($settlement_master_response);
        }


        S::flashSuccess($settlement_master_response->status_message, true);
        return redirect(route("cms.yukk_co.settlement_master.show", $settlement_master_id));
    }

}
