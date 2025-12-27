@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Settlement")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.settlement_master.list") }}" class="breadcrumb-item">@lang("cms.Settlement List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail") {{ $settlement_master->ref_code }}</span>
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
            <h5 class="card-title">@lang("cms.Settlement Detail")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->customer->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Customer Description")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_master->customer->description }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_master->customer->email }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Is Customer Switching?")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->customer_is_switching ? "Yes" : "No" }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Customer Switching Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->customer_switching_code }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->customer->bank->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Branch")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->customer->branch_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->customer->account_number }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->customer->account_name }}">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Name")</label>
                        <div class="col-lg-4">
                            @if ($settlement_master->partner)
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->partner->name }}">
                            @else
                                <input type="text" class="form-control" readonly="" value="-">
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Description")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_master->partner->description }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Email List")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_master->partner->email_list }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->partner->bank->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Branch")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->partner->account_branch_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->partner->account_number }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->partner->account_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.BCA / NON BCA")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->partner->bank_type }}">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Ref Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->ref_code }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($settlement_master->created_at) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Settlement Date")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->settlement_date }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Will be Processed At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->will_be_processed_at }}">
                        </div>
                    </div>
                    {{--<div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Start Time Transaction")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($settlement_master->start_time_transaction) }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.End Time Transaction")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($settlement_master->end_time_transaction) }}">
                        </div>
                    </div>--}}
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Start Processed At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->start_processed_at ? @\App\Helpers\H::formatDateTime($settlement_master->start_processed_at) : "" }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Finish Processed At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->finish_processed_at ? @\App\Helpers\H::formatDateTime($settlement_master->finish_processed_at) : "" }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Ref Code Journal")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->ref_code }}_TRANSACTION_BULK">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->status }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Status YUKK Cash to Settlement")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->status_transfer_yukk_cash_to_settlement }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Status YUKK Points to Settlement")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->status_transfer_yukk_points_to_settlement }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Status Settlement to Parking")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->status_transfer_settlement_to_parking }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Settlement to Parking Bulk")</label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                @if (@$settlement_master->settlement_to_parking_transfer != null)
                                    <input type="text" class="form-control" readonly="" value="{{ @$settlement_master->settlement_to_parking_transfer->ref_code }}">

                                    <span class="input-group-prepend">
                                    <a href="{{ route("cms.yukk_co.settlement_transfer.show", $settlement_master->settlement_to_parking_transfer->id) }}" target="_blank">
                                        <span class="input-group-text text-primary"><i class="icon-new-tab"></i></span>
                                    </a>
                                </span>
                                @else
                                    <input type="text" class="form-control" readonly="" value="-">
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total YUKK Cash")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_master->total_yukk_cash, 2) }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Total YUKK Points")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_master->total_yukk_points, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Other Currency")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_master->total_other_currency, 2) }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Grand Total")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_master->total_grand_total, 2) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_master->total_merchant_portion, 2) }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Partner Fee")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_master->total_fee_partner, 2) }}">
                        </div>
                    </div>

                    {{--@if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("SETTLEMENT_MASTER.ACTION", "AND"))
                        <hr>

                        <form method="post" action="{{ route("cms.yukk_co.settlement_master.action", @$settlement_master->id) }}">
                            @csrf
                            <input type="hidden" class="d-none" name="status" value="{{ @$settlement_master->status }}">
                            @if (@$settlement_master->status == "ON_GOING_SETTLEMENT_TO_PARKING")
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="RETRY">@lang("cms.Retry")</button>
                                        <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="PROCEED">@lang("cms.Proceed")</button>
                                    </div>
                                </div>
                            @endif
                        </form>

                        <hr>
                    @endif--}}
                </div>
            </div>

            <br>

        </div>
    </div>

    @if ($settlement_master->is_multiple_partner_fee)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang("cms.Partner Fees")</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                            <tr>
                                <th>@lang("cms.Partner Name")</th>
                                <th>@lang("cms.Percentage")</th>
                                <th>@lang("cms.Partner Fee")</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($settlement_master->settlement_master_partner_fees as $index => $settlement_master_partner_fee)
                                <tr>
                                    <td>{{ @$settlement_master_partner_fee->partner->name }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_master_partner_fee->fee_partner_percentage, 2) }}%</td>
                                    <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($settlement_master_partner_fee->total_fee_partner, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>@lang("cms.Total")</th>
                                <th></th>
                                <th class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($settlement_master->total_fee_partner, 2) }}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Transaction Payment")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <form method="post" action="{{ route("cms.yukk_co.settlement_master.export_csv_transaction", $settlement_master->id) }}">
                                @csrf
                                <button class="btn btn-primary" type="submit"><i class="icon-file-excel"></i> @lang("cms.Export to CSV")</button>
                            </form>
                        </div>
                    </div>

                    <form method="get" action="{{ route("cms.yukk_co.settlement_master.show", $settlement_master->id) }}">
                        <div class="form-group row mt-4">
                            <label class="col-lg-4 col-form-label" for="rrn">@lang("cms.RRN")</label>
                            <div class="col-lg-6">
                                <input type="text" name="rrn" id="rrn" class="form-control" placeholder="@lang("cms.RRN")" value="{{ @$rrn }}">
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary btn-block" type="submit">@lang("cms.Search")</button>
                            </div>
                        </div>
                    </form>



                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Merchant Branch Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Transaction Time")</th>
                            <th>@lang("cms.Type")</th>
                            <th>@lang("cms.Source")</th>
                            <th>@lang("cms.User")</th>
                            <th>@lang("cms.YUKK Cash")</th>
                            <th>@lang("cms.YUKK Points")</th>
                            <th>@lang("cms.Other Currency")</th>
                            <th>@lang("cms.Grand Total")</th>
                            <th>@lang("cms.YUKK Portion")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($settlement_master->transaction_payments as $index => $transaction_payment)
                            <tr>
                                {{-- MERCHANT BRANCH NAME --}}
                                <td>{{ $transaction_payment->merchant_branch_name }}</td>
                                {{-- TRANSACTION CODE --}}
                                <td>
                                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("TRANSACTION_PAYMENT_VIEW", "AND"))
                                        <a href="{{ route("cms.yukk_co.transaction_payment.item", $transaction_payment->id) }}" target="_blank">
                                            {{ $transaction_payment->transaction_code }}
                                        </a>
                                    @else
                                        {{ $transaction_payment->transaction_code }}
                                    @endif
                                </td>
                                {{-- TRANSACTION TIME --}}
                                <td>{{ \App\Helpers\H::formatDateTime($transaction_payment->transaction_time) }}</td>
                                {{-- TYPE --}}
                                <td>
                                    @if ($transaction_payment->yukk_as == "ISSUER")
                                        <span class="badge badge-success">@lang("cms.YUKK")</span>
                                    @else
                                        <span class="badge badge-danger">@lang("cms.NON YUKK")</span>
                                    @endif
                                </td>
                                {{-- SOURCE --}}
                                <td>
                                    @if ($transaction_payment->type == "CROSS_BORDER")
                                        @lang("cms.CROSS BORDER")
                                    @else
                                        @lang("cms.DOMESTIC")
                                    @endif
                                </td>
                                {{-- User ID --}}
                                <td>
                                    {{ @$transaction_payment->user->yukk_id }} ({{ @$transaction_payment->user_id }})
                                </td>
                                {{-- YUKK CASH --}}
                                <td class="text-right">
                                    @if ($transaction_payment->yukk_as == "ACQUIRER")
                                        {{ \App\Helpers\H::formatNumber(0, 2) }}
                                    @else
                                        {{ \App\Helpers\H::formatNumber($transaction_payment->yukk_p, 2) }}
                                    @endif
                                </td>
                                {{-- YUKK POINTS --}}
                                <td class="text-right">
                                    @if ($transaction_payment->yukk_as == "ACQUIRER")
                                        {{ \App\Helpers\H::formatNumber(0, 2) }}
                                    @else
                                        {{ \App\Helpers\H::formatNumber($transaction_payment->yukk_e, 2) }}
                                    @endif
                                </td>
                                {{-- OTHER CURRENCY --}}
                                <td class="text-right">
                                    @if ($transaction_payment->yukk_as == "ACQUIRER")
                                        {{ \App\Helpers\H::formatNumber($transaction_payment->yukk_p, 2) }}
                                    @else
                                        {{ \App\Helpers\H::formatNumber(0, 2) }}
                                    @endif
                                </td>
                                {{-- GRAND TOTAL --}}
                                <td class="text-right">{{ \App\Helpers\H::formatNumber($transaction_payment->grand_total, 2) }}</td>
                                {{-- YUKK PORTION --}}
                                <td>
                                    <span class="text-right">
                                        {{ \App\Helpers\H::formatNumber($transaction_payment->yukk_portion, 2) }}
                                    </span>
                                </td>
                                {{-- MERCHANT PORTION --}}
                                <td class="text-right">{{ \App\Helpers\H::formatNumber($transaction_payment->merchant_portion, 2) }}</td>
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
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("cms.yukk_co.settlement_master.show", array_merge([$settlement_master->id], request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.settlement_master.show", array_merge([$settlement_master->id], request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.settlement_master.show", array_merge([$settlement_master->id], request()->all(), ["page" => $current_page+1])) }}" class="page-link">
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

            $(".btn-need-confirmation").click(function(e) {
                if (confirm("@lang("cms.general_confirmation_dialog_content")")) {
                    setInterval(function() {
                        // Need interval because if disable first, then the button is not included on the form
                        $(".btn-need-confirmation").attr("disabled", "disabled");
                    }, 50);
                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
