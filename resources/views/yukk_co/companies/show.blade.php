@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Company Detail")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.companies.list") }}" class="breadcrumb-item">@lang("cms.Company List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Company Detail")</span>
                </div>
            </div>
        </div>

    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label for="company_name" class="col-form-label col-sm-2">@lang("cms.Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company_detail->name }}" readonly>
                </div>

                @if ($company_detail->status_legal !== 'NEW')
                <label for="status" class="col-form-label col-sm-2">@lang("cms.Submitted By")</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="status" value="{{ $company_detail->review_submit_by }}">
                </div>
            @endif
            </div>

            <div class="form-group row">
                <label for="description" class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="description" name="description" value="{{ $company_detail->description }}" readonly>
                </div>

                @if ($company_detail->status_legal == 'APPROVED')
                    <label for="status" class="col-form-label col-sm-2">@lang("cms.Approved By")</label>
                    <div class="col-sm-4">
                        <input type="text" readonly class="form-control" id="status" value="{{ $company_detail->legal_approve_by }}">
                    </div>
                @elseif ($company_detail->status_legal == 'REJECTED')
                    <label for="status" class="col-form-label col-sm-2">@lang("cms.Rejected By")</label>
                    <div class="col-sm-4">
                        <input type="text" readonly class="form-control" id="status" value="{{ $company_detail->legal_reject_by }}">
                    </div>
                @endif
            </div>

            <div class="form-group row">
                <label for="type" class="col-form-label col-sm-2">@lang("cms.Type")</label>
                <div class="col-sm-4">
                    <select class="select2 form-group" id="type" name="type" multiple disabled>
                        <option value="QRIS_EVENTS">@lang("cms.QRIS - Events")</option>
                        <option value="QRIS_OFFLINE_INDIVIDUAL">@lang("cms.QRIS - Offline Individual")</option>
                        <option value="QRIS_OFFLINE_CORPORATION">@lang("cms.QRIS - Offline Corporation")</option>
                        <option value="QRIS_INTEGRATION_INDIVIDUAL">@lang("cms.QRIS - Integration Individual")</option>
                        <option value="QRIS_INTEGRATION_CORPORATION">@lang("cms.QRIS - Integration Corporation")</option>
                        <option value="YUKKPG_INDIVIDUAL">@lang("cms.Payment Gateway YUKK (YUKKPG) - Individual")</option>
                        <option value="YUKKPG_CORPORATION">@lang("cms.Payment Gateway YUKK (YUKKPG) - Corporation")</option>
                        <option value="MERCHANTS_AGGREGATOR">@lang("cms.Merchants Aggregator")</option>
                        <option value="DISBURSEMENT_PAYROLL_YUKK_CASH">@lang("cms.Disbursement & Payroll Yukk Cash")</option>
                    </select>
                </div>

                @if (@$company_detail->status_legal == 'REJECTED')
                    <label for="reason" class="col-form-label col-sm-2">@lang("cms.Rejection Reason")</label>
                    <div class="col-sm-4">
                        <textarea disabled class="form-control" id="reason" name="reason">{{ $company_detail->legal_reject_remark }}</textarea>
                    </div>
                @endif
            </div>
            <div class="form-group row">
                <label for="risk_level" class="col-form-label col-sm-2">@lang("cms.Risk Level")</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="risk_level" value="{{ $company_detail->risk_level }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="status" class="col-form-label col-sm-2">@lang("cms.Status")</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="status" value="{{ $company_detail->status_legal }}">
                </div>
            </div>
        </div>

        <div class="card-header form-group row">
            <div class="card-title ml-2">
                <h4>@lang('cms.Document List')</h4>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.Name")</th>
                    <th>@lang("cms.Description")</th>
                    <th>@lang("cms.Created At")</th>
                    <th>@lang("cms.Actions")</th>
                </tr>
                </thead>

                <tbody>
                    @if($company_contract_list == [])
                            <tr>
                                <td class="text-center" colspan="4">
                                    No Contract Available
                                </td>
                            </tr>
                        @else
                        @foreach ($company_contract_list as $company_contract)
                            @php($checker = @isset($company_contract->file_url) ? '1' : '0')
                            <tr>
                                <td>{{ $company_contract->name }}</td>
                                <td>{{ $company_contract->description }}</td>
                                <td>{{ $company_contract->created_at }}</td>
                                @if ($checker == '1')
                                    <td class="text-center">
                                        <div class="d-flex col justify-content-center">
                                            <a class="dropdown-item btn-confirm d-flex border justify-content-center" href="{{ $company_contract->file_url }}" target="_blank" class="dropdown-item"><i class="icon-folder-download3"></i></a>
                                            @if ($company_detail->status_legal !== 'IN_REVIEW')
                                                <div class="border col-6">
                                                    <form method="POST" action="{{ route("yukk_co.company_contracts.destroy", $company_contract->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item btn-confirm d-flex justify-content-center"><i class="icon-bin"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                @else
                                    <td class="text-center">
                                        <div class="d-flex col justify-content-center">
                                            <a class="dropdown-item btn-confirm d-flex border justify-content-center" href="{{ $company_contract->path }}" target="_blank" class="dropdown-item"><i class="icon-folder-download3"></i></a>
                                            @if ($company_detail->status_legal !== 'IN_REVIEW')
                                                <div class="border col-6">
                                                    <form method="POST" action="{{ route("yukk_co.company_contracts.destroy", $company_contract->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item btn-confirm d-flex justify-content-center"><i class="icon-bin"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var x = @json($types);
            $("#type").val(x).trigger('change');

            $(".btn-confirm").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection

