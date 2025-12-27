@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Create Partner Payout")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.partner_payout_master.index") }}" class="breadcrumb-item">@lang("cms.Partner Payout List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Create Partner Payout")</h5>
        </div>

        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">@lang("cms.Partner")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" value="{{ $partner->name }}" readonly="readonly"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label">@lang("cms.Total Fee Partner")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control text-right" value="@lang("cms.IDR") {{ \App\Helpers\H::formatNumber($sum_fee_partner, 2) }}" readonly="readonly"/>
                </div>

                <label class="col-md-2 col-form-label">@lang("cms.Total Invoice")</label>
                <div class="col-md-4">
                    <input type="text" class="form-control text-right" value="{{ \App\Helpers\H::formatNumber($count_all_invoice, 0) }}" readonly="readonly"/>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>@lang("cms.Beneficiary")</th>
                    <th>@lang("cms.Kwitansi Number")</th>
                    <th>@lang("cms.Kwitansi Date")</th>
                    <th>@lang("cms.Total Fee Partner")</th>
                    <th>@lang("cms.Status")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                @foreach($customer_invoice_master_list as $index => $customer_invoice_master)
                    <tr>
                        <td>{{ @$customer_invoice_master->customer->name }}</td>
                        <td>{{ @$customer_invoice_master->invoice_number }}</td>
                        <td>{{ @\App\Helpers\H::formatDateTime($customer_invoice_master->invoice_date, "d-M-Y") }}</td>
                        <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber($customer_invoice_master->sum_fee_partner_fixed + $customer_invoice_master->sum_fee_partner_percentage, 2) }}</td>
                        <td class="text-center">{{ @$customer_invoice_master->status }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a target="_blank" href="{{ route("cms.yukk_co.customer_invoice_master.item", $customer_invoice_master->id) }}" class="dropdown-item"><i class="icon-new-tab"></i> @lang("cms.Detail")</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-12">
                    <form action="{{ route("cms.yukk_co.partner_payout_master.create_partner_payout_master", $partner->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block" id="btn-create-payout">@lang("cms.Create Partner Payout")</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();

            $("#btn-create-payout").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection