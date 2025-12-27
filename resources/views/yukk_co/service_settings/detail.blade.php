@extends('layouts.master')

@section('header')
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Platform Settings")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('cms.Home')</a>
                    <a href="{{ route('platform_setting.index') }}" class="breadcrumb-item">@lang('cms.Platform Settings')</a>
                    <span class="breadcrumb-item active">@lang('cms.Detail')</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <h3 class="col-sm-12 font-weight-bold">{{ @$entity['entity']['name'] }} &#9;&#9; | &#9;&#9; {{ @$entity['entity_type'] }}</h3>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Entity Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="name" name="name" value="{{ @$entity['entity']['name'] }}" readonly>
                </div>

                <label class="col-form-label col-lg-2">@lang("cms.Skip User Validation")</label>
                <div class="col-sm-4 mt-1">
                    <input type="checkbox" id="is_skip_validation" name="is_skip_validation" disabled>
                    <span>@lang('cms.Verihubs')</span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Entity Type")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="type" name="type" value="{{ @$entity['entity_type'] }}" readonly>
                </div>

                <label class="col-form-label col-lg-2">@lang("cms.Status")</label>
                <div class="col-sm-4">
                    <select class="form-control select2" name="status" id="status" disabled>
                        <option value="0" @if(@$entity['entity']['active'] == 1) selected @endif>@lang("cms.Active")</option>
                        <option value="1" @if(@$entity['entity']['active'] == 0) selected @endif>@lang("cms.Inactive")</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
            @if(@$settings['LOGO'] !== '')
                <label for="logo" class="col-form-label col-sm-2">@lang("cms.Logo")</label>
                <div class="col-sm-4">
                    <img src="{{ @$settings['LOGO'] }}" alt="Image Error" width="350" height="300">
                </div>
            @else
                <label for="logo" class="col-form-label col-sm-2"></label>
                <div class="col-sm-4">
                </div>
            @endif
                <label class="col-form-label col-lg-2">@lang("cms.Created At")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="name" name="name" value="{{ @$entity['entity']['created_at'] }}" readonly>
                </div>
            </div>
        </div>

        <hr class="mx-3 font-weight-bold">

        <div class="card-body">
            <div class="form-group row">
                <h3 class="col-sm-12 font-weight-bold">@lang('cms.Payment Settings')</h3>
            </div>

            <div class="form-group row">
                <label for="mdr_type" class="col-form-label col-sm-2">@lang("cms.MDR Type")</label>
                <div class="col-sm-4">
                    <select class="form-control select2" name="mdr_type" id="mdr_type" disabled>
                        <option value="FIXED" @if(@$settings['MDR_FEE']->type == 'FIXED') selected @endif>@lang("cms.FIXED")</option>
                        <option value="PERCENTAGE" @if(@$settings['MDR_FEE']->type == 'PERCENTAGE') selected @endif>@lang("cms.PERCENTAGE")</option>
                    </select>
                </div>

                <label for="timeout_time" class="col-form-label col-sm-2">@lang("cms.Timeout Time (in seconds)")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="timeout_time" name="timeout_time" value="{{ @$settings['TIMEOUT'] }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label for="mdr_fee" class="col-form-label col-sm-2">@lang("cms.MDR Fee")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="mdr_fee" name="mdr_fee" value="{{ @$settings['MDR_FEE']->value }}" readonly>
                </div>

                <label for="autocomplete" class="col-form-label col-sm-2">@lang("cms.Autocomplete")</label>
                <div class="col-sm-4">
                    <select class="form-control select2" name="autocomplete" id="autocomplete" disabled>
                        <option value="FIXED" @if(@$settings['AUTOCOMPLETE_TIME']->type == 'FIXED') selected @endif>@lang("cms.FIXED")</option>
                        <option value="DYNAMIC" @if(@$settings['AUTOCOMPLETE_TIME']->type == 'DYNAMIC') selected @endif>@lang("cms.DYNAMIC")</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="payment_mode" class="col-form-label col-sm-2">@lang("cms.Payment Mode")</label>
                <div class="col-sm-4">
                    <select class="form-control select2" name="payment_mode" id="payment_mode" disabled>
                        <option value="YUKK_P_THEN_YUKK_E" @if(@$settings['CURRENCY_TYPE'] == 'YUKK_P_THEN_YUKK_E') selected @endif>@lang("cms.YUKK CASH THEN YUKK POINT")</option>
                        <option value="YUKK_P_ONLY" @if(@$settings['CURRENCY_TYPE'] == 'YUKK_P_ONLY') selected @endif>@lang("cms.YUKK CASH ONLY")</option>
                        <option value="YUKK_E_ONLY" @if(@$settings['CURRENCY_TYPE'] == 'YUKK_E_ONLY') selected @endif>@lang("cms.YUKK POINT ONLY")</option>
                    </select>
                </div>
                @if(@$settings['AUTOCOMPLETE_TIME']->type == 'FIXED')
                    <label for="autocomplete_at" class="col-form-label col-sm-2">@lang("cms.Autocomplete At")</label>
                    <div class="form-group row w-25">
                        <label for="payment_mode" class="col-form-label w-25 ml-3">@lang("cms.Day")</label>
                        <input type="text" class="form-control w-auto" id="timeout_time" name="timeout_time" value="{{ @$settings['AUTOCOMPLETE_TIME']->value->day }}" readonly>
                    </div>
                @elseif(@$settings['AUTOCOMPLETE_TIME']->type == 'DYNAMIC')
                    <label for="mdr_type" class="col-form-label col-sm-2">@lang("cms.Autocomplete Time (in seconds)")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="mdr_fee" name="mdr_fee" value="{{ @$settings['AUTOCOMPLETE_TIME']->value->seconds }}" readonly>
                    </div>
                @endif
            </div>

            <div class="form-group row">
                <label for="callback_url" class="col-form-label col-sm-2"></label>
                <div class="col-sm-4">
                </div>

                @if(@$settings['AUTOCOMPLETE_TIME']->type == 'FIXED')
                    <label for="autocomplete_at" class="col-form-label col-sm-2"></label>
                    <div class="form-group row w-25">
                        <label for="time" class="col-form-label w-25 ml-3">@lang("cms.Time")</label>
                        <input type="text" class="form-control w-auto" id="time" name="time" value="{{ @$settings['AUTOCOMPLETE_TIME']->value->time }}" readonly>
                    </div>
                @endif
            </div>

        </div>

        <hr class="mx-3 font-weight-bold">

        <div class="card-body">
            <div class="form-group row">
                <h3 class="col-sm-12 font-weight-bold">@lang('cms.Partner URL Settings')</h3>
            </div>

