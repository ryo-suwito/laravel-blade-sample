@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Disbursement")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.partner.disbursement_partner.list") }}" class="breadcrumb-item">@lang("cms.Disbursement")</a>
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
            <h5 class="card-title">@lang("cms.Disbursement")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->partner_account_name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->bank->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_partner->bank->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$disbursement_partner->partner->email_list }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($disbursement_partner->created_at) }}">
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th>@lang("cms.Type")</th>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Beneficiary Name")</th>
                            <th>@lang("cms.Total Grand Total")</th>
                            <th>@lang("cms.Total Fee Partner")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php($sum_total_grand_total = 0)
                        @foreach($disbursement_partner->disbursement_partner_detail_list as $index => $disbursement_partner_detail)
                            @if($disbursement_partner_detail->type == "QRIS")
                                @php($sum_total_grand_total += $disbursement_partner_detail->settlement_master->total_grand_total)
                                <tr>
                                    <td>QRIS</td>
                                    <td>{{ @$disbursement_partner_detail->settlement_master->settlement_date }}</td>
                                    <td>{{ @$disbursement_partner_detail->settlement_master->ref_code }}</td>
                                    <td>{{ @$disbursement_partner_detail->settlement_master->customer->name }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_master->total_grand_total, 2) }}</td>
                                    @if (@$disbursement_partner_detail->settlement_master_partner_fee != null)
                                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_master_partner_fee->total_fee_partner, 2) }}</td>
                                    @else
                                        <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_master->total_fee_partner, 2) }}</td>
                                    @endif
                                </tr>
                            @elseif ($disbursement_partner_detail->type == "PG")
                                @php($sum_total_grand_total += $disbursement_partner_detail->settlement_pg_master->total_grand_total)
                                <tr>
                                    <td>PG</td>
                                    <td>{{ @$disbursement_partner_detail->settlement_pg_master->settlement_date }}</td>
                                    <td>{{ @$disbursement_partner_detail->settlement_pg_master->ref_code }}</td>
                                    <td>{{ @$disbursement_partner_detail->settlement_pg_master->customer->name }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_pg_master->total_grand_total, 2) }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_pg_master->total_fee_partner, 2) }}</td>
                                </tr>
                            @elseif ($disbursement_partner_detail->type == "DEBT")
                                @php($sum_total_grand_total += $disbursement_partner_detail->settlement_debt_master->total_grand_total)
                                <tr>
                                    <td>
                                        @if ($disbursement_partner_detail->settlement_debt_master->type == "SHARING_PROFIT")
                                            Sharing Profit
                                        @else
                                            Dispute
                                        @endif
                                    </td>
                                    <td>{{ @$disbursement_partner_detail->settlement_debt_master->settlement_date }}</td>
                                    <td>{{ @$disbursement_partner_detail->settlement_debt_master->ref_code }}</td>
                                    <td>{{ @$disbursement_partner_detail->settlement_debt_master->customer->name }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_debt_master->total_grand_total, 2) }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_partner_detail->settlement_debt_master->total_fee_partner * -1, 2) }}</td>
                                </tr>
                            @endif
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
                                <h3>@lang("cms.Total Grand Total")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($sum_total_grand_total, 2) }}</span></h3>
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
                                <h3><span>{{ @\App\Helpers\H::formatNumber(($disbursement_partner->total_fee_partner_qris + $disbursement_partner->total_fee_partner_pg), 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    @if ($disbursement_partner->total_fee_partner_debt != 0)
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <div class="form-control-plaintext">
                                    <h3>@lang("cms.Total Dispute")</h3>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-control-plaintext text-right">
                                    <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_partner->total_fee_partner_debt, 2) }}</span></h3>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Disbursement Fee")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>-{{ @\App\Helpers\H::formatNumber($disbursement_partner->disbursement_fee, 2) }}</span></h3>
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
        });
    </script>
@endsection
