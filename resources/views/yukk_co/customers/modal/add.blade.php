<div id="add-customer-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route("yukk_co.customers.store") }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('cms.Add Beneficiary')</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    @csrf
                    {{-- <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="customer_id">@lang("cms.Customer ID")</label>
                        <div class="col-lg-8">
                            <input type="text" id="customer_id" name="customer_id" class="form-control" placeholder="@lang("cms.Customer ID")" value="{{ old('customer_id') }}" required>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="name">@lang('cms.Name')</label>
                        <div class="col-lg-8">
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="@lang('cms.Name')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="ktp_no">@lang('cms.KTP No')</label>
                        <div class="col-lg-8">
                            <input type="text" id="ktp_no" name="ktp_no" class="form-control"
                                placeholder="@lang('cms.KTP No')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="file_ktp">@lang('cms.File KTP')</label>
                        <div class="col-lg-8">
                            <input type="file" id="file_ktp" name="file_ktp" class="form-control" value=""
                                required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="npwp_no">@lang('cms.NPWP No')</label>
                        <div class="col-lg-8">
                            <input type="text" id="npwp_no" name="npwp_no" class="form-control"
                                placeholder="@lang('cms.NPWP No')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="file_npwp">@lang('cms.File NPWP')</label>
                        <div class="col-lg-8">
                            <input type="file" id="file_npwp" name="file_npwp" class="form-control"
                                placeholder="@lang('cms.File NPWP')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="bank_id">@lang('cms.Bank Name')</label>
                        <div class="col-lg-8">
                            <select id="bank_id" name="bank_id" class="form-control" required>
                                @foreach ($banks as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="thumbnail">@lang("cms.Thumbnail")</label>
                        <div class="col-lg-8">
                            <input type="file" id="thumbnail" name="thumbnail" class="form-control" placeholder="@lang("cms.Thumbnail")" value="">
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="branch_name">@lang('cms.Bank Branch Name')</label>
                        <div class="col-lg-8">
                            <input type="text" id="branch_name" name="branch_name" class="form-control"
                                placeholder="@lang('cms.Title')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="account_number">@lang('cms.Account Number')</label>
                        <div class="col-lg-8">
                            <input type="text" id="account_number" name="account_number" class="form-control"
                                placeholder="@lang('cms.Account Number')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="account_name">@lang('cms.Account Name')</label>
                        <div class="col-lg-8">
                            <input type="text" id="account_name" name="account_name" class="form-control"
                                placeholder="@lang('cms.Account Name')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="email">@lang('cms.Email')</label>
                        <div class="col-lg-8">
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="@lang('cms.Email')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="contact_no">@lang('cms.Contact No')</label>
                        <div class="col-lg-8">
                            <input type="text" id="contact_no" name="contact_no" class="form-control"
                                placeholder="@lang('cms.Contact No')" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="city_id">@lang('cms.City')</label>
                        <div class="col-lg-8">
                            <select id="city_id" name="city_id" class="form-control">
                                @foreach ($cities as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="address">@lang('cms.Address')</label>
                        <div class="col-lg-8">
                            <textarea type="text" id="address" name="address" class="form-control" placeholder="@lang('cms.Address')"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="description">@lang('cms.Description')</label>
                        <div class="col-lg-8">
                            <textarea type="text" id="description" name="description" class="form-control" placeholder="@lang('cms.Description')"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="disbursement_fee">@lang('cms.Disbursement Fee')</label>
                        <div class="col-lg-8">
                            <input type="number" id="disbursement_fee" name="disbursement_fee" class="form-control"
                                placeholder="@lang('cms.Disbursement Fee')" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="bank_type">@lang('cms.Bank Type')</label>
                        <div class="col-lg-8">
                            {{-- <select id="bank_type" class="form-control bank_type" disabled>
                                <option value="@lang("cms.BCA")">@lang("cms.BCA")</option>
                                <option value="@lang("cms.NON-BCA")">@lang("cms.NON-BCA")</option>
                            </select> --}}
                            <input type="text" id="bank_type_name" name="bank_type"
                                class="bank_type form-control" value="@lang('cms.BCA')" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label"
                            for="auto_disbursement_interval">@lang('cms.Disbursement Interval')</label>
                        <div class="col-lg-8">
                            <select id="auto_disbursement_interval" name="auto_disbursement_interval"
                                class="form-control">
                                <option value="DAILY">@lang('cms.Daily')</option>
                                <option value="WEEKLY">@lang('cms.Weekly')</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">@lang('cms.Cancel')</button>
                    <button type="submit" class="btn btn-primary" id="btn-add-credit">@lang('cms.Add')</button>
                </div>
            </form>
        </div>
    </div>
</div>
