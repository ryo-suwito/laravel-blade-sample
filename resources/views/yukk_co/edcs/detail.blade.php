@extends('layouts.master')

@section('header')
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
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
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.IMEI")</label>
                    <div class="col-sm-4">
                        <input type="text" name="imei" value="{{$edc->imei}}"  class="form-control" readonly>
                    </div>
                    <label class="col-form-label col-sm-2">@lang("cms.Type")</label>
                    <div class="col-sm-4">
                        <input type="text" name="type" value="{{$edc->type}}"  class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Beneficiary")</label>
                    <div class="col-sm-4">
                        <select id="customer_id" name="customer_id" class="form-control select2" disabled>
                            <option value="{{ $edc->customer->id }}">{{ $edc->customer->name }}</option>
                        </select>
                    </div>
                    <label class="col-form-label col-sm-2">@lang("cms.Merchant Branch")</label>
                    <div class="col-sm-4">
                        <input type="text" value="{{ $edc->branch->name }}"  class="form-control" readonly>
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
                        <select class="form-control select2" name="status" id="status" disabled>
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
                        <input type="text" class="form-control" value="{{ @$edc->partner->name }}" readonly>
                    </div>
                    <label class="col-form-label col-sm-2">@lang("cms.Partner Fee")</label>
                    <div class="col-sm-4 d-flex">
                        <select class="form-control select2" name="partner_fee_id" id="partner_fee_id" disabled>
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
                        <select class="form-control select2 col-sm-3" id="name_id" name="event_id" disabled>
                            @if(!$edc->event_id)
                                <option value="0">@lang("cms.NON Event")</option>
                            @else
                                @foreach ($event_list as $event)
                                    <option value="{{ $event->id }}" @if($edc->event_id == $event->id) selected @endif>{{ $event->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <hr>

                @php($is_snap_enabled = (isset($edc) && isset($edc->partner) && $edc->partner->is_snap_enabled))
                @if($edc->type == 'STICKER')
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang('cms.Grant Type')</label>
                        <div class="col-lg-4">
                            <select id="grant_type" name="grant_type" class="form-control select2" disabled>
                                <option value="NONE" @if($edc->grant_type === "NONE") selected @endif>@lang('cms.NONE')</option>
                                <option value="CLIENT_ID_SECRET" @if($edc->grant_type === "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                            </select>
                        </div>
                    </div>
                    <div id="grant_id_secret" class="w-100" hidden>
                    <div class="col-lg-6">
                        <div class="form-group row">
                                <label for="snap_client_id" class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                <div class="col-lg-4">
                                    <input type="text" readonly name="snap_client_id" value="{{ $edc->client_id }}" class="form-control" id="snap_client_id">
                                </div>
                                <div class="col-lg-4">
                                    <button class="btn btn-primary" disabled type="button">@lang("cms.Generate")</button>
                                    <button class="btn btn-success" type="button" id="btn-copy-client-id">@lang("cms.Copy")</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="snap_client_secret" class="col-lg-4 col-form-label @if($errors->has("snap_client_secret")) text-danger @endif">@lang('cms.Client Secret')</label>
                                <div class="col-sm-4">
                                    <input type="text" readonly name="snap_client_secret" value="{{ $edc->client_secret }}" class="form-control @if ($errors->has("snap_client_secret")) is-invalid @endif" id="snap_client_secret">
                                </div>
                                <div class="col-sm-4">
                                    <button class="btn btn-primary" disabled type="button">@lang("cms.Generate")</button>
                                    <button class="btn btn-success" type="button" id="btn-copy-client-secret">@lang("cms.Copy")</button>
                                </div>
                                @if ($errors->has("snap_client_secret"))
                                    <span class="invalid-feedback">{{ $errors->first("snap_client_secret") }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang('cms.Webhook')</label>
                        <div class="col-lg-4">
                            <select id="payment_notify_mode" name="payment_notify_mode" class="form-control select2" disabled>
                                <option value="NONE" @if($edc->payment_notify_mode == "NONE") selected @endif>@lang('cms.NONE')</option>
                                <option value="WEBHOOK" @if($edc->payment_notify_mode == "WEBHOOK") selected @endif>@lang('cms.Webhook')</option>
                            </select>
                        </div>
                    </div>
                    <div id="is_payment_notify" class="form-group row" hidden>
                        <label class="col-lg-2">@lang('cms.Webhook URL')</label>
                        <div class="col-lg-4">
                            <input type="text" id="webhook_url" name="webhook_url" readonly class="form-control" value="{{ $edc->webhook_url }}">
                        </div>
                    </div>
                @else
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang('cms.Grant Type')</label>
                        <div class="col-lg-4">
                            <select id="grant_type" name="grant_type" class="form-control select2" disabled>
                                <option value="NONE" @if(@$partner_login->grant_type === "NONE") selected @endif>@lang('cms.NONE')</option>
                                <option value="CLIENT_ID_SECRET" @if(@$partner_login->grant_type === "CLIENT_ID_SECRET") selected @endif>@lang('cms.CLIENT_ID_SECRET')</option>
                            </select>
                        </div>
                    </div>
                    <div id="grant_id_secret" class="w-100" hidden>
                        @if($is_snap_enabled)
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label for="store_id" class="col-lg-4 col-form-label">@lang('cms.Store ID')</label>
                                    <div class="col-lg-4">
                                        <input type="text" readonly name="store_id" value="{{ @$partner_login->username }}" class="form-control" id="store_id">
                                    </div>
                                    <div class="col-lg-4">
                                        <button class="btn btn-primary" disabled type="button">@lang("cms.Generate")</button>
                                        <button class="btn btn-success" type="button" id="btn-copy-store-id">@lang("cms.Copy")</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label for="snap_client_id" class="col-lg-4 col-form-label">@lang('cms.Client ID')</label>
                                    <div class="col-lg-4">
                                        <input type="text" readonly name="snap_client_id" value="{{ @$partner_login->username }}" class="form-control @if ($errors->has("snap_client_id")) is-invalid @endif" id="snap_client_id">
                                    </div>
                                    <div class="col-lg-4">
                                        <button class="btn btn-primary" disabled type="button">@lang("cms.Generate")</button>
                                        <button class="btn btn-success" type="button" id="btn-copy-client-id">@lang("cms.Copy")</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label for="snap_client_secret" class="col-lg-4 col-form-label">@lang('cms.Client Secret')</label>
                                    <div class="col-sm-4">
                                        <input type="text" readonly name="snap_client_secret" value="{{ @$partner_login->password }}" class="form-control @if ($errors->has("snap_client_secret")) is-invalid @endif" id="snap_client_secret">
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-primary" disabled type="button">@lang("cms.Generate")</button>
                                        <button class="btn btn-success" type="button" id="btn-copy-client-secret">@lang("cms.Copy")</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang('cms.Webhook')</label>
                        <div class="col-lg-4">
                            <select id="payment_notify_mode" name="payment_notify_mode" class="form-control select2" disabled>
                                <option value="NONE" @if(@$partner_login->payment_notify_mode == "NONE") selected @endif>@lang('cms.NONE')</option>
                                <option value="WEBHOOK" @if($partner_login->payment_notify_mode == "WEBHOOK" && $partner_login->is_payment_gateway == 0) selected @endif>@lang('cms.Webhook')</option>
                                <option value="WEBHOOK_PG" @if($partner_login->payment_notify_mode == "WEBHOOK" && $partner_login->is_payment_gateway == 1) selected @endif>@lang('cms.Webhook - YUKK PG')</option>
                            </select>
                        </div>
                    </div>
                    <div id="is_payment_notify" class="form-group row" hidden>
                        <label class="col-lg-2">@lang('cms.Webhook URL')</label>
                        <div class="col-lg-4">
                            <input type="text" id="webhook_url" name="webhook_url" readonly class="form-control" value="{{ @$partner_login->webhook_url }}">
                        </div>
                    </div>
                @endif
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
                $("#is_payment_notify").removeAttr("hidden");
                $("#is_payment_notify input").removeAttr("disabled");
            } else if (document.getElementById("payment_notify_mode").value == 'WEBHOOK_PG') {
                var webhookPg = "{{ config('payment_gateway.urls.webhook') }}";

                $("#is_payment_notify").removeAttr("hidden");
                $("#is_payment_notify input").attr("readonly", true);
                $("#is_payment_notify input").attr("value", webhookPg);
            } else {
                $("#is_payment_notify").attr("hidden", "hidden");
                $("#is_payment_notify input").attr("disabled", "disabled");
            }
        });
        $("#payment_notify_mode").change();
    </script>
@endsection