{{--            <span><u>@lang('cms.DIRECT PAYMENT')</u></span>--}}
{{--            <div class="form-group row">--}}
{{--                <label for="callback_url" class="col-form-label col-sm-2">@lang("cms.Callback URL")</label>--}}
{{--                <div class="col-sm-4">--}}
{{--                    <input type="text" class="form-control" id="callback_url" name="callback_url" value="{{ @$settings['CALLBACK_URL'] }}" readonly>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="form-group row">--}}
{{--                <label for="notification_url" class="col-form-label col-sm-2">@lang("cms.Notification URL (Webhook)")</label>--}}
{{--                <div class="col-sm-4">--}}
{{--                    <input type="text" class="form-control" id="notification_url" name="notification_url" value="{{ @$settings['NOTIFICATION_URL'] }}" readonly>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <br>--}}

            <span><u>@lang('cms.ACCOUNT CREATION')</u></span>
            <div class="form-group row">
                <label for="callback_url" class="col-form-label col-sm-2">@lang("cms.Callback URL")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="callback_url" name="callback_url" value="{{ @$creations['CALLBACK_URL'] }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label for="notification_url" class="col-form-label col-sm-2">@lang("cms.Notification URL (Webhook)")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="notification_url" name="notification_url" value="{{ @$creations['NOTIFICATION_URL'] }}" readonly>
                </div>
            </div>
        </div>
    </div>
@endsection

