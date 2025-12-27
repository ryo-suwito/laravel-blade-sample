@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Merchant Branch List')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">@lang('cms.Merchant Branch List')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header bg-transparent header-elements-sm-inline py-sm-0">
            <h4 class="card-title py-sm-3 mt-3"></h4>
            <div class="header-elements">
                @if (in_array('MASTER_DATA.MERCHANT_BRANCH.UPDATE', $access_control))
                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                        data-target="#modal-add-bulk-merchant-branch">
                        <i class="icon-add mr-2"></i>@lang('cms.Add Bulk')
                    </button>

                    <a class="btn btn-primary mx-auto" href="{{ route('yukk_co.merchant_branch.add') }}">
                        <i class="icon-add mr-2"></i>@lang('cms.Add')
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('yukk_co.merchant_branch.list') }}" method="GET">
                <div class="row justify-content-between">
                    <div class="row col-lg-11">
                        <div class="row ml-2">
                            <div class="form-group mr-2">
                                <label>@lang('cms.Merchant Branch')</label>
                                <input type="text" name="branch" value="{{ $branch }}" class="form-control" placeholder="@lang('cms.Search')">
                            </div>
                            <div class="form-group mr-2 mt-2">
                                <label></label>
                                <select name="partner" class="form-control select2" onchange='if(this.value != 0) { this.form.submit(); }'>
                                    <option value="">@lang('cms.Select Partner')</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}" @if($partner_id == $partner->id) selected @endif>{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2 mt-2">
                                <label></label>
                                <select id="type" name="type" class="form-control select2" onchange='if(this.value != 0) { this.form.submit(); }'>
                                    <option value="" selected>@lang('cms.Select Type')</option>
                                    <option value="both" @if($type == 'both') selected @endif>@lang('cms.BOTH')</option>
                                    <option value="offline" @if($type == 'offline') selected @endif>@lang('cms.OFFLINE')</option>
                                    <option value="online" @if($type == 'online') selected @endif>@lang('cms.ONLINE')</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <label></label>
                                <button class="btn btn-primary form-control mt-1" type="submit"><i class="icon-search4"></i>
                                    @lang('cms.Search')
                                </button>
                            </div>
                            <div class="form-group">
                                <label></label>
                                <a class="btn btn-danger form-control mt-1" href="{{ route("yukk_co.merchant_branch.bulk_search.form") }}"><i class="icon-search4"></i>
                                    @lang('cms.Search Bulk')
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-1 justify-content-end mr-3">
                        <div class="flex flex-row">
                            <label>@lang("cms.Per page")&nbsp;</label>
                            <div class="form-group">
                                <select class="select2 form-group" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                                    <option @if($per_page == 10) selected @endif>10</option>
                                    <option @if($per_page == 25) selected @endif>25</option>
                                    <option @if($per_page == 50) selected @endif>50</option>
                                    <option @if($per_page == 100) selected @endif>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>@lang('cms.ID')</th>
                        <th>@lang('cms.Merchant Branch')</th>
                        <th>@lang('cms.Company')</th>
                        <th>@lang('cms.Merchant')</th>
                        <th>@lang('cms.Owner')</th>
                        <th>@lang('cms.Partner')</th>
                        <th>@lang('cms.Type')</th>
                        <th>@lang('cms.MPAN')</th>
                        <th>@lang('cms.MID')</th>
                        <th>@lang('cms.City')</th>
                        <th>@lang('cms.Start Date')</th>
                        <th>@lang('cms.Status')</th>
                        <th>@lang('cms.Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($merchant_branches as $merchant_branch)
                        <tr>
                            <td>{{ $merchant_branch->id }}</td>
                            <td>{{ $merchant_branch->name }}</td>
                            <td>{{ $merchant_branch->merchant->company->name ?? '' }}</td>
                            <td>{{ $merchant_branch->merchant->name ?? '' }}</td>
                            <td>
                                @if($merchant_branch->owner)
                                    <a target="_blank" href="{{ route('yukk_co.owners.detail', $merchant_branch->owner->id) }}">{{ $merchant_branch->owner->name ?? '' }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ @$merchant_branch->partner_has_merchant_branch ? $merchant_branch->partner_has_merchant_branch->partner->name : '-' }}</td>
                            <td>{{ Str::upper(@$merchant_branch->type ?? '-') }}</td>
                            <td>{{ @$merchant_branch->mpan }}</td>
                            <td>{{ @$merchant_branch->mid }}</td>
                            <td>{{ @$merchant_branch->city->name ?? '' }}</td>
                            <td>{{ @$merchant_branch->start_date }}</td>
                            <td>
                                @if($merchant_branch->active)
                                    <span class="badge badge-success">@lang('cms.Active')</span>
                                @else
                                    <span class="badge badge-danger">@lang('cms.Inactive')</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if (in_array('MASTER_DATA.MERCHANT_BRANCH.UPDATE', $access_control))
                                                <a href="{{ route('yukk_co.merchant_branch.edit', $merchant_branch->id) }}"
                                                    class="dropdown-item"><i class="icon-pencil7"></i>
                                                    @lang('cms.Edit')
                                                </a>
                                            @endif
                                            @if (in_array('MASTER_DATA.MERCHANT_BRANCH.VIEW', $access_control))
                                                <a href="{{ route('yukk_co.merchant_branch.show', $merchant_branch->id) }}"
                                                    class="dropdown-item"><i class="icon-zoomin3"></i>
                                                    @lang('cms.Detail')
                                                </a>
                                            @endif
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
                    {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
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
                                <a href="{{ route('yukk_co.merchant_branch.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
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
                                    <a href="{{ route('yukk_co.merchant_branch.list', array_merge(request()->all(), ['page' => $i])) }}"
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
                                <a href="{{ route('yukk_co.merchant_branch.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
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

    <div id="modal-add-bulk-merchant-branch" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
        <form method="post" enctype="multipart/form-data" action="{{ route('yukk_co.merchant_branch.bulk.preview') }}">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('cms.Add Merchant Branch')</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-3 mt-1">@lang('cms.Import Merchant Branches')</label>
                            <div class="col-9 col">
                                <input type="file" name="merchant_branch_list" id="merchant_branch_list" required autofocus>
                                <div class="col-4 mt-2">
                                    <a href="{{ asset('/template.xlsx') }}">
                                        @lang('cms.Download Template')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mb-2 mt-4">
                        <button class="btn btn-primary btn-block col-3" type="submit">@lang('cms.Submit')</button>
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
        });
    </script>
@endsection
