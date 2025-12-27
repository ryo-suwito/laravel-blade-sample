@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Partner")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Partner")</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header form-group row">
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @if(in_array('MASTER_DATA.PARTNER.UPDATE', $access_control))
                    <div class="dropdown p-0" style="display:inline-block; border-right:1px solid white">
                        <a class="dropdown-item btn-secondary" href="{{ route('yukk_co.partner.download_public_key') }}">
                            <i class="icon-download"></i>@lang("cms.Download YUKK Public Key")
                        </a>
                    </div>
                    <div class="dropdown p-0" style="display:inline-block">
                        <a class="dropdown-item btn-primary" href="{{ route('yukk_co.partner.create') }}">
                            <i class="icon-add"></i>@lang("cms.Create Partner")
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('yukk_co.partner.list') }}" method="GET">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Partner")</label>
                            <input type="text" name="partner" class="form-control" value="{{ $partner }}" placeholder="@lang("cms.Search")">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Code")</label>
                            <input type="text" name="code" class="form-control" value="{{ $code }}" placeholder="@lang("cms.Search")">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Type")</label>
                            <select name="partner_type" id="partner_type" class="form-control select2" data-minimum-results-for-search="Infinity">
                                <option value="">Select Type</option>
                                <option value="MA" {{ request()->get('partner_type') == 'MA' ? 'selected' : (old('partner_type') == 'MA' ? 'selected' : '') }}>@lang("cms.Merchant Aggregator (MA)")</option>
                                <option value="Internal" {{ request()->get('partner_type') == 'Internal' ? 'selected' : (old('partner_type') == 'Internal' ? 'selected' : '') }}>@lang("cms.Internal")</option>
                                <option value="Others" {{ request()->get('partner_type') == 'Others' ? 'selected' : (old('partner_type') == 'Others' ? 'selected' : '') }}>@lang("cms.Others")</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="input-group input-group-append position-static">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                            </div>
                        </div>
                    </div>
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

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.ID")</th>
                    <th>@lang("cms.Partner Name")</th>
                    <th>@lang("cms.Partner Type")</th>
                    <th>@lang("cms.Code")</th>
                    <th>@lang("cms.Rek Parking Partner")</th>
                    <th>@lang("cms.Bank Type")</th>
                    <th>@lang("cms.Detail Snap")</th>
                    <th>@lang("cms.Snap")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($partner_list as $index => $partner)
                    <tr>
                        <td>{{ @$partner->id }}</td>
                        <td>{{ @$partner->name }}</td>
                        <td>{{ @$partner->type }}</td>
                        <td>{{ @$partner->code }}</td>
                        @if (@$partner->bank_accounts)
                            <td>{{ @$partner->bank_accounts->name }}</td>
                        @else
                            <td class="text-center"> <b> - </b> </td>
                        @endif
                        <td>{{ @$partner->bank_type }}</td>
                        <td>
                            @if ($partner->is_snap_enabled)
                                @lang("cms.Client ID") : <span>{{ $partner->snap_client_id }}</span><br>
                                @lang("cms.Client Secret") : <span>{{ $partner->snap_client_secret }}</span>
                            @else

                            @endif
                        </td>
                        <td>
                            @if ($partner->is_snap_enabled)
                               @lang("cms.SNAP")
                            @else
                                @lang("cms.NON SNAP")
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        @if(in_array('MASTER_DATA.PARTNER.UPDATE', $access_control))
                                            <a href="{{ route("yukk_co.partner.edit", $partner->id) }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                        @endif
                                        @if(in_array('MASTER_DATA.PARTNER.VIEW', $access_control))
                                            <a href="{{ route("yukk_co.partner.detail", $partner->id) }}" class="dropdown-item"><i class="icon-zoomin3"></i> @lang("cms.Detail")</a>
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
                                <a href="{{ route("yukk_co.partner.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("yukk_co.partner.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("yukk_co.partner.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
                "ordering": true,
                "info": false,
                "searching": false,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
            $("<style>")
                .prop("type", "text/css")
                .html(`
                    .card-body>.dataTables_wrapper .dataTables_info {
                        position: absolute !important;
                    }
                    .card-footer {
                        margin-top: -2.5rem;
                    }
                `)
                .appendTo("head");
        });
    </script>
@endsection
