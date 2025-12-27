@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Preview Kwitansi")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.customer_invoice_master.index") }}" class="breadcrumb-item">@lang("cms.Beneficiary PG Invoice")</a>
                    <span class="breadcrumb-item active">@lang("cms.Preview Kwitansi")</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">@lang("cms.Preview Kwitansi")</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="collapse show">
            <div class="card-body">
                <form class="" action="{{ route("cms.yukk_co.customer_invoice_master.create_invoice") }}" method="GET">
                    <input type="hidden" name="customer_id" value="{{ $customer_id }}">
                    <input type="hidden" name="partner_id" value="{{ $partner_id }}">
                    <input type="hidden" name="start_time" value="{{ $start_time->format("d-M-Y H:i:s") }}">
                    <input type="hidden" name="end_time" value="{{ $end_time->format("d-M-Y H:i:s") }}">

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">@lang("cms.Kwitansi Date")</label>
                        <div class="col-sm-4">
                            <input type="text" id="invoice_date" name="invoice_date" class="form-control" placeholder="" value="{{ isset($invoice_date) ? $invoice_date->format("d-M-Y") : \Carbon\Carbon::now()->format("d-M-Y") }}">
                        </div>

                        <label class="col-sm-2 col-form-label">@lang("cms.Kwitansi Number")</label>
                        <div class="col-sm-4">
                            <input type="text" id="invoice_number" name="invoice_number" class="form-control" placeholder="YKI/PG/KWITANSI/{{ Carbon\Carbon::now()->format("y") }}/{{ Carbon\Carbon::now()->format("m") }}/XXXX" value="{{ isset($invoice_number) ? $invoice_number : "" }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-primary form-control" type="submit">@lang("cms.Submit")</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>

        <div class="card-footer">

        </div>
    </div>


    @if (isset($preview_data) && $preview_data)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang("cms.Detail")</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    @if (isset($customer))
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="customer_id">@lang("cms.Beneficiary")</label>
                                <input class="form-control" id="customer_id" value="{{ @$customer->name }}" readonly="readonly"/>
                            </div>
                        </div>
                    @endif

                    @if (isset($partner))
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partner_id">@lang("cms.Partner")</label>
                                <input class="form-control" id="partner_id" value="{{ @$partner->name }}" readonly="readonly"/>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="customer_id">@lang("cms.Kwitansi Date")</label>
                            <input class="form-control" id="customer_id" value="{{ @$invoice_date->format("d-M-Y") }}" readonly="readonly"/>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="customer_id">@lang("cms.Kwitansi Number")</label>
                            <input class="form-control" id="customer_id" value="{{ @$invoice_number }}" readonly="readonly"/>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y H:i:s") }} - {{ $end_time->format("d-M-Y H:i:s") }}" readonly="readonly">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang("cms.Summary")</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <form action="{{ route("cms.yukk_co.customer_invoice_master.preview_download_transaction_pg_list") }}" method="post">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer_id }}">
                            <input type="hidden" name="partner_id" value="{{ $partner_id }}">
                            <input type="hidden" name="start_time" value="{{ $start_time->format("d-M-Y H:i:s") }}">
                            <input type="hidden" name="end_time" value="{{ $end_time->format("d-M-Y H:i:s") }}">
                            <button type="submit" class="btn btn-success"><i class="icon-file-download"></i> @lang("cms.Download Transaction List") ({{ @\App\Helpers\H::formatNumber($preview_data->count) }})</button>
                        </form>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-6">
                        <div class="card bg-success text-center p-3">
                            <div>
                                <h1 class="mb-3 mt-1">
                                    @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($invoice_data->total_amount) }}
                                </h1>
                            </div>

                            <blockquote class="blockquote mb-0">
                                <h3>@lang("cms.Sum Total Invoice")</h3>
                                <footer class="blockquote-footer">
                                    <span>@lang("cms.sum_total_invoice_help_text")</span>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card bg-warning text-center p-3">
                            <div>
                                <h1 class="mb-3 mt-1">
                                    @lang("cms.IDR") {{ \App\Helpers\H::formatNumber(($preview_data->sum_mdr_internal_fixed + $preview_data->sum_mdr_internal_percentage) + ($preview_data->sum_fee_partner_fixed + $preview_data->sum_fee_partner_percentage)) }}
                                </h1>
                            </div>

                            <blockquote class="blockquote mb-0">
                                <h3>@lang("cms.Sum Total Reimbursement")</h3>
                                <footer class="blockquote-footer">
                                    <span>@lang("cms.sum_total_reimbursement_help_text")</span>
                                </footer>
                            </blockquote>
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="card bg-primary text-center p-3">
                            <div>
                                <h1 class="mb-3 mt-1">
                                    @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($preview_data->sum_mdr_external_fixed + $preview_data->sum_mdr_external_percentage) }}
                                </h1>
                            </div>

                            <blockquote class="blockquote mb-0">
                                <h3>@lang("cms.Sum MDR External")</h3>
                                <footer class="blockquote-footer">
                                    <span>@lang("cms.sum_mdr_external_help_text")</span>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card bg-danger text-center p-3">
                            <div>
                                <h1 class="mb-3 mt-1">
                                    @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($preview_data->sum_mdr_internal_fixed + $preview_data->sum_mdr_internal_percentage) }}
                                </h1>
                            </div>

                            <blockquote class="blockquote mb-0">
                                <h3>@lang("cms.Sum MDR Internal")</h3>
                                <footer class="blockquote-footer">
                                    <span>@lang("cms.sum_mdr_internal_help_text")</span>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card bg-secondary text-center p-3">
                            <div>
                                <h1 class="mb-3 mt-1">
                                    @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($preview_data->sum_fee_partner_fixed + $preview_data->sum_fee_partner_percentage) }}
                                </h1>
                            </div>

                            <blockquote class="blockquote mb-0">
                                <h3>@lang("cms.Sum Fee Partner")</h3>
                                <footer class="blockquote-footer">
                                    <span>@lang("cms.sum_fee_partner_help_text")</span>
                                </footer>
                            </blockquote>
                        </div>
                    </div>

                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">@lang("cms.Kwitansi")</h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show">
                        <div class="card-body">
                            <div class="row">
                                {{--<div class="col-md-6">
                                    <iframe src="{{ route("cms.yukk_co.customer_invoice_master.invoice_pdf_preview", ["data" => encrypt($invoice_data)]) }}" style="width: 100%; height: 900px;"></iframe>
                                    <div class="input-group input-group-append position-static">
                                        <a href="{{ route("cms.yukk_co.customer_invoice_master.invoice_pdf_preview", ["data" => encrypt($invoice_data)]) }}" target="_blank" class="btn btn-primary form-control"><i class="icon-new-tab"></i> @lang("cms.Open in New Tab")</a>
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                            <a href="{{ route("cms.yukk_co.customer_invoice_master.invoice_pdf_preview", ["data" => encrypt($invoice_data), "export_to_pdf" => 1]) }}" class="dropdown-item"><i class="icon-file-download"></i> @lang("cms.Download")</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <iframe src="{{ route("cms.yukk_co.customer_invoice_master.reimbursement_pdf_preview", ["data" => encrypt($reimbursement_data)]) }}" style="width: 100%; height: 900px;"></iframe>
                                    <div class="input-group input-group-append position-static">
                                        <a href="{{ route("cms.yukk_co.customer_invoice_master.reimbursement_pdf_preview", ["data" => encrypt($reimbursement_data)]) }}" target="_blank" class="btn btn-primary form-control"><i class="icon-new-tab"></i> @lang("cms.Open in New Tab")</a>
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                            <a href="{{ route("cms.yukk_co.customer_invoice_master.reimbursement_pdf_preview", ["data" => encrypt($reimbursement_data), "export_to_pdf" => 1]) }}" class="dropdown-item"><i class="icon-file-download"></i> @lang("cms.Download")</a>
                                        </div>
                                    </div>
                                </div>--}}

                                <div class="col-12">
                                    <iframe src="{{ route("cms.yukk_co.customer_invoice_master.kwitansi_pdf_preview", ["data" => encrypt($kwitansi_data)]) }}" style="width: 100%; height: 900px;"></iframe>
                                    <div class="input-group input-group-append position-static">
                                        <a href="{{ route("cms.yukk_co.customer_invoice_master.kwitansi_pdf_preview", ["data" => encrypt($kwitansi_data)]) }}" target="_blank" class="btn btn-primary form-control"><i class="icon-new-tab"></i> @lang("cms.Open in New Tab")</a>
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                            <a href="{{ route("cms.yukk_co.customer_invoice_master.kwitansi_pdf_preview", ["data" => encrypt($kwitansi_data), "export_to_pdf" => 1]) }}" class="dropdown-item"><i class="icon-file-download"></i> @lang("cms.Download")</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang("cms.Provider List")</h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>@lang("cms.Provider")</th>
                                <th>@lang("cms.Count Transaction")</th>
                                <th>@lang("cms.Total Fee Provider")</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php($sum = 0)
                            @php($count = 0)
                            @foreach($provider_list as $index => $provider)
                                <tr>
                                    @php($sum += @$provider->sum_mdr_internal_fixed + $provider->sum_mdr_internal_percentage)
                                    @php($count += @$provider->count)
                                    <td>{{ @$provider->provider->name }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($provider->count, 0) }}</td>
                                    <td class="text-right">@lang("cms.Rp"). {{ \App\Helpers\H::formatNumber(@$provider->sum_mdr_internal_fixed + @$provider->sum_mdr_internal_percentage, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>@lang("cms.Total Fee Provider")</th>
                                <th class="text-right">{{ \App\Helpers\H::formatNumber($count) }}</th>
                                <th class="text-right">@lang("cms.Rp"). {{ \App\Helpers\H::formatNumber($sum, 2) }}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl(["PG_INVOICE.CREATE_INVOICE"]))
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route("cms.yukk_co.customer_invoice_master.create_customer_invoice") }}" method="post">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer_id }}">
                        <input type="hidden" name="partner_id" value="{{ $partner_id }}">
                        <input type="hidden" name="start_time" value="{{ $start_time->format("Y-m-d H:i:s") }}">
                        <input type="hidden" name="end_time" value="{{ $end_time->format("Y-m-d H:i:s") }}">
                        <input type="hidden" name="invoice_date" value="{{ $invoice_date->format("Y-m-d") }}">
                        <input type="hidden" name="invoice_number" value="{{ $invoice_number }}">
                        <button type="submit" id="create-customer-invoice" class="btn btn-primary btn-block">@lang("cms.Create Kwitansi")</button>
                    </form>
                </div>
            </div>
        @endif

    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();
            $(".select2").select2();

            $("#invoice_date").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
                singleDatePicker: true,
            });

            $("#create-customer-invoice").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection