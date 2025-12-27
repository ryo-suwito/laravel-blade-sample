@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.QRIS ReHit")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.qris_re_hit.list") }}" class="breadcrumb-item">@lang("cms.QRIS ReHit")</a>
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
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.QRIS ReHit")</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.RRN")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->rrn }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Amount")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($qris_re_hit->transaction_amount) }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch Name")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->merchant_name }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Merchant branch City")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->merchant_city }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Merchant PAN")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->pan }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Terminal ID")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->terminal_id }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Merchant Branch Postal Code")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->postal_code }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.MID")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->merchant_id }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Issuer ID")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->issuer_id }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Customer PAN")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->customer_pan }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Customer Data")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->customer_data }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.QRIS Type")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->qris_type }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Order ID")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->partner_order_order_id }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->status }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Last Updated At")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($qris_re_hit->updated_at) }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Requester")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->requester_id }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($qris_re_hit->created_at) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.Releaser")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @$qris_re_hit->approver_rejector_id }}">
                                </div>
                                <label class="col-lg-2 col-form-label">@lang("cms.Released At")</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($qris_re_hit->approved_rejected_at) }}">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">@lang("cms.JSON")</label>
                                <div class="col-lg-10">
                                    <textarea type="text" class="form-control" readonly="">{{ @$qris_re_hit->raw }}</textarea>
                                </div>
                            </div>

                            @if (@$qris_re_hit->status == "APPROVED")
                                <hr>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Response Body")</label>
                                    <div class="col-lg-10">
                                        <textarea type="text" class="form-control" readonly="">{{ @$qris_re_hit->response_body }}</textarea>
                                    </div>
                                </div>
                            @endif

                            {{-- Button Section --}}
                            @if (@$qris_re_hit->status == "PENDING")
                                <hr>

                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("QRIS_RE_HIT.RELEASE", "AND"))
                                            <form action="{{ route("cms.yukk_co.qris_re_hit.release", $qris_re_hit->id) }}" method="post">
                                                @csrf
                                                <button class="btn btn-primary btn-need-confirmation" type="submit" name="approve">@lang("cms.Approve")</button>
                                                <button class="btn btn-danger btn-need-confirmation" type="submit" name="reject">@lang("cms.Reject")</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endif
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