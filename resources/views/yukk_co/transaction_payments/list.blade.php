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
                    <span class="breadcrumb-item active">@lang("cms.Transaction Payment List")</span>
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
                <form action="{{ route("cms.yukk_co.transaction_payment.list") }}" method="get">
                    <div class="row">

                        {{--<div class="col-lg-4">
                            <div class="form-group">
                                <label>@lang("cms.Order ID")</label>
                                <input type="text" name="order_id" class="form-control" placeholder="@lang("cms.Search Order ID")" value="{{ $order_id }}">
                            </div>
                        </div>--}}

                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2" for="date_range">
                                    @lang("cms.Transaction Time")
                                </label>
                                <div class="col-lg-10">
                                    <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Transaction Time")" value="@if($start_time && $end_time) {{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }} @endif">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2" for="rrn">
                                    @lang("cms.RRN")
                                </label>
                                <div class="col-lg-10">
                                    <input type="text" id="rrn" name="rrn" class="form-control" placeholder="@lang("cms.Search RRN")" value="{{ $rrn }}">
                                </div>
                            </div>
                            {{--<div class="form-group row">
                                <label class="col-form-label col-lg-2" for="grand_total">
                                    @lang("cms.Grand Total")
                                </label>
                                <div class="col-lg-10">
                                    <input type="text" id="grand_total" name="grand_total" class="form-control" placeholder="@lang("cms.Search Grand Total")" value="{{ $grand_total }}">
                                </div>
                            </div>--}}
                            <div class="form-group row">
                                <label class="col-12 col-lg-9">&nbsp;</label>
                                <div class="col-12 col-lg-3">
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
                    </div>
                </form>
                
                <div class="form-group row">
                    <div class="col-auto ml-auto">
                        <select class="form-control" name="per_page" id="per_page">
                            <option value="10" @if(request()->per_page == 10) selected @endif>10</option>
                            <option value="25" @if(request()->per_page == 25) selected @endif>25</option>
                            <option value="50" @if(request()->per_page == 50) selected @endif>50</option>
                            <option value="100" @if(request()->per_page == 100) selected @endif>100</option>
                        </select>
                    </div>
                </div>
                
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>@lang("cms.RRN")</th>
                        <th>@lang("cms.Transaction Time")</th>
                        <th>@lang("cms.Merchant Branch Name")</th>
                        <th>@lang("cms.Beneficiary")</th>
                        <th>@lang("cms.Partner")</th>
                        <th>@lang("cms.Grand Total")</th>
                        <th>@lang("cms.YUKK As")</th>
                        <th>@lang("cms.YUKK ID")</th>
                        <th>@lang("cms.Status")</th>
                        <th>@lang("cms.Actions")</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($transaction_payment_list as $transaction_payment)
                        <tr>
                            <td>{{ @$transaction_payment->transaction_code }}</td>
                            <td>{{ @\App\Helpers\H::formatDateTime($transaction_payment->transaction_time) }}</td>
                            <td>{{ @$transaction_payment->merchant_branch_name }}</td>
                            <td>{{ @$transaction_payment->customer->name }}</td>
                            <td>{{ @$transaction_payment->partner->name }}</td>
                            <td class="text-right">{{ @\App\Helpers\H::formatNumber($transaction_payment->grand_total) }}</td>
                            <td>{{ @$transaction_payment->yukk_as }}</td>
                            <td>{{ @$transaction_payment->user->yukk_id }}</td>
                            <td>{{ @$transaction_payment->status }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route("cms.yukk_co.transaction_payment.item", $transaction_payment->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6">
                    @if ($showing_data['total'] > 0)
                        <p>
                            Showing {{ $showing_data['from'] }} to {{ $showing_data['to'] }} of {{ $showing_data['total'] }} entries
                        </p>
                    @endif
                </div>
                <div class="col-lg-6">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("cms.yukk_co.transaction_payment.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.transaction_payment.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.transaction_payment.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
            $(".dataTable").DataTable();




            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY HH:mm:ss',
                    firstDay: 1,
                    cancelLabel: 'Clear',
                },
                timePicker: false,
                timePicker24Hour: true,
                autoUpdateInput: false,
            });

            $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                //do something, like clearing an input
                $(this).val('');
            });

            $("#date_range").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MMM-YYYY HH:mm:ss') + ' - ' + picker.endDate.format('DD-MMM-YYYY HH:mm:ss'));
            });

            $("#per_page").change(function() {
                var per_page = $(this).val();
                var currentQuery = new URLSearchParams(window.location.search);
                currentQuery.delete('page'); currentQuery.set('per_page', per_page);
                window.location.href = window.location.pathname + "?" + currentQuery.toString();
            });
        });
    </script>
@endsection
