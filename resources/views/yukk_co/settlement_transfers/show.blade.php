@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Settlement Transfer")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.yukk_co.settlement_transfer.list") }}" class="breadcrumb-item">@lang("cms.Settlement Transfer")</a>
                    <span class="breadcrumb-item active">{{ @$settlement_transfer->ref_code }}</span>
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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Settlement Transfer")</h5>
        </div>
    
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Ref Code")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->ref_code }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Settlement Date")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->settlement_date }}">
                        </div>
                    </div>

                    <hr>

                    @if ($settlement_transfer->partner)
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Partner Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->partner->name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Partner Description")</label>
                            <div class="col-lg-4">
                                <textarea type="text" class="form-control" readonly="">{{ @$settlement_transfer->partner->description }}</textarea>
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Email List")</label>
                            <div class="col-lg-4">
                                <textarea type="text" class="form-control" readonly="">{{ @$settlement_transfer->partner->email_list }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->partner->bank->name }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Bank Branch")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->partner->account_branch_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->partner->account_number }}">
                            </div>
                            <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->partner->account_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.BCA / NON BCA")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->partner->bank_type }}">
                            </div>
                        </div>
                    @else
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Partner Name")</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" readonly="" value="-">
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Merchant Portion")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_transfer->total_merchant_portion) }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Fee Partner")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_transfer->total_fee_partner) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Total Transfer")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($settlement_transfer->total_transfer) }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Source Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->source_account_number }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Destination Account Number")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->destination_account_number }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Status")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->status }}">
                        </div>
                        <label class="col-lg-2 col-form-label">@lang("cms.Type")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$settlement_transfer->type }}">
                        </div>
                    </div>

                    @if (@in_array($settlement_transfer->status, ["ON_GOING", "FAILED"]))
                        <hr>
                        <form method="post" action="{{ route("cms.yukk_co.settlement_transfer.action", @$settlement_transfer->id) }}">
                            @csrf
                            <input type="hidden" class="d-none" name="status" value="{{ @$settlement_transfer->status }}">
                            @if (@$settlement_transfer->status == "ON_GOING")
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="RETRY">@lang("cms.Retry")</button>
                                        <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="PROCEED">@lang("cms.Proceed")</button>
                                    </div>
                                </div>
                            @elseif (@$settlement_transfer->status == "FAILED")
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <button name="action" type="submit" class="btn btn-danger btn-need-confirmation" value="RETRY">@lang("cms.Retry")</button>
                                    </div>
                                </div>
                            @endif
                        </form>
                    @endif


                    @if (@$disbursement_partner->status == "FAILED")
                        <div class="form-group row mt-5">
                            <div class="col-12 text-center">
                                <form method="post" action="{{ @route("cms.yukk_co.settlement_transfer.action", $settlement_transfer->id) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ @$settlement_transfer->status }}"/>
                                    <button class="btn btn-danger btn-confirm" name="action" value="RETRY">@lang("cms.Retry")</button>
                                    <button class="btn btn-warning btn-confirm" name="action" value="PROCEED">@lang("cms.Proceed")</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Settlement Master List")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>@lang("cms.Ref Code")</th>
                            <th>@lang("cms.Settlement Date")</th>
                            <th>@lang("cms.Beneficiary")</th>
                            <th>@lang("cms.Status")</th>
                            @if (@$settlement_transfer->type == "SETTLEMENT_TO_PARKING")
                                <th>@lang("cms.Total Merchant Portion")</th>
                                <th>@lang("cms.Total Fee Partner")</th>
                            @endif
                            <th>@lang("cms.Actions")</th>
                        </tr>
                        @foreach (@$settlement_transfer->settlement_to_parking_masters as $settlement_master)
                            <tr>
                                <td>{{ @$settlement_master->ref_code }}</td>
                                <td>{{ @\App\Helpers\H::formatDateTimeWithoutTime($settlement_master->settlement_date) }}</td>
                                <td>{{ @$settlement_master->customer->name }}</td>
                                <td>{{ @$settlement_master->status }}</td>
                                @if (@$settlement_transfer->type == "SETTLEMENT_TO_PARKING")
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_master->total_merchant_portion) }}</td>
                                    <td class="text-right">{{ @\App\Helpers\H::formatNumber($settlement_master->total_fee_partner) }}</td>
                                @endif
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route("cms.yukk_co.settlement_master.show", $settlement_master->id) }}" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();

            $(".btn-need-confirmation").click(function(e) {
                if (confirm("@lang("cms.general_confirmation_dialog_content")")) {
                    setInterval(function() {
                        // Need interval because if disable first, then the button is not included on the form
                        $(".btn-need-confirmation").attr("disabled", "disabled");
                    }, 50);
                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection