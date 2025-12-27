<?php

namespace App\Http\Controllers\Customers;

use App\Helpers\AccessControlHelper;
use App\Helpers\ApiHelper;
use App\Helpers\H;
use App\Helpers\S;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisbursementEditController extends BaseController
{
    const ALLOWED_IMAGES_FILES = [
        'image/png',
        'image/jpg',
        'image/jpeg',
    ];
    const FILE_IMAGE_MIMES = 'jpg,png,JPG,jpeg,JPEG';

    public function index()
    {
        $access_control = "BENEFICIARY_EDIT_REQUEST.VIEW";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }
        $disbursement_response = ApiHelper::requestGeneral("POST",ApiHelper::END_POINT_DISBURSEMENT_VIEW_YUKK_CO, []);

        if ($disbursement_response->is_ok){
            $response = $disbursement_response->result;

            return view("customers.disbursement_edit.setting", compact("response"));
        }else{
            return $this->getApiResponseNotOkDefaultResponse($disbursement_response);
        }
    }

    public function detail()
    {
        $access_control = "BENEFICIARY_EDIT_REQUEST.CREATE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $disbursement_response = ApiHelper::requestGeneral("POST",ApiHelper::END_POINT_DISBURSEMENT_VIEW_YUKK_CO, []);
        $bank_response = ApiHelper::requestGeneral("POST",ApiHelper::END_POINT_DISBURSEMENT_LIST_BANK_CUSTOMER, []);

        if ($disbursement_response->is_ok){
            if($bank_response->is_ok){
                $response =  $disbursement_response->result;
                $bank_list =  $bank_response->result;

                return view("customers.disbursement_edit.edit", [
                    "response" => $response,
                    "bank_list" => $bank_list,
                ]);
            }else{
                return $this->getApiResponseNotOkDefaultResponse($bank_response);
            }
        }else{
            return $this->getApiResponseNotOkDefaultResponse($disbursement_response);
        }
    }

    public function edit(Request $request)
    {
        $access_control = "BENEFICIARY_EDIT_REQUEST.CREATE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $validator = Validator::make($request->all(), [
            "bank_name" => "required",
            "account_name" => "required",
            "account_number" => "required",
            "branch_name" => "required",
            "email" => "required",
            "cover_bank" => "required|file",
            "otp" => "required|numeric|string|digits:6",
        ]);

        if ($validator->fails()) {
            S::flashFailed($validator->errors()->first(), true);
            return back()->withInput();
        }

        //For Image Saving and Checking Ext,Mime
        $extension = $request->file('cover_bank')->getClientOriginalExtension();
        $mime = $request->file('cover_bank')->getMimeType();

        $allowed_file_mimes = self::ALLOWED_IMAGES_FILES;
        $allowed_ext = explode(',',self::FILE_IMAGE_MIMES);
        if(!in_array($extension, $allowed_ext)){
            return redirect()->back()->with('error', 'mimes type is not allowed!');
        }
        if(!in_array($mime, $allowed_file_mimes)){
            return redirect()->back()->with('error', 'mimes type is not allowed!');
        }

        $fileLogo =strtotime('now').'_'.$request->cover_bank->getClientOriginalName();
        $request->cover_bank->move(public_path('assets/images/beneficiary'), $fileLogo);
        $cover_book_image_path = asset('').'assets/images/beneficiary/' . $fileLogo;

        $request_response = ApiHelper::requestGeneral("POST",ApiHelper::END_POINT_DISBURSEMENT_CREATE_REQUEST_CUSTOMER, [
            "form_params" => [
                "bank_id" =>  $request->get("bank_name"),
                "account_name" => $request->get("account_name"),
                "account_number" => $request->get("account_number"),
                "branch_name" => $request->get("branch_name"),
                "cover_bank" => $cover_book_image_path,
                "otp" => $request->get("otp"),
            ],
        ]);

        if ($request_response->is_ok){
            H::flashSuccess($request_response->status_message,true);
            return redirect(route("cms.customer.disbursement_edit.setting"));
        } else {
            return $this->getApiResponseNotOkDefaultResponse($request_response);
        }
    }


    public function requestOtp()
    {
        $access_control = "BENEFICIARY_EDIT_REQUEST.CREATE";
        if (! AccessControlHelper::checkCurrentAccessControl($access_control, "AND")) {
            return abort(401, __("cms.401_unauthorized_message", [
                "access_contol_list" => $access_control,
            ]));
        }

        $beneficiary_edit_otp_response = ApiHelper::requestGeneral("POST", ApiHelper::END_POINT_DISBURSEMENT_CREATE_OTP_CUSTOMER, []);

        return response($beneficiary_edit_otp_response->body_string, 200);
    }
}
