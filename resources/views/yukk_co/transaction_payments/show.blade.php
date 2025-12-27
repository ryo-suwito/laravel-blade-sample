@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Transaction Payment List")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.transaction_payment.list") }}" class="breadcrumb-item">@lang("cms.Transaction Payment List")</a>
                    <span class="breadcrumb-item active">{{ @$transaction_payment->transaction_code }}</span>
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
        <div class="card-header header-elements-inline">
            <h5 class="card-title">@lang("cms.Detail")</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>


        <div class="collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.RRN")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->transaction_code }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Transaction Time")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($transaction_payment->transaction_time) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Bill Code")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->bill_code }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.YUKK As")</label>
                            <div class="col-lg-4">
                                @if(@$transaction_payment->yukk_as == "ISSUER" && @$transaction_payment->is_yukk)
                                    <input type="text" class="form-control" readonly="" value="ISSUER_ON_US">
                                @elseif(@$transaction_payment->yukk_as == "ISSUER" && ! @$transaction_payment->is_yukk)
                                    <input type="text" class="form-control" readonly="" value="ISSUER_OFF_US">
                                @else
                                    <input type="text" class="form-control" readonly="" value="{{ $transaction_payment->yukk_as }}">
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->status }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.QRIS Type")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->qris_qr_type }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch Name (QRIS)")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->merchant_branch_name }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch City")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->merchant_branch_city }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.EDC")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->edc->imei }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.EDC ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->edc->id }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.YUKK ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->user->yukk_id }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.User ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->user->id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->merchant_branch->name }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->merchant_branch->id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Customer Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->customer->name }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Customer ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->customer->id }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Partner Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->partner->name }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Partner ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->partner->id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Order ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->transaction_payment_extra->partner_order_order_id }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Grand Total")</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->grand_total, 2) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.YUKK Cash")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->yukk_p, 2) }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.YUKK Points")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->yukk_e, 2) }}">
                            </div>
                        </div>

                        <hr>

                        @if (request()->has("full"))
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Merchant Portion")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->merchant_portion, 2) }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.YUKK Portion")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->yukk_portion, 2) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Fee Partner % in IDR")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->fee_partner_percentage, 2) }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Fee YUKK Additional % in IDR")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->fee_yukk_additional_percentage, 2) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Fee Partner in IDR")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->fee_partner_fixed, 2) }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Fee YUKK Additional in IDR")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->fee_yukk_additional_fixed, 2) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Fee YUKK")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->transaction_payment_extra->fee_yukk, 2) }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Fee Switching")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($transaction_payment->transaction_payment_extra->fee_switching, 2) }}">
                                </div>
                            </div>

                            <hr>
                        @endif

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Issuer ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->issuer_id }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Acquirer ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->acquirer_id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Issuer Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->issuer_name }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Acquirer Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->acquirer_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.MPAN")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->preferable_merchant_mpan }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.CPAN")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->customer_pan }}">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Source Type")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->transaction_payment_extra->source_type}}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Source ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$transaction_payment->transaction_payment_extra->source_id }}">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">@lang("cms.Partner Webhook List")</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>


        <div class="collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>@lang("cms.Time")</th>
                                <th>@lang("cms.Request URL")</th>
                                <th>@lang("cms.Response HTTP Status Code")</th>
                                <th>@lang("cms.Webhook Type")</th>
                                <th>@lang("cms.Actions")</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach (@$partner_webhook_log_list as $partner_webhook_log)
                                <tr>
                                    <td>{{ @\App\Helpers\H::formatDateTime($partner_webhook_log->created_at) }}</td>
                                    <td>{{ @$partner_webhook_log->request_url }}</td>
                                    <td class="text-center">{{ @$partner_webhook_log->response_status_code }}</td>
                                    <td class="text-center">{{ @$partner_webhook_log->webhook_type }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" data-toggle="modal" data-target="#modal-webhook-detail-{{ $partner_webhook_log->id }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
    </div>

    @foreach(@$partner_webhook_log_list as $index => $partner_webhook_log)
        <div id="modal-webhook-detail-{{ $partner_webhook_log->id }}" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang("cms.Partner Webhook")</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Time")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ \App\Helpers\H::formatDateTime($partner_webhook_log->created_at) }}">
                            </div>

                            <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ $partner_webhook_log->response_status_code }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Type")</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" readonly="" value="{{ $partner_webhook_log->webhook_type }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Request URL")</label>
                            <div class="col-lg-10">
                                <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{ $partner_webhook_log->request_method }}</span>
                                        </span>
                                    <input type="text" class="form-control" placeholder="Left icon" value="{{ $partner_webhook_log->request_url }}" readonly="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Request Body")</label>
                            <div class="col-lg-10">
                                <textarea type="text" class="form-control" readonly="">{{ $partner_webhook_log->request_parameters }}</textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Response Body")</label>
                            <div class="col-lg-10">
                                <textarea type="text" class="form-control" readonly="">{{ $partner_webhook_log->response_body }}</textarea>
                            </div>
                        </div>

                        @if ($partner_webhook_log->webhook_type !== "YUKK_MERCHANT")
                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.cURL Command")</label>
                                <div class="col-lg-10">
                                    <textarea type="text" class="form-control" readonly="">{{ @\App\Http\Controllers\YukkCo\TransactionPaymentController::generateCurlPartnerWebhookLog($partner_webhook_log) }}</textarea>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">@lang("cms.Close")</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();
        });
    </script>
@endsection
