@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Detail QRIS Settings')</h4>
            </div>

        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.qris_setting.list') }}" class="breadcrumb-item">@lang('cms.Manage QRIS Settings')</a>
                    <span class="breadcrumb-item active">@lang('cms.Detail QRIS Settings')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="card-header">
                <h5>@lang('cms.Merchant Information')</h5>
            </div>

            <div class="card-body row">
                <div class="col-8">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Merchant")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="merchant" value="{{ @$merchant_branch->merchant->name }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Merchant Branch")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="merchant_branch" name="merchant_branch" value="{{ @$merchant_branch->name }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.MPAN")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="mpan" value="{{ @$merchant_branch->mpan }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.MID")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="mid" value="{{ @$merchant_branch->mid }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.NMID")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name" value="{{ @$merchant_branch->nmid_pten }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Branch Name (PTEN)")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name" value="{{ @$merchant_branch->merchant_branch_name_pten_25 }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.City (PTEN)")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name" value="{{ @$merchant_branch->city_name_pten }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group col-4">
                    <img class="img-fluid" src="{{ @$merchant_branch->qr_static_path }}">
                </div>
            </div>

            <div class="card-body">
                <h5 class="mb-3">@lang('cms.EDC List')</h5>
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th class="text-center">@lang("cms.QRIS Type")</th>
                            <th class="text-center">@lang("cms.EDC IMEI")</th>
                            <th class="text-center">@lang("cms.Actions")</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($edc_list as $edc)
                        <tr>
                            <td class="text-center">{{ @$edc->type }}</td>
                            <td class="text-center">{{ @$edc->imei }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route('yukk_co.edc.detail', @$edc->id) }}" class="dropdown-item">
                                            <i class="icon icon-link"></i> @lang("cms.Detail")
                                        </a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <hr>

            @if ($merchant_branch->qr_type == 'b')
                <div class="card-body">
                    <div class="row mb-4">
                        <h5 class="mb-3">@lang('cms.QRIS Dynamic')</h5>
                    </div>

                    @foreach($edc_dynamic_list as $edc)
                        @php($is_snap_enabled = (isset($edc) && isset($edc->partner) && $edc->partner->is_snap_enabled))
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.IMEI')</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" value="{{ $edc->imei }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($edc->partner_logins as $partner_login)
                                        @if($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                    <div class="col-lg-8">
                                                        <select class="form-control select2" disabled>
                                                            <option value="NONE" @if($partner_login->grant_type === "NONE") selected @endif>@lang('cms.NONE')</option>
                                                            <option value="CLIENT_ID_SECRET" @if($partner_login->grant_type === "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="w-100">
                                                @if($is_snap_enabled)
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Store ID')</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" readonly id="store_id_dynamic_{{ $partner_login->id }}" name="store_id_dynamic_{{ $partner_login->id }}" value="{{ $partner_login->username }}" class="form-control">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <button class="btn btn-success btn-copy-store-id-dynamic" data-id="{{ $partner_login->id }}" type="button" id="btn-copy-store-id-dynamic-{{ $partner_login->id }}">@lang("cms.Copy")</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if($partner_login->payment_notify_mode == "WEBHOOK" && $partner_login->is_payment_gateway != 1)
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" readonly id="snap_client_id_dynamic_{{ $partner_login->id }}" name="snap_client_id_dynamic_{{ $partner_login->id }}" value="{{ $partner_login->username }}" class="form-control">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <button class="btn btn-success btn-copy-client-id-dynamic" data-id="{{ $partner_login->id }}" type="button" id="btn-copy-client-id-dynamic-{{ $partner_login->id }}">@lang("cms.Copy")</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="snap_client_secret_static" class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" readonly id="snap_client_secret_dynamic_{{ $partner_login->id }}" value="{{ $partner_login->password }}"  class="form-control">
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <button class="btn btn-success btn-copy-client-secret-dynamic" type="button" data-id="{{ $partner_login->id }}" id="btn-copy-client-secret-dynamic-{{ $partner_login->id }}">@lang("cms.Copy")</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.SNAP Implementation')</label>
                                            <div class="col-lg-8">
                                                @if($is_snap_enabled)
                                                    <input class="form-control" value="SNAP" readonly>
                                                @else
                                                    <input class="form-control" value="NON SNAP" readonly>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach($edc->partner_logins as $partner_login)
                                        @if($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Payment Notify Mode')</label>
                                                    <div class="col-lg-8">
                                                        <select class="form-control select2" disabled>
                                                            <option value="NONE" @if($partner_login->payment_notify_mode == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                            <option value="WEBHOOK" @if($partner_login->payment_notify_mode == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                                            <option value="WEBHOOK_PG" @if($partner_login->payment_notify_mode == "WEBHOOK" && $partner_login->is_payment_gateway == 1) selected @endif>@lang('cms.Webhook - YUKK PG')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($partner_login->payment_notify_mode == "WEBHOOK")
                                                <div id="is_payment_notify_static" class="w-100">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="webhook_url_static" class="col-lg-4 col-form-label">@lang('cms.Webhook URL')</label>
                                                            <div class="col-lg-8">
                                                                <input type="text" disabled id="webhook_url_static" name="webhook_url_static" value="{{ $partner_login->webhook_url }}" placeholder="https://example.com" class="form-control" readonly>
                                                            </div>
                                                            @if ($errors->has("webhook_url_static"))
                                                                <span class="invalid-feedback">{{ $errors->first("date") }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">@lang('cms.Order Timeout (in secs)')</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" name="partner_order_timeout" value="{{ $merchant_branch->partner_order_timeout ? : old('partner_order_timeout') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">@lang('cms.Max Payment')</label>
                                        <div class="col-lg-8">
                                            <input type="number" class="form-control" name="partner_order_id_max_payment" value="{{ $merchant_branch->partner_order_id_max_payment ? : old('partner_order_id_max_payment') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">@lang('cms.Order ID Prefix')</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" id="partner_order_id_prefix" name="partner_order_id_prefix" value="{{ $merchant_branch->partner_order_id_prefix ? : old('partner_order_id_prefix') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">@lang('cms.Order ID Length')</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="number" id="partner_order_id_length" name="partner_order_id_length" value="{{ $merchant_branch->partner_order_id_length ? : old('partner_order_id_length') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                </div>

                <div class="card-body">
                    <h5 class="mb-3">@lang('cms.QRIS Static')</h5>

                    @foreach($edc_sticker as $edc)
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.IMEI')</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" value="{{ $edc->imei }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                            <div class="col-lg-8">
                                                <select disabled class="form-control select2">
                                                    <option value="NONE" @if($edc->grant_type === "NONE") selected @endif>@lang('cms.NONE')</option>
                                                    <option value="CLIENT_ID_SECRET" @if($edc->grant_type === "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @if($edc->grant_type === "CLIENT_ID_SECRET")
                                        <div class="w-100">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="snap_client_id_static" class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                                    <div class="col-lg-6">
                                                        <input type="text" readonly name="client_id_static" value="{{ $edc->client_id }}" class="form-control" id="snap_client_id_static">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button class="btn btn-success" type="button" id="btn-copy-client-id-static">@lang("cms.Copy")</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="snap_client_secret_static" class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" readonly name="client_secret_static" value="{{ $edc->client_secret }}"  class="form-control" id="snap_client_secret_static">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button class="btn btn-success" type="button" id="btn-copy-client-secret-static">@lang("cms.Copy")</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label for="payment_notify_mode_static" class="col-lg-4 col-form-label @if ($errors->has("payment_notify_mode_static")) text-danger @endif">@lang('cms.Payment Notify Mode')</label>
                                            <div class="col-lg-8">
                                                <select disabled class="form-control select2">
                                                    <option value="NONE" @if($edc->payment_notify_mode == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                    <option value="WEBHOOK" @if($edc->payment_notify_mode == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @if($edc->payment_notify_mode == "WEBHOOK")
                                        <div class="w-100">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Webhook URL')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" id="webhook_url_static" name="webhook_url_static" placeholder="https://example.com" class="form-control" value="{{ $edc->webhook_url }}" readonly disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($merchant_branch->qr_type == 's')
                <div class="card-body">
                    <h5 class="mb-3">@lang('cms.QRIS Static')</h5>

                    @foreach($edc_sticker as $edc)
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.IMEI')</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" value="{{ $edc->imei }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                            <div class="col-lg-8">
                                                <select disabled class="form-control select2">
                                                    <option value="NONE" @if($edc->grant_type === "NONE") selected @endif>@lang('cms.NONE')</option>
                                                    <option value="CLIENT_ID_SECRET" @if($edc->grant_type === "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @if($edc->grant_type === "CLIENT_ID_SECRET")
                                        <div class="w-100">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="snap_client_id_static" class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                                    <div class="col-lg-6">
                                                        <input type="text" readonly name="client_id_static" value="{{ $edc->client_id }}" class="form-control" id="snap_client_id_static">
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button class="btn btn-success" type="button" id="btn-copy-client-id-static">@lang("cms.Copy")</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="snap_client_secret_static" class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" readonly name="client_secret_static" value="{{ $edc->client_secret }}"  class="form-control" id="snap_client_secret_static">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button class="btn btn-success" type="button" id="btn-copy-client-secret-static">@lang("cms.Copy")</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label for="payment_notify_mode_static" class="col-lg-4 col-form-label @if ($errors->has("payment_notify_mode_static")) text-danger @endif">@lang('cms.Payment Notify Mode')</label>
                                            <div class="col-lg-8">
                                                <select disabled class="form-control select2">
                                                    <option value="NONE" @if($edc->payment_notify_mode == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                    <option value="WEBHOOK" @if($edc->payment_notify_mode == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @if($edc->payment_notify_mode == "WEBHOOK")
                                        <div class="w-100">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Webhook URL')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" id="webhook_url_static" name="webhook_url_static" placeholder="https://example.com" class="form-control" value="{{ $edc->webhook_url }}" readonly disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($merchant_branch->qr_type == 'd')
                <div class="card-body">
                    <div class="row mb-4">
                        <h5 class="mb-3">@lang('cms.QRIS Dynamic')</h5>
                    </div>

                    @foreach($edc_dynamic_list as $edc)
                        @php($is_snap_enabled = (isset($edc) && isset($edc->partner) && $edc->partner->is_snap_enabled))
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.IMEI')</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" value="{{ $edc->imei }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($edc->partner_logins as $partner_login)
                                        @if($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                    <div class="col-lg-8">
                                                        <select class="form-control select2" disabled>
                                                            <option value="NONE" @if($partner_login->grant_type === "NONE") selected @endif>@lang('cms.NONE')</option>
                                                            <option value="CLIENT_ID_SECRET" @if($partner_login->grant_type === "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="w-100">
                                                @if($is_snap_enabled)
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Store ID')</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" readonly id="store_id_dynamic_{{ $partner_login->id }}" name="store_id_dynamic_{{ $partner_login->id }}" value="{{ $partner_login->username }}" class="form-control">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <button class="btn btn-success btn-copy-store-id-dynamic" data-id="{{ $partner_login->id }}" type="button" id="btn-copy-store-id-dynamic-{{ $partner_login->id }}">@lang("cms.Copy")</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                                            <div class="col-lg-6">
                                                                <input type="text" readonly id="snap_client_id_dynamic_{{ $partner_login->id }}" name="snap_client_id_dynamic_{{ $partner_login->id }}" value="{{ $partner_login->username }}" class="form-control">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <button class="btn btn-success btn-copy-client-id-dynamic" data-id="{{ $partner_login->id }}" type="button" id="btn-copy-client-id-dynamic-{{ $partner_login->id }}">@lang("cms.Copy")</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="snap_client_secret_static" class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" readonly id="snap_client_secret_dynamic_{{ $partner_login->id }}" value="{{ $partner_login->password }}"  class="form-control">
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <button class="btn btn-success btn-copy-client-secret-dynamic" type="button" data-id="{{ $partner_login->id }}" id="btn-copy-client-secret-dynamic-{{ $partner_login->id }}">@lang("cms.Copy")</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.SNAP Implementation')</label>
                                            <div class="col-lg-8">
                                                @if($is_snap_enabled)
                                                    <input class="form-control" value="SNAP" readonly>
                                                @else
                                                    <input class="form-control" value="NON SNAP" readonly>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach($edc->partner_logins as $partner_login)
                                        @if($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Payment Notify Mode')</label>
                                                    <div class="col-lg-8">
                                                        <select class="form-control select2" disabled>
                                                            <option value="NONE" @if($partner_login->payment_notify_mode == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                            <option value="WEBHOOK" @if($partner_login->payment_notify_mode == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                                            <option value="WEBHOOK_PG" @if($partner_login->payment_notify_mode == "WEBHOOK" && $partner_login->is_payment_gateway == 1) selected @endif>@lang('cms.Webhook - YUKK PG')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($partner_login->payment_notify_mode == "WEBHOOK")
                                                <div id="is_payment_notify_static" class="w-100">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="webhook_url_static" class="col-lg-4 col-form-label">@lang('cms.Webhook URL')</label>
                                                            <div class="col-lg-8">
                                                                <input type="text" disabled id="webhook_url_static" name="webhook_url_static" value="{{ $partner_login->webhook_url }}" placeholder="https://example.com" class="form-control" readonly>
                                                            </div>
                                                            @if ($errors->has("webhook_url_static"))
                                                                <span class="invalid-feedback">{{ $errors->first("date") }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">@lang('cms.Order Timeout (in secs)')</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" name="partner_order_timeout" value="{{ $merchant_branch->partner_order_timeout ? : old('partner_order_timeout') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">@lang('cms.Max Payment')</label>
                                        <div class="col-lg-8">
                                            <input type="number" class="form-control" name="partner_order_id_max_payment" value="{{ $merchant_branch->partner_order_id_max_payment ? : old('partner_order_id_max_payment') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">@lang('cms.Order ID Prefix')</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" id="partner_order_id_prefix" name="partner_order_id_prefix" value="{{ $merchant_branch->partner_order_id_prefix ? : old('partner_order_id_prefix') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">@lang('cms.Order ID Length')</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="number" id="partner_order_id_length" name="partner_order_id_length" value="{{ $merchant_branch->partner_order_id_length ? : old('partner_order_id_length') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(".btn-copy-client-id-dynamic").click(function() {
            // Get the text field
            var id = $(this).data('id');
            var copyText = $("#snap_client_id_dynamic_"+id);

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $(".btn-copy-store-id-dynamic").click(function() {
            // Get the text field
            var id = $(this).data('id');
            var copyText = $("#store_id_dynamic_"+id);

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $(".btn-copy-client-secret-dynamic").click(function() {
            // Get the text field
            var id = $(this).data('id');
            var copyText = $("#snap_client_secret_dynamic_"+id);

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $("#btn-copy-client-id-static").click(function() {
            // Get the text field
            var copyText = $("#snap_client_id_static");

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $("#btn-copy-client-secret-static").click(function() {
            // Get the text field
            var copyText = $("#snap_client_secret_static");

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });
    </script>
@endsection
