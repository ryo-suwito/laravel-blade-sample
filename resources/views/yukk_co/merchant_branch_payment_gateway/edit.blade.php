@extends('layouts.master')

@section('html_head')
    <style type="text/css">
        .info-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 12px;
        }

        .row-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .row-info label {
            margin-top: 15px;
            margin-bottom: 15px;
        }
    </style>
@endsection
@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Merchant Branch")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ request()->query('from_partner_id') ? route("cms.yukk_co.partner_has_merchant_branch.list", request()->query("from_partner_id")) : route("cms.yukk_co.merchant_branch_pg.list") }}" class="breadcrumb-item">@lang("cms.Merchant Branch")</a>
                    <span class="breadcrumb-item active">@lang("cms.Edit")</span>
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
    <form method="post" action="{{ route("cms.yukk_co.merchant_branch_pg.update", $merchant_branch->id) }}">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang("cms.Detail")</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">@lang("cms.ID")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" readonly="" value="{{ @$merchant_branch->id }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">@lang("cms.Name")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" readonly="" value="{{ @$merchant_branch->name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">@lang("cms.Status")</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" readonly="" value="{{ @$merchant_branch->active ? trans("cms.Active") : trans("cms.Inactive") }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-12">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">@lang("cms.Payment Channel")</h5>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-striped dataTable">
                                        <thead>
                                        <tr>
                                            <th>@lang("cms.Payment Channel Name")</th>
                                            <th class="text-center">@lang("cms.Status")</th>
                                            <th>@lang("cms.Is Settle")</th>
                                            <th>@lang("cms.Bank Fee")</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($payment_channel_list as $index => $payment_channel)
                                            <tr>
                                                <td
                                                @if ($payment_channel->code == 'QRIS')
                                                    class="row-info"
                                                @endif
                                                >
                                                    <label for="cb-pc-{{ @$payment_channel->id }}">
                                                        @if ($payment_channel->active)
                                                        {{ @$payment_channel->name }}
                                                        @else
                                                        <span class="text-danger" title="@lang("cms.Inactive")">{{ @$payment_channel->name }}</span>
                                                        @endif
                                                    </label>
                                                    @if ($payment_channel->code == 'QRIS')
                                                        <a href="#" data-popup="tooltip" data-placement="top" data-original-title="Dengan mencentang QRIS, Provider Setting akan otomatis terbuat"> <span class="info-circle">i</span></a>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="payment_channel[{{ $payment_channel->id }}]" id="cb-pc-{{ @$payment_channel->id }}" @if(@isset($merchant_branches_has_payment_channel_map[$payment_channel->id])) checked="checked" @endif>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="is_settle[{{ $payment_channel->id }}]" id="cb-pc-{{ @$payment_channel->id }}"
                                                        @if(@isset($merchant_branches_has_payment_channel_map[$payment_channel->id]) && $merchant_branches_has_payment_channel_map[$payment_channel->id]->is_settle)
                                                            @if ($payment_channel->code != 'QRIS')
                                                                checked="checked"
                                                            @endif
                                                        @endif

                                                        @if ($payment_channel->code == 'QRIS')
                                                            disabled
                                                        @endif
                                                    >
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number" name="bank_fee[{{ $payment_channel->id }}]" id="cb-pc-{{ @$payment_channel->id }}"
                                                        @if(@isset($merchant_branches_has_payment_channel_map[$payment_channel->id]))
                                                            @if ($payment_channel->code != 'QRIS')
                                                                value="{{ $merchant_branches_has_payment_channel_map[$payment_channel->id]->bank_fee }}"
                                                            @else
                                                                value="0.00"
                                                            @endif
                                                                {{-- value="{{ $merchant_branches_has_payment_channel_map[$payment_channel->id]->bank_fee }}" --}}
                                                        @else
                                                            value="0.00"
                                                        @endif

                                                        @if ($payment_channel->code == 'QRIS')
                                                            readonly
                                                        @endif
                                                    >
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <button class="btn btn-primary btn-block">@lang("cms.Submit")</button>
            </div>
        </div>
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
        });
    </script>
@endsection
