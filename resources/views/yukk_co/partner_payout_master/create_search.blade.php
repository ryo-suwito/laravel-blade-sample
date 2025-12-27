@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Create Partner Payout")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.partner_payout_master.index") }}" class="breadcrumb-item">@lang("cms.Partner Payout List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create")</span>
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
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Create Partner Payout")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.partner_payout_master.create_search") }}" method="get">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="partner_id">@lang("cms.Partner")</label>
                    <div class="col-md-8">
                        <select class="form-control select2" name="partner_id" id="partner_id">
                            <option value="-1">@lang("cms.All")</option>
                            @foreach(@$partner_list as $partner)
                                <option value="{{ $partner->id }}" @if(@$partner_id == $partner->id) selected @endif>{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                        {{--<div class="input-group input-group-append position-static">
                            <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                            <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                            <div class="dropdown-menu dropdown-menu-right" style="">
                                <button class="dropdown-item" name="export_to_csv" value="1"><i class="icon-file-download"></i> @lang("cms.Export to CSV")</button>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </form>

            @if (isset($partner_payout_master_list))
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                    <tr>
                        <th>@lang("cms.Partner Name")</th>
                        <th>@lang("cms.Total Fee Partner")</th>
                        <th>@lang("cms.Invoices")</th>
                        <th>@lang("cms.Actions")</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($partner_payout_master_list as $index => $partner_payout_master)
                        <tr>
                            <td>{{ @$partner_payout_master->partner ? $partner_payout_master->partner->name : "-" }}</td>
                            <td class="text-right">{{ @\App\Helpers\H::formatNumber($partner_payout_master->sum_fee_partner_fixed + $partner_payout_master->sum_fee_partner_percentage, 2) }}</td>
                            <td class="text-center">{{ $partner_payout_master->count_all_invoice }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a target="_blank" href="{{ route("cms.yukk_co.partner_payout_master.create_partner", $partner_payout_master->partner->id) }}" class="dropdown-item"><i class="icon-pencil7"></i> @lang("cms.Create Partner Payout")</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if (count($partner_payout_master_list) <= 0)
                        <tr>
                            <td class="text-center" colspan="7">@lang("cms.No Data Found")</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": false,
            });
        });
    </script>
@endsection