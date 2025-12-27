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
                    <input type="text" class="form-control" name="name" value="{{ $master['name'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <textarea type="text" class="form-control" name="description" readonly>{{ $master['description'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                <div class="col-sm-4">
                    <textarea type="text" class="form-control text-left" name="short_description" readonly>{{ $master['short_description'] ?? '' }}</textarea>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Sort Number")</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" name="sort_number" value="{{ $master['sort_number'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Display Status")</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" name="display_status" value="{{ $master['display_status'] ?? '' }}" readonly>
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in %")</label>
                <div class="col-sm-4">
                    <input type="number" step="0.01" class="form-control" name="fee_partner_percentage" value="{{ $master['fee_partner_percentage'] ?? '' }}" readonly>
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in IDR")</label>
                <div class="col-sm-4">
                    <input type="number" step="0.01" class="form-control" name="fee_partner_fixed" value="{{ $master['fee_partner_fixed'] ?? '' }}" readonly>
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK Additional in %")</label>
                <div class="col-sm-4">
                    <input type="number" step="0.01" class="form-control" name="fee_yukk_additional_percentage" value="{{ $master['fee_yukk_additional_percentage'] ?? '' }}" readonly>
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK Additional in IDR")</label>
                <div class="col-sm-4">
                    <input type="number" step="0.01" class="form-control" name="fee_yukk_additional_fixed" value="{{ $master['fee_yukk_additional_fixed'] ?? '' }}" readonly>
                </div>
            </div>
        </div>
    </x-page.content>
</x-app-layout>
