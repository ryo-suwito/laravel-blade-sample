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
                    <a href="{{ route("cms.yukk_co.settlement_debt.input_dispute.form") }}" class="breadcrumb-item">@lang("cms.Settlement Debt Input Sharing Profit")</a>
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
    @if ($not_found_sharing_profit_list->count() > 0)
        <div class="alert alert-danger alert-styled-left">
            <span class="font-weight-bold">@lang("cms.Warning")!!!</span> @lang("cms.There are problem from the input data"). <a href="#problem-section-header">@lang("cms.See Details") <i class="icon-arrow-right13"></i></a>
        </div>
    @endif

    <div class="mb-3 mt-2">
        <h6 class="mb-0 font-weight-semibold">
            @lang("cms.Summary Sharing Profit")
        </h6>
        <span class="text-muted d-block">@lang("cms.Summary Sharing Profit Grouped by Partner")</span>
    </div>

    @if ($found_sharing_profit_grouped->count() > 0)

        @foreach ($found_sharing_profit_grouped as $found_sharing_profit)
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">@lang("cms.Sharing Profit") <b>{{ $found_sharing_profit->partner->name }}</b></h5>
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
                                <h5 class="ml-3">@lang("cms.Partner")</h5>
                            </div>
                            <div class="col-8">
                                <h5><b>{{ @$found_sharing_profit->partner->name }}</b></h5>
                            </div>
                            <div class="col-4">
                                <h5 class="ml-3">@lang("cms.Account Number")</h5>
                            </div>
                            <div class="col-8">
                                <h5><b>{{ @$found_sharing_profit->partner->account_number }}</b></h5>
                            </div>
                            <div class="col-4">
                                <h5 class="ml-3">@lang("cms.Account Name")</h5>
                            </div>
                            <div class="col-8">
                                <h5><b>{{ @$found_sharing_profit->partner->account_name }}</b></h5>
                            </div>
                            <div class="col-4">
                                <h5 class="ml-3">@lang("cms.Bank")</h5>
                            </div>
                            <div class="col-8">
                                <h5><b>{{ @$found_sharing_profit->partner->bank->name }}</b></h5>
                            </div>
                            <div class="col-4">
                                <h5 class="ml-3">@lang("cms.Disbursement Interval")</h5>
                            </div>
                            <div class="col-8">
                                <h5><b>{{ @$found_sharing_profit->partner->auto_disbursement_interval }}</b></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>@lang("cms.Notes")</th>
                                        <th>@lang("cms.Type")</th>
                                        <th>@lang("cms.Ref Code")</th>
                                        <th>@lang("cms.Sharing Profit Amount")</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($found_sharing_profit->items as $sharing_profit)
                                        <tr>
                                            <td>{{ @$sharing_profit->notes }}</td>
                                            <td>{{ @$sharing_profit->type }}</td>
                                            <td>{{ @$sharing_profit->ref_code }}</td>
                                            <td class="text-right">{{ @\App\Helpers\H::formatNumber($sharing_profit->fee_partner, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th colspan="3">@lang("cms.Total")</th>
                                        <th class="text-right">{{ \App\Helpers\H::formatNumber($found_sharing_profit->items->sum('fee_partner'), 2) }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <h5>@lang("cms.Total Fee Partner")</h5>

                    <h5><b>@lang("cms.IDR") {{ \App\Helpers\H::formatNumber($found_sharing_profit->items->sum('fee_partner'), 2) }}</b></h5>
                </div>
            </div>
        @endforeach
    @endif

    <hr>

    @if ($not_found_sharing_profit_list->count() > 0)
        <div class="mb-3 mt-2" id="problem-section-header">
            <h6 class="mb-0 font-weight-semibold text-danger">
                @lang("cms.Problem(s) from Data")
            </h6>
            <span class="text-muted d-block">@lang("cms.Problem from input data")</span>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">@lang("cms.Sharing Profit not Found")</h5>
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
                                            <th>@lang("cms.Partner")</th>
                                            <th>@lang("cms.Partner Detail")</th>
                                            <th>@lang("cms.Beneficiary")</th>
                                            <th>@lang("cms.Sharing Profit Amount")</th>
                                            <th>@lang("cms.Notes")</th>
                                            <th>@lang("cms.Ref Code")</th>
                                            <th>@lang("cms.Errors")</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($not_found_sharing_profit_list as $index => $not_found_sharing_profit)
                                            <tr>
                                                <td>{{ @$not_found_sharing_profit->partner->name }}</td>
                                                <td>{{ @$not_found_sharing_profit->partner->account_number }}<br>{{ @$not_found_sharing_profit->partner->account_name }}<br>{{ @$not_found_sharing_profit->partner->bank->name }}</td>
                                                <td>{{ @$not_found_sharing_profit->customer->name }}</td>
                                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($not_found_sharing_profit->fee_partner, 2) }}</td>
                                                <td>{{ @$not_found_sharing_profit->notes }}</td>
                                                <td>{{ @$not_found_sharing_profit->ref_code }}</td>
                                                <td>{{ @$not_found_sharing_profit->error }}</td>
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

    <form action="{{ route("cms.yukk_co.settlement_debt.input_sharing_profit.submit") }}" method="post">
        @csrf
        @foreach($found_sharing_profit_list as $sharing_profit)
            <input type="hidden" name="partner_ids[]" value="{{ $sharing_profit->partner_id }}">
            <input type="hidden" name="customer_ids[]" value="{{ $sharing_profit->customer_id }}">
            <input type="hidden" name="fee_partners[]" value="{{ $sharing_profit->fee_partner }}">
            <input type="hidden" name="notes[]" value="{{ $sharing_profit->notes }}">
            <input type="hidden" name="ref_codes[]" value="{{ $sharing_profit->ref_code }}">
            <input type="hidden" name="types[]" value="{{ $sharing_profit->type }}">
        @endforeach
        <div class="row">
            <div class="col-3">
                <a class="btn btn-secondary btn-block" href="{{ route("cms.yukk_co.settlement_debt.input_sharing_profit.form") }}"><i class="icon-undo2"></i> @lang("cms.Cancel")</a>
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
        });
    </script>
@endsection
