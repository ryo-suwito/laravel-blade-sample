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
        <div class="row">

            <div class="col-sm-12 col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="background-color: #191919;">
                            <div class="card-header">
                                <h3>@lang('cms.General Information')</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="name">@lang('cms.Merchant Label')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="name" name="name" class="form-control"
                                                    placeholder="@lang('cms.Merchant Label')" value="{{ $master['name'] ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="status">@lang('cms.Status')</label>
                                            <div class="col-lg-8">
                                                <select class="form-control status" id="status" name="status" disabled>
                                                    <option value="1" {{ $master['status'] == 1 ? 'selected' : '' }}>
                                                        @lang('cms.Active')</option>
                                                    <option value="0" {{ $master['status'] == 0 ? 'selected' : '' }}>
                                                        @lang('cms.Inactive')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="email">@lang('cms.Billing Address')</label>
                                            <div class="col-lg-8">
                                                <textarea class="form-control" name="address" id="address" placeholder="@lang('cms.Billing Address')" readonly>{{ $master['address'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="email">@lang('cms.Disbursement Email')</label>
                                            <div class="col-lg-8">
                                                <textarea class="form-control" name="email" id="email" placeholder="@lang('cms.Disbursement Email')" readonly>{{ $master['email'] ?? '' }}</textarea>

                                                <small>Multiple email, please use comma separate</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="background-color: #191919;">
                            <div class="card-header">
                                <h3>@lang('cms.Bank Acct Disbursement')</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="name">@lang('cms.Merchant Label')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="name" name="name" class="form-control"
                                                    placeholder="@lang('cms.Merchant Label')" value="{{ $master['name'] ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="bank_id">@lang('cms.Bank Name')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="bank_id" name="bank_id"
                                                    class="form-control" placeholder="@lang('cms.Bank Branch Name')"
                                                    value="{{ $master['bank']['name'] ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="branch_name">@lang('cms.Bank Branch Name')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="branch_name" name="branch_name"
                                                    class="form-control" placeholder="@lang('cms.Bank Branch Name')"
                                                    value="{{ $master['branch_name'] ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="account_number">@lang('cms.Account Number')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="account_number" name="account_number"
                                                    class="form-control" placeholder="@lang('cms.Account Number')"
                                                    value="{{ $master['account_number'] ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="account_name">@lang('cms.Account Name')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="account_name" name="account_name"
                                                    class="form-control" placeholder="@lang('cms.Account Name')"
                                                    value="{{ $master['account_name'] ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="bank_type">@lang('cms.Bank Type')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="bank_type_name" name="bank_type"
                                                    class="bank_type form-control" value="{{ $master['bank_type'] ?? '' }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="disbursement_fee">@lang('cms.Disbursement Fee')</label>
                                            <div class="col-lg-8">
                                                <input type="number" id="disbursement_fee" name="disbursement_fee"
                                                    class="form-control" placeholder="@lang('cms.Disbursement Fee')"
                                                    value="{{ explode(".", $master['disbursement_fee'] ?? '0.00')[0] }}" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label"
                                                for="auto_disbursement_interval">@lang('cms.Disbursement Interval')</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="auto_disbursement_interval" name="auto_disbursement_interval"
                                                    class="bank_type form-control" value="{{ $master['auto_disbursement_interval'] ?? '' }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page.content>
</x-app-layout>
