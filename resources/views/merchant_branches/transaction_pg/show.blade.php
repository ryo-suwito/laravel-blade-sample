@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Transaction Payment Gateway")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.merchant_branch.transaction_pg.list") }}" class="breadcrumb-item">@lang("cms.Transaction Payment Gateway")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Transaction Payment Gateway")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Transaction Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_pg->code }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Request Time")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($transaction_pg->request_at) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Order ID")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_pg->order_id }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Customer Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_pg->customer_name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Payment Channel")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_pg->payment_channel->name }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Grand Total")</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_pg->grand_total, 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Fee")</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_pg->bank_fee, 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Fixed")</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_pg->mdr_external_fixed + $transaction_pg->fee_partner_fixed, 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Percentage")</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_pg->mdr_external_percentage + $transaction_pg->fee_partner_percentage, 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Merchant Portion")</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_pg->grand_total - ($transaction_pg->mdr_external_fixed + $transaction_pg->fee_partner_fixed + $transaction_pg->mdr_external_percentage + $transaction_pg->fee_partner_percentage), 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_pg->status }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Status Settlement")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_pg->is_settle ? trans("cms.Settlement") : trans("cms.Not Settlement") }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($transaction_pg->created_at) }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Updated At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($transaction_pg->updated_at) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Paid Time")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @(isset($transaction_pg->paid_at) && @$transaction_pg->paid_at) ? @\App\Helpers\H::formatDateTime($transaction_pg->paid_at) : "-"}}">
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
        });
    </script>
@endsection