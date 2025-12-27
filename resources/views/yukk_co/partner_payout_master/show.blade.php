@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Partner Payout")</h4>
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
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Partner Payout")</h5>
        </div>

        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">@lang("cms.Partner")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ $partner_payout_master->partner->name }}"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label">@lang("cms.Status")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ $partner_payout_master->status }}"/>
                </div>

                <label class="col-md-2 col-form-label">@lang("cms.Disbursement Fee")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control text-right" value="@lang("cms.IDR") {{ \App\Helpers\H::formatNumber($partner_payout_master->partner->disbursement_fee, 2) }}"/>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-md-2 col-form-label">@lang("cms.Total Invoice")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ \App\Helpers\H::formatNumber($partner_payout_master->count_all_invoice) }}"/>
                </div>

                <label class="col-md-2 col-form-label">@lang("cms.Total Fee Partner")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control text-right" value="@lang("cms.IDR") {{ \App\Helpers\H::formatNumber($partner_payout_master->sum_fee_partner_fixed + $partner_payout_master->sum_fee_partner_percentage, 2) }}"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label">@lang("cms.Total Invoice Paid")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ \App\Helpers\H::formatNumber($partner_payout_master->count_done_invoice) }}"/>
                </div>

                <label class="col-md-2 col-form-label">@lang("cms.Total Fee Partner Paid")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control text-right" value="@lang("cms.IDR") {{ \App\Helpers\H::formatNumber($partner_payout_master->sum_done_fee_partner_fixed + $partner_payout_master->sum_done_fee_partner_percentage, 2) }}"/>
                </div>
            </div>

            <table class="table table-bordered table-striped" id="table-invoice">
                <thead>
                <tr>
                    <th></th>
                    <th>@lang("cms.Beneficiary")</th>
                    <th>@lang("cms.Kwitansi Number")</th>
                    <th>@lang("cms.Kwitansi Date")</th>
                    <th>@lang("cms.Ref Code Journal")</th>
                    <th>@lang("cms.Journal Date")</th>
                    <th>@lang("cms.Total Fee Partner")</th>
                    <th>@lang("cms.Status Invoice")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($partner_payout_master->partner_payout_details as $index => $partner_payout_detail)
                    <tr>
                        <td class="td-select text-center">
                            @if ($partner_payout_detail->status == "PENDING" && $partner_payout_detail->customer_invoice_master->status == "PAID")
                                <input type="checkbox" class="select-invoice" data-partner-payout-detail-id="{{ @$partner_payout_detail->id }}" data-fee-partner="{{ @$partner_payout_detail->customer_invoice_master->sum_fee_partner_fixed + @$partner_payout_detail->customer_invoice_master->sum_fee_partner_percentage }}"/>
                            @endiF
                        </td>
                        <td>{{ @$partner_payout_detail->customer_invoice_master->customer->name }}</td>
                        <td>{{ @$partner_payout_detail->customer_invoice_master->invoice_number }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($partner_payout_detail->customer_invoice_master->invoice_date, "d-M-Y") }}</td>
                        <td>
                            {{ $partner_payout_detail->ref_code_journal }} &nbsp;
                            @if ($partner_payout_detail->status == "PENDING")
                                <span class="badge badge-warning badge-pill">{{ $partner_payout_detail->status }}</span>
                            @else
                                <span class="badge badge-primary badge-pill">{{ $partner_payout_detail->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if (@$partner_payout_detail->journal_date)
                                {{ @\App\Helpers\H::formatDateTime($partner_payout_detail->journal_date, "d-M-Y") }}
                            @endif
                        </td>
                        <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($partner_payout_detail->customer_invoice_master->sum_fee_partner_fixed + $partner_payout_detail->customer_invoice_master->sum_fee_partner_percentage, 2) }}</td>
                        <td class="text-center">
                            @if($partner_payout_detail->customer_invoice_master->status == "PAID")
                                <span class="badge badge-pill badge-success">{{ @$partner_payout_detail->customer_invoice_master->status }}</span>
                            @else
                                <span class="badge badge-pill badge-danger">{{ @$partner_payout_detail->customer_invoice_master->status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a target="_blank" href="{{ route("cms.yukk_co.customer_invoice_master.item", $partner_payout_detail->customer_invoice_master->id) }}" class="dropdown-item"><i class="icon-new-tab"></i> @lang("cms.Detail")</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>


                @if ($partner_payout_master->status != "DONE")
                    <tfoot>
                    <tr>
                        <td></td>
                        <td class="font-weight-bold" colspan="4">@lang("cms.Selected")</td>
                        <td class="text-right font-weight-bold" id="td-total-selected-fee-partner"></td>
                        <td class="text-center font-weight-bold" id="td-total-selected-count"></td>
                        <td></td>
                    </tr>
                    </tfoot>
                @endif
            </table>

            @if ($partner_payout_master->status != "DONE")
                <button class="btn btn-primary btn-block" id="btn-make-payment">@lang("cms.Make Payment")</button>
            @endif
        </div>
    </div>

    <div id="create-partner-payout-modal" class="modal fade" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <form id="form-mark-as-paid" action="{{ route("cms.yukk_co.partner_payout_master.mark_as_paid") }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang("cms.Summary Partner Payout")</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-form-label col-sm-4" for="journal_date">@lang("cms.Journal Date")</label>
                            <div class="col-sm-8">
                                <input type="text" name="journal_date" id="journal_date" class="form-control" value="" readonly="readonly" required="required"/>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <label class="col-form-label col-sm-4">@lang("cms.Partner Name")</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ @$partner_payout_master->partner->name }}" readonly="readonly"/>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">@lang("cms.Total Fee Partner")</label>
                            <div class="col-sm-8">
                                <input type="text" id="summary-total-fee-partner" class="form-control text-right" value="" readonly="readonly"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">@lang("cms.Disbursement Fee")</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control text-right" value="@lang("cms.IDR") {{ \App\Helpers\H::formatNumber($partner_payout_master->partner->disbursement_fee) }}" readonly="readonly"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">@lang("Total yang harus ditransfer")</label>
                            <div class="col-sm-8">
                                <input type="text" id="summary-total-transferable" class="form-control text-right" value="" readonly="readonly"/>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">@lang("cms.Bank Account Number")</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ @$partner_payout_master->partner->account_number }}" readonly="readonly"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">@lang("cms.Bank Account Name")</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ @$partner_payout_master->partner->account_name }}" readonly="readonly"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">@lang("cms.Bank Name")</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ @$partner_payout_master->partner->bank->name }}" readonly="readonly"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">@lang("cms.Bank Branch")</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ @$partner_payout_master->partner->account_branch_name }}" readonly="readonly"/>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="partner_payout_master_id" value="{{ $partner_payout_master->id }}"/>
                        <input type="hidden" name="partner_payout_detail_ids" id="partner_payout_detail_ids" value=""/>
                        <button type="button" class="btn btn-link" data-dismiss="modal">@lang("cms.Close")</button>
                        <button type="button" id="mark-as-paid" class="btn btn-primary">@lang("cms.Mark as Paid")</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        var dataTable;
        $(document).ready(function() {
            var totalSelectedFeePartner = 0;
            var totalSelectedCount = 0;
            var disbursementFee = parseInt("{{ number_format($partner_payout_master->partner->disbursement_fee, 2, ".", "") }}");

            dataTable = $("#table-invoice").DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
            });

            function refreshTFooter() {
                var rowData = $(".select-invoice:checked");

                totalSelectedFeePartner = 0;
                for (var i = 0; i < rowData.length; i++) {
                    totalSelectedFeePartner += parseFloat($(rowData[i]).data("fee-partner"));
                }
                $("#td-total-selected-fee-partner").html("@lang("cms.IDR") " + new Intl.NumberFormat('id-ID', { }).format(totalSelectedFeePartner));
                $("#summary-total-fee-partner").val("@lang("cms.IDR") " + new Intl.NumberFormat('id-ID', { }).format(totalSelectedFeePartner));
                $("#summary-total-transferable").val("@lang("cms.IDR") " + new Intl.NumberFormat('id-ID', { }).format(totalSelectedFeePartner - disbursementFee));

                totalSelectedCount = rowData.length;

                $("#td-total-selected-count").html(totalSelectedCount);
            }

            $("#table-invoice").on('click', ".td-select", function(e) {
                var tdSelect = $(this).find(".select-invoice")[0];
                tdSelect.click();
            });

            $(".select-invoice").click(function(e) {
                refreshTFooter();
                e.stopPropagation();
            });

            $(".select-invoice").each(function(index, item) {
                item.checked = true;
            });

            refreshTFooter();

            $("#btn-make-payment").click(function() {
                if (totalSelectedFeePartner - disbursementFee < 10000) {
                    alert("@lang("cms.minimum_transfer_alert", ["minimum_transfer" => 10000])");
                } else {
                    $("#create-partner-payout-modal").modal();
                }
            });

            $("#mark-as-paid").click(function(e) {
                if (! window.confirm("@lang("cms.general_confirmation_dialog_content")")) {
                    e.preventDefault();
                } else {
                    var arrIds = [];
                    $(".select-invoice:checked").each(function(index, item) {
                        var partnerPayoutDetailId = $(item).data("partner-payout-detail-id");
                        arrIds.push(partnerPayoutDetailId);
                    });

                    $("#partner_payout_detail_ids").val(JSON.stringify(arrIds));
                    $("#form-mark-as-paid").submit();
                }
            });

            $("#journal_date").daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });
        });
    </script>

@endsection