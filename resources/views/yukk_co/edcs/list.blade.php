@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.EDC List")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.EDC List")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <form action="{{ route('yukk_co.edc.list') }}" method="GET">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Beneficiary")</label>
                            <input type="text" name="beneficiary" class="form-control" value="{{ $beneficiary }}" placeholder="@lang("cms.Search")">
                        </div>
                    </div>


                    
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Merchant Branch")</label>
                            <input type="text" name="branch" class="form-control" value="{{ $branch }}" placeholder="@lang("cms.Search")">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Partner")</label>
                            <input type="text" name="partner" class="form-control" value="{{ $partner }}" placeholder="@lang("cms.Search")">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>@lang("cms.Type")</label>
                            <input type="text" name="type" class="form-control" value="{{ $type }}" placeholder="@lang("cms.Search")">
                        </div>
                    </div>
                </div>

                <div class="row justify-content-between">
                    <div class="row col-lg-6">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang("cms.Created At")</label>
                                <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="input-group input-group-append position-static">
                                    <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                        <button class="dropdown-item" name="export_to_excel" value="1"><i class="icon-file-download"></i> @lang("cms.Export to Excel")</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-1 justify-content-end mr-1">
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
                        <th><center>@lang("cms.ID")</center></th>
                        <th><center>@lang("cms.Beneficiary")</center></th>
                        <th><center>@lang("cms.Merchant")</center></th>
                        <th><center>@lang("cms.Merchant Branch")</center></th>
                        <th><center>@lang("cms.Partner")</center></th>
                        <th><center>@lang("cms.Partner Fee")</center></th>
                        <th><center>@lang("cms.IMEI")</center></th>
                        <th><center>@lang("cms.Type")</center></th>
                        <th><center>@lang("cms.NMID")</center></th>
                        <th><center>@lang("cms.Created At")</center></th>
                        <th><center>@lang("cms.Status")</center></th>
                        <th><center>@lang("cms.Actions")</center></th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($edc_list as $edc)
                    <tr>
                        <td><center>{{ $edc->id }}</center></td>
                        <td><center>{{ ($edc->customer != null) ? $edc->customer->name : '-' }}</center></td>
                        <td><center>{{ ($edc->branch->merchant != null) ? $edc->branch->merchant->name : '-' }}</center></td>
                        <td><center>{{ ($edc->branch != null) ? $edc->branch->name : '-' }}</center></td>
                        <td>
                            <center>
                            {{ (@$edc->partner_has_merchant_branch->partner != null) ? @$edc->partner_has_merchant_branch->partner->name : '-' }}
                            @if(@$edc->partner_has_merchant_branch->partner->is_snap_enabled)
                                <span class="badge badge-primary">@lang("cms.SNAP")</span>
                            @endif
                            </center>
                        </td>
                        <td class="text-center">
                            @if ($edc->partner_fee)
                                <a href="{{ route("yukk_co.partner_fee.detail", $edc->partner_fee->id) }}">
                                    {{ ($edc->partner_fee != null) ? $edc->partner_fee->name : '-' }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td><center>{{ $edc->imei }}</center></td>
                        <td>
                            <center>
                                @if(!empty($edc->partner_logins) && $edc->partner_logins[0]->is_payment_gateway == 1) 
                                    @lang('cms.QRIS_PG')
                                @else 
                                    {{$edc->type}}
                                @endif
                                @if($edc->is_qris) <span class="badge badge-primary">@lang("cms.QRIS")</span>@endif
                            </center>
                        </td>
                        <td><center>{{ $edc->nmid_pten }}</center></td>
                        <td><center>{{$edc->created_at}}</center></td>
                        @if($edc->active == 1)
                            <td>
                                <center>
                                    <span class="badge badge-success">
                                        Active
                                    </span>
                                </center>
                            </td>
                        @else
                            <td>
                                <center>
                                    <span class="badge badge-danger">
                                        Inactive
                                    </span>
                                </center>
                            </td>
                        @endif
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        @if(in_array('QRIS_MENU.EDC.UPDATE', $access_control))
                                            <a href="{{ route("yukk_co.edc.edit", $edc->id) }}" class="form-control dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                        @endif
                                        @if(in_array('QRIS_MENU.EDC.VIEW', $access_control))
                                            <a href="{{ route("yukk_co.edc.detail", $edc->id) }}" class="form-control dropdown-item"><i class="icon-zoomin3"></i> @lang("cms.Detail")</a>
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
                                <a href="{{ route("yukk_co.edc.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("yukk_co.edc.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("yukk_co.edc.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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
