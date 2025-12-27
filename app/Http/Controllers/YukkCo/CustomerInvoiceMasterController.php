<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 27-Oct-22
 * Time: 12:12
 */

namespace App\Http\Controllers\YukkCo;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CustomerInvoiceMasterController extends BaseController {

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
        $page = $request->get("page", 1);
        $date_range_string = $request->get("invoice_date", null);
        $customer_id = $request->get("customer_id", -1);
        $partner_id = $request->get("partner_id", -1);
        $invoice_number = $request->get("invoice_number", null);

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
        } else {
            $start_date = Carbon::now()->subDays(31)->startOfDay();
            $end_date = Carbon::now()->endOfDay();
        }

        $per_page = 10;

        $query_params = [
            "page" => $page,
            "per_page" => $per_page,
        ];
        if ($start_date && $end_date) {
            $query_params["start_date"] = $start_date->format("Y-m-d");
            $query_params["end_date"] = $end_date->format("Y-m-d");
        }

        if ($request->get("customer_id", -1) != -1) {
            $query_params['customer_id'] = $request->get("customer_id", -1);
        }
        if ($request->get("partner_id", -1) != -1) {
            $query_params['partner_id'] = $request->get("partner_id", -1);
        }
        if ($request->get("invoice_number", null) != null) {
            $query_params['invoice_number'] = $request->get("invoice_number", null);
        }

        $customer_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_LIST_YUKK_CO, []);
        $customer_list = collect([]);
        if ($customer_response->is_ok) {
            $customer_list = $customer_response->result;
        }
        $partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_PARTNER_LIST_YUKK_CO, []);
        $partner_list = collect([]);
        if ($partner_response->is_ok) {
            $partner_list = $partner_response->result;
        }

        $customer_invoice_master_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_LIST_YUKK_CO, [
            "query" => $query_params,
        ]);


        //dd($customer_invoice_master_response);
        if (! $customer_invoice_master_response->is_ok) {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_master_response);
        }

        $result = $customer_invoice_master_response->result;

        $customer_invoice_master_list = $result->data;

        if ($request->has("export_to_csv")) {
            $file_name = "Beneficiary Invoice " . $start_date->format("d-M-Y") . " - " . $end_date->format("d-M-Y");
            $headers = [
                "Beneficiary Name",
                "Partner Name",
                "Invoice Number",
                "Invoice Date",
                "Start Paid At",
                "End Paid At",
                "Count Transaction",
                "Sum Grand Total",
                "Sum MDR Internal Fixed",
                "Sum MDR Internal Percentage",
                "Sum MDR External Fixed",
                "Sum MDR External Percentage",
                "Sum Fee Partner Fixed",
                "Sum Fee Partner Percentage",
                "Status",
            ];
            return H::getStreamCsv($file_name, $headers, $customer_invoice_master_list, function($customer_invoice_master) {
                return [
                    @$customer_invoice_master->customer->name,
                    @$customer_invoice_master->partner->name,
                    @$customer_invoice_master->invoice_number,
                    @Carbon::parse($customer_invoice_master->invoice_date)->format("Y-m-d"),
                    @Carbon::parse($customer_invoice_master->start_paid_at)->format("Y-m-d H:i:s"),
                    @Carbon::parse($customer_invoice_master->end_paid_at)->format("Y-m-d H:i:s"),
                    @$customer_invoice_master->count_transaction,
                    @number_format($customer_invoice_master->sum_grand_total, 2, ",", ""),
                    @number_format($customer_invoice_master->sum_mdr_internal_fixed, 2, ",", ""),
                    @number_format($customer_invoice_master->sum_mdr_internal_percentage, 2, ",", ""),
                    @number_format($customer_invoice_master->sum_mdr_external_fixed, 2, ",", ""),
                    @number_format($customer_invoice_master->sum_mdr_external_percentage, 2, ",", ""),
                    @number_format($customer_invoice_master->sum_fee_partner_fixed, 2, ",", ""),
                    @number_format($customer_invoice_master->sum_fee_partner_percentage, 2, ",", ""),
                    @$customer_invoice_master->status,
                ];
            });
        }
        $current_page = $result->current_page;
        $last_page = $result->last_page;

        return view("yukk_co.customer_invoice_master.list", [
            "customer_invoice_master_list" => $customer_invoice_master_list,
            "current_page" => $current_page,
            "last_page" => $last_page,
            "start_date" => $start_date,
            "end_date" => $end_date,

            "invoice_number" => $invoice_number,

            "customer_list" => $customer_list,
            "partner_list" => $partner_list,
            "partner_id" => $partner_id,
            "customer_id" => $customer_id,
        ]);
    }


    public function createCustomerInvoice(Request $request) {
        $access_control = "PG_INVOICE.CREATE_INVOICE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "customer_id" => "required",
            "partner_id" => "required",
            "start_time" => "required",
            "end_time" => "required",
            "invoice_number" => "required",
            "invoice_date" => "required",
        ]);

        if ($validator->fails()) {
            return view("global.default_api_response_not_ok", ["custom_response" => (object)["status_code" => 0, "status_message" => $validator->errors()->first()]]);
        }

        $customer_id = $request->get("customer_id");
        $partner_id = $request->get("partner_id");
        $start_time = $request->get("start_time");
        $end_time = $request->get("end_time");
        $invoice_number = $request->get("invoice_number");
        $invoice_date = $request->get("invoice_date");

        $create_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CREATE_CUSTOMER_INVOICE, [
            "form_params" => [
                "customer_id" => $customer_id,
                "partner_id" => $partner_id,
                "start_time" => $start_time,
                "end_time" => $end_time,
                "invoice_number" => $invoice_number,
                "invoice_date" => $invoice_date,
            ],
        ]);

        if ($create_invoice_response->is_ok) {
            S::flashSuccess($create_invoice_response->status_message, true);
            return redirect(route("cms.yukk_co.customer_invoice_master.item", @$create_invoice_response->result->id));
        } else if ($create_invoice_response->status_code == 10900) {
            $data = [
                "customer_invoice_detail_list" => $create_invoice_response->result,
                "status_message" => $create_invoice_response->status_message,
            ];
            return view("yukk_co.customer_invoice_master.error_transaction_already_invoiced", $data);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($create_invoice_response);
        }
    }

    public function show(Request $request, $customer_invoice_master_id) {
        $customer_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_ITEM_YUKK_CO, [
            "form_params" => [
                "customer_invoice_master_id" => $customer_invoice_master_id,
            ],
        ]);

        if ($customer_invoice_response->is_ok) {
            $customer_invoice_master = $customer_invoice_response->result;

            $total_invoice = @($customer_invoice_master->sum_mdr_external_fixed + $customer_invoice_master->sum_mdr_external_percentage) - ($customer_invoice_master->sum_mdr_internal_fixed + $customer_invoice_master->sum_mdr_internal_percentage);
            $total_reimbursement = @($customer_invoice_master->sum_mdr_internal_fixed + $customer_invoice_master->sum_mdr_internal_percentage) + ($customer_invoice_master->sum_fee_partner_fixed + $customer_invoice_master->sum_fee_partner_percentage);
            $total_mdr_internal = @($customer_invoice_master->sum_mdr_internal_fixed + $customer_invoice_master->sum_mdr_internal_percentage);
            $total_fee_partner = @($customer_invoice_master->sum_fee_partner_fixed + $customer_invoice_master->sum_fee_partner_percentage);
            $kwitansi_data = (object)[
                "customer" => $customer_invoice_master->customer,
                "partner" => $customer_invoice_master->partner,
                "invoice_number" => @$customer_invoice_master->invoice_number,
                "invoice_date" => @Carbon::parse($customer_invoice_master->invoice_date),
                "start_time" => @Carbon::parse($customer_invoice_master->start_paid_at),
                "end_time" => @Carbon::parse($customer_invoice_master->end_paid_at),
                "total_invoice" => $total_invoice,
                "total_reimbursement" => $total_reimbursement,
                "total_mdr_internal" => $total_mdr_internal,
                "total_fee_partner" => $total_fee_partner,
                "total_amount" => @$total_invoice + $total_reimbursement,
            ];

            $ppn = round(round($total_invoice / 1.11, 2) * 0.11, 2);
            $dpp = $total_invoice - $ppn;

            return view("yukk_co.customer_invoice_master.show", [
                "customer_invoice_master" => $customer_invoice_master,
                "kwitansi_data" => $kwitansi_data,
                "dpp" => $dpp,
                "ppn" => $ppn,
            ]);
        } else if ($customer_invoice_response->status_code == 7014) {
            return abort(404);
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_response);
        }
    }

    public function downloadTransaction(Request $request) {
        $customer_invoice_master_id = $request->get("customer_invoice_master_id");

        $customer_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_DETAIL_LIST_YUKK_CO, [
            "form_params" => [
                "customer_invoice_master_id" => $customer_invoice_master_id,
            ],
            "query" => [
                "per_page" => 100000,
            ],
        ]);

        if ($customer_invoice_response->is_ok) {
            $customer_invoice_master = @$customer_invoice_response->result->customer_invoice_master;
            $customer_invoice_details = @$customer_invoice_response->result->data;

            $file_name = "Transaction List " . $customer_invoice_master->invoice_number;
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


            return H::getStreamCsv($file_name, $columns, $customer_invoice_details, function($item) {
                return [
                    @$item->transaction->beneficiary->name,
                    @$item->transaction->partner->name,
                    @$item->transaction->provider->name,
                    @$item->transaction->payment_channel->name,
                    @$item->transaction->code,
                    @$item->transaction->order_id,
                    @$item->transaction->paid_at,
                    @number_format($item->transaction->grand_total, 2, ",", ""),
                    @number_format($item->transaction->mdr_internal_fixed, 2, ",", ""),
                    @number_format($item->transaction->mdr_internal_percentage, 2, ",", ""),
                    @number_format($item->transaction->mdr_external_fixed, 2, ",", ""),
                    @number_format($item->transaction->mdr_external_percentage, 2, ",", ""),
                    @number_format($item->transaction->fee_partner_fixed, 2, ",", ""),
                    @number_format($item->transaction->fee_partner_percentage, 2, ",", ""),
                ];
            });
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_response);
        }
    }

    public function delete(Request $request) {
        $access_control = "PG_INVOICE.DELETE_INVOICE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $customer_invoice_master_id = $request->get("customer_invoice_master_id");

        $customer_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_DELETE_YUKK_CO, [
            "form_params" => [
                "customer_invoice_master_id" => $customer_invoice_master_id,
            ],
        ]);

        if ($customer_invoice_response->is_ok) {
            S::flashSuccess($customer_invoice_response->status_message, true);
            return redirect(route("cms.yukk_co.customer_invoice_master.index"));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_response);
        }
    }

    public function postInvoice(Request $request) {
        $access_control = "PG_INVOICE.POST_INVOICE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $customer_invoice_master_id = $request->get("customer_invoice_master_id");

        $customer_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_POST_YUKK_CO, [
            "form_params" => [
                "customer_invoice_master_id" => $customer_invoice_master_id,
            ],
        ]);

        if ($customer_invoice_response->is_ok) {
            S::flashSuccess($customer_invoice_response->status_message, true);
            return redirect(route("cms.yukk_co.customer_invoice_master.item", $customer_invoice_master_id));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_response);
        }
    }

    public function payInvoice(Request $request) {
        $access_control = "PG_INVOICE.POST_INVOICE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $customer_invoice_master_id = $request->get("customer_invoice_master_id");

        $customer_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_PAY_YUKK_CO, [
            "form_params" => [
                "customer_invoice_master_id" => $customer_invoice_master_id,
            ],
        ]);

        if ($customer_invoice_response->is_ok) {
            S::flashSuccess($customer_invoice_response->status_message, true);
            return redirect(route("cms.yukk_co.customer_invoice_master.item", $customer_invoice_master_id));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_response);
        }
    }

    public function searchCustomerPartner(Request $request) {
        $date_range_string = $request->get("date_range", null);

        if ($date_range_string) {
            $date_range_exploded = explode(" - ", $date_range_string);
            try {
                $start_time = Carbon::parse($date_range_exploded[0]);
            } catch (\Exception $e) {
                $start_time = Carbon::now()->subMonth()->startOfMonth()->subDay();
            }
            try {
                $end_time = isset($date_range_exploded[1]) ? Carbon::parse($date_range_exploded[1]) : Carbon::now()->subMonth()->endOfMonth()->subDay();;
            } catch (\Exception $e) {
                $end_time = Carbon::now()->subMonth()->endOfMonth()->subDay();
            }
        } else {
            $start_time = Carbon::now()->subMonth()->startOfMonth()->subDay();
            $end_time = Carbon::now()->subMonth()->endOfMonth()->subDay();
        }


        $data = [
            "start_time" => $start_time,
            "end_time" => $end_time,
        ];

        // Get Beneficiary x Partner
        $search_customer_partner_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_SEARCH_CUSTOMER_PARTNER, [
            "query" => [
                "start_time" => $start_time->format("Y-m-d H:i:s"),
                "end_time" => $end_time->format("Y-m-d H:i:s"),
            ],
        ]);

        if ($search_customer_partner_response->is_ok) {
            $data["result"] = $search_customer_partner_response->result;
        } else {
            return $this->getApiResponseNotOkDefaultResponse($search_customer_partner_response);
        }
        return view("yukk_co.customer_invoice_master.search_customer_partner", $data);
    }

    public function previewInvoice(Request $request) {
        $validator = Validator::make($request->all(), [
            "customer_id" => "required",
            "partner_id" => "required",
            "start_time" => "required",
            "end_time" => "required",
            //"invoice_number" => "required",
            //"invoice_date" => "required",
        ]);

        if ($validator->fails()) {
            return view("global.default_api_response_not_ok", ["custom_response" => (object)["status_code" => 0, "status_message" => $validator->errors()->first()]]);
        }

        $_start_time = $request->get("start_time", null);
        $_end_time = $request->get("end_time", null);
        $customer_id = $request->get("customer_id", null);
        $partner_id = $request->get("partner_id", null);

        if ($_start_time) {
            try {
                $start_time = Carbon::parse($_start_time);
            } catch (\Exception $e) {
                $start_time = Carbon::now()->subMonth()->startOfMonth();
            }
        } else {
            $start_time = Carbon::now()->subMonth()->startOfMonth();
        }
        if ($_end_time) {
            try {
                $end_time = Carbon::parse($_end_time);
            } catch (\Exception $e) {
                $end_time = Carbon::now()->subMonth()->endOfMonth();
            }
        } else {
            $end_time = Carbon::now()->subMonth()->endOfMonth();
        }
        $data = [
            "start_time" => $start_time,
            "end_time" => $end_time,
            "customer_id" => $customer_id,
            "partner_id" => $partner_id,
        ];


        $validator = Validator::make($request->all(), [
            "invoice_number" => "required",
            "invoice_date" => "required",
        ]);
        if ($validator->fails()) {
            // Invoice date and Invoice number not inputed yet
            return view("yukk_co.customer_invoice_master.preview_invoice", $data);
        } else {
            $data['invoice_number'] = $invoice_number = $request->get("invoice_number");
            $invoice_date = Carbon::parse($request->get("invoice_date"));
            $data['invoice_date'] = $invoice_date;

            $preview_data_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_GET_PREVIEW_DATA, [
                "form_params" => [
                    "customer_id" => $customer_id,
                    "partner_id" => $partner_id,
                    "start_time" => $start_time->format("Y-m-d H:i:s"),
                    "end_time" => $end_time->format("Y-m-d H:i:s"),
                ],
            ]);

            //dd($preview_data_response);

            if ($preview_data_response->is_ok) {
                $customer = @$preview_data_response->result->customer;
                $partner = @$preview_data_response->result->partner;
                $preview_data = @$preview_data_response->result->preview_data;

                $data['customer'] = $customer;
                $data['partner'] = $partner;
                $data['preview_data'] = $preview_data;

                $total_amount = @round(($preview_data->sum_mdr_external_fixed + $preview_data->sum_mdr_external_percentage) - ($preview_data->sum_mdr_internal_fixed + $preview_data->sum_mdr_internal_percentage), 2);
                $ppn = @round(($total_amount / 1.11) * 0.11, 2);
                $dpp = $total_amount - $ppn;

                # Trim data for partner and customer
                $customer_ = collect($customer);
                $customer_ = (object)$customer_->only(["id", "name", "address"])->toArray();
                $partner_ = collect($partner);
                $partner_ = (object)$partner_->only(["id", "name"])->toArray();


                $data['invoice_data'] = (object)[
                    "customer" => $customer_,
                    "partner" => $partner_,
                    "invoice_number" => @$invoice_number,
                    "invoice_date" => @$invoice_date,
                    "start_time" => @$start_time,
                    "end_time" => @$end_time,
                    "dpp" => @$dpp,
                    "ppn" => @$ppn,
                    "total_amount" => @$total_amount,
                ];

                $total_invoice = round($total_amount, 2);

                $total_amount = @round($preview_data->sum_mdr_internal_fixed + $preview_data->sum_mdr_internal_percentage + $preview_data->sum_fee_partner_fixed + $preview_data->sum_fee_partner_percentage, 2);
                $data['reimbursement_data'] = (object)[
                    "customer" => $customer_,
                    "partner" => $partner_,
                    "invoice_number" => @$invoice_number,
                    "invoice_date" => @$invoice_date,
                    "start_time" => @$start_time,
                    "end_time" => @$end_time,
                    "total_amount" => @$total_amount,
                ];

                $total_reimbursement = round($total_amount, 2);

                $total_amount = round($total_invoice, 2) + round($total_reimbursement, 2);

                $total_mdr_internal = @round($preview_data->sum_mdr_internal_fixed + $preview_data->sum_mdr_internal_percentage, 2);
                $total_fee_partner = @round($preview_data->sum_fee_partner_fixed + $preview_data->sum_fee_partner_percentage, 2);
                $data['kwitansi_data'] = (object)[
                    "customer" => $customer_,
                    "partner" => $partner_,
                    "invoice_number" => @$invoice_number,
                    "invoice_date" => @$invoice_date,
                    "start_time" => @$start_time,
                    "end_time" => @$end_time,
                    "dpp" => @$dpp,
                    "ppn" => @$ppn,
                    "total_invoice" => @$total_invoice,
                    "total_reimbursement" => @$total_reimbursement,
                    "total_mdr_internal" => @$total_mdr_internal,
                    "total_fee_partner" => @$total_fee_partner,
                    "total_amount" => @$total_amount,
                ];

                $data['provider_list'] = @$preview_data_response->result->preview_provider_list;

                return view("yukk_co.customer_invoice_master.preview_invoice", $data);
            } else {
                return $this->getApiResponseNotOkDefaultResponse($preview_data_response);
            }

            /*if (! $validator->fails()) {
                $data["customer_id"] = $customer_id;
                $data["partner_id"] = $partner_id;
                $data["customer_id"] = $invoice_number;
                $data["invoice_date"] = Carbon::parse($invoice_date);
                $data["invoice_number"] = $invoice_number;
                $data["sum_total_mdr_internal"] = rand(0, 9999999);
                $data["sum_total_mdr_external"] = rand($data["sum_total_mdr_internal"], $data["sum_total_mdr_internal"] + 100000);

                $data["show_invoice"] = true;

                $encoded_data = encrypt($data);

                $data["url_invoice"] = route("cms.yukk_co.transaction_pg_invoice.invoice_pdf", ["data" => $encoded_data]);
                $data["url_reimbursement"] = route("cms.yukk_co.transaction_pg_invoice.reimbursement_pdf", ["data" => $encoded_data]);
                $data["url_download_invoice"] = route("cms.yukk_co.transaction_pg_invoice.invoice_pdf", ["data" => $encoded_data, "export_to_pdf" => 1]);
                $data["url_download_reimbursement"] = route("cms.yukk_co.transaction_pg_invoice.reimbursement_pdf", ["data" => $encoded_data, "export_to_pdf" => 1]);
            }


            return view("yukk_co.customer_invoice_master.preview_invoice", $data);*/
        }
    }

    public function invoicePdfPreview(Request $request) {
        $data = null;
        try {
            $data = (decrypt($request->get("data")));
        } catch (\Exception $e) { $e->getTrace(); }

        if ($data == null) {
            return abort(400);
        }
        $data = (object) $data;

        $path = "assets/images/pg_invoice_sign.png";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $sign_data = file_get_contents($path);
        $base64_sign = 'data:image/' . $type . ';base64,' . base64_encode($sign_data);

        $filename = "Invoice_Report_" . (isset($data->start_time) ? $data->start_time->format("Y-m-d His") : "") . " - " . (isset($data->end_time) ? $data->end_time->format("Y-m-d His") : "");
        $view = view("yukk_co.customer_invoice_master.invoice", [
            "filename" => $filename,
            "start_time" => @$data->start_time ?? null,
            "end_time" => @$data->end_time ?? null,
            "dpp" => $data->dpp,
            "ppn" => $data->ppn,
            "total_amount" => $data->total_amount,
            "invoice_date" => $data->invoice_date,
            "invoice_number" => $data->invoice_number,
            "customer" => (object)[
                "npwp_no" => @$data->customer->npwp_no,
                "account_name" => @$data->customer->name,
                "address" => @$data->customer->address,
            ],
            "preview" => true,
            "base64_sign" => $base64_sign,
        ]);
        if ($request->has("export_to_pdf")) {
            $dompdf = new Dompdf();
            $dompdf->loadHtml($view->render());

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            $dompdf->stream($filename);
        } else {
            return $view;
        }
    }

    public function reimbursementPdfPreview(Request $request) {
        $data = null;
        try {
            $data = (decrypt($request->get("data")));
        } catch (\Exception $e) { $e->getTrace(); }

        if ($data == null) {
            return abort(400);
        }
        $data = (object) $data;

        $path = "assets/images/pg_invoice_sign.png";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $sign_data = file_get_contents($path);
        $base64_sign = 'data:image/' . $type . ';base64,' . base64_encode($sign_data);

        $filename = "Reimbursement_Form_" . (isset($data->start_time) ? $data->start_time->format("Y-m-d His") : "") . " - " . (isset($data->end_time) ? $data->end_time->format("Y-m-d His") : "");
        $view = view("yukk_co.customer_invoice_master.reimbursement", [
            "filename" => $filename,
            "start_time" => $data->start_time ?? null,
            "end_time" => $data->end_time ?? null,
            "amount" => $data->total_amount,
            "invoice_date" => $data->invoice_date,
            "invoice_number" => $data->invoice_number,
            "customer" => (object)[
                "npwp_no" => @$data->customer->npwp_no,
                "account_name" => @$data->customer->name,
                "address" => @$data->customer->address,
            ],
            "preview" => true,
            "base64_sign" => $base64_sign,
        ]);
        if ($request->has("export_to_pdf")) {
            $dompdf = new Dompdf();
            $dompdf->loadHtml($view->render());

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            $dompdf->stream($filename);
        } else {
            return $view;
        }
    }

    public function kwitansiPdfPreview(Request $request) {
        $data = null;
        try {
            $data = (decrypt($request->get("data")));
        } catch (\Exception $e) { $e->getTrace(); }

        if ($data == null) {
            return abort(400);
        }
        $data = (object) $data;

        $path = "assets/images/pg_invoice_sign.png";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $sign_data = file_get_contents($path);
        $base64_sign = 'data:image/' . $type . ';base64,' . base64_encode($sign_data);

        $filename = "Kwitansi_" . (isset($data->start_time) ? $data->start_time->format("Y-m-d His") : "") . " - " . (isset($data->end_time) ? $data->end_time->format("Y-m-d His") : "");
        $view = view("yukk_co.customer_invoice_master.kwitansi_pdf", [
            "filename" => $filename,
            "start_time" => @$data->start_time ?? null,
            "end_time" => @$data->end_time ?? null,
            "total_invoice" => @$data->total_invoice,
            "total_reimbursement" => @$data->total_reimbursement,
            "total_mdr_internal" => @$data->total_mdr_internal,
            "total_fee_partner" => @$data->total_fee_partner,
            "total_amount" => @$data->total_amount,
            "invoice_date" => $data->invoice_date,
            "invoice_number" => $data->invoice_number,
            "customer" => (object)[
                "npwp_no" => @$data->customer->npwp_no,
                "name" => @$data->customer->name,
                "address" => @$data->customer->address,
            ],
            "preview" => true,
            "base64_sign" => $base64_sign,
        ]);
        if ($request->has("export_to_pdf")) {
            $dompdf = new Dompdf();
            $dompdf->loadHtml($view->render());

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            $dompdf->stream($filename);
        } else {
            return $view;
        }
    }

    public function kwitansiPdf(Request $request) {
        $data = null;
        try {
            $data = (decrypt($request->get("data")));
        } catch (\Exception $e) { $e->getTrace(); }

        if ($data == null) {
            return abort(400);
        }
        $data = (object) $data;

        $path = "assets/images/pg_invoice_sign.png";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $sign_data = file_get_contents($path);
        $base64_sign = 'data:image/' . $type . ';base64,' . base64_encode($sign_data);

        $filename = "Kwitansi_" . (isset($data->start_time) ? $data->start_time->format("Y-m-d His") : "") . " - " . (isset($data->end_time) ? $data->end_time->format("Y-m-d His") : "");
        $view = view("yukk_co.customer_invoice_master.kwitansi_pdf", [
            "filename" => $filename,
            "start_time" => @$data->start_time ?? null,
            "end_time" => @$data->end_time ?? null,
            "total_invoice" => @$data->total_invoice,
            "total_reimbursement" => @$data->total_reimbursement,
            "total_mdr_internal" => @$data->total_mdr_internal,
            "total_fee_partner" => @$data->total_fee_partner,
            "total_amount" => @$data->total_amount,
            "invoice_date" => $data->invoice_date,
            "invoice_number" => $data->invoice_number,
            "customer" => (object)[
                "npwp_no" => @$data->customer->npwp_no,
                "name" => @$data->customer->name,
                "address" => @$data->customer->address,
            ],
            "preview" => false,
            "base64_sign" => $base64_sign,
        ]);
        if ($request->has("export_to_pdf")) {
            $dompdf = new Dompdf();
            $dompdf->loadHtml($view->render());

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            $dompdf->stream($filename);
        } else {
            return $view;
        }
    }

    public function previewDownloadTransaction(Request $request) {
        $validator = Validator::make($request->all(), [
            "customer_id" => "required",
            "partner_id" => "required",
            "start_time" => "required",
            "end_time" => "required",
            //"invoice_number" => "required",
            //"invoice_date" => "required",
        ]);

        if ($validator->fails()) {
            return view("global.default_api_response_not_ok", ["custom_response" => (object)["status_code" => 0, "status_message" => $validator->errors()->first()]]);
        }

        $_start_time = $request->get("start_time", null);
        $_end_time = $request->get("end_time", null);
        $customer_id = $request->get("customer_id", null);
        $partner_id = $request->get("partner_id", null);

        if ($_start_time) {
            try {
                $start_time = Carbon::parse($_start_time);
            } catch (\Exception $e) {
                $start_time = Carbon::now()->subMonth()->startOfMonth();
            }
        } else {
            $start_time = Carbon::now()->subMonth()->startOfMonth();
        }
        if ($_end_time) {
            try {
                $end_time = Carbon::parse($_end_time);
            } catch (\Exception $e) {
                $end_time = Carbon::now()->subMonth()->endOfMonth();
            }
        } else {
            $end_time = Carbon::now()->subMonth()->endOfMonth();
        }


        $preview_transaction_list_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_GET_PREVIEW_TRANSACTION_LIST, [
            "form_params" => [
                "customer_id" => $customer_id,
                "partner_id" => $partner_id,
                "start_time" => $start_time->format("Y-m-d H:i:s"),
                "end_time" => $end_time->format("Y-m-d H:i:s"),
            ],
        ]);

        //dd($preview_transaction_list_response);

        if ($preview_transaction_list_response->is_ok) {
            $transaction_list = @$preview_transaction_list_response->result;

            $file_name = "Preview Transaction List " . $start_time->format("d-M-Y His") . " - " . $end_time->format("d-M-Y His") . ".csv";
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
        } else {
            return $this->getApiResponseNotOkDefaultResponse($preview_transaction_list_response);
        }
    }

    public function revertStatusEmail(Request $request) {
        $access_control = "PG_INVOICE.POST_INVOICE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "customer_invoice_master_id" => "required",
        ]);
        $validator->validate();
        $customer_invoice_master_id = $request->get("customer_invoice_master_id");

        $customer_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_REVERT_STATUS_EMAIL_YUKK_CO, [
            "form_params" => [
                "customer_invoice_master_id" => $customer_invoice_master_id,
            ],
        ]);

        if ($customer_invoice_response->is_ok) {
            S::flashSuccess($customer_invoice_response->status_message, true);
            return redirect(route("cms.yukk_co.customer_invoice_master.item", $customer_invoice_master_id));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_response);
        }
    }

    public function changeCustomerEmail(Request $request) {
        $access_control = "PG_INVOICE.POST_INVOICE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "customer_invoice_master_id" => "required",
            "customer_email" => "required",
        ]);
        $validator->validate();

        $customer_invoice_master_id = $request->get("customer_invoice_master_id");
        $customer_email = $request->get("customer_email");

        $customer_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_CHANGE_CUSTOMER_EMAIL_YUKK_CO, [
            "form_params" => [
                "customer_invoice_master_id" => $customer_invoice_master_id,
                "customer_email" => $customer_email,
            ],
        ]);

        if ($customer_invoice_response->is_ok) {
            S::flashSuccess($customer_invoice_response->status_message, true);
            return redirect(route("cms.yukk_co.customer_invoice_master.item", $customer_invoice_master_id));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_response);
        }
    }

    public function triggerSendCustomerEmail(Request $request) {
        $access_control = "PG_INVOICE.POST_INVOICE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "customer_invoice_master_id" => "required",
        ]);
        $validator->validate();

        $customer_invoice_master_id = $request->get("customer_invoice_master_id");

        $customer_invoice_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PG_INVOICE_CUSTOMER_INVOICE_MASTER_TRIGGER_SEND_CUSTOMER_EMAIL_YUKK_CO, [
            "form_params" => [
                "customer_invoice_master_id" => $customer_invoice_master_id,
            ],
        ]);

        if ($customer_invoice_response->is_ok) {
            S::flashSuccess($customer_invoice_response->status_message, true);
            return redirect(route("cms.yukk_co.customer_invoice_master.item", $customer_invoice_master_id));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($customer_invoice_response);
        }
    }
}