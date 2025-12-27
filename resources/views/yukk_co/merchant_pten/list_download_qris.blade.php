@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.List Merchant Branch')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.merchant.pten.list') }}" class="breadcrumb-item">@lang('cms.QRIS (PTEN) Menu')</a>
                    <span class="breadcrumb-item active">@lang('cms.List Merchant Branch')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <form action="{{ route('yukk_co.merchant.pten.download.list') }}" method="GET">
                <div class="row">
                    <div class="form-group">
                        <label>@lang("cms.Merchant Branch")</label>
                        <input type="text" name="branch" value="{{ $branch }}" class="form-control" placeholder="@lang("cms.Search")">
                    </div>
                    <div class="form-group">
                        <label></label>
                        <button class="btn btn-primary form-control mt-1" type="submit"><i class="icon-search4"></i></button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-right">
                        <label>
                            @lang("cms.Per page")&nbsp;
                            <select name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                                <option @if($per_page == 10) selected @endif>10</option>
                                <option @if($per_page == 25) selected @endif>25</option>
                                <option @if($per_page == 50) selected @endif>50</option>
                                <option @if($per_page == 100) selected @endif>100</option>
                            </select>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route("yukk_co.merchant.pten.download.qris") }}">
                @csrf
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="select-all" id="select-all" />
                            </th>
                            <th>@lang('cms.Branch Name')</th>
                            <th>@lang('cms.Merchant Name')</th>
                            <th>@lang('cms.MID')</th>
                            <th>@lang('cms.MPAN')</th>
                            <th>@lang('cms.NMID')</th>
                            <th>@lang('cms.Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchant_branch_list as $merchant_branch)
                        <tr>
                            <td class="td-checkbox">
                                <input class="checkbox" type="checkbox" name="checkbox[{{ $merchant_branch->id }}]">
                            </td>
                            <td>{{ $merchant_branch->merchant_branch_name_pten_50 }}</td>
                            <td>{{ $merchant_branch->merchant ? $merchant_branch->merchant->name : '' }}</td>
                            <td>{{ $merchant_branch->mid }}</td>
                            <td>{{ $merchant_branch->mpan }}</td>
                            <td>{{ $merchant_branch->nmid_pten}}</td>
                            <td>
                                <span class="badge badge-success badge-pill ml-auto">
                                    {{ $merchant_branch->status_pten}}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-block btn-primary mt-4 mx-auto">@lang('cms.Download QRIS')</button>
            </form>
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
                                <a href="{{ route("yukk_co.merchant.pten.download.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("yukk_co.merchant.pten.download.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("yukk_co.merchant.pten.download.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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

            $('#select-all').click(function(event) {
                if(this.checked) {
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });
        });

    </script>
@endsection
