@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Partner Payout List")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Partner Payout List")</span>
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
    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("PG_INVOICE.CREATE_PAYOUT", "AND"))
        <div class="row">
            <div class="col-sm-12 text-right">
                <a href="{{ route("cms.yukk_co.partner_payout_master.create_search") }}" class="btn btn-primary"><i class="icon-plus3"></i> @lang("cms.Create Partner Payout")</a>
            </div>
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Partner Payout List")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.partner_payout_master.index") }}" method="get">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="partner_id">@lang("cms.Partner")</label>
                            <select class="form-control select2" name="partner_id" id="partner_id">
                                <option value="-1">@lang("cms.All")</option>
                                @foreach(@$partner_list as $partner)
                                    <option value="{{ $partner->id }}" @if(@$partner_id == $partner->id) selected @endif>{{ $partner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="created_at">@lang("cms.Created At")</label>
                            <input class="form-control date_range" name="created_at" id="created_at" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}"/>
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

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>@lang("cms.Partner Name")</th>
                    <th>@lang("cms.Ref Code")</th>
                    <th>@lang("cms.Total Fee Partner")</th>
                    <th>@lang("cms.Invoices")</th>
                    <th>@lang("cms.Created At")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($partner_payout_master_list as $index => $partner_payout_master)
                    <tr>
                        <td>{{ @$partner_payout_master->partner ? $partner_payout_master->partner->name : "-" }}</td>
                        <td>{{ @$partner_payout_master->ref_code }}</td>
                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($partner_payout_master->sum_fee_partner_fixed + $partner_payout_master->sum_fee_partner_percentage, 2) }}</td>
                        <td class="text-center">{{ @$partner_payout_master->count_done_invoice }}/{{ $partner_payout_master->count_all_invoice }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($partner_payout_master->created_at) }}</td>
                        <td>{{ @$partner_payout_master->status }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route("cms.yukk_co.partner_payout_master.show", $partner_payout_master->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if (count($partner_payout_master_list) <= 0)
                    <tr>
                        <td class="text-center" colspan="7">@lang("cms.No Data Found")</td>
                    </tr>
                @endif
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
                                <a href="{{ route("cms.yukk_co.partner_payout_master.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.partner_payout_master.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.partner_payout_master.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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

            $("#created_at").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY HH:mm:ss',
                    firstDay: 1,
                },
            });
        });
    </script>
@endsection