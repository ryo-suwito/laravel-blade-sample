<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\ApiHelper;
use App\Helpers\H;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\String\b;

class ServiceSettingController extends BaseController
{
    protected $setting;

    public function __construct()
    {
        $this->setting = api('core_api', 'service_setting');
    }

    public function index(Request $request) {
        $query = [
            'per_page' => $request->get('per_page', 10),
            'page' => $request->get('page', 1),
            'search' => $request->get('search'),
        ];

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_PLATFORM_SETTING_LIST, [
            'query' => $query
        ]);

        $result = $response->result;

        if ($response->is_ok){
            return view("yukk_co.service_settings.list", [
                'datas' => $result->data,

                'from' =>  $result->from,
                'to' => $result->to,
                'total' => $result->total,

                'current_page' => $result->current_page,
                'last_page' => $result->last_page,

                'per_page' => $query['per_page'],
                'search' => $query['search'],
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }

    public function edit(Request $request, $id) {
        $response = $this->setting->find($id);

        if($response->status() == '404' || $response->status() == '500') {
            H::flashFailed($response->json('status_message') ?? 'Server Error Please Contact Our Administrator!', true);

            return back();
        }

        $result = (object) $response->json('result');

        $collection = collect($result);

        $settings = $collection->where('service', 'TRANSACTION')->mapWithKeys(function ($item) {
            if ($item['name'] == 'LOGO'){
                $key = [
                    $item['name'] => $item['file_url'],
                ];
            }else{
                $key = [
                    $item['name'] => $item['value'],
                ];
            }
            return $key;
        });
        $creations = $collection->where('service', 'ACCOUNT_CREATION')->mapWithKeys(function ($item) {
            $key = [
                $item['name'] => $item['value'],
            ];
            return $key;
        });
        $entity = $collection[0];

        return view('yukk_co.service_settings.edit', [
            'id' => $id,
            'settings' => $settings,
            'creations' => $creations,
            'entity' => $entity,
        ]);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'logo' => 'mimes:jpeg,jpg,png|max:2049'
        ],
        [
            'logo.uploaded' => 'Failed to upload an file, the maximum size is 2MB.',
        ]);
        if ($validator->fails()){
            H::flashFailed($validator->errors()->first(), true);
            return redirect(route('platform_setting.edit', $id));
        }

        $data = $request->all();

        $response = $this->setting->update(
            $id, $data
        );

        $data = $response->json();

        H::flashSuccess($data['status_message'], true);
        return redirect(route('platform_setting.detail', $data['result'][0]['entity_id']));
    }

    public function detail(Request $request, $id) {
        $response = $this->setting->find($id);

        if($response->status() == '404' || $response->status() == '500') {
            H::flashFailed($response->json('status_message') ?? 'Server Error Please Contact Our Administrator!', true);

            return back();
        }

        $result = (object) $response->json('result');

        $collection = collect($result);

        $settings = $collection->where('service', 'TRANSACTION')->mapWithKeys(function ($item) {
            if ($item['name'] == 'LOGO'){
                $key = [
                    $item['name'] => $item['file_url'],
                ];
            }else{
                $key = [
                    $item['name'] => $item['value'],
                ];
            }
            return $key;
        });
        $creations = $collection->where('service', 'ACCOUNT_CREATION')->mapWithKeys(function ($item) {
            $key = [
                $item['name'] => $item['value'],
            ];
            return $key;
        });
        $entity = $collection[0];

        return view('yukk_co.service_settings.detail', [
            'id' => $id,
            'settings' => $settings,
            'creations' => $creations,
            'entity' => $entity,
        ]);
    }

    public function create(Request $request){
        return view("yukk_co.service_settings.create", [
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'logo' => 'mimes:jpeg,jpg,png|max:2049'
        ]);
        if ($validator->fails()){
            H::flashFailed($validator->errors()->first(), true);
            return redirect(route('platform_setting.create'));
        }

        if ($request->has('logo')){
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PLATFORM_SETTING_STORE, [
                'multipart' => [
                    [
                        "name" => "entity_id",
                        "contents" =>  $request->get('entity_id'),
                    ],
                    [
                        "name" => "entity_name",
                        "contents" =>  $request->get('entity_name'),
                    ],
                    [
                        "name" => "type",
                        "contents" =>  $request->get('type'),
                    ],
//                    [
//                        "name" => "notification_url",
//                        "contents" => $request->get('notification_url'),
//                    ],
//                    [
//                        "name" => "callback_url",
//                        "contents" =>  $request->get('callback_url'),
//                    ],
                    [
                        "name" => "creation_notification_url",
                        "contents" => $request->get('creation_notification_url'),
                    ],
                    [
                        "name" => "creation_callback_url",
                        "contents" =>  $request->get('creation_callback_url'),
                    ],
                    [
                        "name" => "currency_type",
                        "contents" =>  $request->get('payment_mode'),
                    ],
                    [
                        "name" => "mdr_fee",
                        "contents" =>  $request->get('mdr_fee'),
                    ],
                    [
                        "name" => "mdr_type",
                        "contents" =>  $request->get('mdr_type'),
                    ],
                    [
                        "name" => "timeout",
                        "contents" =>  $request->get('timeout_time'),
                    ],
                    [
                        "name" => "autocomplete",
                        "contents" =>  $request->get('autocomplete'),
                    ],
                    [
                        "name" => "day",
                        "contents" =>  $request->get('day'),
                    ],
                    [
                        "name" => "time",
                        "contents" =>  $request->get('time'),
                    ],
                    [
                        "name" => "autocomplete_time",
                        "contents" =>  $request->get('autocomplete_time'),
                    ],
                    [
                        "name" => "logo",
                        "contents" =>  $request->file('logo')->getContent(),
                        "filename" => $request->file('logo')->getClientOriginalName(),
                    ]
                ],
            ]);
        }else{
            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_PLATFORM_SETTING_STORE, [
                'form_params' => [
                    'entity_id' => $request->get('entity_id'),
                    'entity_name' => $request->get('entity_name'),
                    'type' => $request->get('type'),
//                    'notification_url' => $request->get('notification_url'),
//                    'callback_url' => $request->get('callback_url'),
                    'creation_notification_url' => $request->get('creation_notification_url'),
                    'creation_callback_url' => $request->get('creation_callback_url'),
                    'currency_type' => $request->get('payment_mode'),
                    'mdr_fee' => $request->get('mdr_fee'),
                    'mdr_type' => $request->get('mdr_type'),
                    'timeout' => $request->get('timeout_time'),
                    'autocomplete' => $request->get('autocomplete'),
                    'day' => $request->get('day'),
                    'time' => $request->get('time'),
                    'autocomplete_time' => $request->get('autocomplete_time'),
                ],
            ]);
        }

        if ($response->is_ok){
            H::flashSuccess($response->status_message, true);
            return redirect(route('platform_setting.detail', $response->result->entity_id));
        }else{
            H::flashFailed($response->status_message, true);
            return redirect(route('platform_setting.create'));
        }
    }
}
