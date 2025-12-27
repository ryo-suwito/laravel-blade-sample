@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Transaction Payment List")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("Transaction Payment List")</span>
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
            <h5 class="card-title">@lang("cms.Transaction Payment List")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                {{--<div class="col-sm-6 alert" style="border: 1px black solid;">
                    <h2>@lang("cms.SUM"): {{ @\App\Helpers\H::formatNumber($sum_grand_total) }}</h2>
                    <h4>@lang("cms.Jumlah Transaksi"): {{ @\App\Helpers\H::formatNumber($total_count) }}</h4>
                    <h4>@lang("cms.Date Range"): {{ @\App\Helpers\H::formatDateTime($start_time) }} - {{ @\App\Helpers\H::formatDateTime($end_time) }}</h4>
                </div>--}}
                <div class="col-6 col-md-3">
                    <div class="card bg-success text-center p-3">
                        <div>
                            <h1 class="mb-3 mt-1">
                                @lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($sum_grand_total) }}
                            </h1>
                        </div>

                        <blockquote class="blockquote mb-0">
                            <h3>@lang("cms.Grand Total")</h3>
                        </blockquote>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card bg-primary text-center p-3">
                        <div>
                            <h1 class="mb-3 mt-1">
                                {{ @\App\Helpers\H::formatNumber($total_count) }}
                            </h1>
                        </div>

                        <blockquote class="blockquote mb-0">
                            <h3>@lang("cms.Jumlah Transaksi")</h3>
                        </blockquote>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card bg-danger text-center p-3">
                        <div>
                            <h2 class="mb-3 mt-1">
                                {{ @\App\Helpers\H::formatDateTime($start_time) }}
                            </h2>
                        </div>

                        <blockquote class="blockquote mb-0">
                            <h3>@lang("cms.Start Time")</h3>
                        </blockquote>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card bg-warning text-center p-3">
                        <div>
                            <h2 class="mb-3 mt-1">
                                {{ @\App\Helpers\H::formatDateTime($end_time) }}
                            </h2>
                        </div>

                        <blockquote class="blockquote mb-0">
                            <h3>@lang("cms.End Time")</h3>
                        </blockquote>
                    </div>
                </div>

            </div>



            <hr>

            <form action="{{ route("cms.merchant_branch.transaction_payment.list") }}" method="get">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Order ID")</label>
                            <input type="text" name="order_id" class="form-control" placeholder="@lang("cms.Search Order ID")" value="{{ $order_id }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.RRN")</label>
                            <input type="text" name="rrn" class="form-control" placeholder="@lang("cms.Search RRN")" value="{{ $rrn }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Grand Total")</label>
                            <input type="number" name="grand_total" class="form-control" placeholder="@lang("cms.Search Grand Total")" value="{{ $grand_total }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Customer Name")</label>
                            <input type="text" name="customer_data" class="form-control" placeholder="@lang("cms.Search Customer Name")" value="{{ $customer_data }}">
                        </div>
                    </div>

                    <div class="col-lg-10">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
                        </div>
                    </div>

                    <div class="col-lg-2">
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
                    <th>@lang("cms.Branch Name")</th>
                    <th>@lang("cms.Grand Total")</th>
                    <th>@lang("cms.Order ID")</th>
                    <th>@lang("cms.Transaction Time")</th>
                    <th>@lang("cms.RRN")</th>
                    <th>@lang("cms.Issuer Name")</th>
                    {{--<th>@lang("cms.Acquirer Name")</th>--}}
                    <th>@lang("cms.Customer Name")</th>
                    <th>@lang("cms.Type")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($transaction_payment_list as $index => $transaction_payment)
                    <tr>
                        <td>{{ @$merchant_branch->name }}</td>
                        <td>{{ number_format($transaction_payment->grand_total) }}</td>
                        <td>{{ $transaction_payment->partner_order_order_id }}</td>
                        <td>{{ \App\Helpers\H::formatDateTime($transaction_payment->transaction_time) }}</td>
                        <td>{{ @$transaction_payment->transaction_code }}</td>
                        <td>{{ @$transaction_payment->issuer_name }}</td>
                        {{--<td>{{ @$transaction_payment->acquirer_name }}</td>--}}
                        <td>{{ @$transaction_payment->customer_data }}</td>
                        <td>{{ @$transaction_payment->qris_qr_type }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route("cms.merchant_branch.transaction_payment.show", $transaction_payment->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });
    </script>
@endsection
