@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Settlement Debt")</h4>
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
            <h5 class="card-title">@lang("cms.Settlement Debt Detail")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @if ($settlement_debt_master->type != "SHARING_PROFIT")
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->customer->name }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary ID")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->customer_id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary Description")</label>
                            <div class="col-lg-4">
                                <textarea type="text" class="form-control" readonly="">{{ @$settlement_debt_master->customer->description }}</textarea>
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                            <div class="col-lg-4">
                                <textarea type="text" class="form-control" readonly="">{{ @$settlement_debt_master->customer->email }}</textarea>
                            </div>
                        </div>

                        <hr>
                    @endif

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->partner->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner ID")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->partner_id }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Description")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_debt_master->partner->description }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_debt_master->partner->email_list }}</textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Ref Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->ref_code }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Settlement Date")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTimeWithoutTime($settlement_debt_master->settlement_date) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($settlement_debt_master->created_at) }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Type")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->type }}">
                        </div>
                    </div>

                    <hr>

                    @if ($settlement_debt_master->type == "QRIS_DISPUTE")
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Grand Total")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_grand_total, 2) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_merchant_portion, 2) }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Partner")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_fee_partner, 2) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Total YUKK Portion")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_yukk_portion, 2) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Fee YUKK")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_fee_yukk, 2) }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Switching")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_fee_switching, 2) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Fee YUKK Additional")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_fee_yukk_additional, 2) }}">
                            </div>
                        </div>
                    @elseif ($settlement_debt_master->type == "SHARING_PROFIT")
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_merchant_portion, 2) }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Partner")</label>
                            <div class="col-lg-4">
                                @if ($settlement_debt_master->type == "SHARING_PROFIT")
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_fee_partner * -1, 2) }}">
                                @else
                                    <input type="text" class="form-control text-right" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_debt_master->total_fee_partner, 2) }}">
                                @endif

                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->status }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Status Disbursement Beneficiary")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->status_disbursement_beneficiary }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Status Disbursement Partner")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->status_disbursement_partner }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Beneficiary")</label>
                        <div class="col-lg-4">
                            @if (@isset($settlement_debt_master->disbursement_customer_masters) && @count($settlement_debt_master->disbursement_customer_masters) > 0)
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->disbursement_customer_masters[0]->ref_code }} - {{ @$settlement_debt_master->disbursement_customer_masters[0]->disbursement_date }}">
                            @else
                                <input type="text" class="form-control" readonly="" value="-">
                            @endif
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Partner")</label>
                        <div class="col-lg-4">
                            @if (@isset($settlement_debt_master->disbursement_customer_partners) && @count($settlement_debt_master->disbursement_customer_partners) > 0)
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_debt_master->disbursement_customer_partners[0]->ref_code }} - {{ @$settlement_debt_master->disbursement_customer_partners[0]->disbursement_date }}">
                            @else
                                <input type="text" class="form-control" readonly="" value="-">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">@lang("cms.Transaction List")</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="collapse show">
            <div class="card-footer bg-light d-sm-flex justify-content-sm-between align-items-sm-center text-center text-sm-left py-sm-2">
                <div>{{ count($settlement_debt_master->settlement_debt_details) }} @lang("cms.transaction(s) found")</div>

                <ul class="pagination pagination-sm pagination-pager pagination-pager-linked justify-content-between mt-2 mt-sm-0">
                    <li class="page-item">
                        <form action="{{ route("cms.yukk_co.settlement_debt.download_transaction_report") }}" method="post">
                            @csrf
                            <input type="hidden" name="settlement_debt_id" value="{{ $settlement_debt_master->id }}"/>
                            <button class="page-link" type="submit">@lang("cms.Download")</button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if ($settlement_debt_master->type == "QRIS_DISPUTE")
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th>@lang("cms.Transaction Ref Code")</th>
                                    <th>@lang("cms.Created At")</th>
                                    <th>@lang("cms.Grand Total")</th>
                                    <th>@lang("cms.Merchant Portion")</th>
                                    <th>@lang("cms.Fee Partner")</th>
                                    <th>@lang("cms.Notes")</th>
                                    <th>@lang("cms.Actions")</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($settlement_debt_master->settlement_debt_details as $index => $settlement_debt_detail)
                                    <tr>
                                        <td>{{ @$settlement_debt_detail->transaction_ref_code }}</td>
                                        <td>{{ @\App\Helpers\H::formatDateTime($settlement_debt_detail->created_at) }}</td>
                                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_debt_detail->grand_total, 2) }}</td>
                                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_debt_detail->merchant_portion, 2) }}</td>
                                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_debt_detail->fee_partner, 2) }}</td>
                                        <td>{{ @$settlement_debt_detail->notes }}</td>
                                        <td>
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("TRANSACTION_PAYMENT_VIEW") && $settlement_debt_detail->type === "QRIS")
                                                            <a href="{{ route("cms.yukk_co.transaction_payment.item", @$settlement_debt_detail->transaction_id) }}" class="dropdown-item" target="_blank"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @elseif ($settlement_debt_master->type == "SHARING_PROFIT")
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th>@lang("cms.Settlement Ref Code")</th>
                                    <th>@lang("cms.Created At")</th>
                                    <th>@lang("cms.Fee Partner")</th>
                                    <th>@lang("cms.Type")</th>
                                    <th>@lang("cms.Notes")</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($settlement_debt_master->settlement_debt_details as $index => $settlement_debt_detail)
                                    <tr>
                                        <td>{{ @$settlement_debt_detail->transaction_ref_code }}</td>
                                        <td>{{ @\App\Helpers\H::formatDateTime($settlement_debt_detail->created_at) }}</td>
                                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_debt_detail->fee_partner * -1, 2) }}</td>
                                        <td>{{ @$settlement_debt_detail->type }}</td>
                                        <td>{{ @$settlement_debt_detail->notes }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
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
