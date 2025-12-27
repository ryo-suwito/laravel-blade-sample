@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Partner Order")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item">@lang("cms.Partner Order")</span>
                    <span class="breadcrumb-item active">{{ @$partner_order->order_id }}</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>

            {{--<div class="header-elements d-none">
                <div class="breadcrumb justify-content-center">
                    <a href="#" class="breadcrumb-elements-item">
                        Link
                    </a>

                    <div class="breadcrumb-elements-item dropdown p-0">
                        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                            Dropdown
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">One more action</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Separate action</a>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.Partner Order")</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Order ID")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$partner_order->order_id }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Nominal")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($partner_order->nominal) }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Expiry Time")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($partner_order->expiry_time) }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Max Payment")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$partner_order->max_payment }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch")</label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" readonly="" value="{{ @$partner_order->merchant_branch->name }}">
                                        <span class="input-group-append">
                                            <span class="input-group-text">{{ @$partner_order->merchant_branch->id }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Partner")</label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" readonly="" value="{{ @$partner_order->partner_login->partner->name }}">
                                        <span class="input-group-append">
                                            <span class="input-group-text">{{ @$partner_order->partner_login->partner->id }}</span>
                                        </span>
                                    </div>
                                </div>
                                <label class="col-lg-2 col-form-label" title="@lang("cms.Client ID")">@lang("cms.Partner Login Username")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$partner_order->partner_login->username }}">
                                </div>
                            </div>

                            @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("PARTNER_ORDER.EDIT", "AND"))
                                <hr>

                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <a href="{{ route("cms.yukk_co.partner_order.edit", @$partner_order->id) }}" class="btn btn-primary">@lang("cms.Edit")</a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();
            $(".btn-need-confirmation").click(function(e) {
                if (confirm("@lang("cms.user_withdrawal_action_button_action_confirmation")")) {
                    setInterval(function() {
                        // Need interval because if disable first, then the button is not included on the form
                        $(".btn-need-confirmation").attr("disabled", "disabled");
                    }, 50);
                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection