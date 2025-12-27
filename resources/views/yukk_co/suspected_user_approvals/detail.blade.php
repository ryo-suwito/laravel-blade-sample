@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>Suspected User Approval Detail</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('cms.yukk_co.suspected_user_approval.list') }}" class="breadcrumb-item">Suspected User Approval</a>
                    <a href="#" class="breadcrumb-item active">@lang('cms.Detail')</a>
                </div>

                <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Detail Suspected User Approval</h2>
        </div>
        <div class="card-body">
            <form id="form" action="#" class="row" method="POST">
                @csrf
                @method('put')
                <div class="col-sm-12 col-lg-6">
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="">Name</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" 
                                value="{{ $user['reference']['reference']['name'] }}" 
                            class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Source</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" value="{{ $source_type }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Source ID</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" value="{{ $user['reference']['source_id'] }}" class="form-control">
                        </div>
                        @if ($source_type != 'CUSTOMER')
                        <div class="form-group col-4">
                            <label for="">YUKK ID</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" 
                                value="{{ $source_type == 'USER' ? $user['reference']['additional']['yukk_id'] : $user['reference']['id']}}" 
                            class="form-control">
                        </div>
                        @endif
                        <div class="form-group col-4">
                            <label for="">KTP no</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" value="{{ $user['reference']['identity_value'] }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">KTP Image</label>
                        </div>
                        <div class="form-group col-8">
                            <!-- file picker with square preview if identity_image exist -->
                            @if (!empty($user['reference']['identity_image']))
                                <div class="image-preview">
                                    <img src="{{ $user['reference']['identity_image'] }}" alt="KTP Image" class="img-fluid">
                                </div>
                            @endif

                        </div> 
                        @if ($source_type == 'CUSTOMER')
                            <div class="form-group col-4">
                                <label for="">NPWP no</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="name" value="{{  $user['reference']['taxpayer_identity_number']  }}" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <label for="">NPWP Image</label>
                            </div>
                            <div class="form-group col-8">
                                <!-- file picker with square preview if identity_image exist -->
                                @if ($source_type == 'CUSTOMER')
                                    @if (!empty($user['reference']['taxpayer_identity_image']))
                                        <div class="image-preview">
                                            <img src="{{ $user['reference']['taxpayer_identity_image'] }}" alt="NPWP Image" class="img-fluid">
                                        </div>
                                    @endif
                                @endif
                            </div>        
                        @endif
                        
                        <!-- email -->
                        <div class="form-group col-4">
                            <label for="">Email</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="email" value="{{ $user['reference']['email'] }}" class="form-control">
                        </div>
                        <!-- phone no -->
                        <div class="form-group col-4">
                            <label for="">Phone No</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="phone_no" value="{{ $user['reference']['phone_number'] }}" class="form-control">
                        </div>
                        
                        @if ($source_type == 'CUSTOMER')
                            <div class="form-group col-12">
                                <h3> Beneficiary Information </h3>
                            </div>
                            <div class="form-group col-4">
                                <label for="">Bank Name</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="bank_name" value="{{ $user['reference']['additional']['bank_name'] }}" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <label for="">Bank Branch Name</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="branch_name" value="{{ $user['reference']['additional']['branch_name'] }}" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <label for="">Account No</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="account_number" value="{{ $user['reference']['additional']['account_number'] }}" class="form-control">
                            </div>
                            @if(isset($user['reference']['additional']['account_name']))
                                <div class="form-group col-4">
                                    <label for="">Account Name</label>
                                </div>
                                <div class="form-group col-8">
                                    <input type="text" readonly name="account_name" value="{{ $user['reference']['additional']['account_name'] }}" class="form-control">
                                </div>
                            @endif
                            <div class="form-group col-4">
                                <label for="">City</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="city" value="{{ $user['reference']['additional']['city'] }}" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <label for="">Address</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="address" value="{{ $user['reference']['additional']['address'] }}" class="form-control">
                            </div>
                        @endif
                        <style>
                                .image-preview {
                                    width: 100%;
                                    height: auto;
                                    overflow: hidden;
                                    border: 1px solid #ddd;
                                    border-radius: 5px;
                                    padding: 5px;
                                }

                                .image-preview img {
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                }
                        </style>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="row">
                        <div class="form-group col-12">
                            <h3> DTTOT Match </h3>
                        </div>
                        <div class="form-group col-4">
                            <label for="">Name</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" value="{{ 
                                $user['reference']['reference']['name']
                             }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Densus Code</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="densus_code" value="{{
                                $user['reference']['reference']['densus_code']
                            }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">DTTOT BATCH</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="batch" value="{{ 
                                $user['reference']['reference']['batch']
                             }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Matching Attribute</label>
                        </div>
                        <div class="form-group col-8">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Attribute</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user['reference']['matching_attribute'] as $index => $score)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $score }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-4">
                            <label for="">Total Score</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="score" value="{{ 
                                $user['reference']['score']
                             }}" class="form-control">
                        </div>
                        <hr style="width: 100%; color: white; height: 1px;">
                        <div class="form-group col-12">
                            <h3> Change Request </h3>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="">Request</label>
                                </div>
                                <div class="form-group col-8">
                                    <input type="text" readonly name="action" value="{{ $user['action'] }}" class="form-control">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Reason</label>
                                </div>
                                <div class="form-group col-8">
                                    <input type="text" readonly name="reason" value="{{ isset($user['payloads']['reason']) ? $user['payloads']['reason'] : '' }}" class="form-control">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Supporting Documents</label>
                                </div>
                                <div class="form-group col-8">
                                    @if (isset($user['documents']))
                                    @foreach ($user['documents'] as $document)
                                        {{ substr($document, strrpos($document, '/') + 1) }}
                                        <!-- button icon download -->
                                        <a href="{{ $document }}" onclick="downloadFile(event, '{{ $document }}')"
                                         style="float: right;">
                                            <i class="icon-download4"></i>
                                        </a>
                                        <br/>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr style="width: 100%; color: white; height: 1px;">
                        <div class="form-group col-12">
                            <h3> Approval Layer </h3>
                        </div>
                        <div class="form-group col-12">
                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="">Current Status</label>
                                </div>
                                <div class="form-group col-8">
                                    <input type="text" readonly name="status" value="{{ $user['status'] }}" class="form-control">
                                </div>
                                <div class="form-group col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Email</th>
                                                <th>Action</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user['approvers'] as $approval)
                                                <tr>
                                                    <td>{{ $approval['name'] }}</td>
                                                    <td>{{ $approval['action'] }}</td>
                                                    <td>{{ $approval['created_at'] ? date('d M Y H:i:s', strtotime($approval['created_at'])) : '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>



        <div class="card-body">
            <div class="row">
                <div class="col-12" style="text-align: center">
                    <div class="btn-group">
                        <a href="{{ route('cms.yukk_co.suspected_user_approval.list') }}" class="btn btn-dark">Cancel</a>
                        @if ($user['is_acted'] == false && $can_edit)
                        <button type="button" class="btn btn-success" onclick="onApprovalToggleConfirmation(event, '{{ $user['id'] }}', '{{ $user['status'] }}')">Approve</button>
                        <button type="button" class="btn btn-danger" onclick="onRejectionToggleConfirmation(event, '{{ $user['id'] }}', '{{ $user['status'] }}')">Reject</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-approveReject-toggle" tabindex="-1" role="dialog"
            aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-approveReject-form"
                data-attribute-action="{{ route('cms.yukk_co.suspected_user_approval.toggle', ':id') }}" class="modal-dialog"
                role="document" method="post" action="">
                @csrf
                <!-- input ids[] for multiple ids -->
                <input id="modal-approveReject-input" name="ids" type="hidden" value="">
                <input id="modal-approveReject-approveOrReject" name="approveOrReject" type="hidden" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Change Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="modal-approveReject-message"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnapproveReject"></button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal fade" id="modal-confirmation-toggle" tabindex="-1" role="dialog"
            aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirmation-form"
                data-attribute-action="{{ route('cms.yukk_co.suspected_user_approval.update', ':id') }}" class="modal-dialog"
                role="document" method="post" action="">
                @csrf
                <input id="modal-confirmation-input" name="id" type="hidden" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p style="text-align:center"> Change Status From <br/>
                        <select name="currentStatus" id="modal-confirmation-status" class="form-control" style="margin-bottom: 10px;">xs
                        </select>
                        <p style="text-align:center"> To <br/>
                        <select name="action" id="modal-confirmation-new-status" class="form-control" style="margin-bottom: 10px;">
                            <option value="PENDING_APPROVE">PENDING_APPROVE</option>
                            <option value="SUSPECTED">SUSPECTED</option>
                            <option value="BLOCKED">BLOCKED</option>
                            <option value="RELEASED">RELEASED</option>
                        </select>
                        <hr/>
                        <label class="form-label">Reason</label> <br/>
                        <textarea name="reason" id="" cols="30" rows="5" class="form-control" style="margin-bottom: 10px;"></textarea>
                        <!-- supporting documents / file -->
                        <label class="form-label">Supporting Documents</label> <br/>
                        <input type="file" name="files[]" id="supportingFiles" class="form-control" onchange="onFileSelected(event)" multiple> <br/>
                        <p id="selectedFiles"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('post_scripts')
    <script>        
        function onActivationToggleConfirmation(event, id, status="") {
            // add status to modal confirmation
            $('#modal-confirmation-status').html('<option value="'+status+'">'+status+'</option>');
            let newStatus = $('#statusSelected').val();
            $('#modal-confirmation-new-status').val(newStatus);

            $('#modal-confirmation-input').val(id)

            let action = $('#modal-confirmation-form').attr('data-attribute-action')
            $('#modal-confirmation-form').attr('action', action.replace(':id', id))

            $('#modal-confirmation-toggle').modal('toggle')
        }
        // on approval toggle confirmation using modal-approveReject-toggle
        function onApprovalToggleConfirmation(event, id, status="") {
            // show modal approveReject toggle
            $('#modal-approveReject-toggle').modal('toggle')
            // add status to modal confirmation
            $('#modal-approveReject-message').html('Are you sure want to approve this user?');
            $('#modal-approveReject-approveOrReject').val('APPROVED');
            $('#modal-approveReject-input').val(id)

            let action = $('#modal-approveReject-form').attr('data-attribute-action')
            $('#modal-approveReject-form').attr('action', action.replace(':id', id))

            $('#btnapproveReject').html('Approve')
        }

        // on rejection toggle confirmation using modal-approveReject-toggle
        function onRejectionToggleConfirmation(event, id, status="") {
            // show modal approveReject toggle
            $('#modal-approveReject-toggle').modal('toggle')
            // add status to modal confirmation
            $('#modal-approveReject-message').html('Are you sure want to reject this user?');
            $('#modal-approveReject-approveOrReject').val('REJECTED');
            $('#modal-approveReject-input').val(id)

            let action = $('#modal-approveReject-form').attr('data-attribute-action')
            $('#modal-approveReject-form').attr('action', action.replace(':id', id))

            $('#btnapproveReject').html('Reject')
        }
        // downloadFile
        function downloadFile(event, url) {
            event.preventDefault();
            var redirectWindow = window.open(url, '_blank');
            redirectWindow.location;
        }
    </script>
@endsection
