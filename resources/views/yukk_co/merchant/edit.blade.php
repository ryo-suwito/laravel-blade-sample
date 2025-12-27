@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Merchant Edit")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.merchants.list") }}" class="breadcrumb-item"><span class="breadcrumb-item">@lang("cms.Merchant List")</span></a>
                    <span class="breadcrumb-item active">@lang("cms.Merchant Edit")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Merchant Edit")</h5>
        </div>

        <form method="post" enctype="multipart/form-data" action="{{ route('yukk_co.merchant.edit', $merchant->id) }}">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Merchant Name")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="merchant_name" value="{{ $merchant->name }}">
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="description" value="{{ $merchant->primary_description }}">
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
                        <select class="form-control select2" name="category_id" id="category_id">
                            <option value="">Please Choose</option>
                            @foreach($response->categories as $category)
                                <option value="{{ $category->id }}" @if( $merchant->category->id == $category->id ) selected @endif>{{ $category->name }}</option>
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
                                <option value="{{ $mcc->code }}" @if($merchant->category_iso == $mcc->code) selected @endif>{{ $mcc->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.MDR QRIS Category")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="mdr_fee" id="mdr_fee">
                            <option value="">Please Choose</option>
                            @foreach($response->mdrFees as $mdrFee)
                                <option value="{{ $mdrFee->id }}" @if($merchant->mdr_fee_id == $mdrFee->id) selected @endif>{{ $mdrFee->mdr_name }}</option>
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
                            @foreach($response->merchantCriterias as $merchantCriteria)
                                <option value="{{ $merchantCriteria->code }}" @if($merchant->merchant_criteria == $merchantCriteria->code) selected @endif>{{ $merchantCriteria->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-form-label col-sm-2">@lang("cms.QR Type")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="qr_type" id="qr_type">
                            <option value="">Please Choose</option>
                            @foreach($response->qrTypes as $qrTypes)
                                <option value="{{ $qrTypes->code }}" @if($merchant->qr_type == $qrTypes->code) selected @endif>{{  $qrTypes->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Status")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="status" id="status">
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
                        <input type="file" class="form-control" name="image_logo" value="{{ $merchant->logo_map_image }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-4">
                        <img style="max-width: 150%;" src="{{ $merchant->logo_url }}">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mb-5">
                <button class="btn btn-primary btn-block col-3" type="submit">@lang("cms.Submit")</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
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

