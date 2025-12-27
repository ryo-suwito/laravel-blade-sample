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
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.EDC Detail")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.edc.list") }}" class="breadcrumb-item">@lang("cms.EDC List")</a>
                    <span class="breadcrumb-item active">@lang("cms.EDC Detail")</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="card-body">
                <form id="mainForm" method="POST" action="{{ route("yukk_co.edc.update", $edc->id) }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.IMEI")</label>
                        <div class="col-sm-4">
                            <input type="text" name="imei" value="{{$edc->imei}}"  class="form-control" readonly>
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.Type")</label>
                        <div class="col-sm-4">
                            <input type="text" id="type" name="type" value="{{$edc->type}}"  class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Beneficiary")</label>
                        <div class="col-sm-4">
                            <select id="customer_id" name="customer_id" class="form-control select2" required>
                                <option value="{{ $edc->customer->id }}">{{ $edc->customer->name }}</option>
                            </select>
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.Merchant Branch")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ $edc->branch->name }}"  class="form-control" readonly>
                            <input type="hidden" name="merchant_branch_id" value="{{ $edc->branch->id }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.YUKK Co Portion Type")</label>
                        <div class="col-sm-4">
                            <input type="text" id="title" value="{{ $edc->yukk_co_portion_type}}" class="form-control" readonly>
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.YUKK Co Portion")</label>
                        <div class="col-sm-4">
                            <input type="text" id="title" value="{{ round($edc->yukk_co_portion, 2)}}" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Min Discount")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ round($edc->min_discount, 2)}}%" class="form-control" readonly>
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.Discount")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ round($edc->discount, 2) }}%" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Service Charge")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ round($edc->service, 2) }}%" class="form-control" readonly>
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.Tax")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ ceil($edc->tax * 100) / 100 }}%" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Currency Type")</label>
                        <div class="col-sm-4">
                            @if($edc->currency_type == "YUKK_P_THEN_YUKK_E")
                                <input type="text" value="COMBINE" class="form-control" readonly autofocus>
                            @elseif($edc->currency_type == "YUKK_E_THEN_YUKK_P")
                                <input type="text" class="form-control" readonly autofocus>
                            @elseif($edc->currency_type == "YUKK_P_ONLY")
                                <input type="text" value="YUKK CASH ONLY" class="form-control" readonly autofocus>
                            @elseif($edc->currency_type == "YUKK_E_ONLY")
                                <input type="text" value="YUKK POINTS ONLY" class="form-control" readonly autofocus>
                            @else
                                <input type="text" value="YUKK POINTS ONLY" class="form-control" readonly autofocus>
                            @endif
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.Created At")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{@$edc->created_at}}" class="form-control" readonly autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Payment Mode")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ $edc->payment_mode }}" class="form-control" readonly>
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.Status")</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="status" id="status">
                                <option value="0" @if(@$edc->active == 0) selected @endif>@lang("cms.Inactive")</option>
                                <option value="1" @if(@$edc->active == 1) selected @endif>@lang("cms.Active")</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.isQRIS?")</label>
                        <div class="col-sm-4 mt-2">
                            @if($edc->is_qris)
                                <b class="icon-check text-success"></b>
                            @else
                                <b class="icon-cross text-danger"></b>
                            @endif
                        </div>
                        @if($edc->is_qris == 1)
                            <label class="col-form-label col-sm-2">@lang("cms.NMID")</label>
                            <div class="col-sm-4">
                                <input type="text" value="{{ $edc->nmid_pten }}" class="form-control" readonly>
                            </div>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Branch Name (PTEN)")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ $edc->merchant_branch_name_pten }}" class="form-control" readonly>
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.City (PTEN)")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ $edc->city_name_pten }}" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.MPAN")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ $edc->mpan }}" class="form-control" readonly>
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.MID")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ $edc->mid }}" class="form-control" readonly>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Partner")</label>
                        <div class="col-sm-4">
                            <input type="text" value="{{ @$edc->partner->name }}" class="form-control" readonly>
                            <input type="hidden" id="hidden_is_partner_snap" name="hidden_is_partner_snap" value="{{ @$partner_has_merchant_branch->partner->is_snap_enabled ? : '' }}">
                        </div>
                        <label class="col-form-label col-sm-2">@lang("cms.Partner Fee")</label>
                        <div class="col-sm-4 d-flex">
                            <select class="form-control select2" name="partner_fee_id" id="partner_fee_id">
                                @foreach ($partner_fee_list as $partner_fee)
                                    <option value="{{ $partner_fee->id }}" @if($edc->partner_fee_id == $partner_fee->id) selected @endif>{{ $partner_fee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Event")</label>
                        <div class="col-sm-4">
                            <select class="form-control select2 col-sm-3" id="name_id" name="event_id">
                                <option value="0">@lang("cms.NON Event")</option>
                                @foreach ($event_list as $event)
                                    <option value="{{ $event->id }}" @if($edc->event_id == $event->id) selected @endif>{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>

                    @php($is_snap_enabled = (isset($partner_has_merchant_branch) && isset($partner_has_merchant_branch->partner) && $partner_has_merchant_branch->partner->is_snap_enabled))
                    <input id="snap_checker" name="snap_checker" type="hidden" @if($is_snap_enabled) value="true" @else value="false" @endif>
                    @if($edc->type == 'STICKER')
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang('cms.Grant Type')</label>
                            <div class="col-lg-4">
                                <select id="grant_type" name="grant_type" class="form-control select2" required>
                                    <option value="NONE" @if($edc->grant_type === "NONE") selected @endif>@lang('cms.NONE')</option>
                                    <option value="CLIENT_ID_SECRET" @if($edc->grant_type === "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                </select>
                            </div>
                        </div>
                        <div id="grant_id_secret" class="w-100" hidden>
                            <div class="w-100">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="snap_client_id" class="col-lg-4 col-form-label @if($errors->has("snap_client_id")) text-danger @endif">@lang('cms.Client ID')</label>
                                        <div class="col-lg-4">
                                            <input type="text" readonly name="snap_client_id" value="{{ $edc->client_id }}" class="form-control @if ($errors->has("snap_client_id")) is-invalid @endif" id="snap_client_id">
                                        </div>
                                        <div class="col-lg-4">
                                            <button class="btn btn-primary" type="button" id="btn-generate-client-id">@lang("cms.Generate")</button>
                                            <button class="btn btn-success" type="button" id="btn-copy-client-id">@lang("cms.Copy")</button>
                                        </div>
                                        @if ($errors->has("snap_client_id"))
                                            <span class="invalid-feedback">{{ $errors->first("snap_client_id") }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="snap_client_secret" class="col-lg-4 col-form-label @if($errors->has("snap_client_secret")) text-danger @endif">@lang('cms.Client Secret')</label>
                                        <div class="col-sm-4">
                                            <input type="text" readonly name="snap_client_secret" value="{{ $edc->client_secret }}" class="form-control @if ($errors->has("snap_client_secret")) is-invalid @endif" id="snap_client_secret">
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-primary" type="button" id="btn-generate-client-secret">@lang("cms.Generate")</button>
                                            <button class="btn btn-success" type="button" id="btn-copy-client-secret">@lang("cms.Copy")</button>
                                        </div>
                                        @if ($errors->has("snap_client_secret"))
                                            <span class="invalid-feedback">{{ $errors->first("snap_client_secret") }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="payment_notify_mode" class="col-lg-2 col-form-label">@lang('cms.Webhook')</label>
                            <div class="col-lg-4">
                                <select id="payment_notify_mode" name="payment_notify_mode" class="form-control select2" required>
                                    <option value="NONE" @if($edc->payment_notify_mode == "NONE") selected @endif>@lang('cms.NONE')</option>
                                    <option value="WEBHOOK" @if($edc->payment_notify_mode == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                                </select>
                            </div>
                        </div>
                        <div id="is_payment_notify" class="form-group row" hidden>
                            <label for="webhook_url" class="col-lg-2">@lang('cms.Webhook URL')</label>
                            <div class="col-lg-4">
                                <input type="text" id="webhook_url" name="webhook_url" placeholder="https://example.com" class="form-control @if ($errors->has("webhook_url")) is-invalid @endif" value="{{ $edc->webhook_url }}">
                                @if ($errors->has("webhook_url"))
                                    <span class="invalid-feedback">{{ $errors->first("webhook_url") }}</span>
                                @endif
                            </div>
                        </div>
                    @elseif($edc->type == 'QRIS_DYNAMIC')
                        @if($partner_login)
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang('cms.Grant Type')</label>
                                <div class="col-lg-4">
                                    <select id="grant_type" name="grant_type" class="form-control select2" required>
                                        <option value="NONE" @if($partner_login->grant_type === "NONE") selected @endif>@lang('cms.NONE')</option>
                                        <option value="CLIENT_ID_SECRET" @if($partner_login->grant_type === "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                                    </select>
                                </div>
                            </div>
                            <div id="grant_id_secret" class="w-100" hidden>
                                <div id="snap_0" class="w-100" hidden>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <label for="snap_client_id" class="col-lg-4 col-form-label @if($errors->has("snap_client_id")) text-danger @endif">@lang('cms.Client ID')</label>
                                            <div class="col-lg-4">
                                                <input type="text" readonly name="snap_client_id" value="{{ $partner_login->username }}" class="form-control @if ($errors->has("snap_client_id")) is-invalid @endif" id="snap_client_id">
                                            </div>
                                            <div class="col-lg-4">
                                                <button class="btn btn-primary" type="button" id="btn-generate-client-id">@lang("cms.Generate")</button>
                                                <button class="btn btn-success" type="button" id="btn-copy-client-id">@lang("cms.Copy")</button>
                                            </div>
                                            @if ($errors->has("snap_client_id"))
                                                <span class="invalid-feedback">{{ $errors->first("snap_client_id") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <label for="snap_client_secret" class="col-lg-4 col-form-label @if($errors->has("snap_client_secret")) text-danger @endif">@lang('cms.Client Secret')</label>
                                            <div class="col-sm-4">
                                                <input type="text" readonly name="snap_client_secret" value="{{ $partner_login->password }}" class="form-control @if ($errors->has("snap_client_secret")) is-invalid @endif" id="snap_client_secret">
                                            </div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-primary" type="button" id="btn-generate-client-secret">@lang("cms.Generate")</button>
                                                <button class="btn btn-success" type="button" id="btn-copy-client-secret">@lang("cms.Copy")</button>
                                            </div>
                                            @if ($errors->has("snap_client_secret"))
                                                <span class="invalid-feedback">{{ $errors->first("snap_client_secret") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="snap_1" class="w-100" hidden>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <label for="store_id_dynamic" class="col-lg-4 col-form-label">@lang('cms.Store ID')</label>
                                            <div class="col-lg-4">
                                                <input type="text" readonly id="store_id" name="store_id" value="{{ $partner_login->username }}" class="form-control">
                                            </div>
                                            <div class="col-lg-4">
                                                <button class="btn btn-primary" type="button" id="btn-generate-store-id">@lang("cms.Generate")</button>
                                                <button class="btn btn-success" type="button" id="btn-copy-store-id">@lang("cms.Copy")</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang('cms.Webhook')</label>
                                <div class="col-lg-4">
                                    <select id="payment_notify_mode" name="payment_notify_mode" class="form-control select2" required>
                                        <option value="NONE" @if($partner_login->payment_notify_mode == "NONE") selected @endif>@lang('cms.NONE')</option>
                                        <option value="WEBHOOK" @if($partner_login->payment_notify_mode == "WEBHOOK" && $partner_login->is_payment_gateway == 0) selected @endif>@lang('cms.Webhook')</option>
                                        <option value="WEBHOOK_PG" @if($partner_login->payment_notify_mode == "WEBHOOK" && $partner_login->is_payment_gateway == 1) selected @endif>@lang('cms.Webhook - YUKK PG')</option>
                                    </select>
                                </div>
                            </div>
                            <div id="is_payment_notify" class="form-group row" hidden>
                                <div class="col-lg-2">
                                    <div class="form-group row"style="
                                        margin-left: 1px;
                                    ">
                                        <label for="webhook_url" style="
                                        margin-top: 5px;
                                    ">@lang('cms.Webhook URL')
                                        </label>
                                        <a href="#" data-popup="tooltip"
                                            data-placement="top"
                                            data-original-title="Webhook URL is non-configurable for QRIS payment channel in Yukk PG"
                                            class="info-pg">
                                            <span class="info-circle">i</span></a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="webhook_url" name="webhook_url" placeholder="https://example.com" class="form-control @if ($errors->has("webhook_url")) is-invalid @endif" value="{{ $partner_login->webhook_url }}">
                                    @if ($errors->has("webhook_url"))
                                        <span class="invalid-feedback">{{ $errors->first("webhook_url") }}</span>
                                    @endif
                                </div>
                            </div>
                            <input name="partner_login_id" value="{{ $partner_login->id }}" hidden>
                        @else
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang('cms.Grant Type')</label>
                                <div class="col-lg-4">
                                    <select id="grant_type" name="grant_type" class="form-control select2" required>
                                        <option value="NONE" selected>@lang('cms.NONE')</option>
                                        <option value="CLIENT_ID_SECRET">@lang('cms.CLIENT_ID_SECRET')</option>
                                    </select>
                                </div>
                            </div>
                            <div id="grant_id_secret" class="w-100" hidden>
                                <div id="snap_0" class="w-100" hidden>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <label for="snap_client_id" class="col-lg-4 col-form-label @if($errors->has("snap_client_id")) text-danger @endif">@lang('cms.Client ID')</label>
                                            <div class="col-lg-4">
                                                <input type="text" readonly name="snap_client_id" class="form-control @if ($errors->has("snap_client_id")) is-invalid @endif" id="snap_client_id">
                                            </div>
                                            <div class="col-lg-4">
                                                <button class="btn btn-primary" type="button" id="btn-generate-client-id">@lang("cms.Generate")</button>
                                                <button class="btn btn-success" type="button" id="btn-copy-client-id">@lang("cms.Copy")</button>
                                            </div>
                                            @if ($errors->has("snap_client_id"))
                                                <span class="invalid-feedback">{{ $errors->first("snap_client_id") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <label for="snap_client_secret" class="col-lg-4 col-form-label @if($errors->has("snap_client_secret")) text-danger @endif">@lang('cms.Client Secret')</label>
                                            <div class="col-sm-4">
                                                <input type="text" readonly name="snap_client_secret" class="form-control @if ($errors->has("snap_client_secret")) is-invalid @endif" id="snap_client_secret">
                                            </div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-primary" type="button" id="btn-generate-client-secret">@lang("cms.Generate")</button>
                                                <button class="btn btn-success" type="button" id="btn-copy-client-secret">@lang("cms.Copy")</button>
                                            </div>
                                            @if ($errors->has("snap_client_secret"))
                                                <span class="invalid-feedback">{{ $errors->first("snap_client_secret") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="snap_1" class="w-100" hidden>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <label for="store_id_dynamic" class="col-lg-4 col-form-label">@lang('cms.Store ID')</label>
                                            <div class="col-lg-4">
                                                <input type="text" readonly id="store_id" name="store_id" class="form-control">
                                            </div>
                                            <div class="col-lg-4">
                                                <button class="btn btn-primary" type="button" id="btn-generate-store-id">@lang("cms.Generate")</button>
                                                <button class="btn btn-success" type="button" id="btn-copy-store-id">@lang("cms.Copy")</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang('cms.Webhook')</label>
                                <div class="col-lg-4">
                                    <select id="payment_notify_mode" name="payment_notify_mode" class="form-control select2" required>
                                        <option value="NONE" selected>@lang('cms.NONE')</option>
                                        <option value="WEBHOOK">@lang('cms.Webhook')</option>
                                        <option value="WEBHOOK_PG">@lang('cms.Webhook - YUKK PG')</option>
                                    </select>
                                </div>
                            </div>
                            <div id="is_payment_notify" class="form-group row" hidden>
                                <div class="col-lg-2">
                                    <div class="form-group row"style="
                                        margin-left: 1px;
                                    ">
                                        <label for="webhook_url" style="
                                        margin-top: 5px;
                                    ">@lang('cms.Webhook URL')
                                        </label>
                                        <a href="#" data-popup="tooltip"
                                            data-placement="top"
                                            data-original-title="Webhook URL is non-configurable for QRIS payment channel in Yukk PG"
                                            class="info-pg">
                                            <span class="info-circle">i</span></a>
                                    </div>
                                </div>
                                <label for="webhook_url" class="col-lg-2">@lang('cms.Webhook URL')</label>
                                <div class="col-lg-4">
                                    <input type="text" id="webhook_url" name="webhook_url" placeholder="https://example.com" class="form-control @if ($errors->has("webhook_url")) is-invalid @endif">
                                    @if ($errors->has("webhook_url"))
                                        <span class="invalid-feedback">{{ $errors->first("webhook_url") }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                    <hr>

                    <div class="d-flex justify-content-center mb-5">
                        <div class="btn-group btn-block col-6 justify-content-center position-static">
                            <button class="btn btn-primary col-4" id="btn-submit" type="submit" data-toggle="tooltip" data-placement="top"  id="btn-submit" title="Submit immediately">Submit</button>
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split col-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu" style="">
                                <a class="dropdown-item" href="#" id="scheduleApply">Schedule Apply</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="scheduleModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Schedule Apply</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="scheduleDatePicker">Schedule Apply</label>
            <div class="d-flex align-items-center">
                <input type="text" id="scheduleDatePicker" class="form-control mr-2" placeholder="Select Date">
                <input type="text" id="fixedTimeDisplay" class="form-control" value='' readonly>
            </div>
                <p class="form-text"><i>Data changes will be effective on the selected date.</i></p>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmScheduleBtn">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog  modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Schedule Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="confirmationMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmationOkBtn">OK</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var partnerFee = $('#partner_fee_id').val();
            var partner = $('#partner_id').val();
            var benef= $('#customer_id').val();
            var timeThreshold = '{{ $time_threshold }}';
            var timeMessage = '{{ $time_message }}'; 
            var timeThresholdBenef = '{{ $time_threshold_benef }}';
            var timeMessageBenef = '{{ $time_message_benef }}';

            // Check if timeThreshold is empty or invalid
            if (!timeThreshold || !/^\d{2}:\d{2}$/.test(timeThreshold)) {
                alert(timeMessage || 'Invalid time threshold format. Ask admin to provide a valid "HH:MM" format.');
                $('.dropdown-item:contains("Schedule Apply")').addClass('disabled').attr('title', timeMessage || 'Time threshold is missing or invalid.');
                return; 
            }
            if (!timeThresholdBenef || !/^\d{2}:\d{2}$/.test(timeThresholdBenef)) {
                alert(timeMessageBenef || 'Invalid time threshold format. Ask admin to provide a valid "HH:MM" format.');
                $('.dropdown-item:contains("Schedule Apply")').addClass('disabled').attr('title', timeMessageBenef || 'Time threshold is missing or invalid.');
                return; 
            }
            function enableScheduleButton() {
                let cbenef = $('#customer_id').val();
                let cpartnerFee = $('#partner_fee_id').val();
                let cpartner = $('#partner_id').val();
                let hour, minute;
                
                if (cpartner !== partner || cpartnerFee !== partnerFee) {
                    $('#fixedTimeDisplay').val(timeThreshold);
                    $('.dropdown-toggle').prop('disabled', false);
                    [hour, minute] = timeThreshold.split(':').map(Number);
                } else if (cbenef !== benef) {
                    $('#fixedTimeDisplay').val(timeThresholdBenef);
                    $('.dropdown-toggle').prop('disabled', false);
                    [hour, minute] = timeThresholdBenef.split(':').map(Number);
                } else {
                    // If no changes, disable the dropdown
                    $('.dropdown-toggle').prop('disabled', true);
                    return;
                }
                updateDatePickerThreshold(hour, minute)
            }

            $('.dropdown-toggle').prop('disabled', true);

            $("select[name='partner_id'], select[name='partner_fee_id'], select[name='customer_id']").on('change', function() {
                enableScheduleButton();
            });


            function checkTimeAndDisableButtons() {
                const [hour, minute] = timeThreshold.split(':').map(Number);
                const [hourBenef, minuteBenef] = timeThresholdBenef.split(':').map(Number);
                let currentTime = moment();
                let disableStartTime = moment().set({ hour: hour, minute: minute });
                let disableEndTime = disableStartTime.clone().add(10, 'minutes');
                let disableStartTimeBenef = moment().set({ hour: hour, minute: minute });
                let disableEndTimeBenef = disableStartTimeBenef.clone().add(10, 'minutes');

                if (currentTime.isBetween(disableStartTime, disableEndTime)) {
                    $('#btn-submit').prop('disabled', true).attr('title', `Cannot submit until ${disableEndTime.format('HH:mm')}, data is currently locked.`);
                    $('.dropdown-item:contains("Schedule Apply")').addClass('disabled').attr('title', `Cannot schedule until ${disableEndTime.format('HH:mm')}, data is currently locked.`);
                } else if (currentTime.isBetween(disableStartTimeBenef, disableEndTimeBenef)) {
                    $('#btn-submit').prop('disabled', true).attr('title', `Cannot submit until ${disableEndTimeBenef.format('HH:mm')}, data is currently locked.`);
                    $('.dropdown-item:contains("Schedule Apply")').addClass('disabled').attr('title', `Cannot schedule until ${disableEndTimeBenef.format('HH:mm')}, data is currently locked.`);
                }else {
                    $('#btn-submit').prop('disabled', false).attr('title', 'Submit immediately');
                    $('.dropdown-item:contains("Schedule Apply")').removeClass('disabled').attr('title', '');
                }
            }

            checkTimeAndDisableButtons();

            setInterval(checkTimeAndDisableButtons, 60000);

            var snap_checker = $('#snap_checker').val();
            var type = document.getElementById('type').value;
            if(type !== 'STICKER'){
                if (snap_checker == 'false'){
                $('#snap_0').removeAttr('hidden');
                $("#snap_0 input").removeAttr("disabled");

                $('#snap_1').attr('hidden', 'hidden');
                $('#snap_1 input').attr("disabled", "disabled");
            }else{
                $('#snap_0').attr('hidden', 'hidden');
                $('#snap_0 input').attr("disabled", "disabled");

                $('#snap_1').removeAttr('hidden');
                $("#snap_1 input").removeAttr("disabled");
            };
            }

            $('.dropdown-item').click(function() {
                $('#scheduleModal').modal('show');
            });

            $('#confirmScheduleBtn').click(function() {
                const selectedDate = $('#scheduleDatePicker').val();
                const fixedTime = $('#fixedTimeDisplay').val()
                if (selectedDate) {
                    $('#scheduleModal').modal('hide');
                    
                    // Add the selected date and time to hidden form inputs
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'schedule_date',
                        value: selectedDate
                    }).appendTo('#mainForm');

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'schedule_time',
                        value: fixedTime
                    }).appendTo('#mainForm');

                    $('#scheduleModal').modal('hide');
                    $('#mainForm').submit();
                } else {
                    alert("Please select a valid date.");
                }
            });

            var notify_mode = "{{ @$partner_login->payment_notify_mode }}"; 
            var is_pg = "{{ @$partner_login->is_payment_gateway }}";
            if(notify_mode == 'WEBHOOK' && is_pg == 1){
                $("#snap_0").hide();
            }else{
                $("#snap_0").show();
            }
        });

        $(document).on('select2:open', () => {
            document.querySelector('.select2-container--open .select2-search__field').focus();
        });

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

        $("#btn-generate-client-id").click(function(e) {
            var clientId = generateClientId();
            $("#snap_client_id").val(clientId);
            e.preventDefault();
        });

        $("#btn-generate-client-secret").click(function(e) {
            var clientSecret = generateClientSecret();
            $("#snap_client_secret").val(clientSecret);
            e.preventDefault();
        });

        $("#btn-copy-client-id").click(function() {
            // Get the text field
            var copyText = $("#snap_client_id");

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $("#btn-copy-client-secret").click(function() {
            // Get the text field
            var copyText = $("#snap_client_secret");

            // Select the text field
            copyText.select();
            //copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.val());
        });

        $("#btn-generate-store-id").click(function(e) {
            var clientId = generateClientId();
            $("#store_id").val(clientId);
            e.preventDefault();
        });

        $("#btn-copy-store-id").click(function() {
            // Get the text field
            var copyText = $("#store_id");

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

        $("#payment_notify_mode").change(function(e) {
            if (document.getElementById("payment_notify_mode").value == 'WEBHOOK') {
                const webhookUrl = $("#webhook_url").attr("value");
                document.getElementById("is_payment_notify").removeAttribute("hidden");
                var inputs = document.querySelectorAll("#is_payment_notify input");
                inputs.forEach(function(input) {
                    input.removeAttribute("disabled");
                    input.removeAttribute("readonly");
                    input.value = '';
                });
                $("#webhook_url")
                    .removeAttr("disabled")
                    .removeAttr("readonly")
                    .val(webhookUrl);
                $(".info-pg").hide();
                $("#snap_0").show();
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
                $(".info-pg").show();
                $("#snap_0").hide();
            } else {
                $("#is_payment_notify").attr("hidden", "hidden");
                $("#is_payment_notify input").attr("disabled", "disabled");
            }
        });
        $("#payment_notify_mode").change();

        function updateDatePickerThreshold(thresholdHour, thresholdMinute) {
            // Validate input parameters
            if (typeof thresholdHour !== 'number' || thresholdHour < 0 || thresholdHour > 23) return;
            if (typeof thresholdMinute !== 'number' || thresholdMinute < 0 || thresholdMinute > 59) return;

            // Calculate time comparison
            const now = moment();
            const thresholdTime = moment().set({
                hour: thresholdHour,
                minute: thresholdMinute,
                second: 0,
                millisecond: 0
            });

            const isPastThreshold = now.isAfter(thresholdTime);
            
            // Determine new date constraints
            const newMinDate = isPastThreshold 
                ? moment().add(1, 'days').startOf('day') 
                : moment().startOf('day');
            
            const newStartDate = isPastThreshold 
                ? moment().add(1, 'days') 
                : moment();

            // Update date picker instance
            const datePicker = $('#scheduleDatePicker');
            
            // Check current value validity
            const currentDate = moment(datePicker.val(), 'YYYY-MM-DD');
            if (!currentDate.isValid() || currentDate.isBefore(newMinDate)) {
                datePicker.val(newStartDate.format('YYYY-MM-DD'));
            }

            // Reinitialize the date picker
            if (datePicker.data('daterangepicker')) {
                datePicker.data('daterangepicker').remove();
            }

            datePicker.daterangepicker({
                singleDatePicker: true,
                timePicker: false,
                autoUpdateInput: true,
                locale: { format: 'YYYY-MM-DD' },
                minDate: newMinDate,
                startDate: newStartDate
            });

            datePicker.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
        }
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

            $('#partner_id').change(function() {
                var snap_enable = $('#hidden_is_partner_snap').val();
                var partner_snap = $('#hidden_is_partner_snap').val(snap_enable);

                if (partner_snap.val() == '0'){
                    $('#snap_0').removeAttr('hidden');
                    $("#snap_0 input").removeAttr("disabled");

                    $('#snap_1').attr('hidden', 'hidden');
                    $('#snap_1 input').attr("disabled", "disabled");
                }else{
                    $('#snap_0').attr('hidden', 'hidden');
                    $('#snap_0 input').attr("disabled", "disabled");

                    $('#snap_1').removeAttr('hidden');
                    $("#snap_1 input").removeAttr("disabled");
                }
            });
        });
    </script>
@endsection
