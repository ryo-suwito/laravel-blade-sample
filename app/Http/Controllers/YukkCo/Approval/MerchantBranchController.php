<?php

namespace App\Http\Controllers\YukkCo\Approval;

use App\Actions\MerchantAcquisition\Filter;
use App\Actions\MerchantAcquisition\GetApproval;
use App\Actions\MerchantAcquisition\GetApprovals;
use App\Actions\MerchantAcquisition\GetMaster;
use App\Actions\MerchantAcquisition\GetStatusCounter;
use App\Actions\MerchantAcquisition\UpdateApproval;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MerchantBranchController extends Controller
{
    protected $title = 'Merchant Branches';
    protected $tableName = 'merchant_branches';

    public function index(Request $request, GetApprovals $approvals, GetStatusCounter $counter)
    {
        $filter = new Filter($request, $this->tableName);
        $approval = $approvals->get($filter->values());

        return view('yukk_co.approval.index', [
            'title' => $this->title,
            'filter' => $filter->values(),
            'filterCounter' => $filter->counter(),
            'approvals' => $approval,
            'statusCounter' => $counter->get($this->tableName),
            'per_page' => $request->get('per_page'),
            'from' => $approval->total() !== 0 ? $approval->perPage() * ($approval->currentPage() - 1) + 1 : 0,
            'to' => $approval->perPage() * $approval->currentPage() > $approval->total() ? $approval->total() : $approval->perPage() * $approval->currentPage(),
            'total' => $approval->total(),
            'current_page' => $approval->currentPage(),
            'last_page' => $approval->lastPage(),
            'routing' => 'approval.merchant_branches.index',
            'route' => 'approval.merchant_branches.action'
        ]);
    }

    public function show($id, GetApproval $approval)
    {
        return view('yukk_co.approval.show', [
            'title' => $this->title,
            'mainMenuUrl' => route('approval.merchant_branches.index'),
            'approval' => $approval->get($id, [
                'table_name' => $this->tableName
            ]),
        ]);
    }

    public function update(Request $request, $id, UpdateApproval $approval)
    {
        $approval->update($id, [
            'table_name' => $this->tableName,
            'action' => $request->action,
        ]);

        toast('success', ucfirst($request->action) . ' success!');

        return redirect()->route('approval.merchant_branches.show', ['id' => $id]);
    }

    public function showMaster($id, GetMaster $approval)
    {
        return view('yukk_co.approval.master.show-merchant-branch', [
            'title' => $this->title,
            'mainMenuUrl' => route('approval.merchant_branches.index'),
            'approvalUrl' => route('approval.merchant_branches.show', $id),
            'master' => $approval->get($id, [
                'table_name' => $this->tableName
            ]),
        ]);
    }

    public function toggleStatus(Request $request){
        $selectedIds = $request->get('ids', '');
        $approveOrReject = $request->get('approveOrReject');

        if(!$selectedIds) {
            H::flashFailed("Please select at least one data.",true);
            return redirect()->back();
        }
        try {
            if(!is_array($selectedIds)) {
                $selectedIds = explode(",", $selectedIds);
            }
        } catch (\Exception $e) {
            Log::error($e);

            H::flashFailed("Error while parsing selected ids.", true);
            return redirect()->back();
        }

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_MENU_APPROVAL_ACTION_YUKK_CO, [
            'form_params' => [
                'action' => $approveOrReject,
                'ids' => $selectedIds,
                'table' => $this->tableName,
                'email' => S::getUser()->email,
            ],
        ]);

        if($response->is_ok){
            toast('success', $response->status_message);
            return redirect(route('approval.merchant_branches.index'));
        }else{
            toast('error', $response->status_message);
            return redirect(route('approval.merchant_branches.index'));
        }
    }
}
