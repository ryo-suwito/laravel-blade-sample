@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Manage QRIS Settings")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Manage QRIS Settings")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('yukk_co.qris_setting.list') }}" method="GET">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Merchant Name")</label>
                            <input type="text" name="merchant" class="form-control" value="{{ $merchant }}" placeholder="@lang("cms.Search")">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Branch Name")</label>
                            <input type="text" name="branch" class="form-control" value="{{ $branch }}" placeholder="@lang("cms.Search")">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Snap Category")</label>
                            <select id="snap_category" name="snap_category" class="form-group select2" onchange='if(this.value != null) { this.form.submit(); }'>
                                <option value="ALL" @if($snap_category == 'ALL') selected @endif>@lang('cms.Category')</option>
                                <option value="1" @if($snap_category == '1') selected @endif>@lang('cms.SNAP')</option>
                                <option value="0" @if($snap_category == '0') selected @endif>@lang('cms.NON SNAP')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="input-group input-group-append position-static">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <form id="filter_status" name="filter_status">
                        <div class="col-lg-11">
                            <label>@lang("cms.Status")</label>
                            <div class="col-md-3">
                                <select class="form-control select2" name="status" id="status">
                                    <option value="">ALL STATUS</option>
                                    <option value="APPROVED_PROCESSED" @if($status == 'APPROVED_PROCESSED') selected @endif>PUBLISHED</option>
                                    <option value="APPROVED" @if($status == 'APPROVED') selected @endif>NEW</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="col-lg-1 justify-content-end">
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
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>@lang('cms.Merchant Name')</th>
                        <th>@lang('cms.Branch Name')</th>
                        <th>@lang('cms.Username')</th>
                        <th>@lang('cms.EDC Count')</th>
                        <th class="text-center">@lang('cms.QRIS Type')</th>
                        <th class="text-center">@lang('cms.Category')</th>
                        <th class="text-center">@lang('cms.Status')</th>
                        <th class="text-center">@lang('cms.Updated At')</th>
                        <th class="not-export-col">@lang('cms.Actions')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($qris_list as $index => $qris)
                        <tr>
                            <td>{{ @$qris->merchant ? @$qris->merchant->name : '' }}</td>
                            <td>{{ @$qris->name }}</td>
                            <td>
                                @if($qris->edcs)
                                    @foreach(collect($qris->edcs) as $edc)
                                        @foreach($edc->partner_logins as $partner_login)
                                            @if($partner_login->grant_type == 'LOGIN')
                                                {{ @$partner_login->username }}
                                                <br>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <center>
                                    {{ count(@$qris->edcs) }}
                                </center>
                            </td>
                            <td>
                                {!! (new \App\Actions\MerchantBranch\GetEdcTypeTags)->handle($qris->edcs) !!}
                            </td>
                            <td class="text-center">
                                @foreach(collect($qris->edcs)->pluck('partner')->pluck('is_snap_enabled')->unique() as $snap_enabled)
                                    @if($snap_enabled == 0)
                                        <span class="badge badge-secondary">
                                            @lang('cms.NON SNAP')
                                        </span>
                                    @endif
                                    @if($snap_enabled == 1)
                                        <span class="badge badge-primary">
                                             @lang('cms.SNAP')
                                        </span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-center">
                                @if($qris->status_pten == "APPROVED")
                                    <span class="badge badge-warning badge-pill ml-auto">
                                        @lang('cms.NEW')
                                    </span>
                                @elseif($qris->status_pten == "APPROVED_PROCESSED")
                                    <span class="badge badge-success badge-pill ml-auto">
                                         @lang('cms.PUBLISHED')
                                    </span>
                                @endif
                            </td>
                            <td>{{ @$qris->updated_at }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        @if(in_array('QRIS_MENU.MANAGE_QRIS.UPDATE', $access_control))
                                            @if($qris->status_pten == "APPROVED")
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('yukk_co.qris_setting.published', $qris->id) }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Publish")</a>
                                                </div>
                                            @elseif($qris->status_pten == "APPROVED_PROCESSED")
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('yukk_co.qris_setting.edit', $qris->id) }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                                    <a href="{{ route('yukk_co.qris_setting.detail', $qris->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                    <a href="{{ route('yukk_co.qris_setting.preview', $qris->id) }}" class="dropdown-item"><i class="icon-mail5"></i> @lang("cms.Email To")</a>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('QRIS_MENU.MANAGE_QRIS.VIEW', $access_control))
                                            @if($qris->status_pten == "APPROVED_PROCESSED")
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('yukk_co.qris_setting.detail', $qris->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                </div>
                                            @endif
                                        @endif
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
                                <a href="{{ route("yukk_co.qris_setting.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("yukk_co.qris_setting.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("yukk_co.qris_setting.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            $('#filter_status').on('submit', function(e) {
                table.draw();
                e.preventDefault();
                table.ajax.reload();
            });
        });
    </script>
@endsection
