<x-app-layout>
    <x-page.header :title="__('cms.Manage Approval')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="$mainMenuUrl" :text="__('cms.' . $title)"/>
            <x-breadcrumb.link :link="$approvalUrl" text="Action Page"/>
            <x-breadcrumb.active>
                Master Detail
            </x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.' . $title)">
        <div>
            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="name" id="name" value="{{ $master['name'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Code")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="code" id="code" value="{{ $master['code'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                <div class="col-sm-4">
                    <textarea type="text" class="form-control" name="short_description" id="short_description" readonly>{{ $master['short_description'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <textarea type="text" class="form-control" name="description" id="description" readonly>{{ $master['description'] ?? '' }}</textarea>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Display Status")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="display_status" id="display_status" value="{{ $master['display_status'] ?? ''}}" readonly>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Location")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="location" id="location" value="{{ $master['location'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Start Date")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="start_date" id="start_date" readonly value="{{ @\App\Helpers\H::formatDateTimeWithoutTime($master['start_date'] ?? '0000-00-00' ) }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.End Date")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="end_date" id="end_date" readonly value="{{ @\App\Helpers\H::formatDateTimeWithoutTime($master['end_date'] ?? '0000-00-00' ) }}">
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Event Organizer Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="event_organizer_name" id="event_organizer_name" readonly value="{{ $master['event_organizer_name'] ?? '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Event Organizer Code")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="event_organizer_code" id="event_organizer_code" readonly value="{{ $master['event_organizer_code'] ?? '' }}">
                </div>
            </div>

        </div>
    </x-page.content>
</x-app-layout>
