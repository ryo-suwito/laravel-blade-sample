@extends('layouts.master')

@section('header')
<!-- Page header -->
<style>
    .bootstrap-select .no-results {
        background: #2c2d33 !important;
    }

    .bootstrap-select .dropdown-menu.inner {
        max-height: 300px;
    }

    div.dropdown-menu.show {
        max-width: 240px !important;
        max-height: 364px !important;
    }

    a.dropdown-item.selected {
        color: #65bbf9 !important;
    }

    li > a > span.text {
        white-space: break-spaces;
        margin-right: 10px !important;
    }

    .bootstrap-select.show-tick .dropdown-menu .selected span.check-mark {
        right: 15px !important;
        top: 25% !important;
    }

    .table th {
        white-space: nowrap;
    }
</style>
<div class="page-header page-header-light">
    <div class="page-header-content d-sm-flex">
        <div class="page-title">
            <h4>Top Up Balance</h4>
        </div>

        <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">

        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                <span class="breadcrumb-item active">Top Up Balance</span>
            </div>

        </div>

    </div>
</div>
<!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <a href="{{ route('money_transfer.provider_deposits.index') }}" class="btn @if($tab == 'all') btn-secondary @else btn-outline-secondary @endif mr-2">
                    All @if(($status_counter['success']+$status_counter['pending']+$status_counter['failed']) > 0)<span class="badge badge-success ml-2">{{ ($status_counter['success']+$status_counter['pending']+$status_counter['failed']) }}</span>@endif
                </a>
                @foreach($status_counter as $key => $stat)
                    <a href="{{ $tab == $key ? '#' : route('money_transfer.provider_deposits.index').'?tab='.$key }}" class="btn @if($tab == $key) btn-secondary @else btn-outline-secondary @endif mr-2">
                        {{ ucfirst($key) }} @if($stat > 0)<span class="badge badge-success ml-2">{{ $stat }}</span>@endif
                    </a>
                @endforeach
            </div>
            <hr>
            <form action="{{ route('money_transfer.provider_deposits.index').'?tab='.$tab }}" method="get">
                <div class="row">

                <input type="hidden" name="tab" value="{{ $tab }}">

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y") }} - {{ $end_time->format("d-M-Y") }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Search")</label>
                            <input type="text" id="search" name="search" class="form-control" placeholder="@lang("cms.Search ID")"
                            value="{{ $search }}">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>Tag</label>
                            <select name="tag" id="tag" class="form-control">
                                <option value="">All</option>
                                <option @if ($tag == "BENEFICIARY")
                                    selected
                                @endif value="BENEFICIARY">Beneficiary</option>
                                <option @if ($tag == "PARTNER")
                                    selected
                                @endif  value="PARTNER">Partner</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="input-group input-group-append position-static">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="w-auto">Ref Id</th>
                        <th class="w-auto">Top Up Id</th>
                        <th class="w-auto">Provider Transaction Id</th>
                        <th class="w-auto">Tag</th>
                        <th class="w-auto">Bank Transfer</th>
                        <th class="w-auto">@lang("cms.Bank Account Number")</th>
                        <th class="w-auto">@lang("cms.Amount")</th>
                        <th class="w-auto">@lang("cms.Unique Code")</th>
                        <th class="w-auto">@lang("cms.Created At")</th>
                        <th class="w-auto">@lang("cms.Status")</th>
                        <th class="w-auto">@lang("cms.Actions")</th>
                    </tr>
                    </thead>

                    <tbody>
                        @if (count($deposits) == 0)
                            <tr>
                                <td colspan="11">Data Not Found</td>
                            </tr>
                        @endif
                        @foreach($deposits as $depo)
                            <tr>
                                <td>{{ $depo['transaction_item_group']['code'] }}</td>
                                <td>{{ $depo['code'] }}</td>
                                <td>{{ $depo['provider_transaction_id'] }}</td>
                                <td>{{ ucfirst(strtolower($depo['entity_type'])) }}</td>
                                <td>{{ $depo['bank']['name'] }}</td>
                                <td>{{ $depo['provider_account_number'] }}</td>
                                <td>{{ number_format($depo['amount'],0,',','.') }}</td>
                                <td>{{ number_format($depo['unique_code'],0,',','.') }}</td>
                                <td>{{ $depo['created_at_format'] }}</td>
                                <td><span class="badge @if($depo['status'] == 'PENDING')
                                        badge-warning
                                    @elseif($depo['status'] == 'SUCCESS')
                                        badge-success
                                    @else 
                                        badge-danger
                                    @endif
                                    ">{{ $depo['status'] }}</span></td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('money_transfer.provider_deposits.detail', ['id' => $depo['id']] ) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                <div class="col-lg-12">
                    <div class="float-left">
                        <div class="d-flex">
                            <span class="mr-2" style="margin:auto;">Total </span>
                            <span style="margin:auto;">{{ $total }}</span>
                        </div>
                    </div>
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("money_transfer.provider_deposits.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("money_transfer.provider_deposits.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("money_transfer.provider_deposits.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
        });
    </script>
@endsection
