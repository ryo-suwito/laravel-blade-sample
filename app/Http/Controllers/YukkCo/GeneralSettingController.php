<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\S;
use App\Services\API;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;

class GeneralSettingController extends Controller
{
    protected $setting;

    public function __construct()
    {
        $this->setting = API::instance('core_api', 'setting');
    }

    public function service(string $serviceName, string $method, array $args = [])
    {
        $customValidationMethod = ['store', 'update'];
        $failedCallback = null;
        $response = $this->{$serviceName}->{$method}(...$args);

        if (in_array($method, $customValidationMethod)) {
            if ($response->status() == 422) {
                $failedCallback = function () use ($response) {
                    toast('error', array_values($response->json('result'))[0][0]);

                    return back();
                };
            }
        }

        apiResponseHandler($response, false, $failedCallback);

        return $response;
    }

    public function index()
    {
        return view("yukk_co.settings.index");
    }

    public function edit($id)
    {
        $response = $this->service(
            'setting',
            'show',
            [
                $id
            ]
        );

        return view("yukk_co.settings.edit", [
            'item' => $response->json('result')
        ]);
    }

    public function show($id)
    {
        $response = $this->service(
            'setting',
            'show',
            [
                $id
            ]
        );

        return view("yukk_co.settings.show", [
            'item' => $response->json('result')
        ]);
    }

    public function update(Request $request, $setting_id)
    {
        $this->service(
            'setting',
            'update',
            [
                $setting_id,
                $request->get('value'),
                $request->get('active') == 'on' ? 1 : 0
            ]
        );

        toast('success', 'Data updated.');
        return redirect(route('yukk_co.general.settings.edit', $setting_id));
    }

    public function data(Request $request)
    {
        $response = $this->service('setting', 'paginated', [
            ($request->length) ? $request->length : 10,
            ($request->start + $request->length) / $request->length,
            $request->search['value'] ?? ''
        ]);

        $request->merge(['start' => 0]);
        return DataTables::of($response->json('result.data'))
            ->setFilteredRecords($response->json('result.total'))
            ->setTotalRecords($response->json('result.total'))
            ->addColumn('action', function ($item) {
                $features = $this->features($item);
                return '
                <div class="list-icons">
                    <div class="dropdown">
                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                            <i class="icon-menu9"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">' . $features . '</div>
                    </div>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make();
    }

    public function features($item)
    {
        $access_control = json_decode(S::getUserRole()->role->access_control);
        $features = '';
        if (in_array('SETTINGS.VIEW', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.general.settings.show", $item['id']) . '"
                class="dropdown-item"><i class="icon-zoomin3"></i>
                Detail
                </a>';
        }
        if (in_array('SETTINGS.UPDATE', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.general.settings.edit", $item['id']) . '"
                class="dropdown-item"><i class="icon-pencil7"></i>
                Edit
                </a>';
        }
        return $features;
    }
}
