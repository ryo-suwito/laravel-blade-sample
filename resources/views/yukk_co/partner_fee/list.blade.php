@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Partner Fee List")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Partner Fee")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header form-group row">
            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                @if(in_array('MASTER_DATA.PARTNER_FEE.UPDATE', $access_control))
                    <div class="dropdown p-0">
                        <a class="dropdown-item btn-primary" href="{{ route('yukk_co.partner_fee.create') }}">
                            <i class="icon-add"></i>@lang("cms.Create Partner Fee")
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.Name")</th>
                    <th>@lang("cms.Short Description")</th>
                    <th>@lang("cms.Fee Partner in %")</th>
                    <th>@lang("cms.Fee Partner in IDR")</th>
                    <th>@lang("cms.Fee Yukk Additional in %")</th>
                    <th>@lang("Fee Yukk Additional in IDR")</th>
                    <th>@lang("cms.Display Status")</th>
                    <th>@lang("cms.Created At")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($partner_fee_list as $index => $partner_fee)
                    <tr>
                        <td>{{ @$partner_fee->name }}</td>
                        <td>{{ @$partner_fee->short_description }}</td>
                        <td class="text-right">{{ number_format(@$partner_fee->fee_partner_percentage, 2) }}</td>
                        <td class="text-right">{{ number_format(@$partner_fee->fee_partner_fixed, 2) }}</td>
                        <td class="text-right">{{ number_format(@$partner_fee->fee_yukk_additional_percentage, 2) }}</td>
                        <td class="text-right">{{ number_format(@$partner_fee->fee_yukk_additional_fixed, 2) }}</td>
                        <td>{{ @$partner_fee->display_status }}</td>
                        <td>{{ @$partner_fee->created_at }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        @if(in_array('MASTER_DATA.PARTNER_FEE.UPDATE', $access_control))
                                            <a href="{{ route("yukk_co.partner_fee.edit", @$partner_fee->id) }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Edit")</a>
                                        @endif
                                        @if(in_array('MASTER_DATA.PARTNER_FEE.VIEW', $access_control))
                                            <a href="{{ route("yukk_co.partner_fee.detail", @$partner_fee->id) }}" class="dropdown-item"><i class="icon-zoomin3"></i> @lang("cms.Detail")</a>
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
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": true,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });
    </script>
@endsection
