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
                <label for="company_name" class="col-form-label col-sm-2">@lang("cms.Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $master['name'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="description" name="description" value="{{ $master['description'] ?? '' }}" readonly>
                </div>
            </div>
        </div>
    </x-page.content>
</x-app-layout>
