@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Partner x Payment Channel x Provider")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.partner_mdr_pg.list") }}" class="breadcrumb-item">@lang("cms.Partner x Payment Channel x Provider")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create")</span>
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
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.Partner x Payment Channel x Provider")</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ route("cms.yukk_co.partner_mdr_pg.store") }}" method="post" id="form">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">@lang("cms.Partner")</label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2" name="partner_id">
                                            <option value="">@lang("cms.Choose Partner")</option>
                                            @foreach (@$partner_list as $partner)
                                                <option value="{{ $partner->id }}" @if(old("partner_id") == $partner->id) selected @endif>{{ $partner->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">@lang("cms.Provider")</label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2" name="provider_id"  id="provider_id">
                                            <option value="">@lang("cms.Choose Provider")</option>
                                            @foreach (@$provider_list as $provider)
                                                <option value="{{ $provider->id }}" @if(old("provider_id") == $provider->id) selected @endif>{{ $provider->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">@lang("cms.Payment Channel")</label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2" name="payment_channel_id" id="payment_channel_id">
                                            <option value="">@lang("cms.Choose Payment Channel")</option>
                                            @foreach (@$payment_channel_list as $payment_channel)
                                                <option value="{{ $payment_channel->id }}" @if(old("payment_channel_id") == $payment_channel->id) selected @endif>{{ $payment_channel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label @if ($errors->has("active")) text-danger @endif">@lang("cms.Status")</label>
                                    <div class="col-lg-8">
                                        <select name="active" class="form-control @if ($errors->has("active")) is-invalid @endif">
                                            <option value="1" @if( old("active", 1)) selected @endif>@lang("cms.Active")</option>
                                            <option value="0" @if(! old("active", 1)) selected @endif>@lang("cms.Inactive")</option>
                                        </select>
                                        @if ($errors->has("active"))
                                            <span class="invalid-feedback">{{ $errors->first("active") }}</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label @if ($errors->has("mdr_fee_fixed")) text-danger @endif">@lang("cms.MDR External (Fixed)")</label>
                                    <div class="col-lg-8">
                                        <input type="number" step="0.01" id="mdr_fee_fixed" name="mdr_fee_fixed" class="form-control @if ($errors->has("mdr_fee_fixed")) is-invalid @endif" required="" value="{{ old("mdr_fee_fixed") }}">
                                        @if ($errors->has("active"))
                                            <span class="invalid-feedback">{{ $errors->first("mdr_fee_fixed") }}</span>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label @if ($errors->has("mdr_fee_percentage")) text-danger @endif">@lang("cms.MDR External (Percentage)")</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="number" step="0.01" id="mdr_fee_percentage" name="mdr_fee_percentage" class="form-control @if ($errors->has("mdr_fee_percentage")) is-invalid @endif" required="" value="{{ old("mdr_fee_fixed") }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            @if ($errors->has("mdr_fee_percentage"))
                                                <span class="invalid-feedback">{{ $errors->first("mdr_fee_percentage") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <button type="submit" class="btn btn-block btn-primary">@lang("cms.Submit")</button>
                            </form>
                        </div>
                    </div>
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

            $("#form").on("submit", function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
            $("#payment_channel_id").change(function() {
                var providerId = $("#provider_id").val();
                var paymentChannel = $("#payment_channel_id").val();

                var paymentChannelId = "{{ isset($payment_channel_item) ? $payment_channel_item->id : '' }}";
                var providerItemId = "{{ isset($provider_item) ? $provider_item->id : '' }}";

                if (paymentChannel == paymentChannelId && providerId == providerItemId) {
                    $("#mdr_fee_fixed").attr("readonly", true);
                    $("#mdr_fee_percentage").attr("readonly", true);
                    $("#mdr_fee_fixed").attr("value", 0);
                    $("#mdr_fee_percentage").attr("value", 0);
                } else {
                    $("#mdr_fee_fixed").removeAttr("readonly");
                    $("#mdr_fee_percentage").removeAttr("readonly");
                }
            });
        });
    </script>
@endsection
