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
                    <input type="text" class="form-control" name="name" readonly value="{{ $master['name'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Code")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="code" readonly value="{{ $master['code'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="description" readonly value="{{ $master['description'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="short_description" readonly value="{{ $master['short_description'] ?? '' }}">
                </div>
            </div>

            <hr>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Type")</label>
                    <div class="col-sm-4">
                        <select name="partner_type" id="partner_type" class="form-control select2" data-minimum-results-for-search="Infinity" disabled>
                            <option value="">Select Type</option>
                            <option value="MA" {{ $master['type'] == 'MA' ? 'selected' : '' }}>@lang("cms.Merchant Aggregator (MA)")</option>
                            <option value="Internal" {{ $master['type'] == 'Internal' ? 'selected' : '' }}>@lang("cms.Internal")</option>
                            <option value="Others" {{ $master['type'] == 'Others' ? 'selected' : '' }}>@lang("cms.Others")</option>
                        </select>
                    </div>
                </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in %")</label>
                <div class="col-sm-4">
                    <input class="form-control" name="fee_in_percentage" readonly value="{{ $master['fee_partner_percentage'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in IDR")</label>
                <div class="col-sm-4">
                    <input class="form-control" name="fee_in_idr" readonly value="{{ $master['fee_partner_fixed'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK in %")</label>
                <div class="col-sm-4">
                    <input class="form-control" name="fee_yukk_in_percentage" readonly value="{{ $master['fee_yukk_additional_percentage'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK in IDR")</label>
                <div class="col-sm-4">
                    <input class="form-control" name="fee_yukk_in_idr" readonly value="{{ $master['fee_yukk_additional_fixed'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Minimum Nominal for QRIS")</label>
                <div class="col-sm-4">
                    <input class="form-control" name="minimum_nominal" readonly value="{{ $master['minimum_nominal'] ?? '' }}">
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.PIC Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="pic_name" readonly value="{{ $master['pic_name'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.PIC Email")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="pic_email" readonly value="{{ $master['pic_email'] }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.PIC Phone")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="pic_phone" readonly value="{{ $master['pic_phone'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Email List")</label>
                <div class="col-sm-4">
                    <textarea class="form-control" readonly name="email_list">{{ $master['email_list'] ?? '' }}</textarea>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Account Number")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="account_number" readonly value="{{ $master['account_number'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Account Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="account_name" readonly value="{{ $master['account_name'] ?? '' }}">
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Bank Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="bank_name" readonly value="{{ $master['bank']['name'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Branch Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="branch_name" readonly value="{{ $master['account_branch_name'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.City Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="city_name" readonly  value="{{ $master['account_city_name'] ?? '' }}">
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Bank Type")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="bank_type" readonly value="{{ $master['bank_type'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Transfer Type")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="transfer_type" readonly value="{{ $master['transfer_type'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Disbursement Fee")</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" name="disbursement_fee" readonly value="{{ intval($master['disbursement_fee']) ?? '' }}">
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Partner Parking Account Number")</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" name="partner_parking_account_number" readonly value="{{ $master['rek_parking_account_number'] ?? '' }}">
                </div>

                <label class="col-form-label col-sm-2">@lang("cms.Partner Parking Owner Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="partner_parking_owner_name" readonly value="{{ $master['rek_parking_account_name'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Disbursement Interval")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="partner_parking_owner_name" readonly value="{{ $master['auto_disbursement_interval'] ?? '' }}">
                </div>
            </div>

            <br>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.SNAP QRIS Enable")</label>
                <input type="checkbox" id="is_snap_enabled" disabled name="is_snap_enabled" class="form-group" @if($master['is_snap_enabled']) checked @endif>
            </div>

            <br>

            <div id="snap_detail" @if(!$master['is_snap_enabled'])) hidden @endif>
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Client ID")</label>
                    <div class="col-sm-4">
                        <input type="text" readonly name="snap_client_id" class="form-control" id="snap_client_id" value="{{ old("snap_client_id", $master['snap_client_id'] ?? '' ) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Client Secret")</label>
                    <div class="col-sm-4">
                        <input type="text" readonly name="snap_client_secret" class="form-control" id="snap_client_secret" value="{{ old("snap_client_id", $master['snap_client_secret'] ?? '' ) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Public Key Partner")</label>
                    <div class="col-sm-4">
                        <textarea type="text" class="form-control" readonly name="snap_public_key">{{ $master['snap_public_key'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.URL B2B Access Token")</label>
                    <div class="col-sm-2">
                        <input type="text" readonly class="form-control" name="qr_access_token_notify_base_url" value="{{ $master['qr_access_token_notify_base_url'] ?? '' }}">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" readonly class="form-control" name="qr_access_token_notify_relative_path" value="{{ $master['qr_access_token_notify_relative_path'] ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.URL MPM Notify")</label>
                    <div class="col-sm-2">
                        <input type="text" readonly class="form-control" name="qr_mpm_notify_base_url" value="{{ $master['qr_mpm_notify_base_url'] ?? '' }}">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" readonly class="form-control" name="qr_mpm_notify_relative_path" value="{{ $master['qr_mpm_notify_relative_path'] ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Client ID Partner")</label>
                    <div class="col-sm-4">
                        <input type="text" readonly class="form-control" name="snap_notify_client_id" value="{{ $master['snap_notify_client_id'] ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Client Secret Partner")</label>
                    <div class="col-sm-4">
                        <input type="text" readonly class="form-control" name="snap_notify_client_secret" value="{{ $master['snap_notify_client_secret'] ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Channel ID")</label>
                    <div class="col-sm-4">
                        <input type="text" readonly class="form-control" name="channel_id" value="{{ $master['channel_id'] ?? '' }}">
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Webhook URL (API QRIS Registration)")</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="{{ $master['webhook_url_api_qris_registration'] ?? '' }}" name="webhook_url_api_qris_registration">
                </div>

            </div>

        </div>
    </x-page.content>
</x-app-layout>
