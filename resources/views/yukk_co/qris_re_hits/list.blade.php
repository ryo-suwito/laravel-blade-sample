@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.QRIS ReHit")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.QRIS ReHit")</span>
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
            <h5 class="card-title">@lang("cms.QRIS ReHit")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("QRIS_RE_HIT.CREATE", "AND"))
                        <a href="{{ route("cms.yukk_co.qris_re_hit.create") }}" type="button" class="btn btn-primary w-100 w-sm-auto float-right">@lang("cms.Add New") @lang("cms.QRIS ReHit")</a>
                    @endif
                </div>
            </div>

            <form action="{{ route("cms.yukk_co.qris_re_hit.list") }}" method="get">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Keyword")</label>
                            <input type="text" name="keyword" class="form-control" placeholder="@lang("cms.Keyword")" value="{{ $keyword }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Status")</label>
                            <select class="form-control" name="status">
                                <option value="PENDING" @if($status == "PENDING") selected @endif>PENDING</option>
                                <option value="APPROVED" @if($status == "APPROVED") selected @endif>APPROVED</option>
                                <option value="REJECTED" @if($status == "REJECTED") selected @endif>REJECTED</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                            {{--<div class="input-group input-group-append position-static">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                                <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                <div class="dropdown-menu dropdown-menu-right" style="">
                                    <button class="dropdown-item" name="export_to_csv" value="1"><i class="icon-file-download"></i> @lang("cms.Export to CSV")</button>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.RRN")</th>
                    <th>@lang("cms.Amount")</th>
                    <th>@lang("cms.Merchant Branch Name")</th>
                    <th>@lang("cms.Created At")</th>
                    <th>@lang("cms.Type")</th>
                    <th>@lang("cms.Order ID")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($qris_re_hit_list as $index => $qris_re_hit)
                    <tr>
                        <td>{{ @$qris_re_hit->rrn }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($qris_re_hit->transaction_amount) }}</td>
                        <td>{{ @$qris_re_hit->merchant_name }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($qris_re_hit->created_at) }}</td>
                        <td>{{ @$qris_re_hit->qris_type }}</td>
                        <td>
                            @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("PARTNER_ORDER.VIEW", "AND"))
                                @if (@$qris_re_hit->partner_order_id)
                                    <a href="{{ route("cms.yukk_co.partner_order.show", @$qris_re_hit->partner_order_id) }}">
                                        {{ @$qris_re_hit->partner_order_order_id }}
                                    </a>
                                @else
                                    {{ @$qris_re_hit->partner_order_order_id }}
                                @endif
                            @else
                                {{ @$qris_re_hit->partner_order_order_id }}
                            @endif
                        </td>
                        <td>{{ @$qris_re_hit->status }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route("cms.yukk_co.qris_re_hit.item", $qris_re_hit->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                                <a href="{{ route("cms.yukk_co.qris_re_hit.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.qris_re_hit.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.qris_re_hit.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
            //$(".dataTable").DataTable();
        });
    </script>
@endsection