@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Transaction Payment Gateway List")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Transaction Payment Gateway List")</span>
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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Transaction Payment Gateway List")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 alert" style="border: 1px black solid;">
                    <h2>@lang("cms.SUM"): {{ @\App\Helpers\H::formatNumber(@collect($transaction_pg_list)->sum("grand_total")) }}</h2>
                    <h4>@lang("cms.Jumlah Transaksi"): {{ @\App\Helpers\H::formatNumber(@collect($transaction_pg_list)->count()) }}</h4>
                </div>
            </div>

            <hr>

            <form action="{{ route("cms.partner.transaction_pg.list") }}" method="get">
                <div class="row">

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Order ID")</label>
                            <input type="text" name="order_id" class="form-control" placeholder="@lang("cms.Search Order ID")" value="{{ $order_id }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Paid Time")</label>
                            <input type="text" id="paid_at_date_range" name="paid_at_date_range" class="form-control" placeholder="@lang("cms.Search Paid Time")" value="{{ $paid_at_start_time->format("d-M-Y H:i:s") }} - {{ $paid_at_end_time->format("d-M-Y H:i:s") }}">
                        </div>
                    </div>

                    <div class="col-lg-1">
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <label><input type="checkbox" id="paid_at_null" name="paid_at_null" value="1" @if($paid_at_null) checked="checked" @endif> @lang("cms.Allow Null?")</label>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Payment Channel")</label>
                            <select class="form-control" name="payment_channel_id">
                                <option value="0" @if(@0 == $payment_channel_id) selected @endif>@lang("cms.All")</option>
                                @foreach ($payment_channel_list as $payment_channel)
                                    <option value="{{ @$payment_channel->id }}" @if(@$payment_channel->id == $payment_channel_id) selected @endif>{{ $payment_channel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Status Settlement")</label>
                            <select class="form-control" name="is_settle" id="is_settle">
                                <option value="ALL" @if(@$is_settle == "ALL") selected @endif>@lang("cms.All")</option>
                                <option value="YES" @if(@$is_settle == "YES") selected @endif>@lang("cms.Yes")</option>
                                <option value="NO" @if(@$is_settle == "NO") selected @endif>@lang("cms.No")</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="input-group input-group-append position-static">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                                <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                <div class="dropdown-menu dropdown-menu-right" style="">
                                    <button class="dropdown-item" name="export_to_csv" value="1"><i class="icon-file-download"></i> @lang("cms.Export to CSV")</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.Merchant Branch")</th>
                    <th>@lang("cms.Payment Method")</th>
                    <th>@lang("cms.Nominal")</th>
                    <th>@lang("cms.Bank Fee")</th>
                    <th>@lang("cms.Reference ID")</th>
                    <th>@lang("cms.Request Time")</th>
                    <th>@lang("cms.Paid Time")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Status Settlement")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($transaction_pg_list as $index => $transaction_pg)
                    <tr>
                        <td>{{ @$transaction_pg->merchant_branch->name }}</td>
                        <td>{{ @$transaction_pg->payment_channel->name }}</td>
                        <td>{{ @number_format($transaction_pg->grand_total) }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($transaction_pg->bank_fee, 2) }}</td>
                        <td>{{ @$transaction_pg->order_id }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($transaction_pg->request_at) }}</td>
                        <td>{{ @$transaction_pg->paid_at ? \App\Helpers\H::formatDateTime($transaction_pg->paid_at) : "" }}</td>
                        <td>{{ @$transaction_pg->status }}</td>
                        <td>{{ @$transaction_pg->is_settle ? trans("cms.Settlement") : trans("cms.Not Settlement") }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route("cms.partner.transaction_pg.item", $transaction_pg->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{--<div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("cms.merchant_branch.transaction_payment.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
                            </li>
                        @endif
                        @if ($current_page - $plus_minus_range > 1)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                            @if ($i == $current_page)
                                <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route("cms.merchant_branch.transaction_payment.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page + $plus_minus_range < $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-right13"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("cms.merchant_branch.transaction_payment.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>--}}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": true,
                "ordering": false,
                "info": false,
                "searching": false,
            });

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY HH:mm:ss',
                    firstDay: 1,
                },
                timePicker: true,
                timePicker24Hour: true,
            });

            $("#paid_at_date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY HH:mm:ss',
                    firstDay: 1,
                },
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
            });

            function refreshPaidAtDateRange() {
                if($("#paid_at_null").is(':checked')) {
                    $("#paid_at_date_range").attr("disabled", "disabled");
                } else {
                    $("#paid_at_date_range").removeAttr("disabled");
                }
            }

            $("#paid_at_null").change(function() {
                refreshPaidAtDateRange();
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            refreshPaidAtDateRange();
        });
    </script>
@endsection
