@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Disbursement Beneficiary Transfer Bulk")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.disbursement_customer_transfer_bulk.list") }}" class="breadcrumb-item">@lang("cms.Disbursement Beneficiary Transfer Bulk")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
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
    <style>
        .table-mark-success td {
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Disbursement Beneficiary Transfer Bulk")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Ref Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer_bulk->ref_code }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Date")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($disbursement_customer_transfer_bulk->disbursement_date, "d-M-Y") }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Source Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer_bulk->source_account_number }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Destination Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer_bulk->destination_account_number }}">
                        </div>
                    </div>

                    <hr>

                    @if (@in_array($disbursement_customer_transfer_bulk->type, ["POOL_TO_FLIP", "PARKING_TO_FLIP", ]))
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer_bulk->total_merchant_portion, 2) }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Disburse Fee")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer_bulk->total_disbursement_fee, 2) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Rounding")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer_bulk->total_rounding, 2) }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Unique Code")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer_bulk->unique_code, 2) }}">
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Transfer")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer_bulk->total_transfer + $disbursement_customer_transfer_bulk->unique_code, 2) }}">
                        </div>
                    </div>

                    @if (@in_array($disbursement_customer_transfer_bulk->type, ["POOL_TO_FLIP", "PARKING_TO_FLIP", ]))
                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Flip Top Up ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer_bulk->flip_top_up_id }}">
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Type")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer_bulk->type }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer_bulk->status }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($disbursement_customer_transfer_bulk->created_at) }}">
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <hr>
                </div>
            </div>

            @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("DISBURSEMENT_CUSTOMER_TRANSFER.ACTION"))
                <div class="row">
                    <div class="col-lg-12 text-center">
                        @if (@$disbursement_customer_transfer_bulk->type == "POOL_TO_MT_PBR_CUSTOMER_TO_PARTNER")
                            @if (@$disbursement_customer_transfer_bulk->status == "FAILED" || @$disbursement_customer_transfer_bulk->status == "ON_GOING")
                                <form action="{{ route("cms.yukk_co.disbursement_customer_transfer_bulk.action", $disbursement_customer_transfer_bulk->id) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="disbursement_customer_transfer_bulk_id" value="{{ $disbursement_customer_transfer_bulk->id }}">
                                    <input type="hidden" name="status" value="{{ $disbursement_customer_transfer_bulk->status }}">
                                    <input type="hidden" name="type" value="{{ $disbursement_customer_transfer_bulk->type }}">
                                    <button class="btn btn-danger btn-confirm" name="action" value="RETRY">@lang("cms.Retry")</button>
                                    <button class="btn btn-warning btn-confirm" name="action" value="PROCEED">@lang("cms.Proceed")</button>
                                </form>
                            @endif
                        @elseif (@$disbursement_customer_transfer_bulk->type == "POOL_TO_MT_PBR_CUSTOMER")
                            @if (@$disbursement_customer_transfer_bulk->status == "FAILED" || @$disbursement_customer_transfer_bulk->status == "ON_GOING")
                                <form action="{{ route("cms.yukk_co.disbursement_customer_transfer_bulk.action", $disbursement_customer_transfer_bulk->id) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="disbursement_customer_transfer_bulk_id" value="{{ $disbursement_customer_transfer_bulk->id }}">
                                    <input type="hidden" name="status" value="{{ $disbursement_customer_transfer_bulk->status }}">
                                    <input type="hidden" name="type" value="{{ $disbursement_customer_transfer_bulk->type }}">
                                    <button class="btn btn-danger btn-confirm" name="action" value="RETRY">@lang("cms.Retry")</button>
                                    <button class="btn btn-warning btn-confirm" name="action" value="PROCEED">@lang("cms.Proceed")</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <hr>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <h4>
                        <form action="{{ route("cms.yukk_co.disbursement_customer_transfer_bulk.export_excel", $disbursement_customer_transfer_bulk->id) }}" method="post">
                            @lang("cms.Disbursement Beneficiary Transfer List")
                            @csrf
                            <button class="btn btn-primary float-right" type="submit">@lang("cms.Download")</button>
                        </form>
                    </h4>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Beneficiary Name")</th>
                            <th>@lang("cms.Partner Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Disbursement Date")</th>
                            <th>@lang("cms.Total Merchant Portion")</th>
                            <th>@lang("cms.Disbursement Fee")</th>
                            <th>@lang("cms.Rounding")</th>
                            <th>@lang("cms.Total Disbursement")</th>
                            <th>@lang("cms.Transfer Using")</th>
                            <th>@lang("cms.Status")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($disbursement_customer_transfer_bulk->disbursement_customer_transfer_list as $index => $disbursement_customer_transfer)
                            <tr>
                                <td>{{ @$disbursement_customer_transfer->customer->name }}</td>
                                <td>{{ @$disbursement_customer_transfer->partner->name }}</td>
                                <td>{{ @$disbursement_customer_transfer->ref_code }}</td>
                                <td>{{ @$disbursement_customer_transfer->disbursement_date }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->total_merchant_portion, 2) }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->disbursement_fee, 2) }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->rounding, 2) }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->total_disbursement, 2) }}</td>
                                <td>{{ @$disbursement_customer_transfer->transfer_using }}</td>
                                <td>{{ @$disbursement_customer_transfer->status }}</td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route("cms.yukk_co.disbursement_customer.item", @$disbursement_customer_transfer->disbursement_customer_master_id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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

            $(".btn-confirm").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
