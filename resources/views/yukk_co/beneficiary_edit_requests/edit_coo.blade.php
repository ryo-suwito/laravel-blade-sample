@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Edit Beneficiary Bank Account")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.beneficiary_edit_request.list_coo") }}" class="breadcrumb-item">@lang("cms.Beneficiary Pending List")</a>
                    <a href="{{ route("cms.yukk_co.beneficiary_edit_request.show", @$beneficiary_edit_request->id) }}" class="breadcrumb-item">{{ @$beneficiary_edit_request->order_id }}</a>
                    {{--<span class="breadcrumb-item active">@lang("cms.Edit Beneficiary Bank Account")</span>--}}
                    <span class="breadcrumb-item active">@lang("cms.Edit")</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>

            {{--<div class="header-elements d-none">
                <div class="breadcrumb justify-content-center">
                    <a href="#" class="breadcrumb-elements-item">
                        Link
                    </a>

                    <div class="breadcrumb-elements-item dropdown p-0">
                        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                            Dropdown
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">One more action</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Separate action</a>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <form method="post" action="{{ route("cms.yukk_co.beneficiary_edit_request.update_coo", @$beneficiary_edit_request->id) }}">
                    @csrf
                    <div class="card-header">
                        <h5 class="card-title">@lang("cms.Detail Data Existing User & New")</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row" style="margin-bottom: 16px;">
                                    <div class="col-sm-6 text-center">@lang("cms.Data Existing ( Read Only )")</div>
                                    <div class="col-sm-6 text-center">@lang("cms.New Data Bank User Disbursement")</div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->customer->bank->name }}">
                                    </div>
                                    <label class="col-lg-2 col-form-label text-center"><i class="icon-arrow-right8 "></i></label>
                                    <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->bank->name }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->customer->account_number }}">
                                    </div>
                                    <label class="col-lg-2 col-form-label text-center"><i class="icon-arrow-right8 "></i></label>
                                    <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->account_number }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Branch Name")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->customer->branch_name }}">
                                    </div>
                                    <label class="col-lg-2 col-form-label text-center"><i class="icon-arrow-right8 "></i></label>
                                    <label class="col-lg-2 col-form-label">@lang("cms.Branch Name")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->branch_name }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Bank Type")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->customer->bank_type }}">
                                    </div>
                                    <label class="col-lg-2 col-form-label text-center"><i class="icon-arrow-right8 "></i></label>
                                    <label class="col-lg-2 col-form-label">@lang("cms.Bank Type")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->bank_type }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->customer->account_name }}">
                                    </div>
                                    <label class="col-lg-2 col-form-label text-center"><i class="icon-arrow-right8 "></i></label>
                                    <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->account_name }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Fee")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @number_format($beneficiary_edit_request->customer->disbursement_fee, 2, ".", "") }}">
                                    </div>
                                    <label class="col-lg-2 col-form-label text-center"><i class="icon-arrow-right8 "></i></label>
                                    <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Fee")</label>
                                    <div class="col-lg-3">
                                        <input type="number" class="form-control" name="disbursement_fee" value="{{ @number_format($beneficiary_edit_request->disbursement_fee, 2, ".", "") }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Interval")</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" readonly="" value="{{ @$beneficiary_edit_request->customer->auto_disbursement_interval }}">
                                    </div>
                                    <label class="col-lg-2 col-form-label text-center"><i class="icon-arrow-right8 "></i></label>
                                    <label class="col-lg-2 col-form-label">@lang("cms.Disbursement Interval")</label>
                                    <div class="col-lg-3">
                                        <select type="text" class="form-control" name="disbursement_interval">
                                            <option value="DAILY" @if(old("disbursement_interval", @$beneficiary_edit_request->auto_disbursement_interval) == "DAILY") selected @endif>DAILY</option>
                                            <option value="WEEKLY" @if(old("disbursement_interval", @$beneficiary_edit_request->auto_disbursement_interval) == "WEEKLY") selected @endif>WEEKLY</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-5 offset-7">
                                        <img class="img-fluid" src="{{ @$beneficiary_edit_request->cover_book_image_path }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <a href="{{ route("cms.yukk_co.beneficiary_edit_request.show", @$beneficiary_edit_request->id) }}" class="btn btn-secondary">@lang("cms.Cancel")</a>
                                        <button type="submit" class="btn btn-primary" id="btn-submit">@lang("cms.Submit")</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>

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

            $("#btn-submit").click(function(e) {
                if (window.confirm("Apakah anda yakin ingin mengedit data ini ?")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection