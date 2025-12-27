<?php

namespace App\Http\Controllers\YukkCo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\S;
use App\Services\API;

class SuspectController extends Controller
{
    protected $suspect;
    protected $suspect_log;
    protected $suspect_transaction_log;

    public function __construct()
    {
        $this->suspect = API::instance('core_api', 'suspect');
        $this->suspect_log = API::instance('core_api', 'suspect_log');
        $this->suspect_transaction_log = API::instance('core_api', 'suspect_transaction_log');
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

    public function index(Request $request)
    {
        $access_control = json_decode(S::getUserRole()->role->access_control);
        $response = $this->service('suspect', 'paginated', [
            $request->get("per_page", 10),
            $request->get("page", 1),
            $request->get('type', ''),
            $request->get('keyword', ''),
            $request->get('date_range', '')
        ]);

        return view("yukk_co.suspects.index", [
            "suspects" => $response->json('result.data'),
            "from" => $response->json('result.from'),
            "to" => $response->json('result.to'),
            "total" => $response->json('result.total'),
            "per_page" => $request->get("per_page", 10),
            "current_page" => $response->json('result.current_page'),
            "last_page" => $response->json('result.last_page'),
            "keyword" => $request->get('keyword', ''),
            "date_range" => $request->get('date_range', ''),
            "type" => $request->get('type', ''),
            "access_control" => $access_control
        ]);
    }

    public function show(Request $request, $id)
    {
        $suspect = $this->service('suspect', 'show', [$id]);

        $suspectLog = $this->service('suspect_log', 'paginated', [
            (int)$id,
            $request->get('per_page_log', 10),
            $request->get('page_log', 1),
            $request->get('type', ''),
            $request->get('keyword', ''),
            $request->get('date_range', '')]
        );

        $transactionLog = $this->service('suspect_transaction_log', 'paginated', [
            (int)$id,
            $request->get('per_page_transaction_log', 10),
            $request->get('page_transaction_log', 1),
            $request->get('type', ''),
            $request->get('keyword', ''),
            $request->get('date_range', '')]
        );

        return view("yukk_co.suspects.show", [
            'suspect' => $suspect->json('result'),
            'suspect_log' => $suspectLog->json('result'),
            'transaction_log' => $transactionLog->json('result')
        ]);
    }

    public function edit(Request $request, $id)
    {
        $suspect = $this->service('suspect', 'show', [$id]);

        $suspectLog = $this->service('suspect_log', 'paginated', [
            (int)$id,
            $request->get('per_page_log', 10),
            $request->get('page_log', 1),
            $request->get('type', ''),
            $request->get('keyword', ''),
            $request->get('date_range', '')]
        );

        $transactionLog = $this->service('suspect_transaction_log', 'paginated', [
            (int)$id,
            $request->get('per_page_transaction_log', 10),
            $request->get('page_transaction_log', 1),
            $request->get('type', ''),
            $request->get('keyword', ''),
            $request->get('date_range', '')]
        );
        //TODO create seeder in cms api
        return view("yukk_co.suspects.edit", [
            'suspect' => $suspect->json('result'),
            'suspect_log' => $suspectLog->json('result'),
            'transaction_log' => $transactionLog->json('result')
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->service(
            'suspect',
            'update',
            [
                $request->get('user_id'),
                $request->get('status')
            ]
        );

        toast('success', 'Data updated.');
        return redirect(route('yukk_co.suspects.edit', $id));
    }

    public function data(Request $request)
    {
        $response = $this->service('suspect', 'paginated', [
            ($request->length) ? $request->length : 10,
            ($request->start + $request->length) / $request->length,
            $request->type ?? '',
            $request->keyword ?? '',
            $request->dateRange ?? ''
        ]);

        $request->merge(['start' => 0]);
        $total = $response->json('result.total');

        return DataTables::of($response->json('result.data'))
            ->setFilteredRecords($total)
            ->setTotalRecords($total)
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
        if (in_array('LAST_TOPUP_VALIDATION.VIEW', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.suspects.show", $item['id']) . '"
                class="dropdown-item"><i class="icon-zoomin3"></i>
                Detail
                </a>';
        }
        if (in_array('LAST_TOPUP_VALIDATION.UPDATE', $access_control)) {
            $features = $features . '<a href="' . route("yukk_co.suspects.edit", $item['id']) . '"
                class="dropdown-item"><i class="icon-pencil7"></i>
                Edit
                </a>';
        }
        return $features;
    }
}
