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
                    <span class="breadcrumb-item active">@lang("cms.Finish")</span>
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
            <h5 class="card-title">@lang("cms.Settlement Debt")</h5>
        </div>
        <div class="collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="pl-3 col-12 text-muted">
                        @lang("cms.Settlement Debt(s) Created Successfully, here is the result:")
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>@lang("cms.Ref Code")</th>
                                <th>@lang("cms.Settlement Date")</th>
                                <th>@lang("cms.Customer Name")</th>
                                <th>@lang("cms.Partner Name")</th>
                                <th>@lang("cms.Total Grand Total")</th>
                                <th>@lang("cms.Total Merchant Portion")</th>
                                <th>@lang("cms.Total Fee Partner")</th>
                                <th>@lang("cms.Actions")</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php($sum_grand_total = $sum_merchant_portion = $sum_fee_partner = 0)
                            @foreach($settlement_debt_list as $index => $settlement_debt)
                                @php($sum_grand_total += $settlement_debt->total_grand_total)
                                @php($sum_merchant_portion += $settlement_debt->total_merchant_portion)
                                @php($sum_fee_partner += $settlement_debt->total_fee_partner)
                                <tr>
                                    <td>{{ @$settlement_debt->ref_code }}</td>
                                    <td>{{ @\App\Helpers\H::formatDateTimeWithoutTime($settlement_debt->settlement_date) }}</td>
                                    <td>{{ @$settlement_debt->customer->name }}</td>
                                    <td>{{ @$settlement_debt->partner->name }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_debt->total_grand_total, 2) }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_debt->total_merchant_portion, 2) }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_debt->total_fee_partner, 2) }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("SETTLEMENT_DEBT.VIEW"))
                                                        <a href="{{ route("cms.yukk_co.settlement_debt.show", $settlement_debt->id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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
                                <th class="text-right">{{ \App\Helpers\H::formatNumber($sum_grand_total, 2) }}</th>
                                <th class="text-right">{{ \App\Helpers\H::formatNumber($sum_merchant_portion, 2) }}</th>
                                <th class="text-right">{{ \App\Helpers\H::formatNumber($sum_fee_partner, 2) }}</th>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-6 col-lg-4">
                        <a href="{{ route("cms.yukk_co.settlement_debt.input_dispute.form") }}" class="btn btn-block btn-secondary"><i class="icon-file-excel"></i> @lang("cms.Input other Disputes")</a>
                    </div>
                    <div class="col-6 col-lg-8">
                        <a href="{{ route("cms.yukk_co.settlement_debt.list") }}" class="btn btn-block btn-primary"><i class="icon-undo2"></i> @lang("cms.Back to Settlement Debt List")</a>
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

            $(".btn-confirm").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
