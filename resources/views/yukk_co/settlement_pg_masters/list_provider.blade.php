@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Payout PG")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Payout PG")</span>
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
            <h5 class="card-title">@lang("cms.Payout PG")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.settlement_pg_master.list_source_of_fund") }}" method="get">
                <div class="row">

                    <div class="col-lg-10">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
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

            @php($total_all = 0)
            @foreach ($source_of_fund_list as $source_of_fund)
                @php($total_all += @$source_of_fund->provider->total)
                <div class="row">
                    <div class="col-lg-12">
                        @lang("cms.Provider"):
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td colspan="2" class="text-center"><b>{{ @$source_of_fund->provider->name }}</b></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>@lang("cms.Nominal")</td>
                                <td>@lang("cms.Fee")</td>
                            </tr>
                            <tr>
                                <td class="text-right">{{ @number_format(@$source_of_fund->provider->nominal, 0, ".", "") }}</td>
                                <td class="text-right">{{ @number_format(@$source_of_fund->provider->fee, 0, ".", "") }}</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2" class="text-center">{{ @number_format(@$source_of_fund->provider->total, 0, ".", "") }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        @lang("cms.Payment Channel"):
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                @foreach ($source_of_fund->payment_channel_list as $payment_channel)
                                    <td colspan="2" class="text-center"><b>{{ @$payment_channel->name }}</b></td>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                @foreach ($source_of_fund->payment_channel_list as $payment_channel)
                                    <td>@lang("cms.Nominal")</td>
                                    <td>@lang("cms.Fee")</td>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($source_of_fund->payment_channel_list as $payment_channel)
                                    <td class="text-right">{{ @number_format(@$payment_channel->nominal, 0, ".", "") }}</td>
                                    <td class="text-right">{{ @number_format(@$payment_channel->fee, 0, ".", "") }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($source_of_fund->payment_channel_list as $payment_channel)
                                    <td colspan="2" class="text-center">{{ @number_format(@$payment_channel->total, 0, ".", "") }}</td>
                                @endforeach
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="{{ count(@$source_of_fund->payment_channel_list) * 2 }}" class="text-center">
                                    {{ @number_format(@$source_of_fund->provider->total, 0, ".", "") }}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <hr>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">

                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Simulasi Money Flow")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table text-nowrap">
                        <tbody>

                        <tr>
                            <td><span class="text-body text-muted">@lang("cms.Rek Provider")</span></td>
                            <td>
                                <div class="d-flex align-items-center text-center">
                                    <div class="col-sm-12 text-center">
                                        <div class="">→</div>
                                        <div class="font-weight-semibold text-danger">
                                            @lang("cms.IDR") {{ \App\Helpers\H::formatNumber(@$total_all, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-body text-muted">@lang("cms.Rek Bank Source of Fund")</span></td>
                        </tr>

                        <tr>
                            <td><span class="text-body text-muted">@lang("cms.Rek Bank Source of Fund")</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="col-sm-12 text-center">
                                        <div class="">→</div>
                                        <div class="font-weight-semibold text-danger">
                                            @lang("cms.IDR") {{ \App\Helpers\H::formatNumber(@$total_all, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-body text-muted">@lang("cms.Rek Settlement")</span></td>
                        </tr>
                        </tbody>
                    </table>
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
                "searching": true,
            });

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });
        });
    </script>
@endsection