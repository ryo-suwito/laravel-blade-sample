@extends('layouts.master')

@section('header')
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Create Platform Settings")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("platform_setting.index") }}" class="breadcrumb-item">@lang("cms.Platform Settings")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create")</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('platform_setting.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Entity Type")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="type" id="type" required>
                            <option value="" selected disabled>@lang("cms.Select One")</option>
                            <option value="PARTNER">@lang("cms.Partner")</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Entity Name")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="entity_id" id="entity_id" required>
                            <option selected disabled>Choose Entity Type First!</option>
                        </select>
                        <input type="hidden" id="entity_name" name="entity_name">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2">@lang("cms.Logo")</label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" id="logo" name="logo">
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
                            <option value="FIXED">@lang("cms.FIXED")</option>
                            <option value="PERCENTAGE">@lang("cms.PERCENTAGE")</option>
                        </select>
                    </div>

                    <label for="timeout_time" class="col-form-label col-sm-2">@lang("cms.Timeout Time (in seconds)")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="timeout_time" name="timeout_time" value="300" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mdr_fee" class="col-form-label col-sm-2">@lang("cms.MDR Fee")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" min="0" max="100" id="mdr_fee" name="mdr_fee" placeholder="MDR Fee" oninput="validity.valid||(value='');" required autofocus step="0.01">
                    </div>

                    <label for="autocomplete" class="col-form-label col-sm-2">@lang("cms.Autocomplete")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="autocomplete" id="autocomplete" required>
                            <option value="FIXED">@lang("cms.FIXED")</option>
                            <option value="DYNAMIC">@lang("cms.DYNAMIC")</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="payment_mode" class="col-form-label col-sm-2">@lang("cms.Payment Mode")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="payment_mode" id="payment_mode" required>
                            <option value="YUKK_P_THEN_YUKK_E">@lang("cms.YUKK CASH THEN YUKK POINT")</option>
                            <option value="YUKK_P_ONLY" selected>@lang("cms.YUKK CASH ONLY")</option>
                            <option value="YUKK_E_ONLY">@lang("cms.YUKK POINT ONLY")</option>
                        </select>
                    </div>

                    <label for="autocomplete_at" class="col-form-label col-sm-2 fixed">@lang("cms.Autocomplete At")</label>
                    <div class="form-group row w-25 fixed">
                        <label for="day" class="col-form-label w-25 ml-3">@lang("cms.Day")</label>
                        <input type="text" class="form-control w-auto" id="day" name="day" value="0">
                    </div>
                    <label for="autocomplete_time" class="col-form-label col-sm-2 dynamic">@lang("cms.Autocomplete Time (in seconds)")</label>
                    <div class="col-sm-4 dynamic">
                        <input type="text" class="form-control" id="autocomplete_time" name="autocomplete_time">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="callback_url" class="col-form-label col-sm-2"></label>
                    <div class="col-sm-4">
                    </div>

                    <label for="autocomplete_at" class="col-form-label col-sm-2 fixed"></label>
                    <div class="form-group row w-25 fixed">
                        <label for="time" class="col-form-label w-25 ml-3">@lang("cms.Time")</label>
                        <input type="text" class="form-control w-auto" id="time" name="time" value="{{ '23:59:59' }}">
                    </div>
                </div>
            </div>

            <hr class="mx-3 font-weight-bold">

            <div class="card-body">
                <div class="form-group row">
                    <h3 class="col-sm-12 font-weight-bold">@lang('cms.Partner URL Settings')</h3>
                </div>

{{--                <span><u>@lang('cms.DIRECT PAYMENT')</u></span>--}}
{{--                <div class="form-group row">--}}
{{--                    <label for="callback_url" class="col-form-label col-sm-2">@lang("cms.Callback URL")</label>--}}
{{--                    <div class="col-sm-4">--}}
{{--                        <input type="text" class="form-control" id="callback_url" name="callback_url" placeholder="@lang("cms.Callback URL")">--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="form-group row">--}}
{{--                    <label for="notification_url" class="col-form-label col-sm-2">@lang("cms.Notification URL (Webhook)")</label>--}}
{{--                    <div class="col-sm-4">--}}
{{--                        <input type="text" class="form-control" id="notification_url" name="notification_url" placeholder="@lang("cms.Notification URL (Webhook)")">--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <br>--}}

                <span><u>@lang('cms.ACCOUNT CREATION')</u></span>
                <div class="form-group row">
                    <label for="callback_url" class="col-form-label col-sm-2">@lang("cms.Callback URL")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="creation_callback_url" name="creation_callback_url" placeholder="@lang("cms.Callback URL")">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="notification_url" class="col-form-label col-sm-2">@lang("cms.Notification URL (Webhook)")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="creation_notification_url" name="creation_notification_url" placeholder="@lang("cms.Notification URL (Webhook)")">
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

@section('post_scripts')
    <script>
        function mdr_validation() {
            let type = document.getElementById('mdr_type').value;
            let fee = document.getElementById('mdr_fee');

            fee.value = '';
            if (type === 'PERCENTAGE'){
                fee.type = 'number';
            }else if (type === 'FIXED'){
                fee.type = 'text';
            }
        }

        $(document).ready(function() {
            $('#type').change(function () {
                $('#entity_id').select2({
                    ajax: {
                        url: "{{ route('credential.partner.select2') }}",
                        type: "GET",
                        dataType: 'json',
                        delay: 500,
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page
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
                        cache: true,
                    },
                    placeholder: 'Search Partner',
                })
            });

            $('#entity_id').change(function (){
                document.getElementById('entity_name').value = $('#entity_id').find(':selected').text()
            });

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

