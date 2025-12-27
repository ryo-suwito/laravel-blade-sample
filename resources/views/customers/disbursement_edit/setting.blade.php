@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Bank Disbursement")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Bank Disbursement")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <h5 class="card-title col-sm-10">@lang("cms.Bank Disbursement")</h5>
                @if(\App\Helpers\AccessControlHelper::checkCurrentAccessControl("BENEFICIARY_EDIT_REQUEST.CREATE","AND"))
                   <a class="btn btn-primary col-sm-2" href="{{ route("cms.customer.disbursement_edit.detail") }}">@lang("cms.Edit")</a>
                @endif
            </div>
            <hr>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" disabled value="{{ $response->bank->name }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" disabled value="{{ $response->account_number }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Branch Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" disabled value="{{ $response->branch_name }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" disabled value="{{ \App\Helpers\S::getUser() ? \App\Helpers\S::getUser()->username : "" }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" disabled value="{{ $response->account_name }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
