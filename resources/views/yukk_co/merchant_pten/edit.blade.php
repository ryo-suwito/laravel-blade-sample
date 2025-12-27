@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{-- <h4><span class="font-weight-semibold">Seed</span> - Static layout</h4> --}}
                <h4>@lang('cms.QRIS (PTEN) Menu')</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{-- <button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button> --}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('yukk_co.merchant.pten.list') }}" class="breadcrumb-item">@lang('cms.QRIS (PTEN) Menu')</a>
                    <span class="breadcrumb-item active">@lang('cms.Edit')</span>
                </div>

                {{-- <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a> --}}
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <form method="POST" action="{{ route('yukk_co.merchant.pten.pending') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                {{-- <h5 class="card-title">@lang('cms.QRIS (PTEN) Menu')</h5> --}}
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="company_id">@lang('cms.Company')</label>
                                                    <div class="col-lg-6">
                                                        <select id="company_id" name="company_id"
                                                            class="form-control select2" required>
                                                            <option value="{{ $item->merchant->company_id }}" selected>
                                                                {{ $item->merchant->company->name ?? null }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <a href="{{ route('yukk_co.companies.list') }}" target="_blank"
                                                            class="reload_company">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="merchant_id">@lang('cms.Merchant')</label>
                                                    <div class="col-lg-6">
                                                        <select id="merchant_id" name="merchant_id"
                                                            class="form-control select2" required>
                                                            <option value="{{ $item->merchant_id }}" selected>
                                                                {{ $item->merchant->name ?? null }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <a href="{{ route('yukk_co.merchant.add') }}" target="_blank"
                                                            class="reload_merchant">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="merchant_branch_id">@lang('cms.Merchant Branch')</label>
                                                    <div class="col-lg-6">
                                                        <input type="hidden" id="input_merchant_branch_name"
                                                            value="{{ $item->name }}">
                                                        <select id="merchant_branch_id" name="merchant_branch_id"
                                                            class="form-control select2" required>
                                                            <option value="{{ $item->id }}" selected>
                                                                {{ $item->name }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <a href="{{ route('yukk_co.merchant_branch.add') }}"
                                                            target="_blank" class="reload_merchant_branch">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="customer_id">@lang('cms.Beneficiary')</label>
                                                    <div class="col-lg-6">
                                                        <select id="customer_id" name="customer_id"
                                                            class="form-control select2" required>
                                                            <option value="{{ $item->customer_id }}" selected>
                                                                {{ $item->customer->name ?? null }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <a href="{{ route('yukk_co.customers.create') }}" target="_blank"
                                                            class="reload_customer">
                                                            <i class="icon-add"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4" for="remark">@lang('cms.Remark')</label>
                                                    <div class="col-lg-8">
                                                        <ul id="remark">
                                                            @if ($item->status_pten == 'REJECTED')
                                                                <li id="remark_rejected">
                                                                    {{ $item->last_pten_reject_remark }}</li>
                                                            @else
                                                                @if (!$item->start_date)
                                                                    <li id="remark_start_date">@lang('cms.startDateNotSetWarning')</li>
                                                                @endif
                                                                @if (!$item->end_date)
                                                                    <li id="remark_end_date">@lang('cms.endDateNotSetWarning')</li>
                                                                @endif
                                                                @if (!$item->city)
                                                                    <li id="remark_city">@lang('cms.cityNotSetWarning')</li>
                                                                @endif
                                                                @if (!$item->total_terminal)
                                                                    <li id="remark_total_terminal">@lang('cms.totalTerminalNotSetWarning')</li>
                                                                @endif
                                                                @if (!$item->zipcode)
                                                                    <li id="remark_zipcode">@lang('cms.postalCodeNotSetWarning')</li>
                                                                @endif
                                                                @if(!$item->owner_id)
                                                                    <li id="remark_owner_id">@lang('cms.ownerNotSetWarning')</li>
                                                                @endif
                                                                @if (!$item->customer_id)
                                                                    <li id="remark_customer_id">@lang('cms.beneficiaryNotSetWarning')</li>
                                                                @else
                                                                    @if (!isset($item->owner) || (isset($item->owner) && $item->owner->merchant_type == 'INDIVIDU' && !$item->owner->id_card_number))
                                                                        <li id="remark_ktp_no"
                                                                            title="Wajib isi KTP apabila Merchant Perorangan">
                                                                            @lang('cms.ownerIdCardNotSetWarning')</li>
                                                                    @endif
                                                                    @if (!isset($item->owner) || (isset($item->owner) && $item->owner->merchant_type == 'BADAN_HUKUM' && !$item->customer->npwp_no))
                                                                        <li id="remark_npwp_no"
                                                                            title="Wajib isi NPWP apabila Merchant Badan Hukum">
                                                                            @lang('cms.ownerNpwpNotSetWarning')</li>
                                                                    @endif
                                                                @endif
                                                                @if ($item->merchant)
                                                                    @if (!$item->merchant->category_iso)
                                                                        <li id="remark_category_iso">@lang('cms.mccNotSetWarning')
                                                                        </li>
                                                                    @endif
                                                                    @if (!$item->merchant->merchant_criteria)
                                                                        <li id="remark_merchant_criteria">
                                                                            @lang('cms.merchantCriteriaNotSetWarning')</li>
                                                                    @endif
                                                                    @if (!$item->merchant->qr_type)
                                                                        <li id="remark_qr_type">@lang('cms.qrTypeNotSetWarning')</li>
                                                                    @endif
                                                                    @if ($item->merchant->company_id)
                                                                        @if ($item->merchant->company->status_legal !== 'APPROVED')
                                                                            <li id="remark_company">@lang('cms.CompanyNotApproved')</li>
                                                                        @endif
                                                                    @else
                                                                        <li id="remark_company">@lang('cms.CompanyNotApproved')</li>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                            {{-- </textarea> --}}
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4" for="company_id">@lang('cms.Status')</label>
                                                    <div class="col-lg-8" id="status_pten">
                                                        {{ $item->status_pten_formatted }}
                                                    </div>
                                                    <input type="hidden" id="status_pten_hidden"
                                                        value="{{ $item->status_pten_formatted }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row info">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4" for="company_id">@lang('cms.Last Updated At')</label>
                                                    <div class="col-lg-8">
                                                        @if ($item->status_pten == 'REJECTED')
                                                            <p>{{ $item->last_pten_rejected_at ?? $item->updated_at }}</p>
                                                        @elseif ($item->status_pten == 'WAITING_FROM_PTEN' || $item->status_pten == 'PENDING_TO_PTEN')
                                                            <p>{{ $item->last_submit_to_pten_at ?? $item->updated_at }}</p>
                                                        @else
                                                            <p>{{ $item->updated_at }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4" for="company_id">@lang('cms.Last Updated By')</label>
                                                    <div class="col-lg-8 updated_by">
                                                        @if ($item->status_pten == 'REJECTED' || $item->status_pten == 'APPROVED')
                                                            <p>SYSTEM</p>
                                                        @else
                                                            <p>{{ $item->updater->full_name ?? '-' }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-sm-4 col-lg-4">
                        <a href="{{ route('yukk_co.merchant.pten.list') }}" class="btn btn-block btn-warning">
                            @lang('cms.Cancel')
                        </a>
                    </div>
                    <div class="col-sm-4 col-lg-4">
                        <button type="button" id="btn-save-beneficiary" class="btn btn-block btn-primary"
                            data-toggle="modal" data-target="#save-beneficiary-modal">
                            @lang('cms.Save Beneficiary')
                        </button>
                    </div>
                    <div class="col-sm-4 col-lg-4">
                        <button type="button" id="btn-submit-to-pten" class="btn btn-block btn-secondary"
                            data-toggle="modal" data-target="#submit-to-pten-modal"
                            {{ !$enableSubmit ? 'disabled' : '' }}>
                            @lang('cms.Submit to PTEN')
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="submit-to-pten-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4>@lang('cms.Submit to PTEN')</h4>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">Proceed to submit data of "<span id="merchant_branch_name"></span>" to PTEN?
                    </div>
                    <input type="hidden" name="submit_pten" value="true">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            @lang('cms.Close')
                        </button>
                        <button class="btn btn-success" type="submit">
                            @lang('cms.Proceed')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal fade" id="save-beneficiary-modal" tabindex="-1" role="dialog">
        <form method="POST" action="{{ route('yukk_co.merchant.pten.status') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4>@lang('cms.Save Beneficiary')</h4>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">Do you want to save the current data of beneficiary?
                    </div>
                    <input type="hidden" name="merchant_branch_id" id="branch2" value="{{ $item->id }}">
                    <input type="hidden" name="status_pten" id="status2" value="{{ $item->status_pten }}">
                    <input type="hidden" name="customer_id" id="customer2">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            @lang('cms.Close')
                        </button>
                        <button class="btn btn-success" type="submit">
                            @lang('cms.Save Beneficiary')
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('post_scripts')
    <script>
        $(document).ready(function() {
            if ($("#merchant_branch_id").val() != "") {
                $("#merchant_branch_name").text($("#merchant_branch_id").find('option:selected').text());
            }

            // SELECT 2 AJAX
            $('#company_id').select2({
                ajax: {
                    url: "{{ route('json.merchant-acquisition.options.companies') }}",
                    type: "GET",
                    delay: 500,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            page: params.page || 1,
                            search: params.term // search term
                        };
                    },
                    processResults: function(data, params) {
                        let more = data.more;
                        params.page = params.page || 1;

                        let result = data.result.map(function(item) {
                            item.id = item.value;
                            item.text = item.label;
                            return item;
                        });

                        return {
                            pagination: {
                                more: more
                            },
                            results: result,
                        };
                    },
                },
                placeholder: "Select Company",

            });

            $('#customer_id').select2({
                ajax: {
                    url: "{{ route('json.merchant-acquisition.options.customers') }}",
                    type: "GET",
                    delay: 500,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        let more = data.more;
                        params.page = params.page || 1;

                        let result = data.result.map(function(item) {
                            item.id = item.value;
                            item.text = item.label;
                            return item;
                        });

                        return {
                            pagination: {
                                more: more
                            },
                            results: result,
                        };
                    },
                },
                placeholder: "Select Beneficiary",

            });

            function initializeMerchantSelect() {
                $('#merchant_id').select2({
                    ajax: {
                        url: "{{ route('json.merchant-acquisition.options.merchants') }}",
                        type: "GET",
                        delay: 500,
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                company_id: $('#company_id').val()
                            };
                        },
                        processResults: function(data, params) {
                            let more = data.more;
                            params.page = params.page || 1;

                            let result = data.result.map(function(item) {
                                item.id = item.value;
                                item.text = item.label;
                                return item;
                            });

                            return {
                                pagination: {
                                    more: more
                                },
                                results: result,
                            };
                        },
                    },
                    placeholder: "Select Merchant",
                });
            }

            function initializeBranchSelect() {
                $('#merchant_branch_id').select2({
                    ajax: {
                        url: "{{ route('json.merchant-acquisition.options.branches') }}",
                        type: "GET",
                        delay: 500,
                        dataType: 'json',
                        data: function(params) {
                            return {
                                search: params.term, // search term,
                                page: params.page || 1,
                                merchant_id: $('#merchant_id').val()
                            };
                        },
                        processResults: function(data, params) {
                            let more = data.more;
                            params.page = params.page || 1;

                            let result = data.result.map(function(item) {
                                item.id = item.value;
                                item.text = item.label;
                                return item;
                            });
                            return {
                                pagination: {
                                    more: more
                                },
                                results: result,
                            };
                        }
                    },
                    placeholder: "Select Merchant Branch",

                });
            }

            // Initialize merchant_id and merchant_branch_id select2 on document ready
            initializeMerchantSelect();
            initializeBranchSelect();

            // TRIGGER CHANGE
            $("#company_id").change(function() {
                $("#btn-save-beneficiary").attr("disabled", true);
                $("#merchant_id").val(null).trigger('change').select2('destroy').empty();
                initializeMerchantSelect();
                $("#merchant_branch_id").val(null).trigger('change').select2('destroy').empty();
                initializeBranchSelect();
                $("#customer_id").val(null).change();
                $(".updated_at").html('');
                $(".updated_by").html('');
                $("#status_pten").html('');
                $("#remark").html('');
            });

            $("#merchant_id").change(function() {
                $("#merchant_branch_id").val(null).trigger('change').select2('destroy').empty();
                initializeBranchSelect();
            });

            $("#merchant_branch_id").change(function() {
                var merchantBranchId = $("#merchant_branch_id").val();
                $.ajax({
                    url: "{{ route('json.merchant.branches.item') }}",
                    type: "GET",
                    data: {
                        merchant_branch_id: merchantBranchId,
                    },
                    dataType: 'json',
                    success: function(result) {
                        var item = result.merchant_branch;
                        console.log(item);
                        var init = 1;
                        $(".info").show();
                        $("#remark").html('');
                        $("#status_pten").html('');
                        $("#status_pten").append('<p>' + item.status_pten_formatted + '</p>');
                        $("#status_pten_hidden").val(item.status_pten_formatted);
                        $("#input_merchant_branch_name").val(item.name);
                        $("#merchant_branch_name").text(item.name);
                        $(".updated_at").html('');
                        if (item.status_pten == 'APPROVED') {
                            $(".updated_at").append('<p>' + item.pten_approved_at + '</p>');
                        } else if (item.status_pten == 'REJECTED') {
                            $(".updated_at").append('<p>' + item.last_pten_rejected_at +
                                '</p>');
                        } else if (item.status_pten == 'WAITING_FROM_PTEN' || item
                            .status_pten == 'PENDING_TO_PTEN') {
                            $(".updated_at").append('<p>' + item.last_submit_to_pten_at +
                                '</p>');
                        } else {
                            $(".updated_at").append('<p>' + item.updated_at + '</p>');
                        }
                        $(".updated_by").html('');
                        if (item.status_pten == 'WAITING_FROM_PTEN' || item.status_pten ==
                            'PENDING_TO_PTEN') {
                            if (item.updater) {
                                $(".updated_by").append('<p>' + item.updater.full_name +
                                    '</p>');
                            } else {
                                $(".updated_by").append('<p>-</p>');
                            }
                        } else if (item.status_pten == 'REJECTED' || item.status_pten ==
                            'APPROVED') {
                            $(".updated_by").append('<p>SYSTEM</p>');
                        } else {
                            if (item.updater) {
                                $(".updated_by").append('<p>' + item.updater.full_name +
                                    '</p>');
                            } else {
                                $(".updated_by").append('<p>-</p>');
                            }
                        }
                        if (item.status_pten == 'REJECTED') {
                            $("#remark").append(
                                '<li>' + item.last_pten_reject_remark + '</li>');
                        } else {
                            if (!(item.start_date)) {
                                init = 0;
                                $("#remark").append(
                                    '<li><b>Start Date</b> is Not Set (CMO or COO)</li>');
                            }
                            if (!(item.end_date)) {
                                init = 0;
                                $("#remark").append(
                                    '<li><b>End Date</b> is Not Set (CMO or COO)</li>');
                            }
                            if (!(item.city)) {
                                init = 0;
                                $("#remark").append(
                                    '<li><b>City</b> is Not Set (CMO or COO)</li>');
                            }
                            if (!(item.total_terminal)) {
                                init = 0;
                                $("#remark").append(
                                    '<li><b>Total Terminal</b> must above 0 (CMO or COO)</li>'
                                );
                            }
                            if (!(item.zipcode)) {
                                init = 0;
                                $("#remark").append(
                                    '<li><b>Postal Code/Zipcode</b> is Not Set (CMO or COO)</li>'
                                );
                            }
                            if (!(item.owner_id)) {
                                init = 0;
                                $("#remark").append(
                                    '<li id="remark_owner_id"><b>Owner</b> is Not Set (COO)</li>'
                                );
                            }
                            if (!(item.customer_id)) {
                                init = 0;
                                $("#remark").append(
                                    '<li><b>Beneficiary</b> is Not Set (CFO)</li>');
                            } else {
                                // if (item.merchant.merchant_type == 1 && ! (item.customer.ktp_no)) {
                                // init = 0;
                                //     $("#remark").append('<li title="Wajib isi KTP apabila Merchant Perorangan"><b>Beneficiary's KTP Number</b> is Not Set (CFO)</li>');
                                // }
                                // if (item.merchant.merchant_type == 2 && ! (item.customer.npwp_no)) {
                                // init = 0;
                                //     $("#remark").append('<li title="Wajib isi NPWP apabila Merchant Badan Hukum"><b>Beneficiary's NPWP Number</b> is Not Set (CFO)</li>');
                                // }
                            }
                            if (item.merchant) {
                                if (!(item.merchant.category_iso)) {
                                    init = 0;
                                    $("#remark").append(
                                        '<li><b>Merchant\'s Criteria</b> is Not Set (COO)</li>'
                                    );
                                }
                                if (!(item.merchant.merchant_criteria)) {
                                    init = 0;
                                    $("#remark").append(
                                        '<li><b>Merchant\'s Type</b> is Not Set (COO)</li>');
                                }
                                if (!(item.merchant.qr_type)) {
                                    init = 0;
                                    $("#remark").append(
                                        '<li><b>Merchant\'s QR Type</b> is Not Set (COO)</li>'
                                    );
                                }
                                if (!(item.merchant.company.status_legal)) {
                                    init = 0;
                                    $("#remark").append(
                                        '<li><b>Company\'s QR Type</b> is not yet <b>APPROVED<b> by Legal</li>'
                                    );
                                }
                            }
                        }

                        if (init == 0) {
                            $("#btn-submit-to-pten").attr("disabled", true);
                        } else {
                            $("#btn-submit-to-pten").removeAttr("disabled");
                        }

                        if (item.customer_id != null) {
                            $("#customer_id").val(item.customer_id).change();
                            $("#customer2").val(item.customer_id);
                        }

                        if (item.status_pten != 'READY_TO_SUBMIT' || item.status_pten !=
                            'REJECTED') {
                            $("#btn-submit-to-pten").attr("disabled", true);
                            $("#remark").append(
                                '<li>This Merchant Branch has been submitted to PTEN</li>');
                        }

                        $("#status2").val(item.status_pten);
                        $("#branch2").val(item.id);

                    }
                });
            });

            $("#customer_id").change(function() {
                var customerId = $("#customer_id").val();
                if (customerId != "") {
                    checkKtpAvailability(customerId, "{{ route('yukk_co.customers.check_ktp') }}");
                    $("#remark_customer_id").remove();
                    if (!( $('#remark_start_date').length || $('#remark_end_date').length || $( '#remark_city').length || 
                    $('#remark_total_terminal').length || $('#remark_zipcode').length || $('#remark_ktp_no').length || 
                    $('#remark_npwp_no').length || $('#remark_category_iso').length || $('#remark_merchant_type').length || 
                    $('#remark_merchant_criteria').length || $('#remark_company').length || $('#remark_qr_type').length) ) {
                        var status_pten_hidden = $("#status_pten_hidden").val();
                        if (!(status_pten_hidden == 'APPROVED' || status_pten_hidden == 'PENDING' ||status_pten_hidden == 'REJECTED')) {
                            $("#btn-submit-to-pten").removeAttr("disabled");
                            $("#status_pten").html("READY");
                        }
                    }
                    $("#customer2").val(customerId);
                    var branchId = $("#merchant_branch_id").val();
                    var companyId = $("#company_id").val();
                    if ((branchId != "" && companyId != "") && !(status_pten_hidden == 'APPROVED' ||
                            status_pten_hidden == 'PENDING')) {
                        $("#btn-save-beneficiary").removeAttr("disabled");
                    }
                }
            });
        });
    </script>
@include ('yukk_co.merchant_pten.scripts.check_ktp')
@endsection
