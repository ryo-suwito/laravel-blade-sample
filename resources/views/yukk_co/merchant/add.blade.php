@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Add Merchant")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.merchants.list") }}" class="breadcrumb-item">@lang("cms.Merchant List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Add Merchant")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Add Merchant")</h5>
        </div>

        <form method="post" id="form_add" enctype="multipart/form-data" action="{{ route('yukk_co.merchant.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Merchant Name")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="merchant_name">
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="description">
                    </div>
                </div>

                <br>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Company Name")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="company_name" id="company_name">
                            <option value="">Please Choose</option>
                            @foreach($response->companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Category")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="company_category" id="company_category">
                            <option value="">Please Choose</option>
                            @foreach($response->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.MCC Name")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="mcc" id="mcc">
                            <option value="">Please Choose</option>
                            @foreach($response->mccs as $mcc)
                                <option value="{{ $mcc->code }}">{{ $mcc->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.MDR QRIS Category")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="mdr_fee" id="mdr_fee">
                            <option value="">Please Choose</option>
                            @foreach($response->mdr_fees as $mdr_fee)
                                <option value="{{ $mdr_fee->id }}">{{ $mdr_fee->mdr_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Merchant Criteria")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="merchant_criteria" id="merchant_criteria">
                            <option value="">Please Choose</option>
                            @foreach($response->merchant_criterias as $merchant_criteria)
                                <option value="{{ $merchant_criteria->code }}">{{ $merchant_criteria->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-form-label col-sm-2">@lang("cms.QR Type")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="qr_type" id="qr_type">
                            @foreach($response->qr_types as $qr_type)
                                <option value="{{ $qr_type->code }}" @if( $qr_type->code == 'b') selected @endif>{{  $qr_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>

                <div class="form-group row mt-1">
                    <label class="col-form-label col-sm-2">@lang("cms.Image Logo")</label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="image_logo">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mb-5">
                <button class="btn btn-primary btn-block col-3 submitBtn" type="submit">@lang("cms.Submit")</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#form_add").submit(function () {
                $(".submitBtn").attr("disabled", true);
                return true;
            });

            $(document).on('select2:open', () => {
                document.querySelector('.select2-container--open .select2-search__field').focus();
            });

            $(".btn-confirm").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection

