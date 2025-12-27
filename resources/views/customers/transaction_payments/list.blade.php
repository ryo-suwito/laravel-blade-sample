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
            <form action="{{ route("cms.customer.transaction_payment.list") }}" method="get">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Merchant Branch")</label>
                            <input type="text" name="merchant_branch_name" class="form-control" placeholder="@lang("cms.Merchant Branch Name")" value="{{ $merchant_branch_name }}">
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Transaction Code")</label>
                            <input type="text" name="rrn" class="form-control" placeholder="@lang("cms.Transaction Code")" value="{{ $rrn }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Order ID")</label>
                            <input type="text" name="order_id" class="form-control" placeholder="@lang("cms.Order ID")" value="{{ $order_id }}">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="status">@lang("cms.Status")</label>
                            <select name="status" id="status" class="form-control">
                                <option value="ALL" @if($status == "ALL") selected @endif>@lang("cms.All")</option>
                                <option value="SUCCESS" @if($status == "SUCCESS") selected @endif>@lang("cms.Success")</option>
                                <option value="FAILED" @if($status == "FAILED") selected @endif>@lang("cms.Failed")</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
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
                    <th>@lang("cms.YUKK ID")</th>
                    <th>@lang("cms.Grand Total")</th>
                    <th>@lang("cms.MDR Value")</th>
                    <th>@lang("cms.Merchant Portion")</th>
                    <th>@lang("cms.Order ID")</th>
                    <th>@lang("cms.Transaction Code")</th>
                    <th>@lang("cms.Transaction Time")</th>
                    <th>@lang("cms.Issuer Name")</th>
                    <th>@lang("cms.Customer Name")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($transaction_payment_list as $index => $transaction_payment)
                    <tr>
                        <td>{{ @$transaction_payment->merchant_branch->name }}</td>
                        <td>{{ @$transaction_payment->user->yukk_id }}</td>
                        <td>{{ @number_format($transaction_payment->grand_total, 0, ".", ",") }}</td>
                        <td>{{ @number_format(($transaction_payment->yukk_portion + $transaction_payment->fee_partner_percentage + $transaction_payment->fee_yukk_additional_percentage + $transaction_payment->fee_partner_fixed + $transaction_payment->fee_yukk_additional_fixed), 2, ".", ",") }}</td>
                        <td>{{ @number_format($transaction_payment->merchant_portion, 2, ".", ",") }}</td>
                        <td>{{ @$transaction_payment->partner_order_order_id }}</td>
                        <td>{{ @$transaction_payment->transaction_code }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($transaction_payment->transaction_time, "Y-m-d H:i:s") }}</td>
                        <td>{{ @$transaction_payment->issuer_name }}</td>
                        <td>{{ @$transaction_payment->customer_data }}</td>
                        <td>{{ @$transaction_payment->status }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route("cms.customer.transaction_payment.show", @$transaction_payment->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                                <a href="{{ route("cms.customer.transaction_payment.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.customer.transaction_payment.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.customer.transaction_payment.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
