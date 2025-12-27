@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Create Event")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.event.list") }}" class="breadcrumb-item">@lang("cms.Event List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Create Event")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <form method="post" enctype="multipart/form-data" action="{{ route('yukk_co.event.store') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control @if ($errors->has("name")) is-invalid @endif" value="{{ old('name') }}" placeholder="Name" name="name">
                            @if ($errors->has("name"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("name") }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Code")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control @if ($errors->has("code")) is-invalid @endif" value="{{ old('code') }}" placeholder="Code" name="code">
                            @if ($errors->has("code"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("code") }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                        <div class="col-sm-4">
                            <textarea type="text" class="form-control @if ($errors->has("short_description")) is-invalid @endif" name="short_description" placeholder="Short Description" id="short_description">{{ old('short_description') }}</textarea>
                            @if ($errors->has("short_description"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("short_description") }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                        <div class="col-sm-4">
                            <textarea type="text" class="form-control @if ($errors->has("description")) is-invalid @endif" name="description" placeholder="Description" id="description">{{ old('description') }}</textarea>
                            @if ($errors->has("description"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("description") }}</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Display Status")</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="display_status" id="display_status">
                                <option value="SHOWN" selected>SHOWN</option>
                                <option value="HIDDEN">HIDDEN</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Location")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control @if ($errors->has('location')) is-invalid @endif" value="{{ old('location') }}" placeholder="Location" name="location">
                            @if ($errors->has("location"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("location") }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Start Date")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="start_date" id="start_date">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.End Date")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="end_date" id="end_date">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Event Organizer Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control @if ($errors->has('event_organizer_name')) is-invalid @endif" value="{{ old('event_organizer_name') }}" placeholder="Event Organizer Name" name="event_organizer_name" id="event_organizer_name">
                            @if ($errors->has("event_organizer_name"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("event_organizer_name") }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Event Organizer Code")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control @if ($errors->has('event_organizer_code')) is-invalid @endif" value="{{ old('event_organizer_code') }}" placeholder="Event Organizer Code" name="event_organizer_code" id="event_organizer_code">
                            @if ($errors->has("event_organizer_code"))
                                <span class="help-block text-danger pt-1">{{ $errors->first("event_organizer_code") }}</span>
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
        $(document).ready(function() {
            let start_time = $('#start_time').val();
            $("#start_date").daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

            $("#end_date").daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 2,
                },
                minDate: {
                    start_time
                },
            });

            $("#btn-submit").click(function(e) {
                if (window.confirm("Are you sure want to create this Event ?")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
