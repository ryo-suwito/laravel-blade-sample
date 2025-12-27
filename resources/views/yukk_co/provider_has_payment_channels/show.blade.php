@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Provider x Payment Channel")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.provider.list") }}" class="breadcrumb-item">@lang("cms.Provider List")</a>
                    <a href="{{ route("cms.yukk_co.provider.item", $provider_has_payment_channel->provider_id) }}" class="breadcrumb-item">{{ $provider_has_payment_channel->provider->name }}</a>
                    <span class="breadcrumb-item active">x {{ $provider_has_payment_channel->payment_channel->name }}</span>
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
                    <h5 class="card-title">@lang("cms.Provider x Payment Channel")</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Provider")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$provider_has_payment_channel->provider->name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Payment Channel")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$provider_has_payment_channel->payment_channel->name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="@if (@$provider_has_payment_channel->active) @lang("cms.Active") @else @lang("cms.Inactive") @endif">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.MDR Internal (Percentage)")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($provider_has_payment_channel->provider_fee_percentage, 2) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.MDR Internal (Fixed)")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($provider_has_payment_channel->provider_fee_fixed, 2) }}">
                                </div>
                            </div>

                            <hr>

                            {{--<div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Bank Account Name")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$provider_has_payment_channel->bank_account_name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Bank Account Number")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$provider_has_payment_channel->bank_account_number }}">
                                </div>
                            </div>

                            <hr>--}}

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Bank Source of Fund (Account Name)")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$provider_has_payment_channel->source_of_fund_account_name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Bank Source of Fund (Account Number)")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$provider_has_payment_channel->source_of_fund_account_number }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Settlement Cut-Off Date")</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">H +</span>
                                        </span>
                                        <input type="text" class="form-control" readonly="" value="{{ @$provider_has_payment_channel->settlement_cut_off_start_date }}">
                                    </div>
                                </div>
                            </div>

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
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
            });
        });
    </script>
@endsection