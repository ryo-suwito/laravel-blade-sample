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
        <form action="{{ route('platform_setting.update', $id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="100[name]" value="CURRENCY_TYPE" />
            <input type="hidden" name="100[service]" value="TRANSACTION" />

            <div class="card-body">
                <div class="form-group row">
                    <h3 class="col-sm-12 font-weight-bold">{{ @$entity['entity']['name'] }} &#9;&#9; | &#9;&#9; {{ @$entity['entity_type'] }}</h3>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">@lang("cms.Entity Name")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" value="{{ @$entity['entity']['name'] }}" readonly>
                    </div>

                    <label class="col-form-label col-lg-2">@lang("cms.Skip User Validation")</label>
                    <div class="col-sm-4 mt-1">
                        <input type="checkbox" id="is_skip_validation" name="is_skip_validation" readonly>
                        <span>@lang('cms.Verihubs')</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="type" class="col-form-label col-sm-2">@lang("cms.Entity Type")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" value="{{ @$entity['entity_type'] }}" readonly>
                    </div>

                    <label class="col-form-label col-lg-2">@lang("cms.Status")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="status" id="status">
                            <option value="1" @if(@$entity['entity']['active'] == 1) selected @endif>@lang("cms.Active")</option>
                            <option value="0" @if(@$entity['entity']['active'] == 0) selected @endif>@lang("cms.Inactive")</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="logo" class="col-form-label col-sm-2">@lang("cms.Logo")</label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" id="logo" name="100[logo]">
                        @if(@$settings['LOGO'])
                            <img class="mt-2" src="{{ @$settings['LOGO'] }}" alt="Image Error" width="350" height="300">
                        @endif
                    </div>

                    <label for="created_at" class="col-form-label col-lg-2">@lang("cms.Created At")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="created_at" name="created_at" value="{{ @$entity['entity']['created_at'] }}" readonly>
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
                        <select class="form-control select2" name="mdr_type" id="mdr_type" onchange="mdr_validation()" required>
                            <option value="FIXED" @if(@$settings['MDR_FEE']['type'] == 'FIXED') selected @endif>@lang("cms.FIXED")</option>
                            <option value="PERCENTAGE" @if(@$settings['MDR_FEE']['type'] == 'PERCENTAGE') selected @endif>@lang("cms.PERCENTAGE")</option>
                        </select>
                    </div>

                    <label for="timeout_time" class="col-form-label col-sm-2">@lang("cms.Timeout Time (in seconds)")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="timeout_time" name="timeout_time" value="{{ @$settings['TIMEOUT'] }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mdr_fee" class="col-form-label col-sm-2">@lang("cms.MDR Fee")</label>
                    <div class="col-sm-4">
                        <input type="number" max="100" min="0" id="mdr_fee" name="mdr_fee" class="form-control valid" value="{{ @$settings['MDR_FEE']['value'] }}" oninput="validity.valid||(value='');" required autofocus step="0.01">
                    </div>

                    <label for="autocomplete" class="col-form-label col-sm-2">@lang("cms.Autocomplete")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="autocomplete" id="autocomplete" required>
                            <option value="FIXED" @if(@$settings['AUTOCOMPLETE_TIME']->type == 'FIXED') selected @endif>@lang("cms.FIXED")</option>
                            <option value="DYNAMIC" @if(@$settings['AUTOCOMPLETE_TIME']->type == 'DYNAMIC') selected @endif>@lang("cms.DYNAMIC")</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="payment_mode" class="col-form-label col-sm-2">@lang("cms.Payment Mode")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="payment_mode" id="payment_mode" required>
                            <option value="YUKK_P_THEN_YUKK_E" @if(@$settings['CURRENCY_TYPE'] == 'YUKK_P_THEN_YUKK_E') selected @endif>@lang("cms.YUKK CASH THEN YUKK POINT")</option>
                            <option value="YUKK_P_ONLY" @if(@$settings['CURRENCY_TYPE'] == 'YUKK_P_ONLY') selected @endif>@lang("cms.YUKK CASH ONLY")</option>
                            <option value="YUKK_E_ONLY" @if(@$settings['CURRENCY_TYPE'] == 'YUKK_E_ONLY') selected @endif>@lang("cms.YUKK POINT ONLY")</option>
                        </select>
                    </div>

                    <label for="autocomplete_at" class="col-form-label col-sm-2 fixed">@lang("cms.Autocomplete At")</label>
                    <div class="form-group row w-25 fixed">
                        <label for="day" class="col-form-label w-25 ml-3">@lang("cms.Day")</label>
                        <input type="text" class="form-control w-auto" id="day" name="day" value="{{ @$settings['AUTOCOMPLETE_TIME']['value']['day'] }}">
                    </div>
                    <label for="autocomplete_time" class="col-form-label col-sm-2 dynamic">@lang("cms.Autocomplete Time (in seconds)")</label>
                    <div class="col-sm-4 dynamic">
                        <input type="text" class="form-control" id="autocomplete_time" name="autocomplete_time" value="{{ @$settings['AUTOCOMPLETE_TIME']['value']['seconds'] }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="callback_url" class="col-form-label col-sm-2"></label>
                    <div class="col-sm-4">
                    </div>

                    <label for="autocomplete_at" class="col-form-label col-sm-2 fixed"></label>
                    <div class="form-group row w-25 fixed">
                        <label for="time" class="col-form-label w-25 ml-3">@lang("cms.Time")</label>
                        <input type="text" class="form-control w-auto" id="time" name="time" value="{{ @$settings['AUTOCOMPLETE_TIME']['value']['time'] }}">
                    </div>
                </div>
            </div>

            <hr class="mx-3 font-weight-bold">

            <div class="card-body">
                <div class="form-group row">
                    <h3 class="col-sm-12 font-weight-bold">@lang('cms.Partner URL Settings')</h3>
                </div>

                <span><u>@lang('cms.ACCOUNT CREATION')</u></span>
                <div class="form-group row">
                    <label for="callback_url" class="col-form-label col-sm-2">@lang("cms.Callback URL")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="creation_callback_url" name="creation_callback_url" value="{{ @$creations['CALLBACK_URL'] }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="notification_url" class="col-form-label col-sm-2">@lang("cms.Notification URL (Webhook)")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="creation_notification_url" name="creation_notification_url" value="{{ @$creations['NOTIFICATION_URL'] }}">
                    </div>
                </div>
            </div>

            <hr class="mx-3 font-weight-bold">

            <div class="justify-content-center row my-3">
                <button type="submit" class="col-sm-2 btn btn-primary justify-content-center mr-4">@lang("cms.Submit")</button>
                <a class="btn btn-secondary col-sm-2" href="{{ route('platform_setting.index') }}">@lang("cms.Cancel")</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function mdr_validation() {
            let type = document.getElementById('mdr_type').value;
            let fee = document.getElementById('mdr_fee');

            if (type === 'PERCENTAGE'){
                fee.type = 'number';
            }else if (type === 'FIXED'){
                fee.type = 'text';
            }
        }
        mdr_validation();

        $(document).ready(function() {
           $('#autocomplete').change(function(){
               var autocomplete = document.getElementById('autocomplete').value;

               if(autocomplete == 'DYNAMIC'){
                   $('.dynamic').removeAttr('hidden');
                   $('.fixed').attr('hidden', true);
               }else{
                   $(".dynamic").attr('hidden', true);
                   $('.fixed').removeAttr('hidden');
               }
           }).change();

           $('#time').daterangepicker({
               timePicker: true,
               singleDatePicker:true,
               timePicker24Hour: true,
               timePickerIncrement: 1,
               timePickerSeconds: true,
               locale: {
                   format: 'HH:mm:ss'
               }
           }).on('show.daterangepicker', function (ev, picker) {
               picker.container.find(".calendar-table").hide();
           });
        });
    </script>
@endsection

