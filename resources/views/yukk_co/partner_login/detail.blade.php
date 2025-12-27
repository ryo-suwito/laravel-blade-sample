@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Account Login Detail')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.partner_login.index') }}" class="breadcrumb-item">
                        @lang('cms.Account Login List')</a>
                    <span class="breadcrumb-item active">@lang('cms.Account Login Detail')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <h4 class="col-sm-2">@lang("cms.Scopes")</h4>
                <div class="col-sm-4">
                </div>
            </div>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th style="width: 25%" class="text-center">@lang('cms.Merchant Branch')</th>
                    <th class="text-center">@lang('cms.EDC IMEI')</th>
                    <th class="text-center">@lang('cms.QRIS Type')</th>
                </tr>
                </thead>
                <tbody>
                @foreach(@$scope_list as $scope)
                    <tr>
                        <td class="text-center">{{ @$scope->merchant_branch_name }}</td>
                        <td class="text-center">{{ @$scope->edc_imei }}</td>
                        <td class="text-center">{{ @$scope->edc_type }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-body">
            <div class="form-group row">
                <h4 class="col-sm-2">@lang("cms.Account Login")</h4>
                <div class="col-sm-4">
                </div>

                <h4 class="col-lg-2">@lang("cms.Profile Information")</h4>
                <div class="col-sm-6">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Username")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="username" readonly value="{{ @$partner_login->username }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="name" readonly value="{{ @$partner_login->name ? : '-' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Password")</label>
                <div class="col-sm-4">
                    <button id="reset-password" data-toggle="modal" class="form-control" data-target="#modal-reset-password">
                        @lang('cms.Reset Password')
                    </button>
                </div>

                <label for="phone" class="col-form-label col-sm-2">@lang("cms.Phone")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="phone" name="phone" readonly value="{{ @$partner_login->phone ? : '-' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-2"></label>
                <div class="col-sm-4">
                </div>

                <label for="email" class="col-form-label col-sm-2">@lang("cms.Email")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="email" name="email" readonly value="{{ @$partner_login->email ? : '-' }}">
                </div>
            </div>
        </div>

        <div class="form-group row justify-content-center mt-5">
            <a href="{{ route('yukk_co.partner_login.index') }}" class="btn btn-default">Back</a> &nbsp;
        </div>
    </div>

    <div id="modal-reset-password" class="modal form-group" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-preview">
        <div class="modal-dialog">
            <form action="{{ route('yukk_co.partner_login.reset', $partner_login->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang("cms.Reset Password")</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body mt-1">
                        <div class="form-group row">
                            <label for="password" class="col-form-label col-sm-4" for="password">@lang("cms.New Password")</label>
                            <div class="col-sm-8">
                                <input type="text" name="password" id="password" class="form-control" required="required"/>
                            </div>
                        </div>

                        <div class="row">
                            <label for="confirmation_password" class="col-form-label col-sm-4" for="confirmation_password">@lang("cms.New Confirmation Password")</label>
                            <div class="col-sm-8">
                                <input type="text" name="confirmation_password" id="confirmation_password" class="form-control" required="required"/>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">@lang("cms.Close")</button>
                        <button id="btn-change-password" type="submit" class="btn btn-primary btn-change-password">@lang("cms.Change Password")</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".btn-change-password").click(function(e) {
                let password = $('#password').val();
                let ch_password = $('#confirmation_password').val();
                if (password !== ch_password){
                    window.navigator.vibrate({
                        duration: 1000,
                    });

                    alert('Password and Confirmation Password Not Same!');
                    e.preventDefault();
                }else{
                    if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                    } else {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endsection
