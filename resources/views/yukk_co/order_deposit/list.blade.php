@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Convert Yukk Cash List")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Convert Yukk Cash List")</span>
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
            <h5 class="card-title">@lang("cms.Convert Yukk Cash List")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.order_deposit.list") }}" method="get">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Status")</label>
                            <select class="form-control" name="status">
                                <option value="ACCEPTED" @if($status == "ACCEPTED") selected @endif>ACCEPTED</option>
                                <option value="ALL" @if($status == "ALL") selected @endif>ALL</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">         
                        <div class="form-group">
                            <label>@lang("cms.Platform")</label>       
                            <br/>          
                            <select id="platform_id" data-live-search="true" name="platform_id" data-live-search-style="contains" class="selectpicker" class="form-control">
                                <option value="">Select Platform</option>
                                @foreach($platforms as $platform)
                                @if($platform_id && in_array(@$platform->id, $platform_id))
                                <option value="{{ @$platform->id }}" selected>{{@$platform->name}}</option>
                                @endif
                                @endforeach
                                @foreach($platforms as $platform)
                                @if(!($platform_id && in_array(@$platform->id, $platform_id)))
                                <option value="{{ @$platform->id }}">{{@$platform->name}}</option>
                                @endif
                                @endforeach
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
                                    <button class="dropdown-item" name="export_to_csv" value="1"><i class="icon-file-download"></i> @lang("cms.Export to CSV")</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <div class="row">
                <div class="col-sm-6">
                    <div class="card bg-secondary text-center p-3">
                        <div>
                            <h1 class="mb-3 mt-1">
                                @lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($sum_raw_amount) }}
                            </h1>
                        </div>

                        <blockquote class="blockquote mb-0">
                            <h3>@lang("cms.Sum Raw Amount")</h3>
                            <footer class="blockquote-footer">
                                <span>
                                    @lang("cms.sum_raw_amount_help_text")
                                </span>
                            </footer>
                        </blockquote>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card bg-success text-center p-3">
                        <div>
                            <h1 class="mb-3 mt-1">
                                @lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($sum_total_amount, 2) }}
                            </h1>
                        </div>

                        <blockquote class="blockquote mb-0">
                            <h3>@lang("cms.Sum Total Amount")</h3>
                            <footer class="blockquote-footer">
                                <span>
                                    @lang("cms.sum_total_amount_help_text")
                                </span>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.Order ID")</th>
                    <th>@lang("cms.Ref Code")</th>
                    <th>@lang("cms.Raw Amount")</th>
                    <th>@lang("cms.Total Amount")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Created At")</th>
                    {{--<th>@lang("cms.Actions")</th>--}}
                </tr>
                </thead>

                <tbody>
                @foreach($order_deposit_list as $index => $order_deposit)
                    <tr>
                        <td>{{ @$order_deposit->ref_id }}</td>
                        <td>{{ @$order_deposit->ref_code }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($order_deposit->raw_amount) }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($order_deposit->total_amount, 2) }}</td>
                        <td>{{ @$order_deposit->status }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($order_deposit->created_at) }}</td>
                        {{--<td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        --}}{{--<a href="{{ route("cms.yukk_co.transaction_payment.show", $order_deposit->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>--}}{{--
                                    </div>
                                </div>
                            </div>
                        </td>--}}
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
                                <a href="{{ route("cms.yukk_co.order_deposit.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.order_deposit.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.order_deposit.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
