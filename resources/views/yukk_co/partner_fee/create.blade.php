@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Create Partner Fee")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.partner_fee.list") }}" class="breadcrumb-item">@lang("cms.Partner Fee List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create Partner Fee")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <form method="post" enctype="multipart/form-data" action="{{ route('yukk_co.partner_fee.store') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control @if ($errors->has("name")) is-invalid @endif" name="name" value="{{ old('name') }}">
                            @if ($errors->has("name"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("name") }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                        <div class="col-sm-4">
                            <textarea type="text" class="form-control @if ($errors->has("description")) is-invalid @endif" name="description">{{ old('description') }}</textarea>
                            @if ($errors->has("description"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("description") }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                        <div class="col-sm-4">
                            <textarea type="text" class="form-control @if ($errors->has("description")) is-invalid @endif" name="short_description">{{ old('short_description') }}</textarea>
                            @if ($errors->has("short_description"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("short_description") }}</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Sort Number")</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control @if ($errors->has("sort_number")) is-invalid @endif" name="sort_number" value="1000.00">
                            @if ($errors->has("sort_number"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("sort_number") }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Display Status")</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="display_status" id="display_status">
                                <option @if(old('display_status') == 'SHOWN') selected @endif value="SHOWN">SHOWN</option>
                                <option @if(old('display_status') == 'HIDDEN') selected @endif value="HIDDEN">HIDDEN</option>
                            </select>
                            @if ($errors->has("display_status"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("display_status") }}</span>
                            @endif
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in %")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control @if ($errors->has("fee_partner_percentage")) is-invalid @endif" value="{{ old('fee_partner_percentage') }}" name="fee_partner_percentage">
                            @if ($errors->has("fee_partner_percentage"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("fee_partner_percentage") }}</span>
                            @endif
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in IDR")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control @if ($errors->has("fee_partner_fixed")) is-invalid @endif" value="{{ old('fee_partner_fixed') }}" name="fee_partner_fixed">
                            @if ($errors->has("fee_partner_fixed"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("fee_partner_fixed") }}</span>
                            @endif
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK Additional in %")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control @if ($errors->has("fee_yukk_additional_percentage")) is-invalid @endif" value="{{ old('fee_yukk_additional_percentage') }}" name="fee_yukk_additional_percentage">
                            @if ($errors->has("fee_yukk_additional_percentage"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("fee_yukk_additional_percentage") }}</span>
                            @endif
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK Additional in IDR")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control @if ($errors->has("fee_yukk_additional_fixed")) is-invalid @endif" value="{{ old('fee_yukk_additional_fixed') }}" name="fee_yukk_additional_fixed">
                            @if ($errors->has("fee_yukk_additional_fixed"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("fee_yukk_additional_fixed") }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mb-5">
                    <button class="btn btn-primary btn-block col-3 btn-submit" id="btn-submit" type="submit">@lang("cms.Submit")</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $("#btn-submit").click(function(e) {
            if (window.confirm("Are you sure want to create this Partner Fee ?")) {

            } else {
                e.preventDefault();
            }
        });
    </script>
@endsection
