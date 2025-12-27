@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Account Login List')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">@lang('cms.Account Login List')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <form id="partner-login-form" action="{{ route('yukk_co.partner_login.index') }}" method="GET">
            <div class="card-header form-group">
                <div class="row justify-content-between">
                    <div class="row col-9 ml-1">
                        <div class="row col-3 mt-1 mr-1">
                            <select class="form-control select2" name="field" id="field">
                                <option value="merchant_branch" @if($field == 'merchant_branch') selected @endif>@lang('cms.Merchant Branch')</option>
                                <option value="username" @if($field == 'username') selected @endif>@lang('cms.Username')</option>
                            </select>
                        </div>
                        <div class="row col-3 mt-1 mr-1">
                            <div class="form-group w-100">
                                <input type="text" id="search-keyword" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Search" onchange='if(this.value != 0) { this.form.submit(); }'>
                            </div>
                        </div>
                        <div class="row col-3 mt-1">
                            <div class="form-group w-100">
                                <select id="edc_type" name="edc_type" class="form-group select2" onchange='if(this.value != 0) { this.form.submit(); }'>
                                    <option value="ALL" @if($edc_type == 'ALL') selected @endif>@lang('cms.EDC Type')</option>
                                    <option value="STICKER" @if($edc_type == 'STICKER') selected @endif>@lang('cms.STICKER')</option>
                                    <option value="QRIS_DYNAMIC" @if($edc_type == 'QRIS_DYNAMIC') selected @endif>@lang('cms.QRIS_DYNAMIC')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(in_array('YUKK_MERCHANT.ACCOUNT_LOGIN.EDIT', $access_control))
                        <div class="row col-3 justify-content-center align-items-center pl-2">
                            <a class="w-75" href="{{ route('yukk_co.partner_login.add') }}">
                                <button class="btn btn-primary form-control" type="button"><i class="fas fa-plus mr-1"></i>@lang("cms.Create")</button>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th class="text-center">@lang('cms.Merchant Branch')</th>
                            <th class="text-center">@lang('cms.Username')</th>
                            <th class="text-center">@lang('cms.EDC Type')</th>
                            <th class="text-center">@lang('cms.Status')</th>
                            <th class="text-center">@lang('cms.Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($partner_login_list)
                            @foreach($partner_login_list as $partner_login)
                                <tr>
                                    <td class="text-center">
                                        @foreach(collect($partner_login->merchant_branches)->unique('name') as $merchant_branch)
                                            <span> {{ @$merchant_branch->name }}</span>
                                            <br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ @$partner_login->username }}<br></td>
                                    <td class="text-center">
                                       @foreach(collect($partner_login->edcs)->unique('type') as $edc)
                                            @if(@$edc->type == 'STICKER')
                                                <span class="badge badge-primary">{{ @$edc->type }}</span>
                                            @elseif(@$edc->type == 'QRIS_DYNAMIC')
                                                <span class="badge badge-info">{{ @$edc->type }}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @if($partner_login->active == '1')
                                            <span class="badge badge-success">@lang('cms.Active')</span>
                                        @else
                                            <Active class="badge badge-danger">@lang('cms.Inactive')</span>
                                        @endif
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="edit-partner dropdown-item" data-partner-id="{{ $partner_login->id }}"
                                                        data-merchant-branch-ids="{{ collect($partner_login->merchant_branches)->unique('name')->pluck('id')->join(',') }}">
                                                        <i class="icon-pencil7"></i> @lang('cms.Edit')
                                                    </a>
                                                    <a href="{{ route('yukk_co.partner_login.detail', $partner_login->id) }}"
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
                            <tr class="text-center">
                                <td colspan="4"> Data Not Found</td>
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
                                    <a href="{{ route('yukk_co.partner_login.index', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
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
                                        <a href="{{ route('yukk_co.partner_login.index', array_merge(request()->all(), ['page' => $i])) }}"
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
                                    <a href="{{ route('yukk_co.partner_login.index', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
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
        let $base_url = "{{ route('yukk_co.partner_login.edit', 0) }}"
        $(document).ready(function() {
            $("#search-keyword").change(function() {
                $('#partner-login-form').delay(1000).submit();
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-partner');

        editButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();  // Prevent the default link behavior

                const partnerId = this.getAttribute('data-partner-id');
                const merchantBranchIds = this.getAttribute('data-merchant-branch-ids');
                let merchant_branch_id = merchantBranchIds.split(',');
                let merchant_branch_id_str = '';
                merchant_branch_id.forEach(function(item) {
                    merchant_branch_id_str += 'merchant_branch_id[]=' + item + '&';
                });
                const url = $base_url.replace(/0(?=\/edit)/, partnerId) + '?' + merchant_branch_id_str;

                // Redirect to the constructed URL
                window.location.href = url;
            });
        });
    });
    </script>
@endsection
