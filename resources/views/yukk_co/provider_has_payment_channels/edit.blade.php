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
                    <a href="{{ route("cms.yukk_co.provider_has_payment_channel.item", [@$provider_has_payment_channel->provider_id, @$provider_has_payment_channel->payment_channel_id]) }}" class="breadcrumb-item">x {{ $provider_has_payment_channel->payment_channel->name }}</a>
                    <span class="breadcrumb-item active">@lang("cms.Edit")</span>
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
                            <form action="{{ route("cms.yukk_co.provider_has_payment_channel.update", [$provider_has_payment_channel->provider_id, $provider_has_payment_channel->payment_channel_id]) }}" method="post">
                                @csrf
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
                                    <label for="provider_channel_code" class="col-lg-4 col-form-label @if($errors->has("provider_channel_code")) text-danger @endif">@lang("cms.Provider Payment Channel Code")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control @if($errors->has("provider_channel_code")) is-invalid @endif" name="provider_channel_code" id="provider_channel_code" value="{{ old("provider_channel_code", @$provider_has_payment_channel->provider_channel_code) }}">
                                        @if($errors->has("provider_channel_code"))
                                            <span class="invalid-feedback">{{ $errors->first("provider_channel_code") }}</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label @if($errors->has("active")) text-danger @endif">@lang("cms.Status")</label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2 @if($errors->has("active")) is-invalid @endif" name="active">
                                            <option value="1" @if(old("active", @$provider_has_payment_channel->active) == 1) selected @endif>@lang("cms.Active")</option>
                                            <option value="0" @if(old("active", @$provider_has_payment_channel->active) != 1) selected @endif>@lang("cms.Inactive")</option>
                                        </select>
                                        @if($errors->has("active"))
                                            <span class="invalid-feedback">{{ $errors->first("active") }}</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label for="provider_fee_percentage" class="col-lg-4 col-form-label @if($errors->has("provider_fee_percentage")) text-danger @endif">@lang("cms.MDR Internal (Percentage)")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control @if($errors->has("provider_fee_percentage")) is-invalid @endif" name="provider_fee_percentage" id="provider_fee_percentage" value="{{ old("provider_fee_percentage", @$provider_has_payment_channel->provider_fee_percentage) }}" required placeholder="in (%)">
                                        @if($errors->has("provider_fee_percentage"))
                                            <span class="invalid-feedback">{{ $errors->first("provider_fee_percentage") }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="provider_fee_fixed" class="col-lg-4 col-form-label @if($errors->has("provider_fee_fixed")) text-danger @endif">@lang("cms.MDR Internal (Fixed)")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control @if($errors->has("provider_fee_fixed")) is-invalid @endif" name="provider_fee_fixed" id="provider_fee_fixed" value="{{ old("provider_fee_fixed", @$provider_has_payment_channel->provider_fee_fixed) }}" required placeholder="in IDR">
                                        @if($errors->has("provider_fee_fixed"))
                                            <span class="invalid-feedback">{{ $errors->first("provider_fee_fixed") }}</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                {{--<div class="form-group row">
                                    <label for="bank_account_name" class="col-lg-4 col-form-label @if($errors->has("bank_account_name")) text-danger @endif">@lang("cms.Bank Account Name")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control @if($errors->has("bank_account_name")) is-invalid @endif" name="bank_account_name" id="bank_account_name" value="{{ old("bank_account_name", @$provider_has_payment_channel->bank_account_name) }}" required>
                                        @if($errors->has("bank_account_name"))
                                            <span class="invalid-feedback">{{ $errors->first("bank_account_name") }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="bank_account_number" class="col-lg-4 col-form-label @if($errors->has("bank_account_number")) text-danger @endif">@lang("cms.Bank Account Number")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control @if($errors->has("bank_account_number")) is-invalid @endif" name="bank_account_number" id="bank_account_number" value="{{ old("bank_account_number", @$provider_has_payment_channel->bank_account_number) }}" required>
                                        @if($errors->has("bank_account_number"))
                                            <span class="invalid-feedback">{{ $errors->first("bank_account_number") }}</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>--}}

                                <div class="form-group row">
                                    <label for="source_of_fund_account_name" class="col-lg-4 col-form-label @if($errors->has("source_of_fund_account_name")) text-danger @endif">@lang("cms.Bank Source of Fund (Account Name)")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control @if($errors->has("source_of_fund_account_name")) is-invalid @endif" name="source_of_fund_account_name" id="source_of_fund_account_name" value="{{ old("source_of_fund_account_name", @$provider_has_payment_channel->source_of_fund_account_name) }}" required>
                                        @if($errors->has("source_of_fund_account_name"))
                                            <span class="invalid-feedback">{{ $errors->first("source_of_fund_account_name") }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="source_of_fund_account_number" class="col-lg-4 col-form-label @if($errors->has("source_of_fund_account_number")) text-danger @endif">@lang("cms.Bank Source of Fund (Account Number)")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control @if($errors->has("source_of_fund_account_number")) is-invalid @endif" name="source_of_fund_account_number" id="source_of_fund_account_number" value="{{ old("source_of_fund_account_number", @$provider_has_payment_channel->source_of_fund_account_number) }}" required>
                                        @if($errors->has("source_of_fund_account_number"))
                                            <span class="invalid-feedback">{{ $errors->first("source_of_fund_account_number") }}</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label for="cut_of_date" class="col-lg-4 col-form-label @if($errors->has("cut_of_date")) text-danger @endif">@lang("cms.Settlement Cut-Off Date")</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">H +</span>
                                            </span>
                                            <input type="text" class="form-control @if($errors->has("cut_of_date")) is-invalid @endif" name="cut_of_date" id="cut_of_date" value="{{ old("cut_of_date", @$provider_has_payment_channel->settlement_cut_off_start_date) }}" placeholder="X" required>
                                        </div>
                                        @if($errors->has("cut_of_date"))
                                            <span class="invalid-feedback">{{ $errors->first("cut_of_date") }}</span>
                                        @endif
                                        <span class="form-text text-muted">
                                            <b class="text-danger">PENTING!!!</b><br>
                                            Kapan kita harus melakukan settlement kepada merchant (dalam hitungan hari)?<br>
                                            Jika tidak tahu mohon tanya ke tim CFO atau lihat ke <a target="_blank" href="https://docs.google.com/spreadsheets/d/1SeLXnS_5mhJvCcEHtaDt-rKzt0ZsvCqf3cM_FeCBOfQ/edit#gid=0">dokumen ini</a>
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <button class="btn btn-primary btn-block">@lang("cms.Submit")</button>
                            </form>
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