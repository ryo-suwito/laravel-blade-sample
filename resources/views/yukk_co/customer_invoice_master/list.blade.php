@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Beneficiary Invoice List")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Beneficiary Invoice List")</span>
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
        <div class="col-sm-12 text-right">
            <a href="{{ route("cms.yukk_co.customer_invoice_master.search_customer_partner") }}" class="btn btn-primary"><i class="icon-search4"></i> @lang("cms.Search Beneficiary")</a>
            <a href="{{ route("cms.yukk_co.customer_invoice_provider.index") }}" class="btn btn-primary"><i class="icon-search4"></i> @lang("cms.Search Fee Provider")</a>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Beneficiary Invoice List")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.customer_invoice_master.index") }}" method="get">
                <div class="form-group row">
                    <label class="col-form-label col-sm-2" for="customer_id">@lang("cms.Beneficiary")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="customer_id" id="customer_id">
                            <option value="-1">@lang("cms.All")</option>
                            @foreach(@$customer_list as $customer)
                                <option value="{{ $customer->id }}" @if(@$customer_id == $customer->id) selected @endif>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="col-form-label col-sm-2" for="partner_id">@lang("cms.Partner")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="partner_id" id="partner_id">
                            <option value="-1">@lang("cms.All")</option>
                            @foreach(@$partner_list as $partner)
                                <option value="{{ $partner->id }}" @if(@$partner_id == $partner->id) selected @endif>{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2" for="invoice_date">@lang("cms.kwitansi date")</label>
                    <div class="col-sm-4">
                        <input class="form-control date_range" name="invoice_date" id="invoice_date" value="{{ $start_date->format("d-M-Y") }} - {{ $end_date->format("d-M-Y") }}"/>
                    </div>

                    <label class="col-form-label col-sm-2" for="invoice_number">@lang("cms.kwitansi number")</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="invoice_number" id="invoice_number" value="{{ @$invoice_number }}"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3 offset-sm-9">
                        <div class="input-group input-group-append position-static">
                            <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                            <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                            <div class="dropdown-menu dropdown-menu-right" style="">
                                <button class="dropdown-item" name="export_to_csv" value="1"><i class="icon-file-download"></i> @lang("cms.Export to CSV")</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable mt-3">
                <thead>
                <tr>
                    <th>@lang("cms.Beneficiary Name")</th>
                    <th>@lang("cms.Partner Name")</th>
                    <th>@lang("cms.Kwitansi Number")</th>
                    <th>@lang("cms.Kwitansi Date")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($customer_invoice_master_list as $customer_invoice_master)
                    <tr>
                        <td>{{ $customer_invoice_master->customer->name }}</td>
                        <td>{{ $customer_invoice_master->partner->name }}</td>
                        <td>{{ $customer_invoice_master->invoice_number }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($customer_invoice_master->invoice_date, "d-M-Y") }}</td>
                        <td class="text-center">
                            @if($customer_invoice_master->status == "DRAFT")
                                <span class="badge badge-pill badge-primary">@lang("cms.Draft")</span>
                            @elseif($customer_invoice_master->status == "POSTED")
                                <span class="badge badge-pill badge-success">@lang("cms.Posted")</span>
                            @else
                                <span class="badge badge-pill badge-secondary">{{ $customer_invoice_master->status }}</span>

                            @endif
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route("cms.yukk_co.customer_invoice_master.item", $customer_invoice_master->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>

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
                                <a href="{{ route("cms.yukk_co.customer_invoice_master.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.customer_invoice_master.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.customer_invoice_master.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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

            $(".date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
                timePicker: false,
                timePicker24Hour: false,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });
    </script>
@endsection