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
            <form action="{{ route("cms.customer.transaction_pg.list") }}" method="get">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Merchant Branch")</label>
                            <input type="text" name="merchant_branch_name" class="form-control" placeholder="@lang("cms.Merchant Branch Name")" value="{{ $merchant_branch_name }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Partner Name")</label>
                            <input type="text" name="partner_name" class="form-control" placeholder="@lang("cms.Partner Name")" value="{{ $partner_name }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Payment Channel")</label>
                            <input type="text" name="payment_channel_name" class="form-control" placeholder="@lang("cms.Payment Channel")" value="{{ $payment_channel_name }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="status">@lang("cms.Status")</label>
                            <select name="status" id="status" class="form-control">
                                <option value="ALL" @if($status == "ALL") selected @endif>@lang("cms.All")</option>
                                <option value="SUCCESS" @if($status == "SUCCESS") selected @endif>@lang("cms.Success")</option>
                                <option value="WAITING" @if($status == "WAITING") selected @endif>@lang("cms.Waiting")</option>
                                <option value="PENDING" @if($status == "PENDING") selected @endif>@lang("cms.Pending")</option>
                                <option value="CANCELLED" @if($status == "CANCELLED") selected @endif>@lang("cms.Cancelled")</option>
                                <option value="FAILED" @if($status == "FAILED") selected @endif>@lang("cms.Failed")</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Paid At")</label>
                            <input type="text" id="paid_at_date_range" name="paid_at_date_range" class="form-control" placeholder="@lang("cms.Search Paid Time")" value="{{ $paid_at_start_time->format("d-M-Y H:i:s") }} - {{ $paid_at_end_time->format("d-M-Y H:i:s") }}">
                        </div>
                    </div>

                    <div class="col-lg-1">
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <label><input type="checkbox" id="paid_at_null" name="paid_at_null" value="1" @if($paid_at_null) checked="checked" @endif> @lang("cms.Allow Null?")</label>
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
                            <label>@lang("cms.Ref Code")</label>
                            <input type="text" name="ref_code" class="form-control" placeholder="@lang("cms.Ref Code")" value="{{ $ref_code }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Order ID")</label>
                            <input type="text" name="order_id" class="form-control" placeholder="@lang("cms.Order ID")" value="{{ $order_id }}">
                        </div>
                    </div>

                    <div class="col-lg-3 offset-lg-8">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="input-group input-group-append position-static">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                                <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                <div class="dropdown-menu dropdown-menu-right" style="">
                                    <button class="dropdown-item" name="export_to_xls" value="1"><i class="icon-file-download"></i> @lang("cms.Export to XLS")</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-right">
                        <label>
                            @lang("cms.Per page")&nbsp;
                            <select name="per_page">
                                <option @if($per_page == 10) selected @endif>10</option>
                                <option @if($per_page == 25) selected @endif>25</option>
                                <option @if($per_page == 50) selected @endif>50</option>
                                <option @if($per_page == 100) selected @endif>100</option>
                            </select>
                        </label>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.Branch Name")</th>
                    <th>@lang("cms.Partner Name")</th>
                    <th>@lang("cms.Payment Channel")</th>
                    <th>@lang("cms.Grand Total")</th>
                    <th>@lang("cms.Bank Fee")</th>
                    <th>@lang("cms.Mdr Fee")</th>
                    <th>@lang("cms.Order ID")</th>
                    <th>@lang("cms.Ref Code")</th>
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
                        <td>{{ @$transaction_pg->partner->name }}</td>
                        <td>{{ @$transaction_pg->payment_channel->name }}</td>
                        <td>{{ @number_format($transaction_pg->grand_total, 0, ".", ",") }}</td>
                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($transaction_pg->bank_fee, 2) }}</td>
                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($transaction_pg->mdr_external_fixed + $transaction_pg->fee_partner_fixed + $transaction_pg->mdr_external_percentage + $transaction_pg->mdr_external_percentage, 2) }}</td>
                        <td>{{ @$transaction_pg->order_id }}</td>
                        <td>{{ @$transaction_pg->code }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($transaction_pg->request_at, "Y-m-d H:i:s") }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($transaction_pg->paid_at, "Y-m-d H:i:s") }}</td>
                        <td>{{ @$transaction_pg->status }}</td>
                        <td>{{ @$transaction_pg->is_settle ? trans("cms.Settlement") : trans("cms.Not Settlement") }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ @route("cms.customer.transaction_pg.show", $transaction_pg->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                                <a href="{{ route("cms.customer.transaction_pg.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.customer.transaction_pg.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.customer.transaction_pg.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
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

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
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

            refreshPaidAtDateRange();
        });
    </script>
@endsection
