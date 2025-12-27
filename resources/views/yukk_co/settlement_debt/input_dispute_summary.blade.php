@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Settlement Debt Input From Dispute")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.settlement_debt.list") }}" class="breadcrumb-item">@lang("cms.Settlement Debt List")</a>
                    <a href="{{ route("cms.yukk_co.settlement_debt.input_dispute.form") }}" class="breadcrumb-item">@lang("cms.Settlement Debt Input From Dispute")</a>
                    <span class="breadcrumb-item active">@lang("cms.Summary")</span>
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
    @if ($rrn_list_not_found->count() > 0 || $rrn_list_double->count() > 0 || $transaction_payment_already_disputed_list->count() > 0)
        <div class="alert alert-danger alert-styled-left">
            <span class="font-weight-bold">@lang("cms.Warning")!!!</span> @lang("cms.There are problem from the input data"). <a href="#problem-section-header">@lang("cms.See Details") <i class="icon-arrow-right13"></i></a>
        </div>
    @endif

    <form action="{{ route("cms.yukk_co.settlement_debt.input_dispute.submit") }}" method="post">
        @csrf
        @if ($transaction_payment_grouped_by->count() > 0)
            <div class="mb-3 mt-2">
                <h6 class="mb-0 font-weight-semibold">
                    @lang("cms.Summary Dispute")
                </h6>
                <span class="text-muted d-block">@lang("cms.Summary from Dispute Grouped by Beneficiary x Partner")</span>
            </div>

            @foreach ($transaction_payment_grouped_by as $transaction_payment_group)
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">@lang("cms.Settlement Debt") <b>{{ $transaction_payment_group->customer->name }}</b> x <b>{{ $transaction_payment_group->partner->name }}</b></h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <h5 class="ml-3">@lang("cms.Beneficiary")</h5>
                                </div>
                                <div class="col-8">
                                    <h5><b>{{ $transaction_payment_group->customer->name }}</b></h5>
                                </div>
                                <div class="col-4">
                                    <h5 class="ml-3">@lang("cms.Partner")</h5>
                                </div>
                                <div class="col-8">
                                    <h5><b>{{ $transaction_payment_group->partner->name }}</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>@lang("cms.RRN")</th>
                                            <th>@lang("cms.Transaction Time")</th>
                                            <th>@lang("cms.Merchant Branch Name")</th>
                                            {{--<th>@lang("cms.Customer Name")</th>--}}
                                            {{--<th>@lang("cms.Partner Name")</th>--}}
                                            <th>@lang("cms.Grand Total")</th>
                                            <th>@lang("cms.Merchant Portion")</th>
                                            <th>@lang("cms.Partner Portion")</th>
                                            <th>@lang("cms.Remark")</th>
                                            <th>@lang("cms.Actions")</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @php(@$transaction_payment_id_already_disputed_ids = isset($transaction_payment_already_disputed_list) ? $transaction_payment_already_disputed_list->pluck("transaction_id") : collect([]))
                                        @foreach($transaction_payment_group->transaction_payment_list as $index => $transaction_payment)
                                            <tr @if($rrn_list_double->keys()->contains($transaction_payment->transaction_code) || $transaction_payment_id_already_disputed_ids->contains($transaction_payment->id)) class="text-danger" @endif>
                                                <td>
                                                    @if($rrn_list_double->keys()->contains($transaction_payment->transaction_code))
                                                        <input type="checkbox" class="cb-transaction-payment" data-transaction-payment-id="{{ @$transaction_payment->id }}" checked="">
                                                    @endif
                                                </td>
                                                <td>
                                                    <input name="transaction_payment_ids[]" value="{{ $transaction_payment->id }}" type="hidden" id="transaction-payment-{{ @$transaction_payment->id }}"/>
                                                    {{ @$transaction_payment->transaction_code }}
                                                </td>
                                                <td>{{ @\App\Helpers\H::formatDateTime($transaction_payment->transaction_time) }}</td>
                                                <td>{{ @$transaction_payment->merchant_branch_name }}</td>
                                                {{--<td>{{ @$transaction_payment->customer->name }}</td>--}}
                                                {{--<td>{{ @$transaction_payment->transaction_payment_extra->partner->name }}</td>--}}
                                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($transaction_payment->grand_total, 2) }}</td>
                                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($transaction_payment->transaction_payment_extra->merchant_portion, 2) }}</td>
                                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($transaction_payment->transaction_payment_extra->fee_partner_fixed + $transaction_payment->transaction_payment_extra->fee_partner_percentage, 2) }}</td>
                                                <td>
                                                    @php($problem = collect([]))
                                                    @if($rrn_list_double->keys()->contains($transaction_payment->transaction_code))
                                                        @php($problem[] = __("cms.RRN has multiple entries"))
                                                    @endif
                                                    @if($transaction_payment_id_already_disputed_ids->contains($transaction_payment->id))
                                                        @php($problem[] = __("cms.Transaction Already Disputed"))
                                                    @endif
                                                    @if (! $problem->isEmpty())
                                                        <ul style="padding-left: 5px; margin-bottom: 0px;">
                                                            @foreach ($problem as $p)
                                                                <li>{{ $p }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="list-icons">
                                                        <div class="dropdown">
                                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                                <i class="icon-menu9"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                @if(\App\Helpers\AccessControlHelper::checkCurrentAccessControl("TRANSACTION_PAYMENT_VIEW"))
                                                                    <a href="{{ route("cms.yukk_co.transaction_payment.item", @$transaction_payment->id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <th colspan="4">@lang("cms.Total")</th>
                                            <th class="text-right">{{ \App\Helpers\H::formatNumber($transaction_payment_group->sum_grand_total, 2) }}</th>
                                            <th class="text-right">{{ \App\Helpers\H::formatNumber($transaction_payment_group->sum_merchant_portion, 2) }}</th>
                                            <th class="text-right">{{ \App\Helpers\H::formatNumber($transaction_payment_group->sum_fee_partner, 2) }}</th>
                                            <td colspan="2"></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <hr>

        @if ($rrn_list_not_found->count() > 0 || $rrn_list_double->count() > 0 || $transaction_payment_already_disputed_list->count() > 0)
            <div class="mb-3 mt-2" id="problem-section-header">
                <h6 class="mb-0 font-weight-semibold text-danger">
                    @lang("cms.Problem(s) from Data")
                </h6>
                <span class="text-muted d-block">@lang("cms.Problem from input data")</span>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-8 col-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h5 class="card-title">@lang("cms.Transaction Already Disputed") ({{ $transaction_payment_already_disputed_list->count() }})</h5>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>

                        <div class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>@lang("cms.RRN")</th>
                                                <th>@lang("cms.Upload Time")</th>
                                                <th>@lang("cms.Settlement Debt Ref Code")</th>
                                                <th>@lang("cms.Settlement Date")</th>
                                                <th>@lang("cms.Actions")</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($transaction_payment_already_disputed_list as $index => $transaction_payment_already_disputed)
                                                <tr>
                                                    <td>{{ @$transaction_payment_already_disputed->transaction_ref_code }}</td>
                                                    <td>{{ @\App\Helpers\H::formatDateTime($transaction_payment_already_disputed->created_at) }}</td>
                                                    <td>{{ @$transaction_payment_already_disputed->settlement_debt_master->ref_code }}</td>
                                                    <td>{{ @\App\Helpers\H::formatDateTimeWithoutTime($transaction_payment_already_disputed->settlement_debt_master->settlement_date) }}</td>
                                                    <td class="text-center">
                                                        <div class="list-icons">
                                                            <div class="dropdown">
                                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                                    <i class="icon-menu9"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if(\App\Helpers\AccessControlHelper::checkCurrentAccessControl("SETTLEMENT_DEBT.VIEW"))
                                                                        <a href="{{ route("cms.yukk_co.settlement_debt.show", @$transaction_payment_already_disputed->settlement_debt_master_id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h5 class="card-title">@lang("cms.RRN List Not Found") ({{ $rrn_list_not_found->count() }})</h5>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>

                        <div class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>@lang("cms.RRN")</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($rrn_list_not_found as $index => $rrn)
                                                <tr>
                                                    <td>{{ @$rrn }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h5 class="card-title">@lang("cms.RRN List Multiple Transaction") ({{ $rrn_list_double->count() }})</h5>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </div>
                            </div>
                        </div>

                        <div class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>@lang("cms.RRN")</th>
                                                <th>@lang("cms.Count")</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($rrn_list_double as $rrn => $count)
                                                <tr>
                                                    <td>{{ @$rrn }}</td>
                                                    <td class="text-right">{{ @$count }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-3">
                <a class="btn btn-secondary btn-block" href="{{ route("cms.yukk_co.settlement_debt.input_dispute.form") }}"><i class="icon-undo2"></i> @lang("cms.Cancel")</a>
            </div>
            <div class="col-9">
                <button class="btn btn-primary btn-block btn-confirm" type="submit">@lang("cms.Submit")</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();

            $(".btn-confirm").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });

            $(".cb-transaction-payment").change(function() {
                let transactionPaymentId = $(this).data("transaction-payment-id");
                let checked = $(this).is(":checked");

                if (checked) {
                    $(`#transaction-payment-${transactionPaymentId}`).removeAttr("disabled");
                } else {
                    $(`#transaction-payment-${transactionPaymentId}`).attr("disabled", "disabled");
                }
            });

            $(".cb-transaction-payment").each(function(index, item) {
                $(item).prop('checked','checked');
            });
        });
    </script>
@endsection
