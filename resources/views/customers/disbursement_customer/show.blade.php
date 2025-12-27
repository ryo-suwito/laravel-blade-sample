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
                    <a href="{{ route("cms.customer.disbursement_customer.list") }}" class="breadcrumb-item">@lang("cms.Disbursement")</a>
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
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->customer_account_name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->bank->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$disbursement_customer->bank->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                        <div class="col-lg-4">
                            <textarea type="text" class="form-control" readonly="">{{ @$disbursement_customer->customer->email }}</textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($disbursement_customer->created_at) }}">
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" style="margin-top: 32px;">
                <div class="col-lg-12">
                    <h4><u>@lang("cms.Table Settlement QRIS")</u></h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>@lang("cms.No")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php($running_number = 1)
                        @foreach(collect($disbursement_customer->disbursement_customer_detail_list)->where("type", "QRIS") as $index => $disbursement_customer_detail)
                            <tr>
                                <td>{{ @$running_number++ }}</td>
                                <td>{{ @$disbursement_customer_detail->settlement_master->ref_code }}</td>
                                <td>{{ @\App\Helpers\H::formatDateTimeWithoutTime($disbursement_customer_detail->settlement_master->settlement_date) }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_detail->settlement_master->total_merchant_portion, 2) }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" data-settlement-master-id="{{ @$disbursement_customer_detail->settlement_master->id }}" class="dropdown-item download-settlement-master-excel"><i class="icon-file-download"></i> @lang("cms.Download Excel")</a>
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

            <div class="row" style="margin-top: 32px;">
                <div class="col-lg-12">
                    <h4><u>@lang("cms.Table Settlement PG")</u></h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>@lang("cms.No")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php($running_number = 1)
                        @foreach(collect($disbursement_customer->disbursement_customer_detail_list)->where("type", "PG") as $index => $disbursement_customer_detail)
                            <tr>
                                <td>{{ @$running_number++ }}</td>
                                <td>{{ @$disbursement_customer_detail->settlement_pg_master->ref_code }}</td>
                                <td>{{ @\App\Helpers\H::formatDateTimeWithoutTime($disbursement_customer_detail->settlement_pg_master->settlement_date) }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_detail->settlement_pg_master->total_merchant_portion, 2) }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" data-settlement-pg-master-id="{{ @$disbursement_customer_detail->settlement_pg_master->id }}" class="dropdown-item download-settlement-pg-master-excel"><i class="icon-file-download"></i> @lang("cms.Download Excel")</a>
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

            <div class="row" style="margin-top: 32px;">
                <div class="col-lg-12">
                    <h4><u>@lang("cms.Table Dispute")</u></h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>@lang("cms.No")</th>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Date")</th>
                            <th>@lang("cms.Merchant Portion")</th>
                            {{--<th>@lang("cms.Actions")</th>--}}
                        </tr>
                        </thead>

                        <tbody>
                        @php($running_number = 1)
                        @foreach(collect($disbursement_customer->disbursement_customer_detail_list)->where("type", "DEBT") as $index => $disbursement_customer_detail)
                            <tr>
                                <td>{{ @$running_number++ }}</td>
                                <td>{{ @$disbursement_customer_detail->settlement_debt_master->ref_code }}</td>
                                <td>{{ @\App\Helpers\H::formatDateTimeWithoutTime($disbursement_customer_detail->settlement_debt_master->settlement_date) }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($disbursement_customer_detail->settlement_debt_master->total_merchant_portion, 2) }}</td>
                                {{--<td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" data-settlement-debt-master-id="{{ @$disbursement_customer_detail->settlement_debt_master->id }}" class="dropdown-item download-settlement-debt-master-excel"><i class="icon-file-download"></i> @lang("cms.Download Excel")</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row" style="margin-top: 32px;">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Total Merchant Portion")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber(($disbursement_customer->total_merchant_portion_qris + $disbursement_customer->total_merchant_portion_pg), 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    @if ($disbursement_customer->total_merchant_portion_debt != 0)
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <div class="form-control-plaintext">
                                    <h3>@lang("cms.Total Dispute")</h3>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-control-plaintext text-right">
                                    <h3><span>-{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_merchant_portion_debt, 2) }}</span></h3>
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
                                <h3><span>-{{ @\App\Helpers\H::formatNumber($disbursement_customer->disbursement_fee, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="form-control-plaintext">
                                <h3>@lang("cms.Rounding")</h3>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-control-plaintext text-right">
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->rounding, 2) }}</span></h3>
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
                                <h3><span>{{ @\App\Helpers\H::formatNumber($disbursement_customer->total_disbursement, 2) }}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form class="hide" id="form-download-settlement-excel" method="post" action="{{ route("cms.customer.settlement_master.download_excel") }}">
        @csrf
        <input type="hidden" id="settlement-master-id" value="" name="settlement_master_id"/>
    </form>

    <form class="hide" id="form-download-settlement-pg-excel" method="post" action="{{ route("cms.customer.settlement_pg_master.download_excel") }}">
        @csrf
        <input type="hidden" id="settlement-pg-master-id" value="" name="settlement_pg_master_id"/>
    </form>
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

            $(".download-settlement-master-excel").click(function (e) {
                e.preventDefault();
                $("#settlement-master-id").val($(this).data("settlement-master-id"));
                $("#form-download-settlement-excel").submit();
            });

            $(".download-settlement-pg-master-excel").click(function (e) {
                e.preventDefault();
                $("#settlement-pg-master-id").val($(this).data("settlement-pg-master-id"));
                $("#form-download-settlement-pg-excel").submit();
            });
        });
    </script>
@endsection
