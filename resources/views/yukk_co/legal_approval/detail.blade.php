@extends('layouts.master')

@section('header')
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Detail")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('cms.Home')</a>
                    <a href="{{ route("legal_approval.companies.index") }}" class="breadcrumb-item">@lang("cms.Legal Approval")</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="name" id="name" value="{{ @$company->name }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                <div class="col-sm-4">
                    <textarea type="text" class="form-control" name="description" id="description" disabled>{{ @$company->description }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Type")</label>
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
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Risk Level")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="risk_level" id="risk_level" readonly value="{{ @$company->risk_level }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">@lang("cms.Status")</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="status" id="status" disabled value="{{ @$company->status_legal }}">
                </div>
            </div>

            @if ($company->status_legal == 'REJECTED')
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">@lang("cms.Rejection Reason")</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reject_remark" id="reject_remark" disabled value="{{ @$company->legal_reject_remark }}">
                    </div>
                </div>
            @endif

            <div class="card-title mt-5">
                <h4>@lang('cms.Contract List')</h4>
            </div>

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
                    @if($contract_list == [])
                            <tr>
                                <td class="text-center" colspan="4">
                                    No Contract Available
                                </td>
                            </tr>
                        @else
                        @foreach ($contract_list as $company_contract)
                        @php($checker = @isset($company_contract->file_url) ? '1' : '0')
                            <tr>
                                <td>{{ $company_contract->name }}</td>
                                <td>{{ $company_contract->description }}</td>
                                <td>{{ $company_contract->created_at }}</td>
                                @if ($checker == '1')
                                    <td class="text-center">
                                        <div class="d-flex col justify-content-center">
                                            <a class="dropdown-item btn-confirm d-flex border justify-content-center" href="{{ $company_contract->file_url }}" target="_blank" class="dropdown-item"><i class="icon-folder-download3"></i></a>
                                            @if ($company->status_legal !== 'IN_REVIEW')
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
                                            @if ($company->status_legal !== 'IN_REVIEW')
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

            <div class="form-group mt-5">
                @if ($company->status_legal == 'IN_REVIEW')
                    <form method="POST" action="{{ route('legal_approval.companies.action') }}">
                        @csrf
                        <input type="hidden" id="hidden-id-reject" name="id" value="{{ $company->id }}">
                        <div class="d-flex justify-content-center mb-5 mt-3">
                            <button class="btn btn-danger border col-3 mx-auto py-2" type="button" data-toggle="modal" data-target="#modal-rejection-remark">@lang("cms.Reject")</button>
                            <a class="btn btn-primary btn-block col-3 mx-auto py-2 approve-btn" data-id="{{ $company->id }}" data-action="APPROVED" id="approve-btn">@lang("cms.Approve")</a>
                            <!-- <button class="btn btn-primary btn-block col-3 mx-auto py-2 approve-btn" id="btn-approve" name="action" type="submit" value="APPROVED">@lang("cms.Approve")</button> -->
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-rejection-remark" tabindex="-1" role="dialog" aria-labelledby="modal-rejection-remark" aria-hidden="true">
        <form id="modalForm" class="modal-dialog" method="POST" action="{{ route('legal_approval.companies.action') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoModalLabel">Rejection Remark</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" name="remark" class="form-control">
                    <input type="hidden" name="action" value="REJECTED">
                    <input type="hidden" id="hidden-id-reject" name="id" value="{{ $company->id }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submitForm" id="submitForm">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var x = @json($types);
            $("#type").val(x).trigger('change');

            $('.approve-btn').click(function(e) {
                e.preventDefault(); // Prevent default action immediately
                    let id = $(this).attr('data-id');
                    let action = $(this).attr('data-action');

                    $('body').after('<div class="loading"></div>');
                    $('.loading').append('<div class="spinner-border text-primary" role="status" id="loading_spinner"><span class="sr-only">Loading...</span></div>');

                    $.ajax({
                        type: "POST",
                        url: "{{ route('legal_approval.companies.action') }}",
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : id,
                            "action": action
                        },
                        success: function(data){
                            $('.loading').remove();
                            if(data.http_status_code == '200'){
                                Swal.fire({
                                    'text': "Update data Success",
                                    'icon': 'success',
                                    'toast': true,
                                    'timer': 2000,
                                    'showConfirmButton': false,
                                    'position': 'top-right',
                                });
                                window.location.href = "{{ route('legal_approval.companies.detail', ':id') }}".replace(':id', id);
                            }else{
                                Swal.fire({
                                    'text': "Failed to Update Data",
                                    'icon': 'warning',
                                    'toast': true,
                                    'timer': 2000,
                                    'showConfirmButton': false,
                                    'position': 'top-right',
                                });
                                window.location.href = "{{ route('legal_approval.companies.detail', ':id') }}".replace(':id', id);
                            }
                        },
                    });
            });     
            
            
        });
    </script>
@endsection

