@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Company List")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Company List")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <form method="get" action="{{ route('yukk_co.companies.list') }}">
            <div class="card-header d-flex justify-content-between">
                <div class="row">
                    <div class="col">
                        <select class="form-control select2" name="field" id="field">
                            <option value="name" @if($field == 'name') selected @endif>@lang('cms.Name')</option>
                            <option value="status" @if($field == 'status') selected @endif>@lang('cms.Status')</option>
                            <option value="type" @if($field == 'type') selected @endif>@lang('cms.Type')</option>
                        </select>
                    </div>
                    <div class="col">
                        <input class="form-control" name="search" id="search" value="{{ $search }}" onchange='if(this.value != 0) { this.form.submit(); }'>
                    </div>
                    <div class="col">
                        <select class="form-control select2" name="risk_level" id="risk_level" onchange='if(this.value != 0) { this.form.submit(); }'>
                            <option value="ALL" selected>@lang('cms.Risk Level')</option>
                            <option value="LOW" @if($risk_level == 'LOW') selected @endif>@lang('cms.Low')</option>
                            <option value="MEDIUM" @if($risk_level == 'MEDIUM') selected @endif>@lang('cms.Medium')</option>
                            <option value="HIGH" @if($risk_level == 'HIGH') selected @endif>@lang('cms.High')</option>
                        </select>
                    </div>
                </div>
                @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl('MASTER_DATA.COMPANY.UPDATE'))
                    <div class="col-2 border">
                        <button type="button" class="dropdown-item btn-primary" data-toggle="modal" data-target="#modal-add-company"><i class="icon-add"></i>@lang("cms.Add Company")</button>
                    </div>
                @endif
            </div>
            <div class="card-header d-flex justify-content-end mr-2">
                <div class="row">
                    <select class="form-control select2" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                        <option @if($per_page == 10) selected @endif>10</option>
                        <option @if($per_page == 25) selected @endif>25</option>
                        <option @if($per_page == 50) selected @endif>50</option>
                        <option @if($per_page == 100) selected @endif>100</option>
                    </select>
                </div> 
            </div>
        </form>

        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>@lang("cms.ID")</th>
                        <th>@lang("cms.Name")</th>
                        <th>@lang("cms.Type")</th>
                        <th>@lang("cms.Risk Level")</th>
                        <th>@lang("cms.Status")</th>
                        <th>@lang("cms.Created At")</th>
                        <th>@lang("cms.Updated At")</th>
                        <th>@lang("cms.Actions")</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr>
                            <td class="text-center">{{ @$company->id }}</td>
                            <td class="text-center">{{ @$company->name }}</td>
                            @php($types = json_decode($company->type))
                            <td class="text-center">
                                @if ($types)
                                    @foreach ($types as $type)
                                        <span class="badge badge-primary my-1">{{ @$type }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                @if($company->risk_level == 'LOW')
                                    <span>@lang('cms.Low')</span>
                                @elseif($company->risk_level == 'MEDIUM')
                                    <span>@lang('cms.Medium')</span>
                                @elseif($company->risk_level == 'HIGH')
                                    <span>@lang('cms.High')</span>
                                @endif
                            </td>
                            @if ($company->status_legal == 'IN_REVIEW')
                                <td class="text-center"><span class="badge badge-warning">IN REVIEW</span></td>
                            @elseif ($company->status_legal == 'APPROVED')
                                <td class="text-center"><span class="badge badge-success">{{ @$company->status_legal }}</span></td>
                            @elseif ($company->status_legal == 'REJECTED')
                                <td class="text-center"><span class="badge badge-danger">{{ @$company->status_legal }}</span></td>
                            @else
                                <td class="text-center"><span class="badge badge-primary">{{ @$company->status_legal }}</span></td>
                            @endif
                            <td class="text-center">{{ @$company->created_at }}</td>
                            <td class="text-center">{{ @$company->updated_at }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        @if($company->status_legal == 'REJECTED' || $company->status_legal == 'NEW' || $company->status_legal == 'APPROVED' || $company->status_legal == '')
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(in_array('MASTER_DATA.COMPANY.UPDATE', $access_control))
                                                    <a href="{{ route("yukk_co.companies.edit", $company->id) }}"class="dropdown-item"><i class="icon-search4"></i>Edit </a>
                                                @endif
                                                @if(in_array('MASTER_DATA.COMPANY.VIEW', $access_control))
                                                    <a href="{{ route("yukk_co.companies.show", $company->id) }}"class="dropdown-item"><i class="icon-zoomin3"></i>Detail </a>
                                                @endif
                                                @if(in_array('MASTER_DATA.COMPANY.UPDATE', $access_control))
                                                    <a href="{{route("yukk_co.companies.destroy", $company->id) }}" class="dropdown-item btn-delete" id="btn-delete"><i class="icon-trash"></i>Delete</a>
                                                @endif
                                            </div>
                                        @elseif($company->status_legal == 'IN_REVIEW')
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(in_array('MASTER_DATA.COMPANY.VIEW', $access_control))
                                                    <a href="{{ route("yukk_co.companies.show", $company->id) }}"class="dropdown-item"><i class="icon-zoomin3"></i>Detail </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer justify-content-between">
            <div class="row">
                <div class="form-group">
                    {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
                </div>
            </div>
            <div class="col">
                <ul class="pagination pagination-flat justify-content-end">
                    @php($plus_minus_range = 3)
                    @if ($current_page == 1)
                        <li class="page-item disabled"><a href="#" class="page-link"><i
                                    class="icon-arrow-left12"></i></a></li>
                    @else
                        <li class="page-item">
                            <a href="{{ route('yukk_co.companies.list', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                class="page-link"><i class="icon-arrow-left12"></i></a>
                        </li>
                    @endif
                    @if ($current_page - $plus_minus_range > 1)
                        <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                    @endif
                    @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                        @if ($i == $current_page)
                            <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('yukk_co.companies.list', array_merge(request()->all(), ['page' => $i])) }}"
                                    class="page-link">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor
                    @if ($current_page + $plus_minus_range < $last_page)
                        <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                    @endif
                    @if ($current_page == $last_page)
                        <li class="page-item disabled"><a href="#" class="page-link"><i
                                    class="icon-arrow-right13"></i></a></li>
                    @else
                        <li class="page-item">
                            <a href="{{ route('yukk_co.companies.list', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                class="page-link">
                                <i class="icon-arrow-right13"></i>
                            </a>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>

    <div id="modal-add-company" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
        <form method="post" enctype="multipart/form-data" action="{{ route('yukk_co.companies.add') }}">
            @csrf
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang("cms.Add Company")</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Name")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Description")</label>
                            <div class="col-lg-8">
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Type")</label>
                            <div class="col-sm-8">
                                <select class="select2 form-group" id="type" name="type[]" multiple>
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
                            <label for="risk_level" class="col-form-label col-lg-4">@lang("cms.Risk Level")</label>
                            <div class="col-sm-8">
                                <select class="select2 form-group" id="risk_level" name="risk_level">
                                    <option value="LOW" @if(old('risk_level') == 'LOW') selected @endif>@lang("cms.Low")</option>
                                    <option value="MEDIUM" @if(old('risk_level') == 'MEDIUM') selected @endif>@lang("cms.Medium")</option>
                                    <option value="HIGH" @if(old('risk_level') == 'HIGH') selected @endif>@lang("cms.High")</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contract_name" class="col-lg-4 col-form-label">@lang("cms.Document Name")</label>
                            <div class="col-lg-8">
                                <select class="select2 form-group" id="contract_name" name="contract_name">
                                    <option value="" selected disabled>@lang('cms.Select One')</option>
                                    <option value="PKS">PKS</option>
                                    <option value="NIB">NIB</option>
                                    <option value="SK Kemenkumham">SK Kemenkumham</option>
                                    <option value="Akta Perusahaan">Akta Perusahaan</option>
                                    <option value="Other Supporting Documents">Other Supporting Documents</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contract_description" class="col-lg-4 col-form-label">@lang("cms.Document Description")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="contract_description" name="contract_description">
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
                                <input type="file" class="form-control" id="document-file" name="company_contract">
                            </div>
                        </div>
                        <div id="contract_text" class="form-group row" hidden>
                            <label class="col-lg-4 col-form-label">@lang("cms.Document")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="document-text" name="company_contract">
                                <span id="error-text" class="text-danger"></span>
                            </div>
                        </div> 
                    </div>

                    <div class="d-flex justify-content-center mb-4 mt-4">
                        <button id="save" class="btn border-primary col-3 mx-auto py-2" name="button" type="submit" value="SAVE_AS_DRAFT">@lang("cms.Save")</button>
                        <button id="submit" class="btn btn-primary btn-block col-3 mx-auto py-2" name="button" type="submit" value="SUBMIT">@lang("cms.Save & Submit")</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#contract_name').select2();

            $("#btn-delete").click(function(e) {
                if (confirm("@lang("cms.general_confirmation_dialog_content")")) {
                   
                }else{
                    e.preventDefault();
                }
            });

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
            $("#contract_type").change();

            $("#save").click(function(e){
                $('#name').attr('required', 'required');
                $('#type').attr('required', 'required');   
                
                const fieldsToRemoveRequired = ['contract_name', 'contract_description', 'contract_type'];
                fieldsToRemoveRequired.forEach(fieldId => {
                    const element = document.getElementById(fieldId) || $(`#${fieldId}`);
                    if (element) {
                        element.removeAttribute('required');
                    }
                });
            })

            $('#submit').click(function(e) {
                $('#name').attr('required', 'required');
                $('#type').attr('required', 'required');

                const fieldsToSetRequired = ['contract_name', 'contract_description', 'contract_type'];
                fieldsToSetRequired.forEach(fieldId => {
                    const element = document.getElementById(fieldId) || $(`#${fieldId}`);
                    if (element) {
                        element.setAttribute('required', 'required');
                    }
                });
            })
        });
    </script>
@endsection
