@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Settlement PG")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.settlement_pg_master.list") }}" class="breadcrumb-item">@lang("cms.Settlement PG List")</a>
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
            <h5 class="card-title">@lang("cms.Settlement PG Detail")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->customer->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Beneficiary Description")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_pg_master->customer->description }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_pg_master->customer->email }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->customer->bank->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Branch")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->customer->branch_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->customer->account_number }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->customer->account_name }}">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Name")</label>
                        <div class="col-lg-4">
                            @if ($settlement_pg_master->partner)
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->partner->name }}">
                            @else
                                <input type="text" class="form-control" readonly="" value="-">
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Partner Description")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_pg_master->partner->description }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Email List")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$settlement_pg_master->partner->email_list }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->partner->bank->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Branch")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->partner->account_branch_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->partner->account_number }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->partner->account_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.BCA / NON BCA")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->partner->bank_type }}">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Ref Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->ref_code }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($settlement_pg_master->created_at) }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Settlement Date")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->settlement_date }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-6 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->status }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-6 col-form-label">@lang("cms.Status Bank Source of Fund to Settlement")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->status_transfer_source_of_fund_to_settlement }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-6 col-form-label">@lang("cms.Status Settlement to Parking")</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_pg_master->status_transfer_settlement_to_parking }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h4>@lang("cms.Settlement Bank Source of Fund to Settlement")</h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <td>@lang("cms.Payment Channel")</td>
                            <td>@lang("cms.Provider")</td>
                            <td>@lang("cms.Bank Source of Fund (Account Number)")</td>
                            <td>@lang("cms.Grand Total")</td>
                            <td>@lang("cms.MDR Internal")</td>
                            <td>@lang("cms.Total Transfer")</td>
                            <td>@lang("cms.Start Time")</td>
                            <td>@lang("cms.End Time")</td>
                            <td>@lang("cms.Transfer Status")</td>
                            <td>@lang("cms.Updated Time")</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(@$settlement_pg_master->settlement_pg_source_of_fund_list as $settlement_pg_source_of_fund)
                        <tr>
                            <td>{{ @$settlement_pg_source_of_fund->payment_channel->name }}</td>
                            <td>{{ @$settlement_pg_source_of_fund->provider->name }}</td>
                            <td>{{ @$settlement_pg_source_of_fund->bank_source_of_fund_account_number }}</td>
                            <td>{{ \App\Helpers\H::formatNumber(@$settlement_pg_source_of_fund->total_grand_total, 2) }}</td>
                            <td>{{ \App\Helpers\H::formatNumber(@$settlement_pg_source_of_fund->total_mdr_internal, 2) }}</td>
                            <td>{{ \App\Helpers\H::formatNumber(@$settlement_pg_source_of_fund->total_transfer, 2) }}</td>
                            <td>{{ @$settlement_pg_source_of_fund->start_time_transaction }}</td>
                            <td>{{ @$settlement_pg_source_of_fund->end_time_transaction }}</td>
                            <td>{{ @$settlement_pg_source_of_fund->status }}</td>
                            <td>{{ @$settlement_pg_source_of_fund->updated_at }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h4>@lang("cms.Transaction List")</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>@lang("cms.Merchant Branch Name")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Transaction Time")</th>
                            <th>@lang("cms.Payment Channel")</th>
                            <th>@lang("cms.Provider")</th>
                            <th>@lang("cms.Grand Total")</th>
                            <th>@lang("cms.Total MDR Internal")</th>
                            <th>@lang("cms.Total MDR External")</th>
                            <th>@lang("cms.Total Partner Fee")</th>
                            <th>@lang("cms.YUKK Portion")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($settlement_pg_master->settlement_pg_detail_list as $index => $settlement_pg_detail)
                            <tr>
                                <td>{{ @$settlement_pg_detail->transaction->merchant_branch->name }}</td>
                                <td>{{ @$settlement_pg_detail->transaction->code }}</td>
                                <td>{{ \App\Helpers\H::formatDateTime(@$settlement_pg_detail->transaction->request_at) }}</td>
                                <td>{{ @$settlement_pg_detail->transaction->payment_channel->name }}</td>
                                <td>{{ @$settlement_pg_detail->transaction->provider->name }}</td>
                                <td class="text-right">{{ \App\Helpers\H::formatNumber(@$settlement_pg_detail->transaction->grand_total, 2) }}</td>
                                <td class="text-right">{{ \App\Helpers\H::formatNumber(@($settlement_pg_detail->transaction->mdr_internal_fixed + $settlement_pg_detail->transaction->mdr_internal_percentage), 2) }}</td>
                                <td class="text-right">{{ \App\Helpers\H::formatNumber(@($settlement_pg_detail->transaction->mdr_external_fixed + $settlement_pg_detail->transaction->mdr_external_percentage), 2) }}</td>
                                <td class="text-right">{{ \App\Helpers\H::formatNumber(@($settlement_pg_detail->transaction->fee_partner_fixed + $settlement_pg_detail->transaction->fee_partner_percentage), 2) }}</td>
                                <td class="text-right">{{ \App\Helpers\H::formatNumber(@(($settlement_pg_detail->transaction->mdr_external_fixed + $settlement_pg_detail->transaction->mdr_external_percentage) - ($settlement_pg_detail->transaction->mdr_internal_fixed + $settlement_pg_detail->transaction->mdr_internal_percentage)), 2) }}</td>
                                <td class="text-right">{{ \App\Helpers\H::formatNumber(@($settlement_pg_detail->transaction->grand_total - ($settlement_pg_detail->transaction->mdr_external_fixed + $settlement_pg_detail->transaction->mdr_external_percentage) - ($settlement_pg_detail->transaction->fee_partner_fixed + $settlement_pg_detail->transaction->fee_partner_percentage)), 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    {{--<div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Grand Total")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($settlement_pg_master->total_grand_total, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>--}}
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Grand Total")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($settlement_pg_master->total_grand_total, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total MDR Internal")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($settlement_pg_master->total_mdr_internal, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total MDR External")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($settlement_pg_master->total_mdr_external, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total YUKK Portion")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber(($settlement_pg_master->total_mdr_external - $settlement_pg_master->total_mdr_internal), 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Partner Portion")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($settlement_pg_master->total_fee_partner, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Merchant Portion")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($settlement_pg_master->total_merchant_portion, 2) }}</span></h3>
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