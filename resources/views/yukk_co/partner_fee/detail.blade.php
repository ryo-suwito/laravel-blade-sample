@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Partner Fee Detail")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.partner_fee.list") }}" class="breadcrumb-item">@lang("cms.Partner Fee List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Partner Fee Detail")</span>
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
                    <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="name" value="{{ $partner_fee->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                    <div class="col-sm-4">
                        <textarea type="text" class="form-control" name="description" readonly>{{ $partner_fee->description }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                    <div class="col-sm-4">
                        <textarea type="text" class="form-control text-left" name="short_description" readonly>{{ $partner_fee->short_description }}</textarea>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Sort Number")</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" name="sort_number" value="{{ $partner_fee->sort_number }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Display Status")</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="display_status" id="display_status" disabled>
                            <option value="SHOWN" @if($partner_fee->display_status == "SHOWN") selected @endif>SHOWN</option>
                            <option value="HIDDEN" @if($partner_fee->display_status == "HIDDEN") selected @endif>HIDDEN</option>
                        </select>
                    </div>
                </div>

                <br>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in %")</label>
                    <div class="col-sm-4">
                        <input type="number" step="0.01" class="form-control" name="fee_partner_percentage" value="{{ $partner_fee->fee_partner_percentage }}" readonly>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in IDR")</label>
                    <div class="col-sm-4">
                        <input type="number" step="0.01" class="form-control" name="fee_partner_fixed" value="{{ $partner_fee->fee_partner_fixed }}" readonly>
                    </div>
                </div>

                <br>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK Additional in %")</label>
                    <div class="col-sm-4">
                        <input type="number" step="0.01" class="form-control" name="fee_yukk_additional_percentage" value="{{ $partner_fee->fee_yukk_additional_percentage }}" readonly>
                    </div>

                    <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK Additional in IDR")</label>
                    <div class="col-sm-4">
                        <input type="number" step="0.01" class="form-control" name="fee_yukk_additional_fixed" value="{{ $partner_fee->fee_yukk_additional_fixed }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
