@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Search Fee Provider")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Search Fee Provider")</span>
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
            <h5 class="card-title">@lang("cms.Filter")</h5>
        </div>

        <div class="card-body">
            <form action="{{ route("cms.yukk_co.customer_invoice_provider.index") }}" method="get">
                <div class="form-group row">
                    <label class="col-form-label col-sm-4" for="date_range">@lang("cms.Date Range Kwitansi Date")</label>
                    <div class="col-sm-8">
                        <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_date->format("d-M-Y") }} - {{ $end_date->format("d-M-Y") }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-4" for="provider_id">@lang("cms.Provider")</label>
                    <div class="col-sm-8">
                        <select class="form-control select2" name="provider_id" id="provider_id">
                            <option value="-1">@lang("cms.All")</option>
                            @foreach(@$provider_list as $provider)
                                <option value="{{ @$provider->id }}" @if(@$provider_id == $provider->id) selected @endif>{{ $provider->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12 col-md-3 offset-md-9 text-right">
                        <button class="btn btn-primary btn-block" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Select for Review Provider Fee")</h5>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <td>@lang("cms.Provider")</td>
                    <td>@lang("cms.Count Transaction")</td>
                    <td>@lang("cms.Total Fee Provider")</td>
                    <td>@lang("cms.Actions")</td>
                </tr>
                </thead>

                @if (@$customer_invoice_provider_list !== null)
                    <tbody>
                    @foreach (@$customer_invoice_provider_list as $customer_invoice_provider)
                        <tr>
                            <td>{{ @$customer_invoice_provider->provider->name }}</td>
                            <td class="text-right">{{ @\App\Helpers\H::formatNumber(@$customer_invoice_provider->sum_count_transaction, 0) }}</td>
                            <td class="text-right">@lang("cms.IDR") {{ @\App\Helpers\H::formatNumber(@$customer_invoice_provider->sum_mdr_internal_fixed + @$customer_invoice_provider->sum_mdr_internal_percentage, 0) }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route("cms.yukk_co.customer_invoice_provider.detail", ["provider_id" => $customer_invoice_provider->provider_id, "start_date" => $start_date->format("d-M-Y"), "end_date" => $end_date->format("d-M-Y")]) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
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