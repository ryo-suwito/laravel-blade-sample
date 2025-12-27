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
                    <span class="breadcrumb-item active">{{ @$customer_invoice_master->invoice_number }}</span>
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

    <div class="row">
        <div class="col-sm-12 text-right">
            {{--<button class="btn btn-success">@lang("cms.Download CSV")</button>--}}
            @if ($customer_invoice_master->status == "DRAFT" && \App\Helpers\AccessControlHelper::checkCurrentAccessControl("PG_INVOICE.DELETE_INVOICE", "AND"))
                <button class="btn btn-danger" id="delete-customer-invoice">@lang("cms.Delete")</button>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Detail")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="customer_id">@lang("cms.Beneficiary")</label>
                        <input class="form-control" id="customer_id" value="{{ @$customer_invoice_master->customer->name }}" readonly="readonly"/>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="partner_id">@lang("cms.Partner")</label>
                        <input class="form-control" id="partner_id" value="{{ @$customer_invoice_master->partner->name }}" readonly="readonly"/>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="customer_email">@lang("cms.Customer Email")</label>
                        <textarea class="form-control" id="customer_email" readonly="readonly">{{ @$customer_invoice_master->customer_email }}</textarea>
                        @if (@$customer_invoice_master->status_email == "PENDING")
                            <button class="btn btn-block btn-primary" id="btn-change-email" data-toggle="modal" data-target="#modal-change-email">@lang("cms.Change Email")</button>
                        @endif
                    </div>
                </div>

                <div class="col-md-6 d-none d-md-block">

                </div>

                <div class="col-12">
                    <hr/>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="customer_id">@lang("cms.Kwitansi Date")</label>
                        <input class="form-control" id="customer_id" value="{{ @\Carbon\Carbon::parse($customer_invoice_master->invoice_date)->format("d-M-Y") }}" readonly="readonly"/>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="customer_id">@lang("cms.Kwitansi Number")</label>
                        <input class="form-control" id="customer_id" value="{{ @$customer_invoice_master->invoice_number }}" readonly="readonly"/>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>@lang("cms.Date Range")</label>
                        <input type="text" id="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ \Carbon\Carbon::parse($customer_invoice_master->start_paid_at)->format("d-M-Y H:i:s") }} - {{ Carbon\Carbon::parse($customer_invoice_master->end_paid_at)->format("d-M-Y H:i:s") }}" readonly="readonly">
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>@lang("cms.Status")</label>
                        <input type="text" class="form-control" value="{{ $customer_invoice_master->status }}" readonly="readonly">
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>@lang("cms.Status Email")</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $customer_invoice_master->status_email }}" readonly="readonly">
                            @if (@$customer_invoice_master->status == "POSTED" && @$customer_invoice_master->status_email != "PENDING")
                                <span class="input-group-append">
                                    <form action="{{ route("cms.yukk_co.customer_invoice_master.revert_status_email") }}" method="post">
                                        @csrf
                                        <input type="hidden" name="customer_invoice_master_id" value="{{ @$customer_invoice_master->id }}">
                                        <button class="btn btn-danger need-confirmation" type="submit"><i class="icon-reload-alt"></i> @lang("cms.Revert")</button>
                                    </form>
                                </span>
                            @elseif (@$customer_invoice_master->status == "POSTED")
                                <span class="input-group-append">
                                    <form action="{{ route("cms.yukk_co.customer_invoice_master.trigger_send_customer_email") }}" method="post">
                                        @csrf
                                        <input type="hidden" name="customer_invoice_master_id" value="{{ @$customer_invoice_master->id }}">
                                        <button class="btn btn-danger need-confirmation" type="submit"><i class="icon-mail5"></i> @lang("cms.Send Email")</button>
                                    </form>
                                </span>
                            @endif
                        </div>
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
                    <form action="{{ route("cms.yukk_co.customer_invoice_master.download_transaction_pg_list") }}" method="post">
                        @csrf
                        <input type="hidden" name="customer_invoice_master_id" value="{{ @$customer_invoice_master->id }}">
                        <button type="submit" class="btn btn-success"><i class="icon-file-download"></i> @lang("cms.Download Transaction List") ({{ @\App\Helpers\H::formatNumber($customer_invoice_master->count_transaction) }})</button>
                    </form>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-6">
                    <div class="card bg-success text-center p-3">
                        <div>
                            <h1 class="mb-3 mt-1">
                                @lang("cms.IDR") {{ \App\Helpers\H::formatNumber(($customer_invoice_master->sum_mdr_external_fixed + $customer_invoice_master->sum_mdr_external_percentage) - ($customer_invoice_master->sum_mdr_internal_fixed + $customer_invoice_master->sum_mdr_internal_percentage)) }}
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
                                @lang("cms.IDR") {{ \App\Helpers\H::formatNumber(($customer_invoice_master->sum_mdr_internal_fixed + $customer_invoice_master->sum_mdr_internal_percentage) + ($customer_invoice_master->sum_fee_partner_fixed + $customer_invoice_master->sum_fee_partner_percentage)) }}
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
                                @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($customer_invoice_master->sum_mdr_external_fixed + $customer_invoice_master->sum_mdr_external_percentage) }}
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
                                @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($customer_invoice_master->sum_mdr_internal_fixed + $customer_invoice_master->sum_mdr_internal_percentage) }}
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
                                @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($customer_invoice_master->sum_fee_partner_fixed + $customer_invoice_master->sum_fee_partner_percentage) }}
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
                            <div class="col-12">
                                <iframe src="{{ route("cms.yukk_co.customer_invoice_master.kwitansi_pdf", ["data" => encrypt($kwitansi_data)]) }}" style="width: 100%; height: 900px;"></iframe>
                                <div class="input-group input-group-append position-static">
                                    <a href="{{ route("cms.yukk_co.customer_invoice_master.kwitansi_pdf", ["data" => encrypt($kwitansi_data)]) }}" target="_blank" class="btn btn-primary form-control"><i class="icon-new-tab"></i> @lang("cms.Open in New Tab")</a>
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                        <a href="{{ route("cms.yukk_co.customer_invoice_master.kwitansi_pdf", ["data" => encrypt($kwitansi_data), "export_to_pdf" => 1]) }}" class="dropdown-item"><i class="icon-file-download"></i> @lang("cms.Download")</a>
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
                        @foreach($customer_invoice_master->customer_invoice_providers as $index => $provider)
                            <tr>
                                @php($sum += @$provider->sum_mdr_internal_fixed + $provider->sum_mdr_internal_percentage)
                                @php($count += @$provider->count)
                                <td>{{ @$provider->provider->name }}</td>
                                <td class="text-right">{{ @\App\Helpers\H::formatNumber($provider->count_transaction, 0) }}</td>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.Journal Simulation")</h5>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>@lang("cms.COA")</th>
                            <th>@lang("cms.COA Name")</th>
                            <th>@lang("cms.Description")</th>
                            <th>@lang("cms.Debit")</th>
                            <th>@lang("cms.Credit")</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php($sum_debit = 0)
                        @php($sum_credit = 0)
                        <tr>
                            <td>1114 {{ $customer_invoice_master->customer_id }}</td>
                            <td>Piutang Beneficiary {{ $customer_invoice_master->customer_id }}</td>
                            <td>Transaksi yukkpg {{ $customer_invoice_master->customer->name }}_{{ $customer_invoice_master->start_paid_at }} - {{ $customer_invoice_master->end_paid_at }}</td>
                            @php($amount = ($customer_invoice_master->sum_mdr_external_fixed + $customer_invoice_master->sum_mdr_external_percentage) + ($customer_invoice_master->sum_fee_partner_fixed + $customer_invoice_master->sum_fee_partner_percentage))
                            @php($sum_debit += $amount)
                            <td class="text-right">{{ \App\Helpers\H::formatNumber($amount, 2) }}</td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            {{--<td>2501 {{ $customer_invoice_master->partner_id }}</td>--}}
                            <td>2501 XXXX</td>
                            <td>Hutang Partner {{ $customer_invoice_master->partner->name }}</td>
                            <td>Transaksi yukkpg {{ $customer_invoice_master->customer->name }}_{{ $customer_invoice_master->start_paid_at }} - {{ $customer_invoice_master->end_paid_at }}</td>
                            <td class="text-right"></td>
                            @php($amount = ($customer_invoice_master->sum_fee_partner_fixed + $customer_invoice_master->sum_fee_partner_percentage))
                            @php($sum_credit += $amount)
                            <td class="text-right">{{ \App\Helpers\H::formatNumber($amount, 2) }}</td>
                        </tr>
                        @foreach ($customer_invoice_master->customer_invoice_provider_payment_channels as $customer_invoice_provider_payment_channel)
                            <tr>
                                <td>2501 XX</td>
                                <td>Hutang Usaha - {{ $customer_invoice_provider_payment_channel->provider->name }}_{{ $customer_invoice_provider_payment_channel->payment_channel->name }}</td>
                                <td>Transaksi yukkpg {{ $customer_invoice_master->customer->name }}_{{ $customer_invoice_master->start_paid_at }} - {{ $customer_invoice_master->end_paid_at }}</td>
                                <td class="text-right"></td>
                                @php($amount = $customer_invoice_provider_payment_channel->sum_mdr_internal_fixed + $customer_invoice_provider_payment_channel->sum_mdr_internal_percentage)
                                @php($sum_credit += $amount)
                                <td class="text-right">{{ \App\Helpers\H::formatNumber($amount, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>4101 01800</td>
                            <td>Pendapatan Fee - Virtual Account (VA)</td>
                            <td>Transaksi yukkpg {{ $customer_invoice_master->customer->name }}_{{ $customer_invoice_master->start_paid_at }} - {{ $customer_invoice_master->end_paid_at }}</td>
                            <td class="text-right"></td>
                            @php($amount = $dpp)
                            @php($sum_credit += $amount)
                            <td class="text-right">{{ \App\Helpers\H::formatNumber($amount, 2)}}</td>
                        </tr>
                        <tr>
                            <td>2601 00100</td>
                            <td>PPn Keluaran</td>
                            <td>Transaksi yukkpg {{ $customer_invoice_master->customer->name }}_{{ $customer_invoice_master->start_paid_at }} - {{ $customer_invoice_master->end_paid_at }}</td>
                            <td class="text-right"></td>
                            @php($amount = $ppn)
                            @php($sum_credit += $amount)
                            <td class="text-right">{{ \App\Helpers\H::formatNumber($amount, 2)}}</td>
                        </tr>
                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="3"><b>@lang("cms.Total")</b></td>
                            <td class="text-right"><b>{{ \App\Helpers\H::formatNumber($sum_debit, 2) }}</b></td>
                            <td class="text-right"><b>{{ \App\Helpers\H::formatNumber($sum_credit, 2) }}</b></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("PG_INVOICE.POST_INVOICE"))
        <div class="row">
            <div class="col-sm-12">
                @if (@$customer_invoice_master->status == "DRAFT")
                    <form action="{{ route("cms.yukk_co.customer_invoice_master.post_invoice") }}" method="post">
                        @csrf
                        <input type="hidden" name="customer_invoice_master_id" value="{{ @$customer_invoice_master->id }}"/>
                        <button class="btn btn-block btn-primary" type="submit" id="btn-post-invoice">@lang("cms.Post Invoice")</button>
                    </form>
                @elseif (@$customer_invoice_master->status == "POSTED")
                    <form action="{{ route("cms.yukk_co.customer_invoice_master.pay_invoice") }}" method="post">
                        @csrf
                        <input type="hidden" name="customer_invoice_master_id" value="{{ @$customer_invoice_master->id }}"/>
                        <button class="btn btn-block btn-success" type="submit" id="btn-pay-invoice">@lang("cms.Mark as Paid")</button>
                    </form>
                @endif
            </div>
        </div>
    @endif


    @if ($customer_invoice_master->status == "DRAFT" && \App\Helpers\AccessControlHelper::checkCurrentAccessControl("PG_INVOICE.DELETE_INVOICE", "AND"))
        <form class="d-none" method="post" action="{{ route("cms.yukk_co.customer_invoice_master.delete") }}" id="form-delete">
            @csrf
            <input name="customer_invoice_master_id" value="{{ $customer_invoice_master->id }}"/>
        </form>
    @endif

    @if (@$customer_invoice_master->status_email == "PENDING")
        <div id="modal-change-email" class="modal fade" tabindex="-1" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <form action="{{ route("cms.yukk_co.customer_invoice_master.change_customer_email") }}" method="post">
                    @csrf
                    <input type="hidden" name="customer_invoice_master_id" value="{{ @$customer_invoice_master->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang("cms.Change Email")</h5>
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <label class="col-form-label col-sm-2" for="customer_email">@lang("cms.Email")</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="customer_email" id="customer_email" placeholder="@lang("cms.New Customer Email")"/>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">@lang("cms.Close")</button>
                            <button type="submit" class="btn btn-primary">@lang("cms.Submit")</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();

            $("#btn-post-invoice,#btn-pay-invoice").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });

            @if ($customer_invoice_master->status == "DRAFT" && \App\Helpers\AccessControlHelper::checkCurrentAccessControl("PG_INVOICE.DELETE_INVOICE", "AND"))
            $("#delete-customer-invoice").click(function(e) {
                e.preventDefault();
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {
                    $("#form-delete").submit();
                }
            });
            @endif

            $(".need-confirmation").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection