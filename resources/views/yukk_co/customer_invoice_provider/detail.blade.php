@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Fee Provider Detail")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.customer_invoice_provider.index") }}" class="breadcrumb-item">@lang("cms.Search Fee Provider")</a>
                    <span class="breadcrumb-item active">@lang("cms.Fee Provider Detail")</span>
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
            <h5 class="card-title">@lang("cms.Summary")</h5>
        </div>

        <div class="card-body">
            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Provider")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="provider_id" value="{{ $customer_invoice_detail->provider->name }}" readonly>
                </div>


                <label class="col-form-label col-sm-2">@lang("cms.Count Transaction")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control text-right" name="count" value="{{ $customer_invoice_detail->summary->sum_count_transaction }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Date Range Kwitansi Date")</label>
                <div class="col-sm-4">
                    <input type="text" id="provider_id" name="date_range" class="form-control" value="{{ $start_date->format("d-M-Y") }} - {{ $end_date->format("d-M-Y") }}" readonly>
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Total Fee Provider")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control text-right" name="fee_provider" value="{{ \App\Helpers\H::formatNumber($customer_invoice_detail->summary->sum_mdr_internal_fixed + $customer_invoice_detail->summary->sum_mdr_internal_percentage, 2) }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Invoice List")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 text-right">
                    <a href="{{ route("cms.yukk_co.customer_invoice_provider.download_transaction_list", ["provider_id" => $customer_invoice_detail->provider->id, "start_date" => $start_date->format("d-M-Y"), "end_date" => $end_date->format("d-M-Y")]) }}" class="btn btn-success"><i class="icon-file-download"></i> @lang("cms.Download All")</a>
                </div>
            </div>

            <table class="table table-striped table-bordered mt-4">
                <thead>
                <tr>
                    <td>@lang("cms.Beneficiary")</td>
                    <td>@lang("cms.Partner")</td>
                    <td>@lang("cms.Kwitansi Number")</td>
                    <td>@lang("cms.Kwitansi Date")</td>
                    <td>@lang("cms.Count Transaction")</td>
                    <td>@lang("cms.Total Fee Provider")</td>
                    <td>@lang("cms.Actions")</td>
                </tr>
                </thead>

                @if (@$customer_invoice_detail->customer_invoice_master_list !== null)
                    <tbody>
                    @foreach (@$customer_invoice_detail->customer_invoice_master_list as $customer_invoice_master)
                        <tr>
                            <td>{{ @$customer_invoice_master->customer_invoice_master->customer->name }}</td>
                            <td>{{ @$customer_invoice_master->customer_invoice_master->partner->name }}</td>
                            <td>{{ @$customer_invoice_master->customer_invoice_master->invoice_number }}</td>
                            <td>{{ @\App\Helpers\H::formatDateTime($customer_invoice_master->customer_invoice_master->invoice_date, "d-M-Y") }}</td>
                            <td class="text-right">{{ @\App\Helpers\H::formatNumber(@$customer_invoice_master->sum_count_transaction, 0) }}</td>
                            <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber(@$customer_invoice_master->sum_mdr_internal_fixed + @$customer_invoice_master->sum_mdr_internal_percentage, 0) }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route("cms.yukk_co.customer_invoice_provider.download_transaction_list", ["provider_id" => $customer_invoice_detail->provider->id, "start_date" => $start_date->format("d-M-Y"), "end_date" => $end_date->format("d-M-Y"), "customer_invoice_master_id" => $customer_invoice_master->customer_invoice_master_id]) }}" class="dropdown-item"><i class="icon-file-download"></i> @lang("cms.Download")</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();


            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
                timePicker: true,
                timePicker24Hour: true,
            });
        });
    </script>
@endsection