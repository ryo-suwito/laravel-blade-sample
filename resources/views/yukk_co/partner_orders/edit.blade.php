@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Partner Order")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item">@lang("cms.Partner Order")</span>
                    <a href="{{ route("cms.yukk_co.partner_order.show", @$partner_order->id) }}" class="breadcrumb-item">{{ @$partner_order->order_id }}</a>
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
                <form method="post" action="{{ route("cms.yukk_co.partner_order.update", @$partner_order->id) }}">
                    @csrf
                    <div class="card-header">
                        <h5 class="card-title">@lang("cms.Partner Order")</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Order ID")</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" readonly="" value="{{ @$partner_order->order_id }}">
                                    </div>
                                    <label class="col-lg-2 col-form-label">@lang("cms.Nominal")</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($partner_order->nominal) }}">
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label" for="expiry_time">@lang("cms.Expiry Time")</label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="expiry_time" id="expiry_time" value="{{ @\App\Helpers\H::formatDateTime($partner_order->expiry_time) }}">
                                            <span class="input-group-append">
                                                <button class="btn btn-light" type="button" id="btn-h-plus-one">@lang("cms.Make H+1")</button>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-lg-2 col-form-label" for="max_payment">@lang("cms.Max Payment")</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" name="max_payment" id="max_payment" value="{{ @$partner_order->max_payment }}">
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch")</label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" readonly="" value="{{ @$partner_order->merchant_branch->name }}">
                                            <span class="input-group-append">
                                            <span class="input-group-text">{{ @$partner_order->merchant_branch->id }}</span>
                                        </span>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Partner")</label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" readonly="" value="{{ @$partner_order->partner_login->partner->name }}">
                                            <span class="input-group-append">
                                            <span class="input-group-text">{{ @$partner_order->partner_login->partner->id }}</span>
                                        </span>
                                        </div>
                                    </div>
                                    <label class="col-lg-2 col-form-label" title="@lang("cms.Client ID")">@lang("cms.Partner Login Username")</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" readonly="" value="{{ @$partner_order->partner_login->username }}">
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary">@lang("cms.Cancel")</a>
                                        <button class="btn btn-primary" type="submit">@lang("cms.Submit")</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();
            function initExpiryTime() {
                $("#expiry_time").daterangepicker({
                    singleDatePicker: true,
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: true,
                    parentEl: '.content-inner',
                    locale: {
                        format: 'DD-MMM-YYYY HH:mm:ss',
                        firstDay: 1,
                    },
                });
            }
            initExpiryTime();


            $(".btn-need-confirmation").click(function(e) {
                if (confirm("@lang("cms.user_withdrawal_action_button_action_confirmation")")) {
                    setInterval(function() {
                        // Need interval because if disable first, then the button is not included on the form
                        $(".btn-need-confirmation").attr("disabled", "disabled");
                    }, 50);
                } else {
                    e.preventDefault();
                }
            });

            $("#btn-h-plus-one").click(function() {
                const date = new Date();
                date.setDate(date.getDate() + 1);

                console.log(date);
                console.log(date.getDate(), convertMonth(date.getMonth()), date.getFullYear(), date.getHours(), date.getMinutes(), date.getSeconds());

                $("#expiry_time").val(`${zeroPad(date.getDate(), 2)}-${convertMonth(date.getMonth())}-${date.getFullYear()} ${zeroPad(date.getHours(), 2)}:${zeroPad(date.getMinutes(), 2)}:${zeroPad(date.getSeconds(), 2)}`);

                initExpiryTime();
            });
        });

        function convertMonth(monthIndex) {
            const months = {
                0: 'Jan',
                1: 'Feb',
                2: 'Mar',
                3: 'Apr',
                4: 'May',
                5: 'Jun',
                6: 'Jul',
                7: 'Aug',
                8: 'Sep',
                9: 'Oct',
                10: 'Nov',
                11: 'Dec'
            };

            return months[monthIndex % 12];
        }

        function zeroPad(num, places) {
            var zero = places - num.toString().length + 1;
            return Array(+(zero > 0 && zero)).join("0") + num;
        }
    </script>
@endsection