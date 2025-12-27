@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Merchant Detail")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.merchants.list") }}" class="breadcrumb-item"><span class="breadcrumb-item">@lang("cms.Merchant List")</span></a>
                    <span class="breadcrumb-item active">@lang("cms.Merchant Detail")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Merchant Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="merchant_name" readonly value="{{ $merchant->name }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="description" readonly value="{{ $merchant->primary_description }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Company Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="company_name" readonly value="{{ $merchant->company->name }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Category")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="category_name" readonly value="{{ $merchant->category->name }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.MCC Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="category_name" readonly value="{{ $merchant->merchant_mcc->description }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Merchant Type")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="category_name" readonly value="{{ $merchant->merchant_type == 1 ? 'Individu' : 'Badan Hukum' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.MDR QRIS Category")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="category_name" readonly value="{{ @$merchant->mdr_fee->mdr_name }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Merchant Criteria")</label>
                <div class="col-sm-4">
                    <select class="form-control select2" name="merchant_criteria" id="merchant_criteria" disabled>
                        <option value="">Please Choose</option>
                        @foreach($response->merchantCriterias as $merchantCriteria)
                            <option value="{{ $merchantCriteria->code }}" @if($merchant->merchant_criteria == $merchantCriteria->code) selected @endif>{{ $merchantCriteria->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Status")</label>
                <div class="col-sm-4">
                    <select class="form-control select2" name="status" id="status" disabled>
                        <option value="">Please Choose</option>
                        <option value="0" @if($merchant->active == 0) selected @endif>Inactive</option>
                        <option value="1" @if($merchant->active == 1) selected @endif>Active</option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="form-group row mt-1">
                <label class="col-form-label col-sm-2">@lang("cms.Image Logo")</label>
                <div class="col-sm-4">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-4">
                    <img style="max-width: 150%;" src="{{ $merchant->logo_url }}">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".btn-confirm").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection

