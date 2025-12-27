@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Settlement Switching")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Settlement Switching")</span>
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
            <h5 class="card-title">@lang("cms.Settlement Switching")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.settlement_master.list_switching") }}" method="get">
                <div class="row">

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Settlement Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y") }} - {{ $end_time->format("d-M-Y") }}">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.Hak dan Kewajiban")</h5>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="text-center font-weight-bold">
                        <tr>
                            <td colspan="4">@lang("cms.Acquirer / Hak")</td>
                            <td colspan="4">@lang("cms.Issuer / Kewajiban")</td>
                        </tr>
                        <tr>
                            <td colspan="2">@lang("cms.QR")</td>
                            <td colspan="2">@lang("cms.Refund")</td>
                            <td colspan="2">@lang("cms.QR")</td>
                            <td colspan="2">@lang("cms.Refund")</td>
                        </tr>
                        <tr>
                            <td>@lang("cms.Nominal")</td>
                            <td>@lang("cms.Fee")</td>
                            <td>@lang("cms.Nominal")</td>
                            <td>@lang("cms.Fee")</td>
                            <td>@lang("cms.Nominal")</td>
                            <td>@lang("cms.Fee")</td>
                            <td>@lang("cms.Nominal")</td>
                            <td>@lang("cms.Fee")</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td>{{ \App\Helpers\H::formatNumber($total_acquirer_qr_nominal, 2)}}</td>
                            <td>{{ \App\Helpers\H::formatNumber($total_acquirer_qr_fee, 2)}}</td>
                            <td>{{ \App\Helpers\H::formatNumber($total_acquirer_refund_nominal, 2)}}</td>
                            <td>{{ \App\Helpers\H::formatNumber($total_acquirer_refund_fee, 2)}}</td>
                            <td>{{ \App\Helpers\H::formatNumber($total_issuer_qr_nominal, 2)}}</td>
                            <td>{{ \App\Helpers\H::formatNumber($total_issuer_qr_fee, 2)}}</td>
                            <td>{{ \App\Helpers\H::formatNumber($total_issuer_refund_nominal, 2)}}</td>
                            <td>{{ \App\Helpers\H::formatNumber($total_issuer_refund_fee, 2)}}</td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2">{{ \App\Helpers\H::formatNumber($total_acquirer_qr_nominal - $total_acquirer_qr_fee, 2)}}</td>
                            <td colspan="2">{{ \App\Helpers\H::formatNumber($total_acquirer_refund_nominal - $total_acquirer_refund_fee, 2)}}</td>
                            <td colspan="2">{{ \App\Helpers\H::formatNumber($total_issuer_qr_nominal - $total_issuer_qr_fee, 2)}}</td>
                            <td colspan="2">{{ \App\Helpers\H::formatNumber($total_issuer_refund_nominal - $total_issuer_refund_fee, 2)}}</td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="4" class="text-success">{{ \App\Helpers\H::formatNumber(($total_acquirer_qr_nominal - $total_acquirer_qr_fee) - ($total_acquirer_refund_nominal - $total_acquirer_refund_fee), 2)}}</td>
                            <td colspan="4" class="text-danger">{{ \App\Helpers\H::formatNumber(($total_issuer_qr_nominal - $total_issuer_qr_fee) - ($total_issuer_refund_nominal - $total_issuer_refund_fee), 2)}}</td>
                        </tr>
                        <tr class="text-center">
                            @php($total_all = (($total_acquirer_qr_nominal - $total_acquirer_qr_fee) - ($total_acquirer_refund_nominal - $total_acquirer_refund_fee)) - (($total_issuer_qr_nominal - $total_issuer_qr_fee) - ($total_issuer_refund_nominal - $total_issuer_refund_fee)))
                            <td colspan="8" class="@if($total_all >= 0) text-success @else text-danger @endif">{{ \App\Helpers\H::formatNumber($total_all, 2)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.Simulasi Money Flow")</h5>
                </div>
            
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table text-nowrap">
                                <tbody>
                                {{-- Rek YUKK CASH --> Rek SETTLEMENT --}}
                                <tr>
                                    <td><span class="text-body text-muted">@lang("cms.Rek YUKK CASH")</span></td>
                                    <td>
                                        <div class="d-flex align-items-center text-center">
                                            <div class="col-sm-12 text-center">
                                                <div class="">→</div>
                                                <div class="font-weight-semibold text-danger">
                                                    @lang("cms.IDR") {{ \App\Helpers\H::formatNumber(@$total_yukk_cash_switching, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-body text-muted">@lang("cms.Rek Settlement")</span></td>
                                </tr>
                                {{-- ./Rek YUKK CASH --> Rek SETTLEMENT --}}

                                {{-- Rek YUKK POINTS --> Rek SETTLEMENT --}}
                                <tr>
                                    <td><span class="text-body text-muted">@lang("cms.Rek YUKK Points")</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="col-sm-12 text-center">
                                                <div class="">→</div>
                                                <div class="font-weight-semibold text-danger">
                                                    @lang("cms.IDR") {{ \App\Helpers\H::formatNumber(@$total_yukk_points_switching, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-body text-muted">@lang("cms.Rek Settlement")</span></td>
                                </tr>
                                {{-- ./Rek YUKK POINTS --> Rek SETTLEMENT --}}

                                @if ($total_all >= 0)
                                    {{-- If HAK > KEWAJIBAN --}}
                                    {{-- Berarti dari JALIN Transfer Selisih-nya ke Rek Parkir JALIN --}}
                                    <tr>
                                        <td><span class="text-body text-muted">@lang("cms.SWITCHING")</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="col-sm-12 text-center">
                                                    <div class="">→</div>
                                                    <div class="font-weight-semibold text-muted">
                                                        @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($total_all, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-body text-muted">@lang("cms.Rek Parkir SWITCHING")</span></td>
                                        {{--<td><span class="badge badge-secondary" data-popup="tooltip" data-placement="left" data-original-title="@lang("cms.Di luar System")"><i class="icon-watch2"></i></span></td>--}}
                                    </tr>

                                    {{-- Lalu dari Rek Parkir JALIN kirim ke Rek Settlement --}}
                                    <tr>
                                        <td><span class="text-body text-muted">@lang("cms.Rek Parkir SWITCHING")</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="col-sm-12 text-center">
                                                    <div class="">→</div>
                                                    <div class="font-weight-semibold text-danger">
                                                        @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($total_all, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-body text-muted">@lang("cms.Rek Settlement")</span></td>
                                        {{--<td><span class="badge badge-primary">xxxxxxxxx</span></td>--}}
                                    </tr>

                                    {{-- Terus dikirim ke masing2 rekening Parkir deh --}}
                                    {{--<tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="text-body text-muted">@lang("cms.Rek Settlement")</span>
                                                    <div class="font-weight-semibold text-danger">
                                                        @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($total_yukk_cash_switching + $total_yukk_points_switching + ($total_other_currency_hak - $total_fee_switching_hak) - ($total_grand_total_kewajiban - $total_fee_yukk_kewajiban), 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>→</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="text-body text-muted">@lang("cms.Rek Parkir Masing-masing Partner")</span>
                                                    <div class="font-weight-semibold text-success">
                                                        @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($total_yukk_cash_switching + $total_yukk_points_switching + ($total_other_currency_hak - $total_fee_switching_hak) - ($total_grand_total_kewajiban - $total_fee_yukk_kewajiban), 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        --}}{{--<td><span class="badge badge-primary">xxxxxxxxx</span></td>--}}{{--
                                    </tr>--}}

                                @else
                                    {{-- If HAK < KEWAJIBAN --}}
                                    {{-- Berarti dari Rek Settlement Transfer Selisih-nya ke Rek Parkir JALIN --}}
                                    <tr>
                                        <td><span class="text-body text-muted">@lang("cms.Rek Settlement")</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="col-sm-12 text-center">
                                                    <div class="">→</div>
                                                    <div class="font-weight-semibold text-danger">
                                                        @lang("cms.IDR") {{ \App\Helpers\H::formatNumber($total_all, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-body text-muted">@lang("cms.Rek Parkir SWITCHING")</span></td>
                                        {{--<td><span class="badge badge-secondary" data-popup="tooltip" data-placement="left" data-original-title="@lang("cms.Di luar System")"><i class="icon-watch2"></i></span></td>--}}
                                    </tr>
                                    {{-- Lalu dari Julie Transfer MANUAL ke si JALIN --}}
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.Settlement List")</h5>
                </div>

                <div class="card-body">
                    @if ($settlement_master_list)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td>@lang("cms.Beneficiary Name")</td>
                                <td>@lang("cms.Partner Name")</td>
                                <td>@lang("cms.Settlement Date")</td>
                                <td>@lang("cms.Ref Code")</td>
                                <td>@lang("cms.Actions")</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($settlement_master_list as $settlement_master)
                                <tr>
                                    <td>{{ @$settlement_master->customer->name }}</td>
                                    <td>{{ @$settlement_master->partner->name }}</td>
                                    <td>{{ @$settlement_master->settlement_date }}</td>
                                    <td>{{ @$settlement_master->ref_code }}</td>
                                    <td>
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route("cms.yukk_co.settlement_master.show", $settlement_master->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
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

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });
    </script>
@endsection