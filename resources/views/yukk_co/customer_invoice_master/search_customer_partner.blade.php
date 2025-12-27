@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Search Beneficiary")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.customer_invoice_master.index") }}" class="breadcrumb-item">@lang("cms.Beneficiary Invoice List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Search Beneficiary")</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>
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
                <form class="" action="{{ route("cms.yukk_co.customer_invoice_master.search_customer_partner") }}" method="GET">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="form-group">
                                <label>@lang("cms.Date Time Paid At Range")</label>
                                <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-block" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <div class="card-footer">

        </div>
    </div>

    @if (isset($result))
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">@lang("cms.Select for Create PG Invoice")</h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>

            <div class="collapse show">
                <div class="card-body">
                    <table class="table table-bordered dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Beneficiary Name")</th>
                            <th>@lang("cms.Partner Name")</th>
                            <th>@lang("cms.Count Transaction")</th>
                            <th>@lang("cms.Sum Grand Total")</th>
                            <th>@lang("cms.Sum Fee YUKK")</th>
                            <th>@lang("cms.Sum Fee Provider")</th>
                            <th>@lang("cms.Sum Fee Partner")</th>
                            <th>@lang("cms.Sum Invoiced Amount")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($result as $item)
                            <tr>
                                <td>{{ @$item->beneficiary->name }}</td>
                                <td>{{ @$item->partner->name }}</td>
                                <td class="text-right">{{ @$item->count }}</td>
                                <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($item->sum_grand_total) }}</td>
                                <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($item->sum_mdr_external - $item->sum_mdr_internal, 2) }}</td>
                                <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($item->sum_mdr_internal, 2) }}</td>
                                <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($item->sum_fee_partner, 2) }}</td>
                                <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($item->sum_mdr_external + $item->sum_fee_partner, 2) }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route("cms.yukk_co.customer_invoice_master.create_invoice", [
                                                    "customer_id" => @$item->beneficiary->id,
                                                    "partner_id" => @$item->partner->id,
                                                    "start_time" => $start_time->format("Y-m-d H:i:s"),
                                                    "end_time" => $end_time->format("Y-m-d H:i:s"),
                                                ]) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Preview kwitansi")</a>
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

            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();
            $(".select2").select2();


            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY HH:mm:ss',
                    firstDay: 1,
                },
                timePicker: true,
                timePicker24Hour: true,
            });
        });
    </script>
@endsection