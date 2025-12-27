@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Transaction Merchant Online Detail")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("transaction_merchant_online.index") }}" class="breadcrumb-item">@lang("cms.Transaction Merchant Online")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Entity Type")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->type_type }}" readonly>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Entity Name")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->type->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.YUKK ID")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->user->yukk_id }}" readonly>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Transaction Time")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->created_at }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Ref Code")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->code }}" readonly>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Partner Ref Code")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->bill_code }}" readonly>
                    </div>
                </div>


                <hr>

                <div class="form-group">
                    <h4 class="font-weight-bold">@lang('cms.Payment Detail')</h4>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.YUKK Cash")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->yukk_p }}" readonly>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.YUKK Points")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->yukk_e }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Final Amount")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->final_amount }}" readonly>
                    </div>
                    <label class="col-form-label col-sm-2">@lang("cms.Notes")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->notes }}" readonly>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.MDR Type")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->yukk_co_portion_type }}" readonly>
                    </div>
                    <label class="col-form-label col-sm-2">@lang("cms.MDR Fee")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->yukk_co_portion }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Status")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->status }}" readonly>
                    </div>
                    <label class="col-form-label col-sm-2"></label>
                    <div class="form-group col-sm-4">
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <h4 class="font-weight-bold">@lang('cms.Status Log')</h4>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Status Name")</label>
                    <div class="form-group col-sm-4">
                        @if($transaction->status == 'PENDING')
                            <input class="form-control" value="WAITING PAYMENT" readonly>
                        @elseif($transaction->status == 'FAILED')
                            <input class="form-control" value="FAILED" readonly>
                        @elseif($transaction->status == 'EXPIRED')
                            <input class="form-control" value="EXPIRED" readonly>
                        @elseif($transaction->status == 'CANCELED')
                            <input class="form-control" value="CANCELED" readonly>
                        @elseif($transaction->status == 'REFUNDED')
                            <input class="form-control" value="REFUNDED" readonly>
                        @elseif($transaction->status == 'SUCCESS')
                            <input class="form-control" value="PAID" readonly>
                        @elseif($transaction->status == 'COMPLETED')
                            <input class="form-control" value="FULFILLED" readonly>
                        @endif
                    </div>
                    <label class="col-form-label col-sm-2">@lang("cms.Action Date")</label>
                    <div class="form-group col-sm-4">
                        <input class="form-control" value="{{ @$transaction->created_at }}" readonly>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
