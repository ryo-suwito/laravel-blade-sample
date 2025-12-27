<?php

namespace App\Http\Controllers\YukkCo;

use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Services\APIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyContractController extends BaseController
{
    public function store(Request $request, $id)
    {
        if($request->get('contract_type') == 'FILE'){
            $validator = Validator::make( $request->all() , [
                'attachment' => 'required|max:2048|mimes:jpg,jpeg,png,pdf,docs,docx,doc,xlsx,xlx,xls'
            ], [
                'attachment.required' => 'File is required',
                'attachment.mimes' => 'File extension is wrong'
            ]);
        }elseif($request->get('contract_type') == 'LINK'){
            $validator = Validator::make( $request->all() , [
                'attachment' => 'required'
            ], [
                'attachment.required' => 'Link is required',
            ]);
        }

        if ($validator->errors()->messages() == null){
            if($request->has('contract_type')){
                if($request->get('contract_type') == 'FILE'){
                    $contract = $request->file('attachment');
                }elseif($request->get('contract_type') == 'LINK'){
                    $contract = $request->get('attachment');
                }
            }

            $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_COMPANY_COMPANY_CONTRACT_ADD_YUKK_CO, [
                "multipart" => [
                    [
                        "name" => "id",
                        "contents" =>  $request->get('company_id'),
                    ],
                    [
                        "name" => "name",
                        "contents" => $request->get('name'),
                    ],
                    [
                        "name" => "description",
                        "contents" => $request->get('description'),
                    ],
                    [
                        "name" => "contract_type",
                        "contents" => $request->get('contract_type'),
                    ],
                    [
                        "name" => "attachment",
                        "contents" => $request->get('contract_type') == 'FILE' ? $contract->getContent() : $contract,
                        "filename" => $request->get('contract_type') == 'FILE' ? $contract->getClientOriginalName() : '',
                    ]
                ],
            ]);

            if($response->is_ok) {
                H::flashSuccess('Success', true);
                return redirect(route('yukk_co.companies.edit', $response->result->id));
            }else{
                $this->getApiResponseNotOkDefaultResponse($response);
            }
        }else{
            $error = $validator->errors()->messages()['attachment'][0];
            H::flashFailed($error ,true);
            return redirect(route('yukk_co.companies.edit',$id));
        }

    }

    public function deleteContract($id)
    {
        $response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_COMPANY_COMPANY_CONTRACT_DELETE_YUKK_CO, [
            "query" => [
                'id' => $id,
            ]
        ]);

        if ($response->is_ok){
            H::flashSuccess('Success', true);
            return back();
        }else{
            return $this->getApiResponseNotOkDefaultResponse($response);
        }
    }
}
