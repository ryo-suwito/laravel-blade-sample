@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.User Withdrawal")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.user_withdrawal.list") }}" class="breadcrumb-item">@lang("cms.User Withdrawal List")</a>
                    <span class="breadcrumb-item active">{{ $user_withdrawal->ref_code }}</span>
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
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.User Withdrawal")</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.YUKK ID")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->user->yukk_id }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Ref Code")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->ref_code }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->bank_name }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Bank Type")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->bank_type }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary Name")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->account_name }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.No Rekening")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->account_number }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Amount")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @App\Helpers\H::formatNumber($user_withdrawal->amount, 2) }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Nominal Transfer")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @App\Helpers\H::formatNumber($user_withdrawal->yukk_p, 2) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.MDR Internal")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @App\Helpers\H::formatNumber($user_withdrawal->fee_internal_fixed + $user_withdrawal->fee_internal_percentage, 2) }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.MDR External")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @App\Helpers\H::formatNumber($user_withdrawal->fee_external_fixed + $user_withdrawal->fee_external_percentage, 2) }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->status }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->created_at }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Last Updated At")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$user_withdrawal->updated_at }}">
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    {{-- BUTTON SECTION --}}
                                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("CASHOUT_ADM", "AND"))
                                        {{-- Have CASHOUT_ADM access control --}}
                                        <form action="{{ route("cms.yukk_co.user_withdrawal.action", $user_withdrawal->id) }}" method="post">
                                            <a href="{{ route("cms.yukk_co.user_withdrawal.list") }}" class="btn btn-secondary">@lang("cms.Cancel")</a>
                                            @csrf
                                            <input type="hidden" name="status" value="{{ $user_withdrawal->status }}">
                                            <input type="hidden" name="bank_type" value="{{ $user_withdrawal->bank_type }}">
                                            @if ($button_actions_visible)
                                                @if ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_FAILED_YUKK_CASH_TO_SETTLEMENT && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_NON_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_EXECUTION_ON_HOLD && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_NON_BCA)
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="check">@lang("cms.Check")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_ON_PROCESS_YUKK_CASH_TO_SETTLEMENT && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_NON_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="proceed">@lang("cms.Proceed")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_FAILED_SETTLEMENT_TO_WD && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_NON_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_ON_PROCESS_SETTLEMENT_TO_WD && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_NON_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="proceed">@lang("cms.Proceed")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_FAILED_WD_TO_DSP && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_NON_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_ON_PROCESS_WD_TO_DSP && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_NON_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="proceed">@lang("cms.Proceed")</button>


                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_FAILED_REFUND_SETTLEMENT_TO_YUKK_CASH && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="retry">@lang("cms.Retry Refund")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_ON_PROCESS_REFUND_SETTLEMENT_TO_YUKK_CASH && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="retry">@lang("cms.Retry Refund")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="proceed">@lang("cms.Proceed Refund")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_FAILED_YUKK_CASH_TO_SETTLEMENT && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="refund">@lang("cms.Refund")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_ON_PROCESS_YUKK_CASH_TO_SETTLEMENT && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA) 
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="refund">@lang("cms.Refund")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="proceed">@lang("cms.Proceed")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_FAILED_SETTLEMENT_TO_WD && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA) 
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="refund">@lang("cms.Refund")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_ON_PROCESS_SETTLEMENT_TO_WD && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA) 
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="proceed">@lang("cms.Proceed")</button>
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="refund">@lang("cms.Refund")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_FAILED_WD_TO_BENEF && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA) 
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="refund">@lang("cms.Refund")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_ON_PROCESS_WD_TO_BENEF && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA) 
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="proceed">@lang("cms.Proceed")</button>
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="revert">@lang("cms.Revert")</button>
                                                    <button name="action" type="submit" class="btn btn-primary btn-need-confirmation" value="refund">@lang("cms.Refund")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_ON_PROCESS_REFUND_WD_TO_SETTLEMENT && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="proceed">@lang("cms.Proceed Refund")</button>
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="retry">@lang("cms.Retry Refund")</button>
                                                @elseif ($user_withdrawal->status == \App\Helpers\UserWithdrawalHelper::STATUS_FAILED_REFUND_WD_TO_SETTLEMENT && $user_withdrawal->bank_type == \App\Helpers\UserWithdrawalHelper::BANK_TYPE_BCA)
                                                    <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="retry">@lang("cms.Retry Refund")</button>
                                                @endif
                                            @endif
                                        </form>
                                    @else
                                        {{-- Don't Have CASHOUT_ADM access control --}}
                                        <a href="{{ route("cms.yukk_co.user_withdrawal.list") }}" class="btn btn-secondary">@lang("cms.Cancel")</a>
                                    @endif

                                    {{-- ./BUTTON SECTION --}}
                                </div>
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
            $(".dataTable").DataTable();
            $(".btn-need-confirmation").click(function(e) {
                if (confirm("@lang("cms.user_withdrawal_action_button_action_confirmation")")) {
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