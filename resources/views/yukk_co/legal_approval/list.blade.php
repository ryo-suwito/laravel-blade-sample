@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Legal Approval - Companies")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item">@lang("cms.Legal Approval")</span>
                    <span class="breadcrumb-item active">@lang("cms.Companies")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

<style>
    .card-pending {
        background-color: #F3B664;
        color: #000000;
    }

    .card-approved {
        background-color: #9FBB73;
        color: #000000;
    }

    .card-rejected {
        background-color: #FA7070;
        color: #000000;
    }

    .btn-action {
        width: 120px;
    }

    form {
        margin-block-end: 0; !important
    }
</style>

@section('content')
    <div class="card mt-4">
        <div class="card-header form-group row text-center">
            <div class="col m-1">
                <div class="border pt-2 card-pending">
                    <h4> {{ @$review }} </h4>
                    <h5>@lang('cms.TOTAL IN REVIEW')</h5>
                </div>
            </div>
            <div class="col m-1">
                <div class="border pt-2 card-approved">
                    <h4> {{ @$approve }} </h4>
                    <h5>@lang('cms.TOTAL APPROVED')</h5>
                </div>
            </div>
            <div class="col m-1">
                <div class="border pt-2 card-rejected">
                    <h4> {{ @$reject }} </h4>
                    <h5>@lang('cms.TOTAL REJECTED')</h5>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form method="get" action="{{ route('legal_approval.companies.index') }}">
                <div class="d-flex justify-content-between">
                    <div class="row w-50">
                        <div class="col w-25">
                            <select class="form-control select2" name="status" id="status" onchange='if(this.value != 0) { this.form.submit(); }'>
                                <option disabled selected>Select One</option>
                                <option value="ALL" @if($status == 'ALL') selected @endif>ALL</option>
                                <option value="REJECTED" @if($status == 'REJECTED') selected @endif>REJECTED</option>
                                <option value="APPROVED" @if($status == 'APPROVED') selected @endif>APPROVED</option>
                                <option value="IN_REVIEW" @if($status == 'IN_REVIEW') selected @endif>IN REVIEW</option>
                            </select>
                        </div>
                        <div class="col w-25">
                            <select class="form-control select2" name="field" id="field">
                                <option value="name" @if($field == 'name') selected @endif>Name</option>
                                <option value="type" @if($field == 'type') selected @endif>@lang('cms.Type')</option>
                            </select>
                        </div>
                        <div class="col w-50">
                            <input class="form-control" name="search" id="search" value="{{ $search }}" onchange='if(this.value != 0) { this.form.submit(); }'>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-control select2" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                                <option @if($per_page == 10) selected @endif>10</option>
                                <option @if($per_page == 25) selected @endif>25</option>
                                <option @if($per_page == 50) selected @endif>50</option>
                                <option @if($per_page == 100) selected @endif>100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <form method="POST" action="{{ route('legal_approval.companies.bulk_action') }}">
                @csrf
                <div class="form-group pt-2">
                    <button id="btn-reject" class="btn btn-danger btn-action btn-bulk-reject mr-2" name="action" type="button" data-toggle="modal" data-target="#modal-bulk-rejection-remark" disabled>@lang("cms.Bulk Reject")</button>
                    <button id="btn-approve" class="btn btn-primary btn-action submitForm" name="action" value="APPROVED" type="submit" disabled>@lang("cms.Bulk Approve")</button>
                </div>
            
                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" id="select-all"></input></th>
                            <th class="text-center">@lang('cms.ID')</th>
                            <th class="text-center">@lang('cms.Name')</th>
                            <th class="text-center">@lang('cms.Type')</th>
                            <th class="text-center">@lang('cms.Request By')</th>
                            <th class="text-center">@lang('cms.Updated By')</th>
                            <th class="text-center">@lang('cms.Status')</th>
                            <th class="text-center">@lang('cms.Created At')</th>
                            <th class="text-center">@lang('cms.Updated At')</th>
                            <th class="text-center">@lang('cms.Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($companies)
                            @foreach ($companies as $company)
                                <tr>
                                    <td class="text-center">
                                        @if ($company->status_legal == 'APPROVED' || $company->status_legal == 'REJECTED')
                                            <input type="checkbox" disabled id="company-{{ @$company->id }}"></td>
                                        @else
                                            <input type="checkbox" class="checkbox-company" name="checkbox[{{ $company->id }}]" id="{{ @$company->id }}">
                                        @endif
                                    </td>
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
                                    <td class="text-center">{{ @$company->review_submit_by }}</td>
                                    <td class="text-center">
                                        @if ($company->status_legal == 'APPROVED')
                                            {{ @$company->legal_approve_by }}
                                        @elseif ($company->status_legal == 'REJECTED')
                                            {{ @$company->legal_reject_by }}
                                        @endif
                                    </td>
                                    @if ($company->status_legal == 'IN_REVIEW')
                                        <td class="text-center"><span class="badge badge-warning">IN REVIEW</span></td>
                                    @elseif ($company->status_legal == 'APPROVED')
                                        <td class="text-center"><span class="badge badge-success">{{ @$company->status_legal }}</span></td>
                                    @elseif ($company->status_legal == 'REJECTED')
                                        <td class="text-center"><span class="badge badge-danger">{{ @$company->status_legal }}</span></td>
                                    @endif
                                    <td class="text-center">{{ @$company->last_review_submitted_at }}</td>
                                    <td class="text-center">
                                        @if ($company->status_legal == 'APPROVED')
                                            {{ @$company->last_legal_approved_at }}
                                        @elseif ($company->status_legal == 'REJECTED')
                                            {{ @$company->last_legal_rejected_at }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('legal_approval.companies.detail', $company->id) }}"  class="form-control dropdown-item">@lang("cms.Detail")</a>
                                                    @if ($company->status_legal == 'IN_REVIEW')
                                                        <a class="form-control dropdown-item approve-btn" data-id="{{ $company->id }}" data-action="APPROVED" id="approve-btn">@lang("cms.Approve")</a>
                                                        <a href="#" data-id="{{ $company->id }}" data-target="#modal-rejection-remark" class="form-control dropdown-item reject-btn">@lang("cms.Reject")</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="10" class="text-center">No Data Found!</td>
                        </tr>
                        @endif
                       
                    </tbody>
                </table>
            </form>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    {{ 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries' }}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("legal_approval.companies.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
                            </li>
                        @endif
                        @if ($current_page - $plus_minus_range > 1)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                            @if ($i == $current_page)
                                <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route("legal_approval.companies.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page + $plus_minus_range < $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-right13"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("legal_approval.companies.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
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
                    <input type="hidden" id="hidden-id-reject" name="id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submitForm">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="modal-bulk-rejection-remark" tabindex="-1" role="dialog" aria-labelledby="modal-bulk-rejection-remark" aria-hidden="true">
        <form id="modalForm" class="modal-dialog" method="POST" action="{{ route('legal_approval.companies.bulk_action') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoModalLabel">Rejection Remark</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="rejection-modal">

                </div>
                <div class="modal-body">
                    <input type="text" name="remark" class="form-control">
                    <input type="hidden" name="action" value="REJECTED">
                    <!-- <input type="hidden" id="hidden-id-bulk-reject" name="checkbox[]"> -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submitForm">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
    
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function countChecked() {
                const checkboxes = document.querySelectorAll('.checkbox-company');
                
                // Initialize the counter
                let checkedCount = 0;

                // Iterate through the checkboxes and count the checked ones
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        checkedCount++;
                    }
                })

                return checkedCount;
            };

            $('#select-all').click(function(event) {
                if(this.checked) {
                    $(':checkbox').each(function() {
                        $('#rejection-modal').append('<input type="hidden" id="' + this.id + '" name=checkbox[' + this.id + ']>')
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        $('#rejection-modal').remove()
                        this.checked = false;
                    });
                }
            });

            $(':checkbox').change(function(e) {
                let checker = countChecked();
                
                if(this.checked) {
                    $('#rejection-modal').append('<input type="hidden" id="' + this.id + '" name=checkbox[' + this.id + ']>')
                    $('#btn-reject').removeAttr('disabled')
                    $('#btn-approve').removeAttr('disabled')
                }else{
                    if(checker == 0){
                        $('#btn-reject').attr('disabled', true)
                        $('#btn-approve').attr('disabled', true)
                    }
                    $('#rejection-modal').remove()
                }
            })

            $('.reject-btn').click(function(e){
                e.preventDefault();
                let id = $(this).attr('data-id');

                $('#modal-rejection-remark').modal('show');
                $('#hidden-id-reject').val(id);
            });

            $('.submitForm').click(function(e){
                if (confirm("@lang("cms.general_confirmation_dialog_content")")) {
                    
                }else{
                    e.preventDefault();
                }
            });

            $('.approve-btn').click(function(e) {
                e.preventDefault(); // Prevent default action immediately
                
                if (confirm("@lang("cms.general_confirmation_dialog_content")")) {
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
                                window.location.href = '{{ route('legal_approval.companies.index') }}';
                            }else{
                                Swal.fire({
                                    'text': "Failed to Update Data",
                                    'icon': 'warning',
                                    'toast': true,
                                    'timer': 2000,
                                    'showConfirmButton': false,
                                    'position': 'top-right',
                                });
                                window.location.href = '{{ route('legal_approval.companies.index') }}';
                            }
                        },
                    });
                }
            });     
        });
    </script>
@endsection
