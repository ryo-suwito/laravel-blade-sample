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
                <h4>@lang('cms.Edit QRIS Settings')</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{-- <button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button> --}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.qris_setting.list') }}" class="breadcrumb-item">@lang('cms.Manage QRIS Settings')</a>
                    <span class="breadcrumb-item active">@lang('cms.Edit QRIS Settings')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <form method="POST" action="{{ route('yukk_co.qris_setting.update', $item->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="merchant_id" class="col-lg-4 col-form-label @if ($errors->has("merchant_id")) text-danger @endif">@lang('cms.Merchant')</label>
                                                    <div class="col-lg-6">
                                                        <select id="merchant_id" name="merchant_id" class="form-control select2 @if ($errors->has("merchant_id")) is-invalid @endif" required>
                                                            <option value="{{ $item->merchant_id ? : '' }}" selected>
                                                                {{ $item->merchant ? $item->merchant->name : '' }}
                                                            </option>
                                                        </select>
                                                        @if ($errors->has("merchant_id"))
                                                            <span class="invalid-feedback">{{ $errors->first("merchant_id") }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2 mt-2">
                                                        <a href="{{ route('yukk_co.merchant.add') }}" target="_blank"
                                                           class="form-controljustify-content-center">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="merchant_branch_id" class="col-lg-4 col-form-label  @if ($errors->has("merchant_branch_id")) text-danger @endif">@lang('cms.Merchant Branch')</label>
                                                    <div class="col-lg-6">
                                                        <select id="merchant_branch_id" name="merchant_branch_id" class="form-control select2 @if ($errors->has("merchant_branch_id")) is-invalid @endif" required>
                                                            <option value="{{ $item->id ? : '' }}" selected>
                                                                {{ $item->name ? : '' }}
                                                            </option>
                                                        </select>
                                                        @if ($errors->has("merchant_branch_id"))
                                                            <span class="invalid-feedback">{{ $errors->first("merchant_branch_id") }}</span>
                                                        @endif
                                                        <input id="merchant_branch_name" name="merchant_branch_name" value="{{ $item->name }}" hidden>
                                                    </div>
                                                    <div class="col-lg-2 mt-2">
                                                        <a href="{{ route('yukk_co.merchant_branch.add') }}"
                                                           target="_blank" class="form-controljustify-content-center">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="customer_id" class="col-lg-4 col-form-label @if ($errors->has("customer_id")) text-danger @endif">@lang('cms.Beneficiary')</label>
                                                    <div class="col-lg-6">
                                                        <select id="customer_id" name="customer_id" class="form-control select2 @if ($errors->has("customer_id")) is-invalid @endif" required>
                                                            <option value="{{ $item->customer->id }}">{{ $item->customer->name }}</option>
                                                        </select>
                                                        @if ($errors->has("customer_id"))
                                                            <span class="invalid-feedback">{{ $errors->first("customer_id") }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2 mt-2">
                                                        <a href="{{ route('yukk_co.customers.create') }}" target="_blank" class="form-controljustify-content-center">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="partner_id" class="col-lg-4 col-form-label @if ($errors->has("partner_id")) text-danger @endif">@lang('cms.Partner Name')</label>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control required" value="{{ @$partner_has_merchant_branch->partner->name ? : '' }}" readonly>
                                                        <input type="hidden" id="partner_id" name="partner_id" class="form-control @if ($errors->has("partner_id")) is-invalid @endif" value="{{ @$partner_has_merchant_branch->partner->id ? : '' }}" required>
                                                        <input type="hidden" id="hidden_is_partner_snap" name="hidden_is_partner_snap" value="{{ $partner_has_merchant_branch->partner->is_snap_enabled }}">
                                                        @if ($errors->has("partner_id"))
                                                            <span class="invalid-feedback">{{ $errors->first("partner_id") }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2 mt-2">
                                                        <a href="{{ route('yukk_co.partner.create') }}" target="_blank" class="form-controljustify-content-center">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="partner_fee_id" class="col-lg-4 col-form-label @if ($errors->has("partner_fee_id")) text-danger @endif">@lang('cms.Partner Fee')</label>
                                                    <div class="col-lg-6">
                                                        <select id="partner_fee_id" name="partner_fee_id" class="form-control select2 @if ($errors->has("partner_fee_id")) is-invalid @endif" required>
                                                        </select>
                                                        @if ($errors->has("partner_fee_id"))
                                                            <span class="invalid-feedback">{{ $errors->first("partner_fee_id") }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2 mt-2">
                                                        <a href="{{ route('yukk_co.partner_fee.create') }}"
                                                           target="_blank" class="form-controljustify-content-center">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">@lang('cms.Event')</label>
                                                    <div class="col-lg-6">
                                                        <select id="event" name="event" class="form-control select2">
                                                            <option value="">Select Event...</option>
                                                            @foreach ($events as $event)
                                                                <option value="{{ $event->id }}" @if(old("event") == $event->id) selected @endif>{{ $event->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2 mt-2">
                                                        <a href="{{ route('yukk_co.event.create') }}" target="_blank" class="form-controljustify-content-center">
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
                                                    <label for="currency_type" class="col-lg-4 col-form-label @if ($errors->has("currency_type")) text-danger @endif">@lang('cms.Currency Type')</label>
                                                    <div class="col-lg-8">
                                                        <select id="currency_type" name="currency_type" class="form-control select2 @if ($errors->has("currency_type")) is-invalid @endif" required>
                                                            {{--<option value="YUKK_P_THEN_YUKK_E">COMBINE</option>--}}
                                                            <option value="YUKK_P_ONLY">YUKK CASH ONLY</option>
                                                            {{--<option value="YUKK_E_ONLY">YUKK POINT ONLY</option>--}}
                                                        </select>
                                                        @if ($errors->has("currency_type"))
                                                            <span class="invalid-feedback">{{ $errors->first("currency_type") }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="min_discount" class="col-lg-4 col-form-label @if ($errors->has("min_discount")) text-danger @endif">@lang('cms.Min Discount')</label>
                                                    <div class="col-lg-8">
                                                        <input type="number" class="form-control @if ($errors->has("min_discount")) is-invalid @endif" name="min_discount" id="min_discount" value="0">
                                                    </div>
                                                    @if ($errors->has("min_discount"))
                                                        <span class="invalid-feedback">{{ $errors->first("min_discount") }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="discount" class="col-lg-4 col-form-label @if ($errors->has("discount")) text-danger @endif">@lang('cms.Discount')</label>
                                                    <div class="col-lg-8">
                                                        <input type="number" class="form-control @if ($errors->has("discount")) is-invalid @endif" name="discount" id="discount" value="0">
                                                    </div>
                                                    @if ($errors->has("discount"))
                                                        <span class="invalid-feedback">{{ $errors->first("discount") }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="service_charge" class="col-lg-4 col-form-label @if ($errors->has("service_charge")) text-danger @endif">@lang('cms.Service Charge')</label>
                                                    <div class="col-lg-8">
                                                        <input type="number" class="form-control @if ($errors->has("service_charge")) is-invalid @endif" name="service_charge" id="service_charge" value="0">
                                                    </div>
                                                    @if ($errors->has("service_charge"))
                                                        <span class="invalid-feedback">{{ $errors->first("service_charge") }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label for="tax" class="col-lg-4 col-form-label">@lang('cms.Tax')</label>
                                                    <div class="col-lg-8">
                                                        <input type="number" class="form-control @if ($errors->has("tax")) is-invalid @endif" name="tax" id="tax" value="0">
                                                    </div>
                                                    @if ($errors->has("tax"))
                                                        <span class="invalid-feedback">{{ $errors->first("tax") }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($item->qr_type == 'b')
                                <div id="hidden_qris" hidden>
                                    <hr class="mx-3">

                                    <div class="card-body">
                                        <h5 class="title">@lang('cms.QRIS Dynamic')</h5>

                                        <br>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="grant_type" class="col-lg-4 col-form-label">@lang('cms.SNAP Implementation')</label>
                                                            <div class="col-lg-8">
                                                                <input class="form-control" id="snap_dynamic" name="snap_dynamic" value="SNAP" readonly>
                                                                <input class="form-control" id="non_snap_dynamic" name="non_snap_dynamic" value="NON SNAP" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="grant_type" class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                            <div class="col-lg-8">
                                                                <select id="grant_type" name="grant_type" class="form-control select2" required>
                                                                    <option value="NONE" @if(old("grant_type", "NONE") == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                                    <option value="CLIENT_ID_SECRET" @if(old("grant_type", "NONE") == "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                                                </select>
                                                            </div>
                                                            @if ($errors->has("grant_type"))
                                                                <span class="invalid-feedback">{{ $errors->first("grant_type") }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="grant_id_secret" class="w-100" hidden>
                                                        <div id="snap_0" class="w-100" hidden>
                                                            <div class="col-lg-12">
                                                                <div class="form-group row">
                                                                    <label for="snap_client_id_dynamic" class="col-lg-4 col-form-label @if ($errors->has("snap_client_id_dynamic")) text-danger @endif">@lang('cms.Client ID')</label>
                                                                    <div class="col-lg-4">
                                                                        <input type="text" readonly required name="snap_client_id_dynamic" id="snap_client_id_dynamic" class="form-control @if ($errors->has("snap_client_id_dynamic")) is-invalid @endif">
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <button class="btn btn-primary" type="button" id="btn-generate-client-id-dynamic">@lang("cms.Generate")</button>
                                                                        <button class="btn btn-success" type="button" id="btn-copy-client-id-dynamic">@lang("cms.Copy")</button>
                                                                    </div>
                                                                    @if ($errors->has("snap_client_id_dynamic"))
                                                                        <span class="invalid-feedback">{{ $errors->first("snap_client_id_dynamic") }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group row">
                                                                    <label for="snap_client_secret_dynamic" class="col-lg-4 col-form-label @if ($errors->has("snap_client_secret_dynamic")) text-danger @endif">@lang('cms.Client Secret')</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" required readonly name="snap_client_secret_dynamic" class="form-control @if ($errors->has("snap_client_secret_dynamic")) is-invalid @endif" id="snap_client_secret_dynamic">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <button class="btn btn-primary" type="button" id="btn-generate-client-secret-dynamic">@lang("cms.Generate")</button>
                                                                        <button class="btn btn-success" type="button" id="btn-copy-client-secret-dynamic">@lang("cms.Copy")</button>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has("snap_client_secret_dynamic"))
                                                                    <span class="invalid-feedback">{{ $errors->first("snap_client_secret_dynamic") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div id="snap_1" class="w-100" hidden>
                                                            <div class="col-lg-12">
                                                                <div class="form-group row">
                                                                    <label for="store_id_dynamic" class="col-lg-4 col-form-label @if ($errors->has("store_id_dynamic")) text-danger @endif">@lang('cms.Store ID')</label>
                                                                    <div class="col-lg-4">
                                                                        <input type="text" readonly name="store_id_dynamic" value="{{ old("store_id_dynamic") }}" class="form-control @if ($errors->has("store_id_dynamic")) is-invalid @endif" id="store_id_dynamic" required>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <button class="btn btn-primary" type="button" id="btn-generate-store-id-dynamic">@lang("cms.Generate")</button>
                                                                        <button class="btn btn-success" type="button" id="btn-copy-store-id-dynamic">@lang("cms.Copy")</button>
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
                                                            <label for="payment_notify_mode" class="col-lg-4 col-form-label">@lang('cms.Payment Notify Mode')</label>
                                                            <div class="col-lg-8">
                                                                <select id="payment_notify_mode" name="payment_notify_mode" class="form-control select2" required>
                                                                    <option value="NONE" @if(old("payment_notify_mode", "NONE") == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                                    <option value="WEBHOOK" @if(old("payment_notify_mode", "NONE") == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                                                    <option value="WEBHOOK_PG" @if(old("payment_notify_mode", "NONE") == "WEBHOOK_PG") selected @endif>@lang('cms.Webhook - YUKK PG')</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="is_payment_notify" class="w-100" hidden>
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <div class="col-lg-4">
                                                                    <div class="form-group row"style="
                                                                        margin-left: 1px;
                                                                    ">
                                                                        <label
                                                                            class="col-form-label @if ($errors->has('webhook_url')) text-danger @endif">@lang('cms.Webhook URL')
                                                                        </label>
                                                                        <a href="#" data-popup="tooltip"
                                                                            data-placement="top"
                                                                            data-original-title="Webhook URL is non-configurable for QRIS payment channel in Yukk PG">
                                                                            <span class="info-circle">i</span></a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-8">
                                                                    <input type="text" id="webhook_url" name="webhook_url" placeholder="https://example.com" value="{{ old('webhook_url') }}" class="form-control @if ($errors->has("webhook_url")) is-invalid @endif">
                                                                </div>
                                                                @if ($errors->has("webhook_url"))
                                                                    <span class="invalid-feedback">{{ $errors->first("webhook_url") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row mt-3">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Order Timeout (in secs)')</label>
                                                            <div class="col-lg-8">
                                                                <input class="form-control" id="partner_order_timeout" name="partner_order_timeout" value="1800" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Max Payment')</label>
                                                            <div class="col-lg-8">
                                                                <input type="number" class="form-control" id="partner_order_id_max_payment" name="partner_order_id_max_payment" value="1" required>
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
                                                                <input class="form-control" id="partner_order_id_prefix" name="partner_order_id_prefix" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Order ID Length')</label>
                                                            <div class="col-lg-8">
                                                                <input class="form-control" type="number" id="partner_order_id_length" name="partner_order_id_length" value="12" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="mx-3">

                                    <div class="card-body">
                                        <h5 class="title">
                                            @lang('cms.QRIS Static')
                                        </h5>

                                        <br>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="grant_type_static" class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                            <div class="col-lg-8">
                                                                <select id="grant_type_static" name="grant_type_static" class="form-control select2" required>
                                                                    <option value="NONE" @if(old("grant_type_static", "NONE") == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                                    <option value="CLIENT_ID_SECRET" @if(old("grant_type_static", "NONE") == "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                                                </select>
                                                            </div>
                                                            @if ($errors->has("grant_type_static"))
                                                                <span class="invalid-feedback">{{ $errors->first("grant_type_static") }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="grant_id_secret_static" class="w-100" hidden>
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label for="snap_client_id_static" class="col-lg-4 col-form-label @if($errors->has("snap_client_id_static")) text-danger @endif">@lang('cms.Client ID')</label>
                                                                <div class="col-lg-4">
                                                                    <input type="text" readonly name="snap_client_id_static" value="{{ old('snap_client_id_static') }}" class="form-control @if ($errors->has("snap_client_id_static")) is-invalid @endif" id="snap_client_id_static">
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary" type="button" id="btn-generate-client-id-static">@lang("cms.Generate")</button>
                                                                    <button class="btn btn-success" type="button" id="btn-copy-client-id-static">@lang("cms.Copy")</button>
                                                                </div>
                                                                @if ($errors->has("snap_client_id_static"))
                                                                    <span class="invalid-feedback">{{ $errors->first("snap_client_id_static") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label for="snap_client_secret_static" class="col-lg-4 col-form-label @if($errors->has("snap_client_secret_static")) text-danger @endif">@lang('cms.Client Secret')</label>
                                                                <div class="col-sm-4">
                                                                    <input type="text" readonly name="snap_client_secret_static" value="{{ old('snap_client_secret_static') }}" class="form-control @if ($errors->has("snap_client_secret_static")) is-invalid @endif" id="snap_client_secret_static">
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <button class="btn btn-primary" type="button" id="btn-generate-client-secret-static">@lang("cms.Generate")</button>
                                                                    <button class="btn btn-success" type="button" id="btn-copy-client-secret-static">@lang("cms.Copy")</button>
                                                                </div>
                                                                @if ($errors->has("snap_client_secret_static"))
                                                                    <span class="invalid-feedback">{{ $errors->first("snap_client_secret_static") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="payment_notify_mode_static" class="col-lg-4 col-form-label @if ($errors->has("payment_notify_mode_static")) text-danger @endif">@lang('cms.Payment Notify Mode')</label>
                                                            <div class="col-lg-8">
                                                                <select id="payment_notify_mode_static" name="payment_notify_mode_static" class="form-control select2 @if ($errors->has("payment_notify_mode_static")) is-invalid @endif" required>
                                                                    <option value="NONE" @if(old("payment_notify_mode_static", "NONE") == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                                    <option value="WEBHOOK" @if(old("payment_notify_mode_static", "NONE") == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                                                </select>
                                                            </div>
                                                            @if ($errors->has("payment_notify_mode_static"))
                                                                <span class="invalid-feedback">{{ $errors->first("payment_notify_mode_static") }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="is_payment_notify_static" class="w-100" hidden>
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label for="webhook_url_static" class="col-lg-4 col-form-label @if ($errors->has("webhook_url_static")) text-danger @endif">@lang('cms.Webhook URL')</label>
                                                                <div class="col-lg-8">
                                                                    <input type="text" id="webhook_url_static" name="webhook_url_static" placeholder="https://example.com" value="{{ old('webhook_url_static') }}" class="form-control @if ($errors->has("webhook_url_static")) is-invalid @endif">
                                                                </div>
                                                                @if ($errors->has("webhook_url_static"))
                                                                    <span class="invalid-feedback">{{ $errors->first("date") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($item->qr_type == 'd')
                                <div id="hidden_qris" hidden>
                                    <hr class="mx-3">

                                    <div class="card-body">
                                        <h5 class="title">@lang('cms.QRIS Dynamic')</h5>

                                        <br>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="grant_type" class="col-lg-4 col-form-label">@lang('cms.SNAP Implementation')</label>
                                                            <div class="col-lg-8">
                                                                <input class="form-control" id="snap_dynamic" name="snap_dynamic" value="SNAP" readonly>
                                                                <input class="form-control" id="non_snap_dynamic" name="non_snap_dynamic" value="NON SNAP" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="grant_type" class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                            <div class="col-lg-8">
                                                                <select id="grant_type" name="grant_type" class="form-control select2" required>
                                                                    <option value="NONE" @if(old("grant_type", "NONE") == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                                    <option value="CLIENT_ID_SECRET" @if(old("grant_type", "NONE") == "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                                                </select>
                                                            </div>
                                                            @if ($errors->has("grant_type"))
                                                                <span class="invalid-feedback">{{ $errors->first("grant_type") }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="grant_id_secret" class="w-100" hidden>
                                                        <div id="snap_0" class="w-100" hidden>
                                                            <div class="col-lg-12">
                                                                <div class="form-group row">
                                                                    <label for="snap_client_id_dynamic" class="col-lg-4 col-form-label @if ($errors->has("snap_client_id_dynamic")) text-danger @endif">@lang('cms.Client ID')</label>
                                                                    <div class="col-lg-4">
                                                                        <input type="text" readonly required name="snap_client_id_dynamic" id="snap_client_id_dynamic" class="form-control @if ($errors->has("snap_client_id_dynamic")) is-invalid @endif">
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <button class="btn btn-primary" type="button" id="btn-generate-client-id-dynamic">@lang("cms.Generate")</button>
                                                                        <button class="btn btn-success" type="button" id="btn-copy-client-id-dynamic">@lang("cms.Copy")</button>
                                                                    </div>
                                                                    @if ($errors->has("snap_client_id_dynamic"))
                                                                        <span class="invalid-feedback">{{ $errors->first("snap_client_id_dynamic") }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group row">
                                                                    <label for="snap_client_secret_dynamic" class="col-lg-4 col-form-label @if ($errors->has("snap_client_secret_dynamic")) text-danger @endif">@lang('cms.Client Secret')</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" required readonly name="snap_client_secret_dynamic" class="form-control @if ($errors->has("snap_client_secret_dynamic")) is-invalid @endif" id="snap_client_secret_dynamic">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <button class="btn btn-primary" type="button" id="btn-generate-client-secret-dynamic">@lang("cms.Generate")</button>
                                                                        <button class="btn btn-success" type="button" id="btn-copy-client-secret-dynamic">@lang("cms.Copy")</button>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has("snap_client_secret_dynamic"))
                                                                    <span class="invalid-feedback">{{ $errors->first("snap_client_secret_dynamic") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div id="snap_1" class="w-100" hidden>
                                                            <div class="col-lg-12">
                                                                <div class="form-group row">
                                                                    <label for="store_id_dynamic" class="col-lg-4 col-form-label @if ($errors->has("store_id_dynamic")) text-danger @endif">@lang('cms.Store ID')</label>
                                                                    <div class="col-lg-4">
                                                                        <input type="text" readonly name="store_id_dynamic" value="{{ old("store_id_dynamic") }}" class="form-control @if ($errors->has("store_id_dynamic")) is-invalid @endif" id="store_id_dynamic" required>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <button class="btn btn-primary" type="button" id="btn-generate-store-id-dynamic">@lang("cms.Generate")</button>
                                                                        <button class="btn btn-success" type="button" id="btn-copy-store-id-dynamic">@lang("cms.Copy")</button>
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
                                                            <label for="payment_notify_mode" class="col-lg-4 col-form-label">@lang('cms.Payment Notify Mode')</label>
                                                            <div class="col-lg-8">
                                                                <select id="payment_notify_mode" name="payment_notify_mode" class="form-control select2" required>
                                                                    <option value="NONE" @if(old("payment_notify_mode", "NONE") == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                                    <option value="WEBHOOK" @if(old("payment_notify_mode", "NONE") == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                                                    <option value="WEBHOOK_PG" @if(old("payment_notify_mode", "NONE") == "WEBHOOK_PG") selected @endif>@lang('cms.Webhook - YUKK PG')</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="is_payment_notify" class="w-100" hidden>
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <div class="col-lg-4">
                                                                    <div class="form-group row"style="
                                                                        margin-left: 1px;
                                                                    ">
                                                                        <label
                                                                            class="col-form-label @if ($errors->has('webhook_url')) text-danger @endif">@lang('cms.Webhook URL')
                                                                        </label>
                                                                        <a href="#" data-popup="tooltip"
                                                                            data-placement="top"
                                                                            data-original-title="Webhook URL is non-configurable for QRIS payment channel in Yukk PG">
                                                                            <span class="info-circle">i</span></a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-8">
                                                                    <input type="text" id="webhook_url" name="webhook_url" placeholder="https://example.com" value="{{ old('webhook_url') }}" class="form-control @if ($errors->has("webhook_url")) is-invalid @endif">
                                                                </div>
                                                                @if ($errors->has("webhook_url"))
                                                                    <span class="invalid-feedback">{{ $errors->first("webhook_url") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row mt-3">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Order Timeout (in secs)')</label>
                                                            <div class="col-lg-8">
                                                                <input class="form-control" id="partner_order_timeout" name="partner_order_timeout" value="1800" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Max Payment')</label>
                                                            <div class="col-lg-8">
                                                                <input type="number" class="form-control" id="partner_order_id_max_payment" name="partner_order_id_max_payment" value="1" required>
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
                                                                <input class="form-control" id="partner_order_id_prefix" name="partner_order_id_prefix" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label class="col-lg-4 col-form-label">@lang('cms.Order ID Length')</label>
                                                            <div class="col-lg-8">
                                                                <input class="form-control" type="number" id="partner_order_id_length" name="partner_order_id_length" value="12" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($item->qr_type == 's')
                                <div id="hidden_qris" hidden>
                                    <hr class="mx-3">

                                    <div class="card-body">
                                        <h5 class="title">@lang('cms.QRIS Static')</h5>

                                        <br>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="grant_type_static" class="col-lg-4 col-form-label">@lang('cms.Grant Type')</label>
                                                            <div class="col-lg-8">
                                                                <select id="grant_type_static" name="grant_type_static" class="form-control select2" required>
                                                                    <option value="NONE" @if(old("grant_type_static", "NONE") == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                                    <option value="CLIENT_ID_SECRET" @if(old("grant_type_static", "NONE") == "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                                                </select>
                                                            </div>
                                                            @if ($errors->has("grant_type_static"))
                                                                <span class="invalid-feedback">{{ $errors->first("grant_type_static") }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="grant_id_secret_static" class="w-100" hidden>
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label for="snap_client_id_static" class="col-lg-4 col-form-label @if($errors->has("snap_client_id_static")) text-danger @endif">@lang('cms.Client ID')</label>
                                                                <div class="col-lg-4">
                                                                    <input type="text" readonly name="snap_client_id_static" value="{{ old('snap_client_id_static') }}" class="form-control @if ($errors->has("snap_client_id_static")) is-invalid @endif" id="snap_client_id_static">
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button class="btn btn-primary" type="button" id="btn-generate-client-id-static">@lang("cms.Generate")</button>
                                                                    <button class="btn btn-success" type="button" id="btn-copy-client-id-static">@lang("cms.Copy")</button>
                                                                </div>
                                                                @if ($errors->has("snap_client_id_static"))
                                                                    <span class="invalid-feedback">{{ $errors->first("snap_client_id_static") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label for="snap_client_secret_static" class="col-lg-4 col-form-label @if($errors->has("snap_client_secret_static")) text-danger @endif">@lang('cms.Client Secret')</label>
                                                                <div class="col-sm-4">
                                                                    <input type="text" readonly name="snap_client_secret_static" value="{{ old('snap_client_secret_static') }}" class="form-control @if ($errors->has("snap_client_secret_static")) is-invalid @endif" id="snap_client_secret_static">
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <button class="btn btn-primary" type="button" id="btn-generate-client-secret-static">@lang("cms.Generate")</button>
                                                                    <button class="btn btn-success" type="button" id="btn-copy-client-secret-static">@lang("cms.Copy")</button>
                                                                </div>
                                                                @if ($errors->has("snap_client_secret_static"))
                                                                    <span class="invalid-feedback">{{ $errors->first("snap_client_secret_static") }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="payment_notify_mode_static" class="col-lg-4 col-form-label @if ($errors->has("payment_notify_mode_static")) text-danger @endif">@lang('cms.Payment Notify Mode')</label>
                                                            <div class="col-lg-8">
                                                                <select id="payment_notify_mode_static" name="payment_notify_mode_static" class="form-control select2 @if ($errors->has("payment_notify_mode_static")) is-invalid @endif" required>
                                                                    <option value="NONE" @if(old("payment_notify_mode_static", "NONE") == "NONE") selected @endif>@lang('cms.NONE')</option>
                                                                    <option value="WEBHOOK" @if(old("payment_notify_mode_static", "NONE") == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                                                </select>
                                                            </div>
                                                            @if ($errors->has("payment_notify_mode_static"))
                                                                <span class="invalid-feedback">{{ $errors->first("payment_notify_mode_static") }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="is_payment_notify_static" class="w-100" hidden>
                                                        <div class="col-lg-12">
                                                            <div class="form-group row">
                                                                <label for="webhook_url_static" class="col-lg-4 col-form-label @if ($errors->has("webhook_url_static")) text-danger @endif">@lang('cms.Webhook URL')</label>
                                                                <div class="col-lg-8">
                                                                    <input type="text" id="webhook_url_static" name="webhook_url_static" placeholder="https://example.com" value="{{ old('webhook_url_static') }}" class="form-control @if ($errors->has("webhook_url_static")) is-invalid @endif">
                                                                </div>
                                                                @if ($errors->has("webhook_url_static"))
                                                                    <span class="invalid-feedback">{{ $errors->first("date") }}</span>
                                                                @endif
                                                            </div>
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
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-5">
            <a href="{{ route('yukk_co.qris_setting.list') }}" class="form-group col-1 btn-block mt-2">
                @lang('cms.Back')
            </a>
            <button id="btn-submit" type="submit" class="form-group btn btn-primary col-2 btn-submit mr-4">
                @lang('cms.Save and Continue')
            </button>
        </div>
    </form>
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

            $("#merchant_id").change(function() {
                var merchantId = $("#merchant_id").val();
                $.ajax({
                    url: "{{ route('json.merchant.branches') }}",
                    type: "GET",
                    data: {
                        merchant_id: merchantId,
                    },
                    dataType: 'json',
                    success: function(result) {
                        $("#merchant_branch_id").html('');
                        $("#merchant_branch_id").append(
                            '<option value="">Select Merchant Branch</option>');
                        $.each(result, function(key, value) {
                            $("#merchant_branch_id").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
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

            $("#grant_type_static").change(function(e) {
                if (document.getElementById("grant_type_static").value == 'CLIENT_ID_SECRET') {
                    $("#grant_id_secret_static").removeAttr("hidden");
                    $("#grant_id_secret_static input").removeAttr("disabled");
                    $("#grant_id_secret_static textarea").removeAttr("disabled");
                } else {
                    $("#grant_id_secret_static").attr("hidden", "hidden");
                    $("#grant_id_secret_static input").attr("disabled", "disabled");
                    $("#grant_id_secret_static textarea").attr("disabled", "disabled");
                }
            });
            $("#grant_type_static").change();

            function generateClientId(length = 24) {
                var result           = [];
                var characters       = '0123456789';
                var charactersLength = characters.length;
                for ( var i = 0; i < length; i++ ) {
                    result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
                }
                return result.join('');
            }
            var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
            function generateClientSecret(length = 24) {
                var result           = [];
                var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                var charactersLength = characters.length;
                for ( var i = 0; i < length; i++ ) {
                    result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
                }
                return Base64.encode(result.join(''));
            }

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

            $("#btn-copy-client-id-dynamic").click(function() {
                // Get the text field
                var copyText = $("#snap_client_id_dynamic");

                // Select the text field
                copyText.select();
                //copyText.setSelectionRange(0, 99999); // For mobile devices

                // Copy the text inside the text field
                navigator.clipboard.writeText(copyText.val());
            });

            $("#btn-copy-client-secret-dynamic").click(function() {
                // Get the text field
                var copyText = $("#snap_client_secret_dynamic");

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

            $("#payment_notify_mode").change(function(e) {
                if (document.getElementById("payment_notify_mode").value == 'WEBHOOK') {
                    document.getElementById("is_payment_notify").removeAttribute("hidden");
                    var inputs = document.querySelectorAll("#is_payment_notify input");
                    inputs.forEach(function(input) {
                        input.removeAttribute("disabled");
                        input.removeAttribute("readonly");
                        input.value = '';
                    });
                } else if (document.getElementById("payment_notify_mode").value == 'WEBHOOK_PG') {
                    var webhookPg = "{{ config('payment_gateway.urls.webhook') }}";

                    var inputs = document.querySelectorAll("#is_payment_notify input");

                    document.getElementById("is_payment_notify").removeAttribute("hidden");
                    inputs.forEach(function(input) {
                        input.removeAttribute("disabled");
                        input.value = '';
                    });
                    setTimeout(function() {
                        inputs.forEach(function(input) {
                            input.value = webhookPg;  // Set nilai baru
                            input.setAttribute("readonly", true);  // Jadikan readonly
                        });
                    }, 100);
                } else {
                    $("#is_payment_notify").attr("hidden", "hidden");
                    $("#is_payment_notify input").attr("disabled", "disabled");
                }
            });
            $("#payment_notify_mode").change();

            $("#btn-generate-client-id-static").click(function(e) {
                var clientId = generateClientId();
                $("#snap_client_id_static").val(clientId);
                e.preventDefault();
            });

            $("#btn-generate-client-secret-static").click(function(e) {
                var clientSecret = generateClientSecret();
                $("#snap_client_secret_static").val(clientSecret);
                e.preventDefault();
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
        });
    </script>
@endsection

@section('post_scripts')
    <script>
        $(document).ready(function() {
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

            $('#customer_id').select2({
                ajax: {
                    url: "{{ route('json.customer.select') }}",
                    type: "GET",
                    delay: 500,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            search: params.term // search term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                },
                placeholder: "Select Beneficiary",

            });

            $('#partner_id').change(function() {
                var data = $("#partner_id").val();
                var snap_enable = $('#hidden_is_partner_snap').val();

                var partner_snap = $('#hidden_is_partner_snap').val(snap_enable);

                if (partner_snap.val() == 0){
                    $('#snap_0').removeAttr('hidden');
                    $('#snap_1').attr('hidden', 'hidden');

                    $('#non_snap_dynamic').removeAttr('hidden');
                    $('#snap_dynamic').attr('hidden', 'hidden');
                }else{
                    $('#snap_0').attr('hidden', 'hidden');
                    $('#snap_1').removeAttr('hidden');

                    $('#snap_dynamic').removeAttr('hidden');
                    $('#non_snap_dynamic').attr('hidden', 'hidden');
                }

                $('#hidden_qris').removeAttr('hidden');
            }).change();
        });
    </script>
@endsection
