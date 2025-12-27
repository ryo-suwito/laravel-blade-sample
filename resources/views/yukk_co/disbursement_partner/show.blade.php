@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Disbursement Partner")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.disbursement_partner.list") }}" class="breadcrumb-item">@lang("cms.Disbursement Partner")</a>
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
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Disbursement Partner")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->partner->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Description")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$disbursement_partner->partner->description }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$disbursement_partner->partner->email_list }}</textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->bank->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Branch")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->partner_branch_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->partner_account_number }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->partner_account_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Type") (@lang("cms.BCA / NON BCA"))</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->bank_type }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Transfer Using")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->transfer_using }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Ref Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->ref_code }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($disbursement_partner->created_at) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Date")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->disbursement_date }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Partner QRIS")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner_qris, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Partner PG")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner_pg, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Partner Debt")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner_debt, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Partner")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Fee")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_partner->disbursement_fee, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Rounding")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_partner->rounding, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Disbursement")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_disbursement, 2) }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->status }}">
                        </div>
                    </div>

                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("DISBURSEMENT_PARTNER.ACTION"))
                        <div class="form-group row">
                            <div class="col-12 text-center">
                                @if (@$disbursement_partner->transfer_using == "BCA")
                                    @if (@$disbursement_partner->status == "FAILED" || @$disbursement_partner->status == "ON_GOING")
                                        <form method="post" action="{{ @route("cms.yukk_co.disbursement_partner.action", $disbursement_partner->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="{{ @$disbursement_partner->status }}"/>
                                            <input type="hidden" name="transfer_using" value="{{ @$disbursement_partner->transfer_using }}"/>
                                            <button type="submit" class="btn btn-warning btn-confirm" name="action" value="RETRY_WITH_CHANGE_BANK_DETAILS">@lang("cms.Change Bank Details + Retry")</button>
                                            <button type="submit" class="btn btn-danger btn-confirm" name="action" value="RETRY">@lang("cms.Retry")</button>
                                            <button type="submit" class="btn btn-primary btn-confirm" name="action" value="PROCEED">@lang("cms.Proceed")</button>
                                        </form>
                                    @endif
                                @elseif (@$disbursement_partner->transfer_using == "PAYOUT_BY_REQUEST")
                                    @if (@$disbursement_partner->status == "FAILED_MONEY_TRANSFER")
                                        <form method="post" action="{{ @route("cms.yukk_co.disbursement_partner.action", $disbursement_partner->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="{{ @$disbursement_partner->status }}"/>
                                            <input type="hidden" name="transfer_using" value="{{ @$disbursement_partner->transfer_using }}"/>
                                            <button type="submit" class="btn btn-primary btn-confirm" name="action" value="RETRY">@lang("cms.Retry")</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <h4><u>@lang("cms.Table Settlement QRIS")</u></h4>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Beneficiary Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Total Grand Total")</th>
                            <th>@lang("cms.Total Fee Partner")</th>
                            <th>@lang("cms.Status")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(collect($disbursement_partner->disbursement_partner_detail_list)->where("type", "QRIS") as $index => $disbursement_partner_detail)
                            <tr>
                                <td>{{ @$disbursement_partner_detail->settlement_master->settlement_date }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_master->customer->name }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_master->ref_code }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_master->total_grand_total, 2) }}</td>
                                @if (@$disbursement_partner_detail->settlement_master_partner_fee != null)
                                    <td>{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_master_partner_fee->total_fee_partner, 2) }}</td>
                                @else
                                    <td>{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_master->total_fee_partner, 2) }}</td>
                                @endif
                                <td>{{ @$disbursement_partner_detail->settlement_master->status }}</td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route("cms.yukk_co.settlement_master.show", @$disbursement_partner_detail->settlement_master->id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                    <h4><u>@lang("cms.Table Settlement PG")</u></h4>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Beneficiary Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Total Grand Total")</th>
                            <th>@lang("cms.Total Fee Partner")</th>
                            <th>@lang("cms.Status")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(collect($disbursement_partner->disbursement_partner_detail_list)->where("type", "PG") as $index => $disbursement_partner_detail)
                            <tr>
                                <td>{{ @$disbursement_partner_detail->settlement_pg_master->settlement_date }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_pg_master->customer->name }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_pg_master->ref_code }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_pg_master->total_grand_total, 2) }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_pg_master->total_fee_partner, 2) }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_pg_master->status }}</td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route("cms.yukk_co.settlement_pg_master.show", @$disbursement_partner_detail->settlement_pg_master->id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                    <h4><u>@lang("cms.Table Settlement Debt")</u></h4>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Beneficiary Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Total Grand Total")</th>
                            <th>@lang("cms.Total Fee Partner")</th>
                            <th>@lang("cms.Type")</th>
                            <th>@lang("cms.Status")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(collect($disbursement_partner->disbursement_partner_detail_list)->where("type", "DEBT") as $index => $disbursement_partner_detail)
                            <tr>
                                <td>{{ @$disbursement_partner_detail->settlement_debt_master->settlement_date }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_debt_master->customer->name }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_debt_master->ref_code }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_debt_master->total_grand_total, 2) }}</td>
                                <td>{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_debt_master->total_fee_partner, 2) }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_debt_master->type }}</td>
                                <td>{{ @$disbursement_partner_detail->settlement_debt_master->status }}</td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route("cms.yukk_co.settlement_debt.show", @$disbursement_partner_detail->settlement_debt_master->id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Fee Partner QRIS")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner_qris, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Fee Partner PG")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner_pg, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Fee Partner Debt")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner_debt, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Fee Partner")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner, 2) }}</span></h3>
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
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_partner->disbursement_fee, 2) }}</span></h3>
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
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_partner->rounding, 2) }}</span></h3>
                            </div>
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
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_disbursement, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
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
