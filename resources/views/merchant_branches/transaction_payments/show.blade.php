@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Transaction Payment")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.merchant_branch.transaction_payment.list") }}" class="breadcrumb-item">@lang("cms.Transaction Payment List")</a>
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
            <h5 class="card-title">@lang("cms.Transaction Payment")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <div class="col-lg-2">
                            <img class="img img-thumbnail" style="max-width: 100px; background-color: white;" src="{{ $transaction_payment->merchant->image }}">
                        </div>

                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="">{{ $transaction_payment->merchant->name }}</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4>{{ $transaction_payment->merchant_branch->name }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Transaction Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->transaction_code }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Order ID")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->partner_order_order_id }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Transaction Time")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ \App\Helpers\H::formatDateTime($transaction_payment->transaction_time) }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Type")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->qris_qr_type }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.YUKK ID")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->user->yukk_id }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Grand Total")</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ \App\Helpers\H::formatNumber($transaction_payment->grand_total, 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--<div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Merchant Portion")</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ \App\Helpers\H::formatNumber($transaction_payment->merchant_portion, 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.YUKK Portion")</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ \App\Helpers\H::formatNumber(($transaction_payment->yukk_portion + $transaction_payment->fee_partner_percentage + $transaction_payment->fee_yukk_additional_percentage + $transaction_payment->fee_partner_fixed + $transaction_payment->fee_yukk_additional_fixed), 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>
                    </div>--}}

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->merchant_branch_name }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Merchant branch City")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->merchant_branch_city }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch Postal Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->merchant_branch_postal_code }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Country Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->country_code }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Issuer Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->issuer_name }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Acquirer Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->acquirer_name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Customer Data")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->customer_data }}">
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
