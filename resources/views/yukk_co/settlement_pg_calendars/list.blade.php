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
                    <span class="breadcrumb-item active">@lang("cms.Settlement PG Calendar")</span>
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
        <div class="card-header header-elements-inline">
            <h5 class="card-title">@lang("cms.Filter")</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("SETTLEMENT_PG_CALENDAR_CREATE", "AND"))
                            <a href="{{ route("cms.yukk_co.settlement_pg_calendar.create") }}" type="button" class="btn btn-primary w-100 w-sm-auto float-right">@lang("cms.Add New") @lang("cms.Calendar")</a>
                        @endif
                    </div>
                </div>

                <form action="{{ route("cms.yukk_co.settlement_pg_calendar.list") }}" method="get">
                    <div class="row">

                        {{--<div class="col-lg-4">
                            <div class="form-group">
                                <label>@lang("cms.Order ID")</label>
                                <input type="text" name="order_id" class="form-control" placeholder="@lang("cms.Search Order ID")" value="{{ $order_id }}">
                            </div>
                        </div>--}}

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="provider_id">@lang("cms.Provider")</label>
                                <select class="form-control" name="provider_id" id="provider_id">
                                    <option value="0" @if(@0 == $provider_id) selected @endif>---@lang("cms.Select Provider")---</option>
                                    @foreach ($provider_list as $provider)
                                        <option value="{{ @$provider->id }}" @if(@$provider->id == $provider_id) selected @endif>{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="payment_channel_id">@lang("cms.Payment Channel")</label>
                                <select class="form-control" name="payment_channel_id" id="payment_channel_id">
                                    <option value="0" @if(@0 == $payment_channel_id) selected @endif>---@lang("cms.Select Payment Channel")---</option>
                                    @foreach ($payment_channel_list as $payment_channel)
                                        <option value="{{ @$payment_channel->id }}" @if(@$payment_channel->id == $payment_channel_id) selected @endif>{{ $payment_channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="date_range">@lang("cms.Settlement Date Range")</label>
                                <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y") }} - {{ $end_time->format("d-M-Y") }}">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($settlement_pg_calendar_list !== null)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang("cms.Settlement PG Calendar List")</h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered table-striped dataTable">
                    <thead>
                    <tr>
                        <th>@lang("cms.Settlement Date")</th>
                        <th>@lang("cms.Start Time Transaction")</th>
                        <th>@lang("cms.End Time Transaction")</th>
                        <th>@lang("cms.Is Skipped?")</th>
                        <th>@lang("cms.Actions")</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($settlement_pg_calendar_list as $index => $settlement_pg_calendar)
                        <tr>
                            <td>{{ @\App\Helpers\H::formatDateTime($settlement_pg_calendar->settlement_date, "d-M-Y") }}</td>
                            <td>{{ @\App\Helpers\H::formatDateTime($settlement_pg_calendar->start_time_transaction, "d-M-Y H:i:s") }}</td>
                            <td>{{ @\App\Helpers\H::formatDateTime($settlement_pg_calendar->end_time_transaction, "d-M-Y H:i:s") }}</td>
                            <td class="text-center">
                                @if (@$settlement_pg_calendar->is_skip)
                                    <i class="icon-check2 text-danger"></i>
                                @else
                                    <i class="icon-cross3 text-success"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route("cms.yukk_co.settlement_pg_calendar.item", $settlement_pg_calendar->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                            @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("SETTLEMENT_PG_CALENDAR_EDIT", "AND"))
                                                <a href="{{ route("cms.yukk_co.settlement_pg_calendar.edit", $settlement_pg_calendar->id) }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination pagination-flat justify-content-end">
                            @php($plus_minus_range = 3)
                            @if ($current_page == 1)
                                <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route("cms.yukk_co.settlement_pg_calendar.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                        <a href="{{ route("cms.yukk_co.settlement_pg_calendar.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                    <a href="{{ route("cms.yukk_co.settlement_pg_calendar.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                        <i class="icon-arrow-right13"></i>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
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

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
                timePicker: false,
                timePicker24Hour: false,
            });

            $("#paid_at_date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
                timePicker: false,
                timePicker24Hour: false,
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