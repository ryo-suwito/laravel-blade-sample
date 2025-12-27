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
        <div class="card-body">
            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Merchant Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="merchant_name" readonly value="{{ $master['name'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="description" readonly value="{{ $master['primary_description'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Company Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="company_name" readonly value="{{ $master['company']['name'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Category")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="category_name" readonly value="{{ $master['category']['name'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.MCC Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="category_name" readonly value="{{ $master['merchant_mcc']['description'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Merchant Type")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="category_name" readonly value="{{ $master['type'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.MDR QRIS Category")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="mdr_fee" readonly value="{{ $master['mdr_fee']['mdr_name'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Merchant Criteria")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="criteria" readonly value="{{ $master['criteria'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.QR Type")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="qr_type" readonly value="{{ $master['qris_type'] ?? '' }}">
                </div>
                <label class="col-form-label col-sm-2">@lang("cms.Status")</label>
                <div class="col-sm-4">
                    <select class="form-control select2" name="status" id="status" disabled>
                        <option value="">Please Choose</option>
                        <option value="0" @if($master['active'] == 0) selected @endif>Inactive</option>
                        <option value="1" @if($master['active'] == 1) selected @endif>Active</option>
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
                    <img style="max-width: 150%;" src="{{ $master['logo_url'] ?? '' }}">
                </div>
            </div>
        </div>
    </x-page.content>
</x-app-layout>
