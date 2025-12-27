@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Merchant List')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">@lang('cms.Merchant List')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header form-group row">
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                <div class="dropdown p-0">
                    @if (in_array('MASTER_DATA.MERCHANT.UPDATE', $access_control))
                        <a class="dropdown-item btn-primary" href="{{ route('yukk_co.merchant.add') }}">
                            <i class="icon-add"></i>@lang('cms.Add')
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            <form method="get" action="{{ route('yukk_co.merchants.list') }}">
                <div class="form-group row">
                    <div class="col-md-2">
                        <select class="form-control select2" name="field" id="field">
                            <option value="name" @if($field == 'name') selected @endif>Name</option>
                            <option value="category" @if($field == 'category') selected @endif>Category</option>
                            <option value="mcc" @if($field == 'mcc') selected @endif>MCC</option>
                            <option value="company" @if($field == 'company') selected @endif>Company</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" name="search" id="search" value="{{ $search }}" onchange='if(this.value != 0) { this.form.submit(); }'>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>@lang('cms.ID')</th>
                        <th>@lang('cms.Name')</th>
                        <th>@lang('cms.Category')</th>
                        <th>@lang('cms.MCC Name')</th>
                        <th>@lang('cms.Company Name')</th>
                        <th>@lang('cms.Created At')</th>
                        <th>@lang('cms.Actions')</th>
                    </tr>
                </thead>

                <tbody>
                @if($merchants)
                    @foreach($merchants as $merchant)
                        <tr>
                            <td>{{ @$merchant->id }}</td>
                            <td>{{ @$merchant->name }}</td>
                            <td>{{ @$merchant->category->name }}</td>
                            <td>{{ @$merchant->merchant_mcc->description }}</td>
                            <td>{{ @$merchant->company->name }}</td>
                            <td>{{ @$merchant->created_at }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if(in_array('MASTER_DATA.MERCHANT.UPDATE', $access_control))
                                                <a href="{{ route("yukk_co.merchant.detail", $merchant->id) }}" class="form-control dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                            @endif
                                            @if(in_array('MASTER_DATA.MERCHANT.VIEW', $access_control))
                                                <a href="{{ route("yukk_co.merchant.show", $merchant->id) }}" class="form-control dropdown-item"><i class="icon-zoomin3"></i> @lang("cms.Detail")</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="text-center">
                        <td colspan="7"> Data Not Found</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="form-group ml-2 mt-1">
                    {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
                </div>
                <form method="get" action="{{ route('yukk_co.merchants.list') }}">
                    <div class="row ml-3">
                        <div class="form-group">
                            <select class="select2 form-group" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                                <option @if($per_page == 10) selected @endif>10</option>
                                <option @if($per_page == 25) selected @endif>25</option>
                                <option @if($per_page == 50) selected @endif>50</option>
                                <option @if($per_page == 100) selected @endif>100</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("yukk_co.merchants.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("yukk_co.merchants.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("yukk_co.merchants.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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

            $(".delete-merchant").click(function (e) {
                if (!window.confirm("Are You Sure Want To Delete This Merchant?")) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
