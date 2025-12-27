@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Transaction Merchant Online")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Transaction Merchant Online")</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <style>
        .badge-success {
            background-color: #80be63;
        }

        .badge-refunded {
            background-color: #be87d6;
        }

        .badge-canceled {
            background-color: #8d8d8d;
        }

        .badge-completed {
            background-color: #4da325;
        }

        .badge-pending {
            background-color: #d6af14;
        }

        .badge-expired {
            background-color: #a03c3c;
        }

        .badge-failed {
            background-color: #df4e4e;
        }
    </style>
    <div class="card mt-4">
        <form action="{{ route('transaction_merchant_online.index') }}" method="GET">
            <div class="card-header form-group">
                <div class="row">
                    <div class="form-group col-2">
                        <label for="search">@lang('cms.Search')</label>
                        <input type="text" id="search" name="search" class="form-control" value="{{ $search }}" placeholder="@lang('cms.Search')" onchange='if(this.value != 0) { this.form.submit(); }'>
                    </div>
                    <div class="form-group col-2">
                        <label for="status">@lang('cms.Status')</label>
                        <select id="status" name="status" class="form-control select2" onchange='if(this.value != null) { this.form.submit(); }'>
                            <option selected value="">@lang('cms.Select One')</option>
                            <option @if($status == 'PENDING') selected @endif value="PENDING">@lang('cms.PENDING')</option>
                            <option @if($status == 'FAILED') selected @endif value="FAILED">@lang('cms.FAILED')</option>
                            <option @if($status == 'SUCCESS') selected @endif value="SUCCESS">@lang('cms.SUCCESS')</option>
                            <option @if($status == 'EXPIRED') selected @endif value="EXPIRED">@lang('cms.EXPIRED')</option>
                            <option @if($status == 'CANCELED') selected @endif value="CANCELED">@lang('cms.CANCELED')</option>0
                            <option @if($status == 'REFUNDED') selected @endif value="REFUNDED">@lang('cms.REFUNDED')</option>
                            <option @if($status == 'COMPLETED') selected @endif value="COMPLETED">@lang('cms.COMPLETED')</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label for="type">@lang('cms.Date Range')</label>
                        <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y") }} - {{ $end_time->format("d-M-Y") }}">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th class="text-center">@lang('cms.Entity Type')</th>
                            <th class="text-center">@lang('cms.Entity Name')</th>
                            <th class="text-center">@lang('cms.YUKK ID')</th>
                            <th class="text-center">@lang('cms.Ref Code')</th>
                            <th class="text-center">@lang('cms.Partner Ref Code')</th>
                            <th class="text-center">@lang('cms.Final Amount')</th>
                            <th class="text-center">@lang('cms.MDR Fee')</th>
                            <th class="text-center">@lang('cms.Status')</th>
                            <th class="text-center">@lang('cms.Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($transaction_list as $transaction)
                        <tr>
                            <td class="text-center">{{ @$transaction->type_type}}</td>
                            <td class="text-center">{{ @$transaction->type->name }}</td>
                            <td class="text-center">{{ @$transaction->user->yukk_id }}</td>
                            <td class="text-center">{{ @$transaction->code }}</td>
                            <td class="text-center">{{ @$transaction->bill_code }}</td>
                            <td class="text-center">{{ @$transaction->final_amount }}</td>
                            <td class="text-center">{{ @$transaction->yukk_co_portion }}</td>
                            <td class="text-center">
                                @if($transaction->status == 'SUCCESS')
                                    <span class="badge badge-success">
                                @elseif($transaction->status == 'EXPIRED')
                                    <span class="badge badge-expired">
                                @elseif($transaction->status == 'PENDING')
                                    <span class="badge badge-pending">
                                @elseif($transaction->status == 'CANCELED')
                                    <span class="badge badge-canceled">
                                @elseif($transaction->status == 'FAILED')
                                    <span class="badge badge-failed">
                                @elseif($transaction->status == 'COMPLETED')
                                    <span class="badge badge-completed">
                                @elseif($transaction->status == 'REFUNDED')
                                    <span class="badge badge-refunded">
                                @endif
                                    {{ @$transaction->status }}
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('transaction_merchant_online.detail', @$transaction->id) }}" class="dropdown-item"><i class="icon-info22"></i>
                                                @lang("cms.Detail")
                                            </a>
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
                    <div class="form-group ml-2 mt-1">
                        {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
                    </div>
                    <div class="col-1">
                        <select class="select2 form-group" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                            <option @if($per_page == 10) selected @endif>10</option>
                            <option @if($per_page == 25) selected @endif>25</option>
                            <option @if($per_page == 50) selected @endif>50</option>
                            <option @if($per_page == 100) selected @endif>100</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination pagination-flat justify-content-end">
                            @php($plus_minus_range = 3)
                            @if ($current_page == 1)
                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                            class="icon-arrow-left12"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('transaction_merchant_online.index', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                       class="page-link"><i class="icon-arrow-left12"></i></a>
                                </li>
                            @endif
                            @if ($current_page - $plus_minus_range > 1)
                                <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                            @endif
                            @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                                @if ($i == $current_page)
                                    <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ route('transaction_merchant_online.index', array_merge(request()->all(), ['page' => $i])) }}"
                                           class="page-link">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                            @if ($current_page + $plus_minus_range < $last_page)
                                <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                            @endif
                            @if ($current_page == $last_page)
                                <li class="page-item disabled"><a href="#" class="page-link"><i
                                            class="icon-arrow-right13"></i></a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('transaction_merchant_online.index', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                       class="page-link">
                                        <i class="icon-arrow-right13"></i>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        </form>
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
        });
    </script>
@endsection
