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
                <h4>@lang("cms.Partner")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.partner.list") }}" class="breadcrumb-item">@lang("cms.Partner")</a>
                    <a href="{{ route("cms.yukk_co.partner.item", $partner->id) }}" class="breadcrumb-item">{{ $partner->name }}</a>
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
    <form action="{{ route("cms.yukk_co.partner.update", $partner->id) }}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-12 col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">@lang("cms.Partner")</h5>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang("cms.Name")</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" readonly="" value="{{ @$partner->name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang("cms.Enable API Payment")</label>
                                            <div class="col-lg-8 form-control-plaintext">
                                                <input type="checkbox" name="permission" @if (@$partner->permission_api_payment) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">@lang("cms.Webhook Partner")</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="webhook_partner" value="{{ @$partner->webhook }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        @hasaccess("PAYMENT_GATEWAY.PARTNER_PROVIDER_CREDENTIALS.VIEW")
                            <a href="{{ route("cms.yukk_co.partners.credentials.index", @$partner->id) }}" class="btn btn-block btn-secondary">
                                @lang("cms.Manage Credentials")
                            </a>
                        @endhasaccess
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route("cms.yukk_co.partner_has_merchant_branch.list", @$partner->id) }}" class="btn btn-block btn-secondary">
                            @lang("cms.Manage Branches")
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang("cms.Payment Channel List")</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-12">
                                <table class="table table-bordered table-striped dataTable">
                                    <thead>
                                    <tr>
                                        <th>@lang("cms.Name")</th>
                                        <th class="text-center">@lang("cms.Status")</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach(@$partner->payment_channel_list as $index => $payment_channel)
                                        <tr>
                                            <td
                                                @if (@$payment_channel->payment_channel->code == 'QRIS')
                                                    class="row-info"
                                                @endif
                                            >
                                                <div>
                                                    {{ @$payment_channel->payment_channel->name }}
                                                </div>
                                                @if (@$payment_channel->payment_channel->code == 'QRIS')
                                                    <a href="#" data-popup="tooltip" data-placement="top" data-original-title="Jika memilih YUKKPG QRIS maka MDR akan otomatis menjadi 0"> <span class="info-circle">i</span></a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" name="payment_channel[{{ @$payment_channel->payment_channel->id }}]" @if (@$payment_channel->active) checked @endif>
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

            <div class="col-sm-12">
                <button class="btn btn-block btn-primary">@lang("cms.Submit")</button>
            </div>
        </div>
    </form>

    @if (@$partner->oauth_client == null)
        <form id="form-generate-client-id-secret" class="d-none" method="post" action="{{ route("cms.yukk_co.partner.generate_Client_id_secret", @$partner->id) }}">
            @csrf
            <input type="hidden" name="partner_id" value="{{ $partner->id }}"/>
        </form>
    @endif
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

            @if (@$partner->oauth_client == null)
                $(".btn-generate-client-id-secret").click(function(e) {
                    $("#form-generate-client-id-secret").submit();
                    e.preventDefault();
                });
            @endif
        });
    </script>
@endsection
