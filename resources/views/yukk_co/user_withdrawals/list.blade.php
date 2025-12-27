@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.User Withdrawal List")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.User Withdrawal List")</span>
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
            <h5 class="card-title">@lang("cms.User Withdrawal List")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.user_withdrawal.list") }}" method="get">
                <div class="row">

                    {{--<div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Order ID")</label>
                            <input type="text" name="order_id" class="form-control" placeholder="@lang("cms.Search Order ID")" value="{{ $order_id }}">
                        </div>
                    </div>--}}

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("Y-m-d H:i:s") }} - {{ $end_time->format("Y-m-d H:i:s") }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Ref Code")</label>
                            <input type="text" name="ref_code" class="form-control" placeholder="@lang("cms.Search Ref Code")" value="{{ $keyword_ref_code }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Bank Name")</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="@lang("cms.Search Bank Name")" value="{{ $keyword_bank_name }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Status")</label>
                            <select name="status" class="form-control">
                                <option value="ALL" @if($status == "ALL") selected @endif>@lang("cms.All")</option>
                                <option value="SUCCESS" @if($status == "SUCCESS") selected @endif>@lang("cms.Success")</option>
                                <option value="PENDING" @if($status == "PENDING") selected @endif>@lang("cms.Pending")</option>
                                <option value="REFUNDED" @if($status == "REFUNDED") selected @endif>@lang("cms.Refunded")</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Bank Type")</label>
                            <select name="bank_type" class="form-control">
                                <option value="ALL" @if($bank_type == "ALL") selected @endif>@lang("cms.All")</option>
                                <option value="BCA" @if($bank_type == "BCA") selected @endif>BCA</option>
                                <option value="NON_BCA" @if($bank_type == "NON_BCA") selected @endif>NON_BCA</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.YUKK ID")</label>
                            <input type="text" name="yukk_id" class="form-control" placeholder="@lang("cms.Search YUKK ID")" value="{{ $yukk_id }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
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
                    <th>@lang("cms.YUKK ID")</th>
                    <th>@lang("cms.Ref Code")</th>
                    <th>@lang("cms.Bank Name")</th>
                    <th>@lang("cms.Bank Type")</th>
                    <th>@lang("cms.YUKK Cash")</th>
                    <th>@lang("cms.Nominal Transfer")</th>
                    <th>@lang("cms.MDR Internal")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Created At")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($user_withdrawal_list as $index => $user_withdrawal)
                    <tr>
                        <td>{{ @$user_withdrawal->user->yukk_id }}</td>
                        <td>{{ @$user_withdrawal->ref_code }}</td>
                        <td>{{ @$user_withdrawal->bank_name }}</td>
                        <td>{{ @$user_withdrawal->bank_type }}</td>
                        <td>{{ @App\Helpers\H::formatNumber($user_withdrawal->yukk_p, 2) }}</td>
                        <td>{{ @App\Helpers\H::formatNumber($user_withdrawal->amount, 2) }}</td>
                        <td>{{ @App\Helpers\H::formatNumber(($user_withdrawal->fee_internal_fixed + $user_withdrawal->fee_internal_percentage), 2) }}</td>
                        <td>{{ @$user_withdrawal->status }}</td>
                        <td>{{ $user_withdrawal->created_at }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route("cms.yukk_co.user_withdrawal.item", $user_withdrawal->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                                <a href="{{ route("cms.yukk_co.user_withdrawal.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.user_withdrawal.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.user_withdrawal.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
                    format: 'YYYY-MM-DD',
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