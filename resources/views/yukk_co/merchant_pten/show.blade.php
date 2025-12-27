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
                    <span class="breadcrumb-item active">@lang('cms.Detail')</span>
                </div>

                {{-- <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a> --}}
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <form method="POST" action="{{ route('yukk_co.customers.store') }}" enctype="multipart/form-data">
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
                                                    <div class="col-lg-8">
                                                        <input type="text" name="" id=""
                                                            class="form-control"
                                                            value="{{ $item->merchant->company->name }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="merchant_id">@lang('cms.Merchant')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" name="" id=""
                                                            class="form-control" value="{{ $item->merchant->name }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="merchant_branch_id">@lang('cms.Merchant Branch')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" name="" id=""
                                                            class="form-control" value="{{ $item->name }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label"
                                                        for="beneficiary_id">@lang('cms.Beneficiary')</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" name="" id=""
                                                            class="form-control" value="{{ $item->customer->name ?? '' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4" for="remark">@lang('cms.Remark')</label>
                                                    <div class="col-lg-8">
                                                        @if ($item->status_pten == 'REJECTED')
                                                            <p id="remark_rejected">
                                                                {{ $item->last_pten_reject_remark }}</p>
                                                        @else
                                                            <ul id="remark">
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
                                                                    @if (!isset($item->owner) || (isset($item->owner) && $item->owner->merchant_type == 'BADAN_HUKUM' && !$item->owner->npwp_number))
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
                                                                @endif
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4" for="company_id">@lang('cms.Status')</label>
                                                    <div class="col-lg-8">
                                                        <p>{{ $item->status_pten_formatted }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($item->status_pten == 'REJECTED_DELETE_PTEN')
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4" for="company_id">@lang('cms.PTEN Rejection Reason')</label>
                                                    <div class="col-lg-8">
                                                        <p>{{ $item->last_delete_pten_reject_remark }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-4" for="company_id">@lang('cms.Last Updated At')</label>
                                                    <div class="col-lg-8">
                                                        @if ($item->status_pten == 'APPROVED')
                                                            <p>{{ $item->pten_approved_at }}</p>
                                                        @elseif ($item->status_pten == 'REJECTED')
                                                            <p>{{ $item->last_pten_rejected_at ?? '-' }}</p>
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
                                                    <div class="col-lg-8">
                                                        @if ($item->status_pten == 'REJECTED' || $item->status_pten == 'APPROVED')
                                                            <p>SYSTEM</p>
                                                        @else
                                                            <p>{{ $item->updater->full_name ?? '-' }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($item->status_pten == 'APPROVED')
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label class="col-lg-4" for="">@lang('cms.QRIS Static')</label>
                                                        <div class="col-lg-8">
                                                            <img src="{{ @$item->qr_static_path }}" alt=""
                                                                style="max-width: 100%;max-height: 100%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label class="col-lg-4" for="">@lang('cms.NMID')</label>
                                                        <div class="col-lg-8">
                                                            <p>{{ $item->nmid_pten ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
                    <div class="col-sm-12 col-lg-12">
                        <a href="{{ route('yukk_co.merchant.pten.list') }}" class="btn btn-block btn-warning">
                            @lang('cms.Go Back')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- <div class="col-sm-12 col-lg-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang('cms.Payment Channel List')</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                                <tr>
                                    <th>@lang('cms.Name')</th>
                                    <th class="text-center">@lang('cms.Status')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach (@$partner->payment_channel_list as $index => $payment_channel)
                                <tr>
                                    <td>{{ @$payment_channel->payment_channel->name }}</td>
                                    <td class="text-center">
                                        @if (@$payment_channel->active)
                                            <i class="icon-checkmark text-success"></i>
                                        @else
                                            <i class="icon-cross2 text-danger"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
            });
            $("#company_id").change(function() {
                var companyId = $("#company_id").val();
                console.log(companyId);
                $.ajax({
                    url: "{{ route('json.merchant') }}",
                    type: "GET",
                    data: {
                        company_id: companyId,
                    },
                    dataType: 'json',
                    success: function(result) {
                        $("#merchant_id").html('');
                        $("#merchant_id").append('<option value="">Select Merchant</option>');
                        $.each(result, function(key, value) {
                            $("#merchant_id").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
            });
            $("#merchant_id").change(function() {
                var merchantId = $("#merchant_id").val();
                $.ajax({
                    url: "{{ route('json.merchant.branches') }}",
                    type: "GET",
                    data: {
                        merchant_id: merchantId,
                    },
                    dataType: 'json',
                    success: function(result) {
                        console.log(result);
                        $("#merchant_branch_id").html('');
                        $("#merchant_branch_id").append(
                            '<option value="">Select Merchant Branch</option>');
                        $.each(result, function(key, value) {
                            $("#merchant_branch_id").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
            });
        });
    </script>
@endsection
