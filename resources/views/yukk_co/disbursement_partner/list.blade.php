@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Disbursement Partner")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Disbursement Partner")</span>
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
            <h5 class="card-title">@lang("cms.Disbursement Partner")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.disbursement_partner.list") }}" method="get">
                <div class="row">

                    <div class="col-lg-9">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
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

            <div class="form-group row">
                <div class="col-auto ml-auto">
                    <select name="per_page" id="per_page" class="form-control">
                        <option value="10" @if(request()->per_page == 10) selected @endif>10</option>
                        <option value="25" @if(request()->per_page == 25) selected @endif>25</option>
                        <option value="50" @if(request()->per_page == 50) selected @endif>50</option>
                        <option value="100" @if(request()->per_page == 100) selected @endif>100</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.Partner Name")</th>
                    <th>@lang("cms.Disbursement Date")</th>
                    <th>@lang("cms.Ref Code")</th>
                    <th>@lang("cms.Total Fee Partner")</th>
                    <th>@lang("cms.Disbursement Fee")</th>
                    <th>@lang("cms.Rounding")</th>
                    <th>@lang("cms.Disbursement Amount")</th>
                    {{--<th>@lang("cms.Partner Bank Type")</th>--}}
                    <th>@lang("cms.Transfer Using")</th>
                    <th>@lang("cms.Bank Name")</th>
                    <th>@lang("cms.Account Number")</th>
                    <th>@lang("cms.Account Name")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($disbursement_partner_list as $index => $disbursement_partner)
                    <tr>
                        <td>{{ @$disbursement_partner->partner->name }}</td>
                        <td>{{ @$disbursement_partner->disbursement_date }}</td>
                        <td>{{ @$disbursement_partner->ref_code }}</td>
                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner, 2) }}</td>
                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner->disbursement_fee, 2) }}</td>
                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner->rounding, 2) }}</td>
                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_disbursement, 2) }}</td>
                        {{--<td>{{ @$disbursement_partner->bank_type }}</td>--}}
                        <td>{{ @$disbursement_partner->transfer_using }}</td>
                        <td>{{ @$disbursement_partner->bank->name }}</td>
                        <td>{{ @$disbursement_partner->partner_account_number }}</td>
                        <td>{{ @$disbursement_partner->partner_account_name }}</td>
                        <td>{{ @$disbursement_partner->status }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route("cms.yukk_co.disbursement_partner.item", @$disbursement_partner->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                                <a href="{{ route("cms.yukk_co.disbursement_partner.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.disbursement_partner.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.disbursement_partner.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
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
