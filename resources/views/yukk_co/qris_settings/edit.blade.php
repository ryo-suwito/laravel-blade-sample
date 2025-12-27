@extends('layouts.master')
@section('html_head')
    <style type="text/css">
        .info-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 12px;
            margin-left: 15px;
            margin-top: 7px;
        }
    </style>
@endsection
@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{-- <h4><span class="font-weight-semibold">Seed</span> - Static layout</h4> --}}
                <h4>@lang('cms.EDIT QRIS Settings')</h4>
            </div>

        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i
                            class="icon-home2 mr-2"></i>@lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.qris_setting.list') }}" class="breadcrumb-item">@lang('cms.Manage QRIS Settings')</a>
                    <span class="breadcrumb-item active">@lang('cms.EDIT QRIS Settings')</span>
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
                        <label class="col-form-label col-sm-2">@lang('cms.Merchant')</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="merchant"
                                value="{{ @$merchant_branch->merchant->name }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang('cms.Merchant Branch')</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="merchant_branch" name="merchant_branch"
                                value="{{ @$merchant_branch->name }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang('cms.MPAN')</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="mpan" value="{{ @$merchant_branch->mpan }}"
                                readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang('cms.MID')</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="mid" value="{{ @$merchant_branch->mid }}"
                                readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang('cms.NMID')</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name"
                                value="{{ @$merchant_branch->nmid_pten }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang('cms.Branch Name (PTEN)')</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name"
                                value="{{ @$merchant_branch->merchant_branch_name_pten_25 }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang('cms.City (PTEN)')</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name"
                                value="{{ @$merchant_branch->city_name_pten }}" readonly>
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
                            <th class="text-center">@lang('cms.QRIS Type')</th>
                            <th class="text-center">@lang('cms.EDC IMEI')</th>
                            <th class="text-center">@lang('cms.Actions')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($edc_list as $edc)
                            <tr>
                                <td class="text-center">{!! (new \App\Actions\MerchantBranch\GetEdcType)->handle($edc) !!}</td>
                                <td class="text-center">{{ @$edc->imei }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('yukk_co.edc.detail', @$edc->id) }}" class="dropdown-item">
                                                <i class="icon icon-link"></i> @lang('cms.Detail')
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

            <form method="POST" action="{{ route('yukk_co.qris_setting.update-data', $merchant_branch->id) }}">
                @csrf
                @if ($merchant_branch->qr_type == 'b')
                    <div class="card-body">
                        <div class="row mb-4">
                            <h5 class="mb-3">@lang('cms.QRIS Dynamic')</h5>

                            <a class="ml-auto form-group" data-toggle="modal" data-target="#modal-add-qris-dynamic"
                                href="#">
                                <i class="icon-add"></i>
                                @lang('cms.Add New EDC QRIS Dynamic')
                            </a>
                        </div>
                        @foreach ($edc_dynamic_list as $edc)
                            <div class="row">
                                <input hidden name="edc-dynamic-id[]" value="{{ $edc->id }}">
                                @php($is_snap_enabled = isset($edc) && isset($edc->partner) && $edc->partner->is_snap_enabled)
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
                                        @foreach ($edc->partner_logins as $partner_login)
                                            @if ($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                        <div class="col-lg-8">
                                                            <select class="form-control select2" disabled>
                                                                <option value="NONE"
                                                                    @if ($partner_login->grant_type === 'NONE') selected @endif>
                                                                    @lang('cms.NONE')</option>
                                                                <option value="CLIENT_ID_SECRET"
                                                                    @if ($partner_login->grant_type === 'CLIENT_ID_SECRET') selected @endif>
                                                                    @lang('cms.CLIENT_ID_SECRET')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="w-100">
                                                    <input type="hidden" name="qr_type[]" value="{{ $partner_login->is_payment_gateway.'#'.$edc->id}}">
                                                    @if ($is_snap_enabled)
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-lg-4 col-form-label">@lang('cms.Store ID')</label>
                                                                <div class="col-lg-6">
                                                                    <input type="text" readonly
                                                                        id="store_id_dynamic_{{ $partner_login->id }}"
                                                                        name="store_id_dynamic_{{ $partner_login->id }}"
                                                                        value="{{ $partner_login->username }}"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <button class="btn btn-success btn-copy-store-id-dynamic"
                                                                        data-id="{{ $partner_login->id }}" type="button"
                                                                        id="btn-copy-store-id-dynamic-{{ $partner_login->id }}">@lang('cms.Copy')</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-lg-12 client_id_dynamic_{{ $edc->id }}">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                                                <div class="col-lg-6">
                                                                    <input type="text" readonly
                                                                        id="snap_client_id_dynamic_{{ $partner_login->id }}"
                                                                        name="snap_client_id_dynamic_{{ $partner_login->id }}"
                                                                        value="{{ $partner_login->username }}"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <button class="btn btn-success btn-copy-client-id-dynamic"
                                                                        data-id="{{ $partner_login->id }}" type="button"
                                                                        id="btn-copy-client-id-dynamic-{{ $partner_login->id }}">@lang('cms.Copy')</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 client_secret_dynamic_{{ $edc->id }}">
                                                            <div class="form-group row">
                                                                <label for="snap_client_secret_static"
                                                                    class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" readonly
                                                                        id="snap_client_secret_dynamic_{{ $partner_login->id }}"
                                                                        value="{{ $partner_login->password }}"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <button
                                                                        class="btn btn-success btn-copy-client-secret-dynamic"
                                                                        type="button" data-id="{{ $partner_login->id }}"
                                                                        id="btn-copy-client-secret-dynamic-{{ $partner_login->id }}">@lang('cms.Copy')</button>
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
                                                    @if ($is_snap_enabled)
                                                        <input class="form-control" value="SNAP" readonly>
                                                    @else
                                                        <input class="form-control" value="NON SNAP" readonly>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach ($edc->partner_logins as $partner_login)
                                            @if ($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label class="col-lg-4 col-form-label">@lang('cms.Payment Notify Mode')</label>
                                                        <div class="col-lg-8">
                                                            <select onchange="showOrHideInput({{ $edc->id }})"
                                                                id="payment_notify_mode_dynamic_{{ $edc->id }}"
                                                                name="payment_notify_mode_dynamic[]"
                                                                class="form-control select2">
                                                                <option value="NONE"
                                                                    @if ($partner_login->payment_notify_mode == 'NONE') selected @endif>
                                                                    @lang('cms.NONE')</option>
                                                                <option value="WEBHOOK"
                                                                    @if ($partner_login->payment_notify_mode == 'WEBHOOK') selected @endif>
                                                                    @lang('cms.Webhook')</option>
                                                                <option value="WEBHOOK_PG"
                                                                    @if ($partner_login->payment_notify_mode == 'WEBHOOK' && $partner_login->is_payment_gateway == 1) selected @endif>
                                                                    @lang('cms.Webhook - YUKK PG')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @if ($partner_login->payment_notify_mode !== 'WEBHOOK') hidden @endif
                                                    id="is_payment_notify_dynamic_{{ $edc->id }}" class="w-100">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group row">
                                                                    <label for="webhook_url_dynamic_{{ $edc->id }}"
                                                                        class="col-form-label"
                                                                        style="
                                                                        margin-left: 9px;
                                                                    ">@lang('cms.Webhook URL')
                                                                    </label>
                                                                    <a href="#" data-popup="tooltip"
                                                                        data-placement="top"
                                                                        data-original-title="Webhook URL is non-configurable for QRIS payment channel in Yukk PG"
                                                                        class="info-pg-{{ $edc->id }}">
                                                                        <span class="info-circle">i</span></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text"
                                                                    id="webhook_url_dynamic_{{ $edc->id }}"
                                                                    name="webhook_url_dynamic[]"
                                                                    value="{{ $partner_login->webhook_url }}"
                                                                    placeholder="https://example.com" class="form-control"
                                                                    @if ($partner_login->payment_notify_mode == 'WEBHOOK' && $partner_login->is_payment_gateway == 1) readonly @endif>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Order Timeout (in secs)')</label>
                                            <div class="col-lg-8">
                                                <input type="number" class="form-control" name="partner_order_timeout"
                                                    value="{{ $merchant_branch->partner_order_timeout ?: old('partner_order_timeout') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Max Payment')</label>
                                            <div class="col-lg-8">
                                                <input type="number" class="form-control"
                                                    name="partner_order_id_max_payment"
                                                    value="{{ $merchant_branch->partner_order_id_max_payment ?: old('partner_order_id_max_payment') }}">
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
                                                <input class="form-control" id="partner_order_id_prefix"
                                                    name="partner_order_id_prefix"
                                                    value="{{ $merchant_branch->partner_order_id_prefix ? $merchant_branch->partner_order_id_prefix : old('partner_order_id_prefix') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Order ID Length')</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="number" id="partner_order_id_length"
                                                    name="partner_order_id_length"
                                                    value="{{ $merchant_branch->partner_order_id_length ?: old('partner_order_id_length') }}">
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

                        @foreach ($edc_sticker as $edc)
                            <div class="row">
                                <input hidden name="edc-static" value="{{ $edc->id }}">
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
                                                        <option value="NONE"
                                                            @if ($edc->grant_type === 'NONE') selected @endif>
                                                            @lang('cms.NONE')</option>
                                                        <option value="CLIENT_ID_SECRET"
                                                            @if ($edc->grant_type === 'CLIENT_ID_SECRET') selected @endif>
                                                            @lang('cms.CLIENT_ID_SECRET')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($edc->grant_type === 'CLIENT_ID_SECRET')
                                            <div class="w-100">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="snap_client_id_static"
                                                            class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                                        <div class="col-lg-6">
                                                            <input type="text" readonly value="{{ $edc->client_id }}"
                                                                class="form-control" id="snap_client_id_static">
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <button class="btn btn-success" type="button"
                                                                id="btn-copy-client-id-static">@lang('cms.Copy')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="snap_client_secret_static"
                                                            class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" readonly value="{{ $edc->client_secret }}"
                                                                class="form-control" id="snap_client_secret_static">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <button class="btn btn-success" type="button"
                                                                id="btn-copy-client-secret-static">@lang('cms.Copy')</button>
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
                                                <label for="payment_notify_mode_static"
                                                    class="col-lg-4 col-form-label @if ($errors->has('payment_notify_mode_static')) text-danger @endif">@lang('cms.Payment Notify Mode')</label>
                                                <div class="col-lg-8">
                                                    <select id="payment_notify_mode_static" name="payment_notify_mode_static"
                                                        class="form-control select2">
                                                        <option value="NONE"
                                                            @if ($edc->payment_notify_mode == 'NONE') selected @endif>
                                                            @lang('cms.NONE')</option>
                                                        <option value="WEBHOOK"
                                                            @if ($edc->payment_notify_mode == 'WEBHOOK') selected @endif>
                                                            @lang('cms.Webhook')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div @if ($edc->payment_notify_mode !== 'WEBHOOK') hidden @endif id="is_payment_notify_static"
                                            class="w-100">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Webhook URL')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" id="webhook_url_static"
                                                            name="webhook_url_static" placeholder="https://example.com"
                                                            class="form-control" value="{{ $edc->webhook_url }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif($merchant_branch->qr_type == 's')
                    <div class="card-body">
                        <h5 class="mb-3">@lang('cms.QRIS Static')</h5>

                        @foreach ($edc_sticker as $edc)
                            <div class="row">
                                <input hidden name="edc-static" value="{{ $edc->id }}">
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
                                                        <option value="NONE"
                                                            @if ($edc->grant_type === 'NONE') selected @endif>
                                                            @lang('cms.NONE')</option>
                                                        <option value="CLIENT_ID_SECRET"
                                                            @if ($edc->grant_type === 'CLIENT_ID_SECRET') selected @endif>
                                                            @lang('cms.CLIENT_ID_SECRET')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($edc->grant_type === 'CLIENT_ID_SECRET')
                                            <div class="w-100">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="snap_client_id_static"
                                                            class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                                        <div class="col-lg-6">
                                                            <input type="text" readonly value="{{ $edc->client_id }}"
                                                                class="form-control" id="snap_client_id_static">
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <button class="btn btn-success" type="button"
                                                                id="btn-copy-client-id-static">@lang('cms.Copy')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="snap_client_secret_static"
                                                            class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" readonly value="{{ $edc->client_secret }}"
                                                                class="form-control" id="snap_client_secret_static">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <button class="btn btn-success" type="button"
                                                                id="btn-copy-client-secret-static">@lang('cms.Copy')</button>
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
                                                <label for="payment_notify_mode_static"
                                                    class="col-lg-4 col-form-label @if ($errors->has('payment_notify_mode_static')) text-danger @endif">@lang('cms.Payment Notify Mode')</label>
                                                <div class="col-lg-8">
                                                    <select id="payment_notify_mode_static" name="payment_notify_mode_static"
                                                        class="form-control select2">
                                                        <option value="NONE"
                                                            @if ($edc->payment_notify_mode == 'NONE') selected @endif>
                                                            @lang('cms.NONE')</option>
                                                        <option value="WEBHOOK"
                                                            @if ($edc->payment_notify_mode == 'WEBHOOK') selected @endif>
                                                            @lang('cms.Webhook')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div @if ($edc->payment_notify_mode !== 'WEBHOOK') hidden @endif id="is_payment_notify_static"
                                            class="w-100">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Webhook URL')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" id="webhook_url_static"
                                                            name="webhook_url_static" placeholder="https://example.com"
                                                            class="form-control" value="{{ $edc->webhook_url }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif($merchant_branch->qr_type == 'd')
                    <div class="card-body">
                        <div class="row mb-4">
                            <h5 class="mb-3">@lang('cms.QRIS Dynamic')</h5>

                            <a class="ml-auto form-group" data-toggle="modal" data-target="#modal-add-qris-dynamic"
                                href="#">
                                <i class="icon-add"></i>
                                @lang('cms.Add New EDC QRIS Dynamic')
                            </a>
                        </div>
                        @foreach ($edc_dynamic_list as $edc)
                            <div class="row">
                                <input hidden name="edc-dynamic-id[]" value="{{ $edc->id }}">
                                @php($is_snap_enabled = isset($edc) && isset($edc->partner) && $edc->partner->is_snap_enabled)
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
                                        @foreach ($edc->partner_logins as $partner_login)
                                            @if ($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                        <div class="col-lg-8">
                                                            <select class="form-control select2" disabled>
                                                                <option value="NONE"
                                                                    @if ($partner_login->grant_type === 'NONE') selected @endif>
                                                                    @lang('cms.NONE')</option>
                                                                <option value="CLIENT_ID_SECRET"
                                                                    @if ($partner_login->grant_type === 'CLIENT_ID_SECRET') selected @endif>
                                                                    @lang('cms.CLIENT_ID_SECRET')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="w-100">
                                                    <input type="hidden" name="qr_type[]" value="{{ $partner_login->is_payment_gateway.'#'.$edc->id}}">
                                                    @if ($is_snap_enabled)
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-lg-4 col-form-label">@lang('cms.Store ID')</label>
                                                                <div class="col-lg-6">
                                                                    <input type="text" readonly
                                                                        id="store_id_dynamic_{{ $partner_login->id }}"
                                                                        name="store_id_dynamic_{{ $partner_login->id }}"
                                                                        value="{{ $partner_login->username }}"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <button class="btn btn-success btn-copy-store-id-dynamic"
                                                                        data-id="{{ $partner_login->id }}" type="button"
                                                                        id="btn-copy-store-id-dynamic-{{ $partner_login->id }}">@lang('cms.Copy')</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-lg-12 client_id_dynamic_{{ $edc->id }}">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                                                <div class="col-lg-6">
                                                                    <input type="text" readonly
                                                                        id="snap_client_id_dynamic_{{ $partner_login->id }}"
                                                                        name="snap_client_id_dynamic_{{ $partner_login->id }}"
                                                                        value="{{ $partner_login->username }}"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <button class="btn btn-success btn-copy-client-id-dynamic"
                                                                        data-id="{{ $partner_login->id }}" type="button"
                                                                        id="btn-copy-client-id-dynamic-{{ $partner_login->id }}">@lang('cms.Copy')</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 client_secret_dynamic_{{ $edc->id }}">
                                                            <div class="form-group row">
                                                                <label for="snap_client_secret_static"
                                                                    class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" readonly
                                                                        id="snap_client_secret_dynamic_{{ $partner_login->id }}"
                                                                        value="{{ $partner_login->password }}"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <button
                                                                        class="btn btn-success btn-copy-client-secret-dynamic"
                                                                        type="button" data-id="{{ $partner_login->id }}"
                                                                        id="btn-copy-client-secret-dynamic-{{ $partner_login->id }}">@lang('cms.Copy')</button>
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
                                                    @if ($is_snap_enabled)
                                                        <input class="form-control" value="SNAP" readonly>
                                                    @else
                                                        <input class="form-control" value="NON SNAP" readonly>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach ($edc->partner_logins as $partner_login)
                                            @if ($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label class="col-lg-4 col-form-label">@lang('cms.Payment Notify Mode')</label>
                                                        <div class="col-lg-8">
                                                            <select onchange="showOrHideInput({{ $edc->id }})"
                                                                id="payment_notify_mode_dynamic_{{ $edc->id }}"
                                                                name="payment_notify_mode_dynamic[]"
                                                                class="form-control select2">
                                                                <option value="NONE"
                                                                    @if ($partner_login->payment_notify_mode == 'NONE') selected @endif>
                                                                    @lang('cms.NONE')</option>
                                                                <option value="WEBHOOK"
                                                                    @if ($partner_login->payment_notify_mode == 'WEBHOOK') selected @endif>
                                                                    @lang('cms.Webhook')</option>
                                                                <option value="WEBHOOK_PG"
                                                                    @if ($partner_login->payment_notify_mode == 'WEBHOOK' && $partner_login->is_payment_gateway == 1) selected @endif>
                                                                    @lang('cms.Webhook - YUKK PG')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @if ($partner_login->payment_notify_mode !== 'WEBHOOK') hidden @endif
                                                    id="is_payment_notify_dynamic_{{ $edc->id }}" class="w-100">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group row">
                                                                    <label for="webhook_url_dynamic_{{ $edc->id }}"
                                                                        class="col-form-label"
                                                                        style="
                                                                        margin-left: 9px;
                                                                    ">@lang('cms.Webhook URL')
                                                                    </label>
                                                                    <a href="#" data-popup="tooltip"
                                                                        data-placement="top"
                                                                        data-original-title="Webhook URL is non-configurable for QRIS payment channel in Yukk PG"
                                                                        class="info-pg-{{ $edc->id }}">
                                                                        <span class="info-circle">i</span></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text"
                                                                    id="webhook_url_dynamic_{{ $edc->id }}"
                                                                    name="webhook_url_dynamic[]"
                                                                    value="{{ $partner_login->webhook_url }}"
                                                                    placeholder="https://example.com" class="form-control"
                                                                    @if ($partner_login->payment_notify_mode == 'WEBHOOK' && $partner_login->is_payment_gateway == 1) readonly @endif>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Order Timeout (in secs)')</label>
                                            <div class="col-lg-8">
                                                <input type="number" class="form-control" name="partner_order_timeout"
                                                    value="{{ $merchant_branch->partner_order_timeout ?: old('partner_order_timeout') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Max Payment')</label>
                                            <div class="col-lg-8">
                                                <input type="number" class="form-control"
                                                    name="partner_order_id_max_payment"
                                                    value="{{ $merchant_branch->partner_order_id_max_payment ?: old('partner_order_id_max_payment') }}">
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
                                                <input class="form-control" id="partner_order_id_prefix"
                                                    name="partner_order_id_prefix"
                                                    value="{{ $merchant_branch->partner_order_id_prefix ? $merchant_branch->partner_order_id_prefix : old('partner_order_id_prefix') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Order ID Length')</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="number" id="partner_order_id_length"
                                                    name="partner_order_id_length"
                                                    value="{{ $merchant_branch->partner_order_id_length ?: old('partner_order_id_length') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                    </div>
                @endif

                <div class="d-flex justify-content-center mt-5">
                    <a href="{{ route('yukk_co.partner_login.add_from_qris', ['merchant_id' => $merchant_branch->merchant_id, 'merchant_name' => $merchant_branch->merchant->name, 'branch_id' => $merchant_branch->id]) }}"
                        class="form-group btn btn-primary col-2 btn-block mr-3">@lang('cms.Create New Account Login')</a>
                    <button class="form-group btn btn-primary col-2 btn-submit" id="btn-submit"
                        type="submit">@lang('cms.Save')</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-add-qris-dynamic" class="modal form-group" role="dialog" aria-hidden="true"
        aria-labelledby="modal-add-qris-dynamic">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('yukk_co.qris_setting.create.dynamic', $merchant_branch->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('cms.Add New EDC QRIS Dynamic')</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <input name="merchant_branch_id" id="merchant_branch_id" value="{{ $merchant_branch->id }}" hidden>

                    <div class="modal-body">
                        <div class="card-body row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group row">
                                            <label for="customer_id"
                                                class="col-lg-4 col-form-label @if ($errors->has('customer_id')) text-danger @endif">@lang('cms.Beneficiary')</label>
                                            <div class="col-lg-6">
                                                <select id="customer_id" name="customer_id"
                                                    class="form-control select2 @if ($errors->has('customer_id')) is-invalid @endif"
                                                    required>
                                                    <option value="{{ $merchant_branch->customer->id }}">
                                                        {{ $merchant_branch->customer->name }}</option>
                                                </select>
                                                @if ($errors->has('customer_id'))
                                                    <span
                                                        class="invalid-feedback">{{ $errors->first('customer_id') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-lg-2 mt-2">
                                                <a href="{{ route('yukk_co.customers.create') }}" target="_blank"
                                                    class="justify-content-center">
                                                    <i class="icon-add"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group row">
                                            <label for="partner_id"
                                                class="col-lg-4 col-form-label @if ($errors->has('partner_id')) text-danger @endif">@lang('cms.Partner Name')</label>
                                            <div class="col-lg-6">
                                                @if(count($merchant_branch->edcs) > 0)
                                                    <input type="text" class="form-control required" 
                                                        value="{{ @$merchant_branch->edcs[0]->partner->name ? : '' }}" readonly>
                                                    <input type="hidden" id="partner_id" name="partner_id" class="form-control 
                                                        @if ($errors->has("partner_id")) is-invalid @endif" value="{{ @$merchant_branch->edcs[0]->partner->id ? : '' }}" required>
                                                @else
                                                    <input type="text" class="form-control required" value="" readonly>
                                                    <input type="hidden" id="partner_id" name="partner_id" class="form-control" value="" required>
                                                @endif
                                                <input type="hidden" id="hidden_is_partner_snap" name="hidden_is_partner_snap">
                                                @if ($errors->has('partner_id'))
                                                    <span
                                                        class="invalid-feedback">{{ $errors->first('partner_id') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-lg-2 mt-2">
                                                <a href="{{ route('yukk_co.partner.create') }}" target="_blank"
                                                    class="justify-content-center">
                                                    <i class="icon-add"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group row">
                                            <label for="partner_fee_id"
                                                class="col-lg-4 col-form-label @if ($errors->has('partner_fee_id')) text-danger @endif">@lang('cms.Partner Fee')</label>
                                            <div class="col-lg-6">
                                                <select id="partner_fee_id" name="partner_fee_id"
                                                    class="form-control select2 @if ($errors->has('partner_fee_id')) is-invalid @endif"
                                                    required>
                                                    <option value="">Select Partner Fee...</option>
                                                </select>
                                                @if ($errors->has('partner_fee_id'))
                                                    <span
                                                        class="invalid-feedback">{{ $errors->first('partner_fee_id') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-lg-2 mt-2">
                                                <a href="{{ route('yukk_co.partner_fee.create') }}" target="_blank"
                                                    class="justify-content-center">
                                                    <i class="icon-add"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang('cms.Event')</label>
                                            <div class="col-lg-6">
                                                <select id="event" name="event" class="form-control select2">
                                                    <option value="">Select Event...</option>
                                                    @foreach ($events as $event)
                                                        <option value="{{ $event->id }}"
                                                            @if (old('event') == $event->id) selected @endif>
                                                            {{ $event->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 mt-2">
                                                <a href="{{ route('yukk_co.event.create') }}" target="_blank"
                                                    class="form-controljustify-content-center">
                                                    <i class="icon-add"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label for="currency_type"
                                                class="col-lg-4 col-form-label @if ($errors->has('currency_type')) text-danger @endif">@lang('cms.Currency Type')</label>
                                            <div class="col-lg-8">
                                                <select id="currency_type" name="currency_type"
                                                    class="form-control select2 @if ($errors->has('currency_type')) is-invalid @endif"
                                                    required>
                                                    {{-- <option value="YUKK_P_THEN_YUKK_E">COMBINE</option> --}}
                                                    <option value="YUKK_P_ONLY">YUKK CASH ONLY</option>
                                                    {{-- <option value="YUKK_E_ONLY">YUKK POINT ONLY</option> --}}
                                                </select>
                                                @if ($errors->has('currency_type'))
                                                    <span
                                                        class="invalid-feedback">{{ $errors->first('currency_type') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group row">
                                            <label for="min_discount"
                                                class="col-lg-4 col-form-label @if ($errors->has('min_discount')) text-danger @endif">@lang('cms.Min Discount')</label>
                                            <div class="col-lg-8">
                                                <input type="number"
                                                    class="form-control @if ($errors->has('min_discount')) is-invalid @endif"
                                                    name="min_discount" id="min_discount" value="0">
                                            </div>
                                            @if ($errors->has('min_discount'))
                                                <span class="invalid-feedback">{{ $errors->first('min_discount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group row">
                                            <label for="discount"
                                                class="col-lg-4 col-form-label @if ($errors->has('discount')) text-danger @endif">@lang('cms.Discount')</label>
                                            <div class="col-lg-8">
                                                <input type="number"
                                                    class="form-control @if ($errors->has('discount')) is-invalid @endif"
                                                    name="discount" id="discount" value="0">
                                            </div>
                                            @if ($errors->has('discount'))
                                                <span class="invalid-feedback">{{ $errors->first('discount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group row">
                                            <label for="service_charge"
                                                class="col-lg-4 col-form-label @if ($errors->has('service_charge')) text-danger @endif">@lang('cms.Service Charge')</label>
                                            <div class="col-lg-8">
                                                <input type="number"
                                                    class="form-control @if ($errors->has('service_charge')) is-invalid @endif"
                                                    name="service_charge" id="service_charge" value="0">
                                            </div>
                                            @if ($errors->has('service_charge'))
                                                <span
                                                    class="invalid-feedback">{{ $errors->first('service_charge') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group row">
                                            <label for="tax"
                                                class="col-lg-4 col-form-label">@lang('cms.Tax')</label>
                                            <div class="col-lg-8">
                                                <input type="number"
                                                    class="form-control @if ($errors->has('tax')) is-invalid @endif"
                                                    name="tax" id="tax" value="0">
                                            </div>
                                            @if ($errors->has('tax'))
                                                <span class="invalid-feedback">{{ $errors->first('tax') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="hidden_qris" hidden>
                            <hr class="mx-3">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="grant_type"
                                                        class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                    <div class="col-lg-8">
                                                        <select id="grant_type" name="grant_type"
                                                            class="form-control select2" required>
                                                            <option value="NONE"
                                                                @if (old('grant_type', 'NONE') == 'NONE') selected @endif>
                                                                @lang('cms.NONE')</option>
                                                            <option value="CLIENT_ID_SECRET"
                                                                @if (old('grant_type', 'NONE') == 'CLIENT_ID_SECRET') selected @endif>
                                                                @lang('cms.CLIENT_ID_SECRET')</option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('grant_type'))
                                                        <span
                                                            class="invalid-feedback">{{ $errors->first('grant_type') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div id="grant_id_secret" class="w-100" hidden>
                                                <div id="snap_0" class="w-100" hidden>
                                                    <div class="col-lg-12 mt-3">
                                                        <div class="form-group row">
                                                            <label for="snap_client_id_dynamic"
                                                                class="col-lg-4 col-form-label @if ($errors->has('snap_client_id_dynamic')) text-danger @endif">@lang('cms.Client ID')</label>
                                                            <div class="col-lg-4">
                                                                <input type="text" readonly required
                                                                    name="snap_client_id_dynamic"
                                                                    id="snap_client_id_dynamic"
                                                                    class="form-control @if ($errors->has('snap_client_id_dynamic')) is-invalid @endif">
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <button class="btn btn-primary" type="button"
                                                                    id="btn-generate-client-id-dynamic">@lang('cms.Generate')</button>
                                                                <button class="btn btn-success" type="button"
                                                                    id="btn-copy-client-id-dynamic">@lang('cms.Copy')</button>
                                                            </div>
                                                            @if ($errors->has('snap_client_id_dynamic'))
                                                                <span
                                                                    class="invalid-feedback">{{ $errors->first('snap_client_id_dynamic') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 mt-3">
                                                        <div class="form-group row">
                                                            <label for="snap_client_secret_dynamic"
                                                                class="col-lg-4 col-form-label @if ($errors->has('snap_client_secret_dynamic')) text-danger @endif">@lang('cms.Client Secret')</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" required readonly
                                                                    name="snap_client_secret_dynamic"
                                                                    class="form-control @if ($errors->has('snap_client_secret_dynamic')) is-invalid @endif"
                                                                    id="snap_client_secret_dynamic">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <button class="btn btn-primary" type="button"
                                                                    id="btn-generate-client-secret-dynamic">@lang('cms.Generate')</button>
                                                                <button class="btn btn-success" type="button"
                                                                    id="btn-copy-client-secret-dynamic">@lang('cms.Copy')</button>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('snap_client_secret_dynamic'))
                                                            <span
                                                                class="invalid-feedback">{{ $errors->first('snap_client_secret_dynamic') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div id="snap_1" class="w-100" hidden>
                                                    <div class="col-lg-12 mt-3">
                                                        <div class="form-group row">
                                                            <label for="store_id_dynamic"
                                                                class="col-lg-4 col-form-label @if ($errors->has('store_id_dynamic')) text-danger @endif">@lang('cms.Store ID')</label>
                                                            <div class="col-lg-4">
                                                                <input type="text" readonly name="store_id_dynamic"
                                                                    value="{{ old('store_id_dynamic') }}"
                                                                    class="form-control @if ($errors->has('store_id_dynamic')) is-invalid @endif"
                                                                    id="store_id_dynamic" required>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <button class="btn btn-primary" type="button"
                                                                    id="btn-generate-store-id-dynamic">@lang('cms.Generate')</button>
                                                                <button class="btn btn-success" type="button"
                                                                    id="btn-copy-store-id-dynamic">@lang('cms.Copy')</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="payment_notify_modal"
                                                        class="col-lg-4 col-form-label">@lang('cms.Payment Notify Mode')</label>
                                                    <div class="col-lg-8">
                                                        <select id="payment_notify_modal" name="payment_notify_modal"
                                                            class="form-control select2" required>
                                                            <option value="NONE"
                                                                @if (old('payment_notify_modal', 'NONE') == 'NONE') selected @endif>
                                                                @lang('cms.NONE')</option>
                                                            <option value="WEBHOOK"
                                                                @if (old('payment_notify_modal', 'NONE') == 'WEBHOOK') selected @endif>
                                                                @lang('cms.Webhook')</option>
                                                            <option value="WEBHOOK_PG"
                                                                @if (old('payment_notify_modal', 'NONE') == 'WEBHOOK_PG') selected @endif>
                                                                @lang('cms.Webhook - YUKK PG')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="is_payment_notify_modal" class="w-100" hidden>
                                                <div class="col-lg-12">
                                                    <div class="form-group row mt-3">
                                                        <div class="col-lg-4">
                                                            <div class="form-group row"style="
                                                                                        margin-left: 1px;
                                                                                    ">
                                                                <label
                                                                    class="col-form-label @if ($errors->has('webhook_url')) text-danger @endif">@lang('cms.Webhook URL')
                                                                </label>
                                                                <a href="#" data-popup="tooltip"
                                                                    data-placement="top"
                                                                    data-original-title="Webhook URL is non-configurable for QRIS payment channel in Yukk PG"
                                                                    class="info-pg">
                                                                    <span class="info-circle">i</span></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" id="webhook_url" name="webhook_url"
                                                                placeholder="https://example.com"
                                                                class="form-control @if ($errors->has('webhook_url')) is-invalid @endif">
                                                        </div>
                                                        @if ($errors->has('webhook_url'))
                                                            <span
                                                                class="invalid-feedback">{{ $errors->first('webhook_url') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">@lang('cms.Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('cms.Create')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var webhookPg = "{{ config('payment_gateway.urls.webhook') }}";
        $('input[name^="qr_type"]').each(function() {
            var value = $(this).val();
            var split = value.split("#");

            if(split[0] == 1){
                $(".client_id_dynamic_"+split[1]).hide();
                $(".client_secret_dynamic_"+split[1]).hide();
            }else{
                $(".info-pg-"+split[1]).hide();
            }
        });

        function showOrHideInput(id) {
            if ($('#payment_notify_mode_dynamic_' + id).val() == 'WEBHOOK') {
                document.getElementById("is_payment_notify_dynamic_" + id).removeAttribute("hidden");
                var inputs = document.querySelectorAll("#is_payment_notify_dynamic_" + id + " input");
                inputs.forEach(function(input) {
                    input.removeAttribute("disabled");
                    input.removeAttribute("readonly");
                    input.value = '';
                });
                $(".info-pg-"+id).hide();
                $(".client_id_dynamic_"+id).show();
                $(".client_secret_dynamic_"+id).show();
            } else if ($('#payment_notify_mode_dynamic_' + id).val() == 'WEBHOOK_PG') {
                document.getElementById("is_payment_notify_dynamic_" + id).removeAttribute("hidden");
                var inputs = document.querySelectorAll("#is_payment_notify_dynamic_" + id + " input");
                inputs.forEach(function(input) {
                    input.removeAttribute("disabled");
                    input.value = '';
                });
                setTimeout(function() {
                    inputs.forEach(function(input) {
                        input.value = webhookPg; // Set nilai baru
                        input.setAttribute("readonly", true); // Jadikan readonly
                    });
                }, 100);
                $(".info-pg-"+id).show();
                $(".client_id_dynamic_"+id).hide();
                $(".client_secret_dynamic_"+id).hide();
            } else {
                $("#is_payment_notify_dynamic_" + id).attr("hidden", "hidden");
                $("#is_payment_notify_dynamic_" + id + " input").attr("disabled", "disabled");
            }
        }

        $(document).on('select2:open', () => {
            document.querySelector('.select2-container--open .select2-search__field').focus();
        });

        function generateClientId(length = 24) {
            var result = [];
            var characters = '0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
            }
            return result.join('');
        }
        var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

        function generateClientSecret(length = 24) {
            var result = [];
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
            }
            return Base64.encode(result.join(''));
        }

        $(".btn-copy-store-id-dynamic").click(function() {
            // Get the text field
            var id = $(this).data('id');
            var copyText = $("#store_id_dynamic_" + id);

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $("#btn-generate-client-id-dynamic").click(function(e) {
            var clientId = generateClientId();
            $("#snap_client_id_dynamic").val(clientId);
            e.preventDefault();
        });
        $("#btn-generate-client-secret-dynamic").click(function(e) {
            var clientSecret = generateClientSecret();
            $("#snap_client_secret_dynamic").val(clientSecret);
            e.preventDefault();
        });

        $(".btn-copy-client-id-dynamic").click(function() {
            // Get the text field
            var id = $(this).data('id');
            var copyText = $("#snap_client_id_dynamic_" + id);

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });
        $(".btn-copy-client-secret-dynamic").click(function() {
            // Get the text field
            var id = $(this).data('id');
            var copyText = $("#snap_client_secret_dynamic_" + id);

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

        $("#btn-generate-store-id-dynamic").click(function(e) {
            var clientId = generateClientId();
            $("#store_id_dynamic").val(clientId);
            e.preventDefault();
        });
        $("#btn-copy-store-id-dynamic").click(function() {
            // Get the text field
            var copyText = $("#store_id_dynamic");

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $("#grant_type").change(function(e) {
            if (document.getElementById("grant_type").value == 'CLIENT_ID_SECRET') {
                $("#grant_id_secret").removeAttr("hidden");
                $("#grant_id_secret input").removeAttr("disabled");
                $("#grant_id_secret textarea").removeAttr("disabled");
            } else {
                $("#grant_id_secret").attr("hidden", "hidden");
                $("#grant_id_secret input").attr("disabled", "disabled");
                $("#grant_id_secret textarea").attr("disabled", "disabled");
            }
        });
        $("#grant_type").change();

        $("#payment_notify_mode_static").change(function(e) {
            if (document.getElementById("payment_notify_mode_static").value == 'WEBHOOK') {
                $("#is_payment_notify_static").removeAttr("hidden");
                $("#is_payment_notify_static input").removeAttr("disabled");
            } else {
                $("#is_payment_notify_static").attr("hidden", "hidden");
                $("#is_payment_notify_static input").attr("disabled", "disabled");
            }
        });
        $("#payment_notify_mode_static").change();

        $("#payment_notify_modal").change(function(e) {
            if (document.getElementById("payment_notify_modal").value == 'WEBHOOK') {
                document.getElementById("is_payment_notify_modal").removeAttribute("hidden");
                var inputs = document.querySelectorAll("#is_payment_notify_modal input");
                inputs.forEach(function(input) {
                    input.removeAttribute("disabled");
                    input.removeAttribute("readonly");
                    input.value = '';
                });
                $(".info-pg").hide();
                $("#snap_0").show();
            } else if (document.getElementById("payment_notify_modal").value == 'WEBHOOK_PG') {
                document.getElementById("is_payment_notify_modal").removeAttribute("hidden");
                var inputs = document.querySelectorAll("#is_payment_notify_modal input");
                inputs.forEach(function(input) {
                    input.removeAttribute("disabled");
                    input.value = '';
                });
                setTimeout(function() {
                    inputs.forEach(function(input) {
                        input.value = webhookPg; // Set nilai baru
                        input.setAttribute("readonly", true); // Jadikan readonly
                    });
                }, 100);
                $(".info-pg").show();
                $("#snap_0").hide();
            } else {
                $("#is_payment_notify_modal").attr("hidden", "hidden");
                $("#is_payment_notify_modal input").attr("disabled", "disabled");
            }
        });
        $("#payment_notify_mode_static").change();

        $("#btn-submit").click(function(e) {
            if (confirm("@lang('cms.general_confirmation_dialog_content')")) {

            } else {
                e.preventDefault();
            }
        });
    </script>
@endsection

@section('post_scripts')
    <script>
        $(document).ready(function() {
            $('#customer_id').select2({
                ajax: {
                    url: "{{ route('json.customer.select') }}",
                    type: "GET",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;

                        return {
                            pagination: {
                                more: true
                            },
                            results: data
                        };
                    },
                    cache: true
                },
                placeholder: "Select Beneficiary",
            });

            $('#partner_fee_id').select2({
                ajax: {
                    url: "{{ route('yukk_co.partner_fee.list_json') }}",
                    type: "POST",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            "_token": "{{ csrf_token() }}",
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;

                        return {
                            pagination: {
                                more: true
                            },
                            results: data
                        };
                    },
                    cache: true
                },
                placeholder: "Select Partner Fee",
            });

            $('#partner_id').change(function() {
                var data = $("#partner_id").select2('data');
                var snap_enable = data[0].is_snap_enable;
                var partner_snap = $('#hidden_is_partner_snap').val(snap_enable);

                if (partner_snap.val() == 0) {
                    $('#snap_0').removeAttr('hidden');
                    $('#snap_1').attr('hidden', 'hidden');

                    $('#non_snap_dynamic').removeAttr('hidden');
                    $('#snap_dynamic').attr('hidden', 'hidden');
                } else {
                    $('#snap_0').attr('hidden', 'hidden');
                    $('#snap_1').removeAttr('hidden');

                    $('#snap_dynamic').removeAttr('hidden');
                    $('#non_snap_dynamic').attr('hidden', 'hidden');
                }

                $('#hidden_qris').removeAttr('hidden');
            });
        });
    </script>
@endsection
