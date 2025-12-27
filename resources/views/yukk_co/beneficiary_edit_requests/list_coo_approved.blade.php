@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Beneficiary Pending List")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Beneficiary Pending List")</span>
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
            <h5 class="card-title">@lang("cms.Beneficiary Pending List")</h5>
        </div>

        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="{{ route("cms.yukk_co.beneficiary_edit_request.list_coo") }}" class="nav-link">@lang("cms.Waiting List")</a></li>
                <li class="nav-item"><a href="#" class="nav-link active">@lang("cms.Approved List")</a></li>
                <li class="nav-item"><a href="{{ route("cms.yukk_co.beneficiary_edit_request.list_coo_rejected") }}" class="nav-link">@lang("cms.Rejected List")</a></li>
            </ul>


            <form action="{{ route("cms.yukk_co.beneficiary_edit_request.list_coo_approved") }}" method="get">
                <div class="row">

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>@lang("cms.Order ID")</label>
                            <input type="text" id="order_id" name="order_id" class="form-control" placeholder="@lang("cms.Search Order ID")" value="{{ $order_id }}">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Name")</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="@lang("cms.Search Name")" value="{{ $customer_name }}">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>@lang("cms.Type")</label>
                            <select id="bank_type" name="bank_type" class="form-control">
                                <option value="ALL" @if($bank_type == "ALL") selected @endif>@lang("cms.All")</option>
                                <option value="BCA" @if($bank_type == "BCA") selected @endif>BCA</option>
                                <option value="NON_BCA" @if($bank_type == "NON_BCA") selected @endif>NON BCA</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
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

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.ID User")</th>
                    <th>@lang("cms.Order ID")</th>
                    <th>@lang("cms.Name")</th>
                    <th>@lang("cms.Bank Name")</th>
                    <th>@lang("cms.Account Number")</th>
                    <th>@lang("cms.Bank Type")</th>
                    <th>@lang("cms.Branch Name")</th>
                    <th>@lang("cms.Account Name")</th>
                    <th>@lang("cms.Created At")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Approve by COO")</th>
                    <th>@lang("cms.Approve by CFO")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($beneficiary_edit_request_list as $index => $beneficiary_edit_request)
                    <tr>
                        <td>{{ @$beneficiary_edit_request->customer_id }}</td>
                        <td>{{ @$beneficiary_edit_request->order_id }}</td>
                        <td>{{ @$beneficiary_edit_request->customer->name }}</td>
                        <td>{{ @$beneficiary_edit_request->bank->name }}</td>
                        <td>{{ @$beneficiary_edit_request->account_number }}</td>
                        <td>{{ @$beneficiary_edit_request->bank_type }}</td>
                        <td>{{ @$beneficiary_edit_request->branch_name }}</td>
                        <td>{{ @$beneficiary_edit_request->account_name }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($beneficiary_edit_request->created_at) }}</td>
                        <td>{{ @$beneficiary_edit_request->status }}</td>
                        <td>{{ @$beneficiary_edit_request->approver_coo_email }}</td>
                        <td>{{ @$beneficiary_edit_request->approver_cfo_email }}</td>
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
                                <a href="{{ route("cms.yukk_co.beneficiary_edit_request.list_coo_approved", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.beneficiary_edit_request.list_coo_approved", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.beneficiary_edit_request.list_coo_approved", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });
    </script>
@endsection
