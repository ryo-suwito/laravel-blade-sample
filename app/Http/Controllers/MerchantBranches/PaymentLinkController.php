<?php

/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 21-Dec-21
 * Time: 11:12
 */

namespace App\Http\Controllers\MerchantBranches;


use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Helpers\H;
use App\Helpers\S;
use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentLinkController extends BaseController
{
    protected $payment_channel;
    protected $payment_link;

    public function __construct()
    {
        parent::__construct();

        $this->payment_channel = api('payment_gateway', 'payment_channel');

        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.VIEW", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "PAYMENT_GATEWAY.PAYMENT_LINK.VIEW",
                ]));
            }
        })->only(['index', 'show']);
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.CREATE", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "PAYMENT_GATEWAY.PAYMENT_LINK.CREATE",
                ]));
            }
        })->only(['create', 'import']);
        $this->middleware(function ($request, $next) {
            if (AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.DELETE", "AND")) {
                return $next($request);
            } else {
                // TODO: Custom 401 page?
                return abort(401, __("cms.401_unauthorized_message", [
                    "access_contol_list" => "PAYMENT_GATEWAY.PAYMENT_LINK.DELETE",
                ]));
            }
        })->only(['delete']);
    }

    public function index(Request $request)
    {
        $user_role = S::getUserRole();
        $merchant_branch_id = $user_role && $user_role->target_id ? $user_role->target_id : "";
        try {
            $check_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MERCHANT_BRANCH_CHECK . $merchant_branch_id . "/check", [
                "headers" =>
                [
                    "X-Request-Segment" => '{"id":3}',
                    "Accept" => "application/json"
                ],
            ]);
            if ($check_response->http_status_code == 404) {
                $check_response->status_code = 404;
                $check_response->status_message = "You have not been registered to Yukk Paymeny Gateway service. Please contact our CS for more info.";
                return $this->getApiResponseNotOkDefaultResponse($check_response);
            }
        } catch (\Exception $e) {
            return $this->getApiResponseNotOkDefaultResponse($check_response);
        }
        $date_range_string = $request->get("date_range", null);
        $order_id = $request->get("order_id", null);
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

        $page = $request->get("page", 1);
        $per_page = 99999999;

        $query_params = [
            "merchant_branch_id" => $merchant_branch_id,
            "page" => $page
        ];
        if ($order_id) {
            $query_params["order_id"] = $order_id;
        }
        if ($start_time && $end_time) {
            $query_params["start_time"] = $start_time->format("Y-m-d H:i:s");
            $query_params["end_time"] = $end_time->format("Y-m-d H:i:s");
        }
        try {
            $payment_link_response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_MERCHANT_BRANCH_LIST_PAYMENT_LINK,  [
                "query" => $query_params,
            ]);

            $payment_link_list = $payment_link_response->result->links->data;
            if ($payment_link_response->status_code == 200) {
                $can_create = false;
                if (AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.CREATE", "AND")) {
                    $can_create = true;
                }
                $can_delete = false;
                if (AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.DELETE", "AND")) {
                    $can_delete = true;
                }
                $current_page = $payment_link_response->result->links->current_page;
                $last_page = $payment_link_response->result->links->last_page;
                return view("merchant_branches.payment_link.list", [
                    "current_page" => $current_page,
                    "last_page" => $last_page,
                    "start_time" => $start_time,
                    "order_id" => $order_id,
                    "end_time" => $end_time,
                    "plus_minus_range" => 3,
                    "merchant_branch_id" => $merchant_branch_id,
                    "payment_link_list" => $payment_link_list,
                    "can_create" => $can_create,
                    "can_delete" => $can_delete
                ]);
            }
        } catch (\Exception $e) {
            return $this->getApiResponseNotOkDefaultResponse($payment_link_response);
        }
        return $this->getApiResponseNotOkDefaultResponse($payment_link_response);
    }

    public function show(Request $request, $payment_link_id)
    {
        $user_role = S::getUserRole();
        $merchant_branch_id = $user_role && $user_role->target_id ? $user_role->target_id : "";

        $response = api('payment_gateway', 'payment_link')->find($payment_link_id, [
            'merchant_branch_id' => $merchant_branch_id,
        ]);

        apiResponseHandler($response)->failedView();

        return view("merchant_branches.payment_link.show", [
            "payment_link" => json_decode(json_encode($response->json('result.link'))),
            "can_delete" => AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.DELETE", "AND")
                ? true
                : false,
        ]);
    }

    public function delete(Request $request, $payment_link_id)
    {
        $user_role = S::getUserRole();
        $merchant_branch_id = $user_role && $user_role->target_id ? $user_role->target_id : "";

        $response = api('payment_gateway', 'payment_link')->delete($payment_link_id, [
            'merchant_branch_id' => $merchant_branch_id,
        ]);

        apiResponseHandler($response, null, function () {
            abort(back()->withErrors(["status_message" => "Delete Payment Link Failed"]));
        });

        return back()->with('success_message', 'Delete Payment Link Success');

    }

    public function create(Request $request)
    {
        if (AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.CREATE", "AND")) {
            if ($request->isMethod('post')) {
                $url = ApiHelper::END_POINT_MERCHANT_BRANCH_CREATE_PAYMENT_LINK;
                $raw = $request->all();
                $raw["use_customer_details"] = ($raw["use_customer_details"] == 'true');
                $raw["use_customer_address"] = ($raw["use_customer_address"] == 'true');
                $raw["expired_at"] = $raw["use_expired_at"] == 'true' ? $raw["expired_at"] : "";
                $raw["payment_channels"] = explode(",", $raw["payment_channels"]);

                try {
                    $payment_link_response = ApiHelper::requestGeneral('POST', $url, [
                        'json' => $raw,
                        'content-type' => 'application/json',
                        "headers" =>
                        [
                            "X-Request-Segment" => '{"id":2}',
                            "Accept" => "application/json"
                        ]
                    ]);
                    if ($payment_link_response->status_code == 200) {
                        return response()->json(['error' => false, 'url' => route('cms.merchant_branches.payment_link.list', [])]);
                    } else if ($payment_link_response->status_code == 7014) {
                        $error_message = "Item not found";
                    } else {
                        $error_message = $payment_link_response->status_message;
                        if (
                            $payment_link_response->status_code == 422
                            && property_exists($payment_link_response, 'result')
                        ) {
                            return response()->json(['error' => true, 'error_message' => $error_message, 'error_data' => $payment_link_response->result]);
                        }
                    }
                    return response()->json(['error' => true, 'error_message' => $error_message]);
                } catch (\Exception $e) {
                    return response()->json(['error' => true, 'error_message' => "We are very sorry but it seems our Service is currently Unavailable. Please try again later or contact our Customer Support."]);
                }
            }
            if ($request->isMethod('get')) {
                $d2 = new \Datetime("now");
                $user_role = S::getUserRole();
                $merchant_branch_id = $user_role && $user_role->target_id ? $user_role->target_id : "";
                try {
                    $payment_channels = ApiHelper::requestGeneral('GET', ApiHelper::END_POINT_MERCHANT_BRANCH_PAYMENT_CHANNEL_PAYMENT_LINK, [
                        "headers" =>
                        [
                            "Accept" => "application/json"
                        ],
                        "query" => [
                            "merchant_branch_id" => $merchant_branch_id
                        ],
                    ]);
                    $payment_link = (object) [
                        "merchant_branch_id" => $merchant_branch_id,
                        "order_id" => $d2->format('U'),
                        "payment_channels" => $payment_channels->result->payment_channels
                    ];
                } catch (\Exception $e) {
                    return $this->getApiResponseNotOkDefaultResponse($payment_channels);
                }
                return view("merchant_branches.payment_link.create", [
                    "payment_link" => $payment_link,
                ]);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => "PAYMENT_GATEWAY.PAYMENT_LINK.CREATE",
            ]));
        }
    }

    public function import(Request $request)
    {
        if (AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.CREATE", "AND")) {
            $d2 = new \Datetime("now");
            $user_role = S::getUserRole();
            $merchant_branch_id = $user_role && $user_role->target_id ? $user_role->target_id : "";

            $payment_link = (object) [
                "merchant_branch_id" => $merchant_branch_id,
            ];

            return view("merchant_branches.payment_link.import", [
                "payment_link" => $payment_link,
            ]);
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => "PAYMENT_GATEWAY.PAYMENT_LINK.CREATE",
            ]));
        }
    }
    public function bulkCreate(Request $request)
    {
        if (AccessControlHelper::checkCurrentAccessControl("PAYMENT_GATEWAY.PAYMENT_LINK.CREATE", "AND")) {
            $user = S::getUser();

            try {
                $response = api('payment_gateway', 'payment_link')->import(
                    $request->file('file_import'),
                    [
                        'merchant_branch_id' => $request->get('merchant_branch_id'),
                        'email' => $user->email,
                    ]
                );

                switch ($response->json('status_code')) {
                    case 200:
                        return response()->json(['error' => false, 'url' => route('cms.merchant_branches.payment_link.list', [])]);
                    case 4001:
                        return response()->json(['error' => true, 'error_message' => "maximum import is 100 row", 'error_data' => $response->json('result')], 400);
                    default:
                        $error_message = $response->json('status_message');

                        return response()->json(['error' => true, 'error_message' => $error_message, 'error_data' => $response->json('result')], 400);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'error_message' => "We are very sorry but it seems our Service is currently Unavailable. Please try again later or contact our Customer Support."]);
            }
        } else {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => "PAYMENT_GATEWAY.PAYMENT_LINK.CREATE",
            ]));
        }
    }
    public function downloadTemplate(Request $request)
    {
        $file_path = public_path('template/payment-link.csv');
        return response()->download($file_path);
    }

    public function downloadListBankCode(Request $request)
    {
        $user_role = S::getUserRole();
        $merchant_branch_id = $user_role && $user_role->target_id ? $user_role->target_id : "";

        $filename = 'list-of-bank-code.csv';

        $payment_channels = ApiHelper::requestGeneral('GET', ApiHelper::END_POINT_MERCHANT_BRANCH_PAYMENT_CHANNEL_PAYMENT_LINK, [
            "headers" =>
            [
                "Accept" => "application/json"
            ],
            "query" => [
                "merchant_branch_id" => $merchant_branch_id
            ],
        ]);

        $data = [];

        foreach ($payment_channels->result->payment_channels as $value) {
            $data[] = [$value->name, $value->code];
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $fileHandle = fopen('php://output', 'w');
            fputcsv($fileHandle, ['Bank Name', 'Bank Code']); // Add the headers

            foreach ($data as $row) {
                fputcsv($fileHandle, $row);
            }

            fclose($fileHandle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
