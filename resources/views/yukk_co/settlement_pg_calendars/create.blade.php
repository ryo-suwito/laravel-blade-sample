@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Settlement PG Calendar")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.settlement_pg_calendar.list") }}" class="breadcrumb-item">@lang("cms.Settlement PG Calendar List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create")</span>
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
    <form method="post" action="{{ route("cms.yukk_co.settlement_pg_calendar.store") }}">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang("cms.Settlement PG Calendar") @lang("cms.Create")</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="provider_id">@lang("cms.Provider")</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="provider_id" id="provider_id">
                                    @foreach ($provider_list as $provider)
                                        <option value="{{ @$provider->id }}" @if(@$provider->id == old("provider_id")) selected @endif>{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="payment_channel_id">@lang("cms.Payment Channel")</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="payment_channel_id" id="payment_channel_id">
                                    @foreach ($payment_channel_list as $payment_channel)
                                        <option value="{{ @$payment_channel->id }}" @if(@$payment_channel->id == old("payment_channel_id")) selected @endif>{{ $payment_channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="settlement_date">@lang("cms.Settlement Date")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="settlement_date" id="settlement_date" value="{{ old("settlement_date") }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="is_skip">@lang("cms.Is Skipped?")</label>
                            <div class="col-lg-8">
                                <p class="form-control-plaintext">
                                    <input type="checkbox" id="is_skip" name="is_skip" @if(old("is_skip", false)) checked @endif>
                                </p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="start_time_transaction">@lang("cms.Start Time Transaction")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="start_time_transaction" id="start_time_transaction" value="{{ old("start_time_transaction") }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="end_time_transaction">@lang("cms.End Time Transaction")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="end_time_transaction" id="end_time_transaction" value="{{ old("end_time_transaction") }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-primary">@lang("cms.Submit")</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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

            $("#settlement_date").daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

            $("#start_time_transaction").daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                locale: {
                    format: 'DD-MMM-YYYY HH:mm:ss',
                    firstDay: 1,
                },
            });

            $("#end_time_transaction").daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                locale: {
                    format: 'DD-MMM-YYYY HH:mm:ss',
                    firstDay: 1,
                },
            });
        });
    </script>
@endsection