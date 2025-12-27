@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Detail Event") - {{ $event->name }}</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.event.list") }}" class="breadcrumb-item">@lang("cms.Event List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail Event")</span>
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
                <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="name" id="name" value="{{ $event->name }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Code")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="code" id="code" value="{{ $event->code }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                <div class="col-sm-4">
                    <textarea type="text" class="form-control" name="short_description" id="short_description" readonly>{{ $event->short_description }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <textarea type="text" class="form-control" name="description" id="description" readonly>{{ $event->description }}</textarea>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Display Status")</label>
                <div class="col-sm-4">
                    <select class="form-control select2" disabled name="display_status" id="display_status">
                        <option value="SHOWN" @if(old("display_status", $event->display_status) == "SHOWN") selected @endif>SHOWN</option>
                        <option value="HIDDEN" @if(old("display_status", $event->display_status) == "HIDDEN") selected @endif>HIDDEN</option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Location")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="location" id="location" value="{{ $event->location }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Start Date")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="start_date" id="start_date" readonly value="{{ @\App\Helpers\H::formatDateTimeWithoutTime($event->start_date) }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.End Date")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="end_date" id="end_date" readonly value="{{ @\App\Helpers\H::formatDateTimeWithoutTime($event->end_date) }}">
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Event Organizer Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="event_organizer_name" id="event_organizer_name" readonly value="{{ $event->event_organizer_name }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Event Organizer Code")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="event_organizer_code" id="event_organizer_code" readonly value="{{ $event->event_organizer_code }}">
                </div>
            </div>

        </div>
    </div>
@endsection

