@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Platform Settings")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Platform Settings")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('platform_setting.index') }}" method="get">
                <div class="row justify-content-between">
                    <div class="row col-lg-6">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <input type="text" id="search" name="search" class="form-control" placeholder="@lang("cms.Search")" value="{{ $search }}">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group input-group-append position-static">
                                    <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row col-3 justify-content-center">
                        <a class="w-75" href="{{ route('platform_setting.create') }}">
                            <button class="btn btn-primary form-control" type="button"><i class="fas fa-plus mr-1"></i>@lang("cms.Create")</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th class="text-center">@lang("cms.Entity Type")</th>
                        <th class="text-center">@lang("cms.Entity Name")</th>
                        <th class="text-center">@lang("cms.Actions")</th>
                    </tr>
                </thead>

                <tbody>
                @if($datas)
                    @foreach($datas as $data)
                        <tr>
                            <td class="text-center">{{ @$data->entity_type }}</td>
                            <td class="text-center">{{ @$data->name  }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('platform_setting.edit', $data->id) }}"
                                               class="dropdown-item"><i class="icon-pencil7"></i>
                                                @lang('cms.Edit')
                                            </a>
                                            <a href="{{ route('platform_setting.detail', $data->id) }}"
                                               class="dropdown-item"><i class="icon-zoomin3"></i>
                                                @lang('cms.Detail')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center">No Data Found!</td>
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
                <form method="get" action="{{ route('platform_setting.index') }}">
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
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('platform_setting.index', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
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
                                    <a href="{{ route('platform_setting.index', array_merge(request()->all(), ['page' => $i])) }}"
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
                                <a href="{{ route('platform_setting.index', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                   class="page-link">
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
