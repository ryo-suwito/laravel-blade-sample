<?php

namespace App\Http\Controllers\YukkCo\LegalApproval;
use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Helpers\H;
use App\Helpers\S;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if (!AccessControlHelper::checkCurrentAccessControl("LEGAL_APPROVAL.COMPANIES", "OR")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => json_encode("LEGAL_APPROVAL.COMPANIES"),
            ]));
        }

        $query_params = [
            'per_page' => $request->get('per_page', 25),    
            'page' => $request->get('page'),
            'field' => $request->get('field'),
            'search' => $request->get('search'),
            'status' => $request->get('status')
        ];
        
        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_LEGAL_APPROVAL_COMPANY_LIST, [
            'query' => $query_params
        ]);

        if($response->http_status_code == '200'){
            $result = $response->result->paginated_result;
            $list = $result->data;
            $total = $response->result->total;

            return view('yukk_co.legal_approval.list', [
                'companies' => $list,
                'approve' => $total->approve,
                'reject' => $total->reject,
                'review' => $total->review,
                'from' => $result->from ? : 0,
                'to' => $result->to ? : 0,
                'total' => $result->total,
                'current_page' => $result->current_page,
                'last_page' => $result->last_page,

                'per_page' => $query_params['per_page'],
                'field' => $query_params['field'],
                'search' => $query_params['search'],
                'status' => $query_params['status'],
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);  
        }
    }

    public function detail(Request $request, $id)
    {
        if (!AccessControlHelper::checkCurrentAccessControl("LEGAL_APPROVAL.COMPANIES", "OR")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => json_encode("LEGAL_APPROVAL.COMPANIES"),
            ]));
        }

        $response = ApiHelper::requestGeneral("GET", ApiHelper::END_POINT_LEGAL_APPROVAL_COMPANY_DETAIL, [
            'query' => [
                'id' => $id
            ]
        ]);

        if($response->http_status_code == '200'){
            $company = $response->result->company;
            $contracts = $response->result->contracts;
            $types = json_decode($company->type);

            return view('yukk_co.legal_approval.detail', [
                'company' => $company,
                'contract_list' => $contracts,
                'types' => $types
            ]);
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);  
        };
    }

    public function action(Request $request){
        if (!AccessControlHelper::checkCurrentAccessControl("LEGAL_APPROVAL.COMPANIES", "OR")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => json_encode("LEGAL_APPROVAL.COMPANIES"),
            ]));
        }

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_LEGAL_APPROVAL_COMPANY_ACTION, [
            'query' => [
                'id[]' => $request->get('id'),
                'action' => $request->get('action'),
                'updated_by' => S::getUser()->username,
                'remark' => $request->get('remark'),
            ]
        ]);

        if($response->http_status_code == '200' && $request->get('action') == 'REJECTED'){
            H::flashSuccess($response->status_message, true);
            return redirect(route('legal_approval.companies.index'));
        }elseif($response->http_status_code == '200'){
            return response()->json($response);
        }
    }

    public function bulkAction(Request $request){
        if (!AccessControlHelper::checkCurrentAccessControl("LEGAL_APPROVAL.COMPANIES", "OR")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => json_encode("LEGAL_APPROVAL.COMPANIES"),
            ]));
        }

        foreach($request->get('checkbox') as $index => $key){
            if(is_int($index)){
                $companies_id[] = $index;
            }
        }

        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_LEGAL_APPROVAL_COMPANY_ACTION, [
            'query' => [
                'id' => $companies_id,
                'action' => $request->get('action'),
                'updated_by' => S::getUser()->username,
                'remark' => $request->get('remark'),
            ]
        ]);
        
        if($response->http_status_code == '200'){
            H::flashSuccess($response->status_message, true);
            return redirect(route('legal_approval.companies.index'));
        }else{
            return $response->body_string;
        }
    }
}