@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Beneficiary Invoice")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.customer_invoice_master.index") }}" class="breadcrumb-item">@lang("cms.Beneficiary Invoice List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Preview Invoice & Reimbursement")</span>
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
            <h5 class="card-title">@lang("cms.System Error")</h5>
        </div>

        <div class="card-body">
            <div class="alert alert-danger alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                <span class="font-weight-semibold">@lang("cms.Transaction Already Invoiced")</span><br>
                {{ @$status_message }}
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>@lang("cms.Transaction")</th>
                            <th>@lang("cms.Kwitansi Number")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($customer_invoice_detail_list as $customer_invoice_detail)
                            <tr>
                                <td>
                                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("TRANSACTIONS_PC_VIEW", "AND"))
                                        <a target="_blank" href="{{ route("cms.yukk_co.transaction_pg.item", $customer_invoice_detail->transaction->id) }}">
                                            {{ $customer_invoice_detail->transaction->code }}
                                        </a>
                                    @else
                                        {{ $customer_invoice_detail->transaction->code }}
                                    @endif
                                </td>
                                <td>
                                    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("PG_INVOICE.VIEW", "AND"))
                                        <a target="_blank" href="{{ route("cms.yukk_co.customer_invoice_master.item", $customer_invoice_detail->customer_invoice_master->id) }}">
                                            {{ $customer_invoice_detail->customer_invoice_master->invoice_number }}
                                        </a>
                                    @else
                                        {{ $customer_invoice_detail->customer_invoice_master->invoice_number }}
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-center">
                                ...and maybe more
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <a href="{{ back()->getTargetUrl() }}" class="btn btn-block btn-primary mt-4"><i class="icon-undo2"></i> @lang("cms.Go Back")</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();
        });
    </script>
@endsection