@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Disbursement Beneficiary")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.disbursement_customer.list") }}" class="breadcrumb-item">@lang("cms.Disbursement Beneficiary")</a>
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
            <h5 class="card-title">@lang("cms.Disbursement Beneficiary")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->customer->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary ID")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->customer_id }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary Description")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$disbursement_customer->customer->description }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$disbursement_customer->customer->email }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Is Customer Switching?")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->customer_is_switching ? "Yes" : "No" }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Customer Switching Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->customer_switching_code }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->bank->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Branch")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->customer_branch_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->customer_account_number }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->customer_account_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Type") (@lang("cms.BCA / NON BCA"))</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->bank_type }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Ref Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->ref_code }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($disbursement_customer->created_at) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Date")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->disbursement_date }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Transfer Using")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->transfer_using }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion QRIS")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion_qris, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion PG")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion_pg, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion Debt")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion_debt, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Fee")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer->disbursement_fee, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Rounding")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer->rounding, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Disbursement")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_disbursement, 2) }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->status }}">
                        </div>

                        <label class="col-lg-2 col-form-label">@lang("cms.Status Email")</label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->status_email }}"/>
                                @if (@$disbursement_customer->status_email == "DONE" && \App\Helpers\AccessControlHelper::checkCurrentAccessControl("DISBURSEMENT_CUSTOMER.RESEND_EMAIL"))
                                    <span class="input-group-append">
                                        <form method="post" action="{{ route("cms.yukk_co.disbursement_customer.resend_email") }}">
                                            @csrf
                                            <input type="hidden" name="disbursement_customer_master_id" value="{{ @$disbursement_customer->id }}"/>
                                            <button class="btn btn-primary btn-confirm" type="submit">@lang("cms.Resend")</button>
                                        </form>
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h4><u>@lang("cms.Table Settlement QRIS")</u></h4>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Partner Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                            <th>@lang("cms.Status")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(collect($disbursement_customer->disbursement_customer_detail_list)->where("type", "QRIS") as $index => $disbursement_customer_detail)
                            <tr>
                                <td>{{ @$disbursement_customer_detail->settlement_master->settlement_date }}</td>
                                <td>{{ @$disbursement_customer_detail->settlement_master->partner->name }}</td>
                                <td>{{ @$disbursement_customer_detail->settlement_master->ref_code }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_detail->settlement_master->total_merchant_portion, 2) }}</td>
                                <td>{{ @$disbursement_customer_detail->settlement_master->status }}</td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route("cms.yukk_co.settlement_master.show", @$disbursement_customer_detail->settlement_master->id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                <form method="post" target="_blank" action="{{ route("cms.yukk_co.settlement_master.export_csv_transaction", $disbursement_customer_detail->settlement_master->id) }}">
                                                    @csrf
                                                    <button class="dropdown-item" type="submit"><i class="icon-file-excel"></i> @lang("cms.Export to CSV")</button>
                                                </form>
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

            <div class="row">
                <div class="col-lg-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h4><u>@lang("cms.Table Settlement PG")</u></h4>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Partner Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                            <th>@lang("cms.Status")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(collect($disbursement_customer->disbursement_customer_detail_list)->where("type", "PG") as $index => $disbursement_customer_detail)
                            @if ($disbursement_customer_detail->settlement_pg_master)
                                <tr>
                                    <td>{{ @$disbursement_customer_detail->settlement_pg_master->settlement_date }}</td>
                                    <td>{{ @$disbursement_customer_detail->settlement_pg_master->partner->name }}</td>
                                    <td>{{ @$disbursement_customer_detail->settlement_pg_master->ref_code }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_detail->settlement_pg_master->total_merchant_portion, 2) }}</td>
                                    <td>{{ @$disbursement_customer_detail->settlement_pg_master->status }}</td>
                                    <td>
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route("cms.yukk_co.settlement_pg_master.show", @$disbursement_customer_detail->settlement_pg_master->id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h4><u>@lang("cms.Table Settlement Debt")</u></h4>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Partner Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                            <th>@lang("cms.Status")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(collect($disbursement_customer->disbursement_customer_detail_list)->where("type", "DEBT") as $index => $disbursement_customer_detail)
                            @if ($disbursement_customer_detail->settlement_debt_master)
                                <tr>
                                    <td>{{ @$disbursement_customer_detail->settlement_debt_master->settlement_date }}</td>
                                    <td>{{ @$disbursement_customer_detail->settlement_debt_master->partner->name }}</td>
                                    <td>{{ @$disbursement_customer_detail->settlement_debt_master->ref_code }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_detail->settlement_debt_master->total_merchant_portion, 2) }}</td>
                                    <td>{{ @$disbursement_customer_detail->settlement_debt_master->status }}</td>
                                    <td>
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("SETTLEMENT_DEBT.VIEW"))
                                                        <a href="{{ route("cms.yukk_co.settlement_debt.show", @$disbursement_customer_detail->settlement_debt_master->id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Merchant Portion QRIS")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion_qris, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Merchant Portion PG")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion_pg, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Merchant Portion Debt")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion_debt, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Merchant Portion")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Disbursement Fee")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->disbursement_fee, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Rounding")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->rounding, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <hr>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Disbursement")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_disbursement, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h4><u>@lang("cms.Table Split Disbursement")</u></h4>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.No")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                            <th>@lang("cms.Disbursement Fee")</th>
                            <th>@lang("cms.Rounding")</th>
                            <th>@lang("cms.Total Disbursement")</th>
                            <th>@lang("cms.Transfer Using")</th>
                            <th>@lang("cms.Status")</th>
                            <th>@lang("cms.Status Provider")</th>
                            <th>@lang("cms.Flip Top Up ID")</th>
                            <th>@lang("cms.Flip Disbursement ID")</th>
                            <th>@lang("cms.Unique Code")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(collect($disbursement_customer->disbursement_customer_transfer_list) as $index => $disbursement_customer_transfer)
                            <tr>
                                <td>{{ @$disbursement_customer_transfer->ref_code }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->total_merchant_portion, 2) }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->disbursement_fee, 2) }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->rounding, 2) }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->total_disbursement, 2) }}</td>
                                <td>{{ @$disbursement_customer_transfer->transfer_using }}</td>
                                <td>{{ @$disbursement_customer_transfer->status }}</td>
                                <td>
                                    @if (@$disbursement_customer_transfer->transfer_using == "FLIP")
                                        {{ @$disbursement_customer_transfer->status_flip }}
                                    @elseif (@$disbursement_customer_transfer->transfer_using == "DSP")
                                        {{ @$disbursement_customer_transfer->status_dsp }}
                                    @else
                                        -
                                    @endif
                                </td>
                                @if (@$disbursement_customer_transfer->transfer_using == "FLIP")
                                    <td>{{ @$disbursement_customer_transfer->flip_top_up_id }}</td>
                                    <td>{{ @$disbursement_customer_transfer->flip_disbursement_id }}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                                <td class="text-right">{{ @$disbursement_customer_transfer->flip_unique_code }}</td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-transfer-detail-{{ $disbursement_customer_transfer->id }}"><i class="icon-search4"></i> @lang("cms.Detail")</button>
                                                @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("DISBURSEMENT_CUSTOMER_TRANSFER.ACTION", "AND"))
                                                    @if (@$disbursement_customer_transfer->transfer_using == "FLIP")
                                                        <form method="post" action="{{ route("cms.yukk_co.disbursement_customer_transfer.action", @$disbursement_customer_transfer->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="transfer_using" value="{{ @$disbursement_customer_transfer->transfer_using }}"/>
                                                            <input type="hidden" name="status" value="{{ @$disbursement_customer_transfer->status }}"/>
                                                            <input type="hidden" name="status_flip" value="{{ @$disbursement_customer_transfer->status_flip }}"/>
                                                            @if (@$disbursement_customer_transfer->status_flip == "FAILED_TOP_UP")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY_TOP_UP"><i class="icon-reload-alt"></i> @lang("cms.Retry Top Up")</button>
                                                            @elseif (@$disbursement_customer_transfer->status_flip == "FAILED_TRANSFER_BCA")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY_TRANSFER_BCA"><i class="icon-reload-alt"></i> @lang("cms.Retry Transfer BCA")</button>
                                                            @elseif (@$disbursement_customer_transfer->status_flip == "ON_GOING_TRANSFER_BCA")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY_TRANSFER_BCA"><i class="icon-reload-alt"></i> @lang("cms.Retry Transfer BCA")</button>
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="PROCEED_TRANSFER_BCA"><i class="icon-forward3"></i> @lang("cms.Proceed to Create Journal")</button>
                                                            @elseif (@$disbursement_customer_transfer->status_flip == "FAILED_CHECK_TOP_UP")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY_CHECK_TOP_UP"><i class="icon-reload-alt"></i> @lang("cms.Retry Check Top Up")</button>
                                                            @elseif (in_array(@$disbursement_customer_transfer->status_flip, ["FAILED_INQUIRY", "NAME_NOT_MATCH", "SUSPECTED_INQUIRY", ]))
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY_INQUIRY"><i class="icon-reload-alt"></i> @lang("cms.Retry Inquiry")</button>
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY_WITH_CHANGE_BANK_DETAILS"><i class="icon-pencil7"></i> @lang("cms.Change Bank Details + Retry")</button>
                                                            @elseif (@$disbursement_customer_transfer->status_flip == "FAILED_DISBURSEMENT")
                                                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-mark-success-{{ $disbursement_customer_transfer->id }}" value="MARK_AS_SUCCESS"><i class="icon-forward3"></i> @lang("cms.Mark as Success")</button>
                                                            @elseif (@$disbursement_customer_transfer->status_flip == "FAILED_CHECK_DISBURSEMENT")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY_CHECK_DISBURSEMENT"><i class="icon-reload-alt"></i> @lang("cms.Retry Check Disbursement")</button>
                                                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-mark-success-{{ $disbursement_customer_transfer->id }}" value="MARK_AS_SUCCESS"><i class="icon-forward3"></i> @lang("cms.Mark as Success")</button>
                                                            @endif
                                                        </form>
                                                    @elseif (@$disbursement_customer_transfer->transfer_using == "PAYOUT_BY_REQUEST_PARTNER")
                                                        <form method="post" action="{{ route("cms.yukk_co.disbursement_customer_transfer.action", @$disbursement_customer_transfer->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="transfer_using" value="{{ @$disbursement_customer_transfer->transfer_using }}"/>
                                                            <input type="hidden" name="status" value="{{ @$disbursement_customer_transfer->status }}"/>
                                                            <input type="hidden" name="status_flip" value="{{ @$disbursement_customer_transfer->status_flip }}"/>
                                                            @if (@$disbursement_customer_transfer->status == "FAILED_MONEY_TRANSFER_SUMMARY")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY"><i class="icon-reload-alt"></i> @lang("cms.Retry")</button>
                                                            @elseif (@$disbursement_customer_transfer->status == "FAILED_MONEY_TRANSFER_DEPOSIT")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY"><i class="icon-reload-alt"></i> @lang("cms.Retry Deposit")</button>
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="PROCEED"><i class="icon-forward3"></i> @lang("cms.Proceed to Inquiry")</button>
                                                            @elseif (@$disbursement_customer_transfer->status == "FAILED_MONEY_TRANSFER_INQUIRY")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY"><i class="icon-reload-alt"></i> @lang("cms.Retry Inquiry")</button>
                                                            @endif
                                                        </form>
                                                    @elseif (@$disbursement_customer_transfer->transfer_using == "PAYOUT_BY_REQUEST")
                                                        <form method="post" action="{{ route("cms.yukk_co.disbursement_customer_transfer.action", @$disbursement_customer_transfer->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="transfer_using" value="{{ @$disbursement_customer_transfer->transfer_using }}"/>
                                                            <input type="hidden" name="status" value="{{ @$disbursement_customer_transfer->status }}"/>
                                                            <input type="hidden" name="status_flip" value="{{ @$disbursement_customer_transfer->status_flip }}"/>
                                                            @if (@$disbursement_customer_transfer->status == "FAILED_MONEY_TRANSFER_SUMMARY")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY"><i class="icon-reload-alt"></i> @lang("cms.Retry")</button>
                                                            @elseif (@$disbursement_customer_transfer->status == "FAILED_MONEY_TRANSFER_DEPOSIT")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY"><i class="icon-reload-alt"></i> @lang("cms.Retry Deposit")</button>
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="PROCEED"><i class="icon-forward3"></i> @lang("cms.Proceed to Inquiry")</button>
                                                            @elseif (@$disbursement_customer_transfer->status == "FAILED_MONEY_TRANSFER_INQUIRY")
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY"><i class="icon-reload-alt"></i> @lang("cms.Retry Inquiry")</button>
                                                            @endif
                                                        </form>
                                                    @else
                                                        @if (@$disbursement_customer_transfer->status == "FAILED")
                                                            <form method="post" action="{{ route("cms.yukk_co.disbursement_customer_transfer.action", @$disbursement_customer_transfer->id) }}">
                                                                @csrf
                                                                <input type="hidden" name="transfer_using" value="{{ @$disbursement_customer_transfer->transfer_using }}"/>
                                                                <input type="hidden" name="status" value="{{ @$disbursement_customer_transfer->status }}"/>
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY_WITH_CHANGE_BANK_DETAILS"><i class="icon-pencil7"></i> @lang("cms.Change Bank Details + Retry")</button>
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY"><i class="icon-reload-alt"></i> @lang("cms.Retry")</button>
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="PROCEED"><i class="icon-forward"></i> @lang("cms.Proceed")</button>
                                                            </form>
                                                        @elseif(@$disbursement_customer_transfer->status == "ON_GOING" && $disbursement_customer_transfer->bank_type == "BCA")
                                                            <form method="post" action="{{ route("cms.yukk_co.disbursement_customer_transfer.action", @$disbursement_customer_transfer->id) }}">
                                                                @csrf
                                                                <input type="hidden" name="transfer_using" value="{{ @$disbursement_customer_transfer->transfer_using }}"/>
                                                                <input type="hidden" name="status" value="{{ @$disbursement_customer_transfer->status }}"/>
                                                                <button name="action" type="submit" class="dropdown-item btn-confirm" value="RETRY"><i class="icon-reload-alt"></i> @lang("cms.Retry")</button>
                                                            </form>
                                                        @endif
                                                    @endif
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
        </div>
    </div>


    @foreach(collect($disbursement_customer->disbursement_customer_transfer_list) as $index => $disbursement_customer_transfer)
        <div id="modal-transfer-detail-{{ $disbursement_customer_transfer->id }}" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang("cms.Detail")</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Ref Code")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->ref_code }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Disbursement Date")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->disbursement_date }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Beneficiary")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->customer->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Account Number")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->customer_account_number }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Account Name")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->customer_account_name }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Partner")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->partner->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Partner Parking Account Number")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->partner_rek_parking_account_number }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Partner Parking Account Name")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->partner_rek_parking_account_name }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Transfer Using")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->transfer_using }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Bank Type")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->bank->name }} ({{ @$disbursement_customer_transfer->bank->id }})">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Bank Type")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->bank_type }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Merchant Portion")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->total_merchant_portion, 2) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Disbursement Fee")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->disbursement_fee, 2) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Rounding")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->rounding, 2) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Total Disbursement")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->total_disbursement, 2) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Unique Code")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->flip_unique_code, 2) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Total Disbursement + Unique Code")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->total_disbursement + $disbursement_customer_transfer->flip_unique_code, 2) }}">
                            </div>
                        </div>

                        <hr>

                        @if (@$disbursement_customer_transfer->transfer_using == "DSP")
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.DSP Message ID")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @$disbursement_customer_transfer->dsp_message_id }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.DSP Inquiry Reference ID")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @$disbursement_customer_transfer->dsp_inq_reference_id }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.DSP Batch ID")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @$disbursement_customer_transfer->dsp_batch_id }}">
                                </div>
                            </div>

                            <hr>
                        @elseif (@$disbursement_customer_transfer->transfer_using == "FLIP")
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Flip Top Up ID")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->flip_top_up_id }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Flip Top Up Account Number")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->flip_top_up_account_number }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">@lang("cms.Flip Disbursement ID")</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->flip_disbursement_id }}">
                                </div>
                            </div>

                            <hr>
                        @else

                        @endif

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->status }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Status DSP")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->status_dsp }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Status Flip")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->status_flip }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Status Replenish")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer_transfer->status_transfer_disbursement_fee }}">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">@lang("cms.Close")</button>
                    </div>
                </div>
            </div>
        </div>

        @if (in_array(@$disbursement_customer_transfer->status_flip, ["FAILED_DISBURSEMENT", "FAILED_CHECK_DISBURSEMENT"]))
            <div id="modal-mark-success-{{ $disbursement_customer_transfer->id }}" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang("cms.Mark as Success")</h5>
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    Before you mark this disbursement as SUCCESS, you need to make sure the money already transferred to Beneficiary with details as follows:
                                    <table class="table table-borderless table-mark-success">
                                        <tr>
                                            <td style="width: 40%;">@lang("cms.Account Number")</td>
                                            <td>{{ @$disbursement_customer_transfer->customer_account_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang("cms.Account Name")</td>
                                            <td>{{ @$disbursement_customer_transfer->customer_account_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang("cms.Bank Name")</td>
                                            <td>{{ @$disbursement_customer_transfer->bank->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang("cms.Amount")</td>
                                            <td>@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($disbursement_customer_transfer->total_disbursement) }}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang("cms.Remark")</td>
                                            <td>YUK{{ @str_replace("_", "-", $disbursement_customer_transfer->ref_code) }}</td>
                                        </tr>
                                    </table><br>
                                    You can check the disbursement on FLIP Dashboard or using <a href="https://business.flip.id/history" target="_blank">this link</a> on menu <b>Riyawat Transaksi</b>.<br><br>
                                    If the disbursement already exist with said details above, then you can proceed to click <b>Mark as Success</b> button below.<br><br><br>

                                    But if the disbursement is <b>FAILED or not found</b>, you can manually transfer using Dashboard Flip on menu <b>Money Transfer</b> and select using CSV or using <a href="https://business.flip.id/transaction/upload-super-csv">this link</a>.<br><br>
                                    @php($data = "data:text/csv;charset=utf-8,")
                                    @php($data .= rawurlencode("No,Bank Tujuan,Nomor Rekening Tujuan,Nominal,Berita Transfer (Opsional),Email Penerima (Opsional),Nama Penerima (Opsional),ID Unik Transaksi (Opsional),Berita Transfer Tambahan (Opsional)\r\n"))
                                    @php($data .= rawurlencode("1,".$disbursement_customer_transfer->bank->flip_bank_code.",".$disbursement_customer_transfer->customer_account_number.",".number_format($disbursement_customer_transfer->total_disbursement, 0, ".", "").",YUK".@str_replace("_", "-", $disbursement_customer_transfer->ref_code).",,".$disbursement_customer_transfer->customer_account_name.",".@str_replace("_", "-", $disbursement_customer_transfer->ref_code).",YUKK Disbursement"))
                                    <a href="{{ $data }}" download="FLIP Manual Transfer {{ $disbursement_customer_transfer->ref_code }}.csv" class="badge badge-light"><i class="icon-file-excel"></i> Download CSV for FLIP</a>
                                    <br><br>
                                    Please download this CSV and upload to FLIP Dashboard. If the money transfer is success, then you can proceed to click <b>Mark as Success</b> button below.<br>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">@lang("cms.Close")</button>

                            <form method="post" action="{{ route("cms.yukk_co.disbursement_customer_transfer.action", @$disbursement_customer_transfer->id) }}">
                                @csrf
                                <input type="hidden" name="transfer_using" value="{{ @$disbursement_customer_transfer->transfer_using }}"/>
                                <input type="hidden" name="status" value="{{ @$disbursement_customer_transfer->status }}"/>
                                <input type="hidden" name="status_flip" value="{{ @$disbursement_customer_transfer->status_flip }}"/>
                                <button name="action" class="btn btn-primary btn-confirm" type="submit" value="MARK_AS_SUCCESS"><i class="icon-forward3"></i> @lang("cms.Mark as Success")</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
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
