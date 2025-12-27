<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 09-Dec-22
 * Time: 13:20
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerInvoiceProviderController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("PG_INVOICE.VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "PG_INVOICE.VIEW",
                ]));
            }
        });
    }

    public function index(Request $request) {
        $provider_id = $request->get("provider_id", -1);
        $date_range_string = $request->get("date_range", null);

        $data = [
            "provider_id" => $provider_id,
        ];

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_date = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_date = Carbon::now()->subDays(31)->startOfDay();
            }
            try {
                $end_date = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->endOfDay();
            } catch (\Exception $e) {
                $end_date = Carbon::now()->endOfDay();
            }

            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
        } else {
            $start_date = null;
            $end_date = null;
            $data['start_date'] = Carbon::now()->subDays(31)->startOfDay();
            $data['end_date'] = Carbon::now()->endOfDay();
        }


        $provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PROVIDER_LIST_YUKK_CO, []);
        if ($provider_response->is_ok) {
            $provider_list = $provider_response->result;
        } else {
            $provider_list = [];
        }
        $data['provider_list'] = $provider_list;


        if ($start_date && $end_date) {
            $form_params = [
                "start_date" => $start_date->format("Y-m-d"),
                "end_date" => $end_date->format("Y-m-d"),
            ];

            if ($provider_id != -1) {
                $form_params['provider_id'] = $provider_id;
            }
            $customer_invoice_provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_PROVIDER_LIST_YUKK_CO, [
                "form_params" => $form_params,
            ]);

            if (! $customer_invoice_provider_response->is_ok) {
                return $this->getApiResponseNotOkDefaultResponse($customer_invoice_provider_response);
            }

            $data['customer_invoice_provider_list'] = $customer_invoice_provider_response->result;
        } else {
            $data['customer_invoice_provider_list'] = null;
        }

        return view("yukk_co.customer_invoice_provider.index", $data);
    }

    public function detail(Request $request) {
        $validator = Validator::make($request->all(), [
            "provider_id" => "required",
            "start_date" => "required",
            "end_date" => "required",
        ]);
        $validator->validate();

        $start_date = Carbon::parse($request->get("start_date"));
        $end_date = Carbon::parse($request->get("end_date"));
        $provider_id = $request->get("provider_id");


        $form_params = [
            "start_date" => $start_date->format("Y-m-d"),
            "end_date" => $end_date->format("Y-m-d"),
            "provider_id" => $provider_id,
        ];
        $customer_invoice_provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_PROVIDER_DETAIL_YUKK_CO, [
            "form_params" => $form_params,
        ]);

        //dd($form_params, $customer_invoice_provider_response);

        if (! $customer_invoice_provider_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_provider_response);
        }

        $customer_invoice_detail = $customer_invoice_provider_response->result;
        //dd($customer_invoice_detail->provider->id);
        return view("yukk_co.customer_invoice_provider.detail", [
            "customer_invoice_detail" => $customer_invoice_detail,
            "start_date" => $start_date,
            "end_date" => $end_date,
        ]);
    }

    public function downloadTransactionList(Request $request) {
        $validator = Validator::make($request->all(), [
            "provider_id" => "required",
            "start_date" => "required",
            "end_date" => "required",
        ]);
        $validator->validate();

        $start_date = Carbon::parse($request->get("start_date"));
        $end_date = Carbon::parse($request->get("end_date"));
        $provider_id = $request->get("provider_id");

        $customer_invoice_master_id = $request->get("customer_invoice_master_id", null);


        $form_params = [
            "start_date" => $start_date->format("Y-m-d"),
            "end_date" => $end_date->format("Y-m-d"),
            "provider_id" => $provider_id,
        ];

        if ($customer_invoice_master_id) {
            $form_params['customer_invoice_master_id'] = $customer_invoice_master_id;
        }
        $customer_invoice_provider_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_PROVIDER_DOWNLOAD_TRANSACTION_LIST_YUKK_CO, [
            "form_params" => $form_params,
        ]);

        $transaction_list = $customer_invoice_provider_response->result;
        $file_name = "Transaction List";
        $columns = [
            'Beneficiary',
            'Partner',
            'Provider',
            'Payment Channel',
            'Ref Code',
            'Order ID',
            'Paid Time',
            'Grand Total',
            'MDR Internal Fixed',
            'MDR Internal Percentage',
            'MDR External Fixed',
            'MDR External Percentage',
            'Fee Partner Fixed',
            'Fee Partner Percentage',
        ];

        return H::getStreamCsv($file_name, $columns, $transaction_list, function($item) {
            return [
                @$item->beneficiary->name,
                @$item->partner->name,
                @$item->provider->name,
                @$item->payment_channel->name,
                @$item->code,
                @$item->order_id,
                @$item->paid_at,
                @number_format($item->grand_total, 2, ",", ""),
                @number_format($item->mdr_internal_fixed, 2, ",", ""),
                @number_format($item->mdr_internal_percentage, 2, ",", ""),
                @number_format($item->mdr_external_fixed, 2, ",", ""),
                @number_format($item->mdr_external_percentage, 2, ",", ""),
                @number_format($item->fee_partner_fixed, 2, ",", ""),
                @number_format($item->fee_partner_percentage, 2, ",", ""),
            ];
        });
    }

}