@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Company Edit")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.companies.list") }}" class="breadcrumb-item">@lang("cms.Company List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Company Edit")</span>
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
                    <input type="text" id="company_name" class="form-control" name="company_name" value="{{ $company_detail->name }}">
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
                    <input type="text" class="form-control" id="description" name="description" value="{{ $company_detail->description }}">
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
                    <select class="select2 form-group" id="type" name="type[]" multiple>
                        <option value="QRIS_EVENTS" @if(old('type') == 'QRIS_EVENTS') selected @endif>@lang("cms.QRIS - Events")</option>
                        <option value="QRIS_OFFLINE_INDIVIDUAL" @if(old('type') == 'QRIS_OFFLINE_INDIVIDUAL') selected @endif>@lang("cms.QRIS - Offline Individual")</option>
                        <option value="QRIS_OFFLINE_CORPORATION" @if(old('type') == 'QRIS_OFFLINE_CORPORATION') selected @endif>@lang("cms.QRIS - Offline Corporation")</option>
                        <option value="QRIS_INTEGRATION_INDIVIDUAL" @if(old('type') == 'QRIS_INTEGRATION_INDIVIDUAL') selected @endif>@lang("cms.QRIS - Integration Individual")</option>
                        <option value="QRIS_INTEGRATION_CORPORATION" @if(old('type') == 'QRIS_INTEGRATION_CORPORATION') selected @endif>@lang("cms.QRIS - Integration Corporation")</option>
                        <option value="YUKKPG_INDIVIDUAL" @if(old('type') == 'YUKKPG_INDIVIDUAL') selected @endif>@lang("cms.Payment Gateway YUKK (YUKKPG) - Individual")</option>
                        <option value="YUKKPG_CORPORATION" @if(old('type') == 'YUKKPG_CORPORATION') selected @endif>@lang("cms.Payment Gateway YUKK (YUKKPG) - Corporation")</option>
                        <option value="MERCHANTS_AGGREGATOR" @if(old('type') == 'MERCHANTS_AGGREGATOR') selected @endif>@lang("cms.Merchants Aggregator")</option>
                        <option value="DISBURSEMENT_PAYROLL_YUKK_CASH" @if(old('type') == 'DISBURSEMENT_PAYROLL_YUKK_CASH') selected @endif>@lang("cms.Disbursement & Payroll Yukk Cash")</option>
                    </select>
                </div>

                @if ($company_detail->status_legal == 'REJECTED')
                    <label for="reason" class="col-form-label col-sm-2">@lang("cms.Rejection Reason")</label>
                    <div class="col-sm-4">
                        <textarea disabled class="form-control" id="reason" name="reason">{{ $company_detail->legal_reject_remark }}</textarea>
                    </div>
                @endif    
            </div>
            <div class="form-group row">
                <label for="risk_level" class="col-form-label col-sm-2">@lang("cms.Risk Level")</label>
                <div class="col-sm-4">
                    <select class="select2 form-group" id="risk_level" name="risk_level">
                        <option value="LOW" @if(old('risk_level') == 'LOW' || $company_detail->risk_level == 'LOW') selected @endif>@lang("cms.Low")</option>
                        <option value="MEDIUM" @if(old('risk_level') == 'MEDIUM' || $company_detail->risk_level == 'MEDIUM') selected @endif>@lang("cms.Medium")</option>
                        <option value="HIGH" @if(old('risk_level') == 'HIGH' || $company_detail->risk_level == 'HIGH') selected @endif>@lang("cms.High")</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="status" class="col-form-label col-sm-2">@lang("cms.Status")</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="status" value="{{ $company_detail->status_legal }}">
                </div>
            </div>
        </div>

        <div class="card-header d-flex justify-content-between">
            <div class="card-title">
                <h4>@lang('cms.Document List')</h4>
            </div>

            <div class="border-2">
                <button type="button" class="dropdown-item btn-primary" data-toggle="modal" data-target="#modal-company-contract"><i class="icon-plus2"></i> @lang("cms.Add")</button>
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
                                No Document Available
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

        <form method="post" action="{{ route("yukk_co.companies.update", $company_detail->id) }}">
            @csrf
            <input id="hidden-company-name" name="company_name" type="hidden" value="{{ $company_detail->name }}">
            <input id="hidden-company-description" name="description" type="hidden" value="{{ $company_detail->description }}">
            <div hidden>
                <select class="select2 form-group" id="hidden-company-type" name="type[]" multiple hidden>
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
            <div hidden>
                <input type="text" id="hidden-company-risk-level" name="risk_level" value="{{ $company_detail->risk_level }}">
            </div>
            @if($company_detail->status_legal == 'REJECTED')
                <div class="d-flex justify-content-center mt-5 mb-5">
                    <button class="btn btn-primary btn-block col-3 mx-auto py-2" name="button" type="submit" value="SUBMIT">@lang("cms.Save & Submit")</button>
                </div>
            @elseif($company_detail->status_legal == 'NEW' || $company_detail->status_legal == '')
                <div class="d-flex justify-content-center mt-5 mb-5">
                    <button class="btn border border-primary col-3 mx-auto py-2" name="button" type="submit" value="SAVE_AS_DRAFT">@lang("cms.Save")</button>
                    <button class="btn btn-primary btn-block col-3 mx-auto py-2" name="button" type="submit" value="SUBMIT">@lang("cms.Save & Submit")</button>
                </div>
            @elseif($company_detail->status_legal == 'APPROVED')
                <div class="d-flex justify-content-center mt-5 mb-5">
                    <button class="btn border border-primary col-3 mx-auto py-2" name="button" type="submit" value="SAVE_AS_DRAFT">@lang("cms.Save")</button>
                </div>
            @endif
        </form>
    </div>

    <div id="modal-company-contract" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
        <form method="post" enctype="multipart/form-data" action="{{ route("yukk_co.companies.store_contract", $company_detail->id) }}">
            @csrf
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang("cms.Add")</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <input type="hidden" name="company_id" value="{{ $company_detail->id }}">

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Document Name")</label>
                            <div class="col-lg-8">
                                <select class="select2 form-group" id="name" name="name">
                                    <option selected disabled>@lang('cms.Select One')</option>
                                    <option value="PKS">PKS</option>
                                    <option value="NIB">NIB</option>
                                    <option value="SK Kemenkumham">SK Kemenkumham</option>
                                    <option value="Akta Perusahaan">Akta Perusahaan</option>
                                    <option value="Other Supporting Documents">Other Supporting Documents</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Document Description")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="description">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Document Type</label>
                            <div class="col-lg-8">
                                 <select class="select2 form-group" id="contract_type" name="contract_type">
                                    <option value="FILE" selected>File</option>
                                    <option value="LINK">Link</option>
                                </select>
                            </div>
                        </div>
                        <div id="contract_file" class="form-group row" hidden>
                            <label class="col-lg-4 col-form-label">@lang("cms.Document")</label>
                            <div class="col-lg-8">
                                <input type="file" class="form-control" id="document-file" name="attachment">
                            </div>
                        </div>
                        <div id="contract_text" class="form-group row" hidden>
                            <label class="col-lg-4 col-form-label">@lang("cms.Document")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="document-text" name="attachment">
                                <span id="error-text" class="text-danger"></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mb-2 mt-4">
                        <button class="btn btn-primary btn-block col-3" id="btn-submit" type="submit">@lang("cms.Submit")</button>
                    </div>
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
            $("#hidden-company-type").val(x).trigger('change');

            $('#company_name').on('keyup', function() {
                $('#hidden-company-name').val($(this).val());
            }).change();

            $('#description').on('keyup', function(){
                document.getElementById('hidden-company-description').value = document.getElementById('description').value;
            }).change();

            $('#type').on('select2:select select2:unselect', function(e) {
                // Get selected values from dropdown1
                let selectedValues = $('#type').val(); // Returns an array of selected values

                $('#hidden-company-type').val(selectedValues).trigger('change');
            });

            $('#risk_level').on('select2:select select2:unselect', function(e) {
                document.getElementById('hidden-company-risk-level').value = document.getElementById('risk_level').value;
            }).change();

            function updateContractFields() {
                let x = $("#contract_type").val();
                
                if(x == 'LINK'){
                    $('#contract_text').removeAttr('hidden');
                    $('#contract_file').attr('hidden', 'hidden');

                    $('#document-file').removeAttr('required');
                    $('#document-text').attr('required', 'required');
                } else {
                    $('#contract_text').attr('hidden', 'hidden');
                    $('#contract_file').removeAttr('hidden');

                    $('#document-file').attr('required', 'required');
                    $('#document-text').removeAttr('required');
                }
            }

            $("#contract_type").on('select2:select', updateContractFields);
            $("#contract_type").on('change', updateContractFields);

            // Trigger initially to set correct state
            updateContractFields();

            $(".btn-confirm").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection

