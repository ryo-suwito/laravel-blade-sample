@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>DTTOT Approval Detail</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('cms.yukk_co.dttot_approval.list') }}" class="breadcrumb-item">DTTOT Approval</a>
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
            <h2>DTTOT Approval Detail</h2>
        </div>
        <div class="card-body">
            <form id="form" action="#" class="row" method="POST">
                @csrf
                @method('put')
                <div class="col-sm-12 col-lg-6">
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="">Name</label>
                            @if(isset($user['payloads']['names']))
                                @foreach ($user['payloads']['names'] as $name)
                                    <input type="text" readonly name="aliases[]" value="{{ $name }}" class="form-control">
                                @endforeach
                            @else
                               @if(isset($user['payloads']['name']))
                                    <input type="text" readonly name="aliases[]" value="{{ $user['payloads']['name'] }}" class="form-control">
                                @else
                                    @if(isset($user['reference']['names']))
                                        @foreach ($user['reference']['names'] as $name)
                                            <input type="text" readonly name="aliases[]" value="{{ $name }}" class="form-control">
                                        @endforeach
                                    @else
                                        <input type="text" readonly name="aliases[]" value="{{ $user['reference']['name'] }}" class="form-control">
                                    @endif
                                @endif
                            @endif
                        </div>
                        <div class="form-group col-12">
                            <label for="">Type</label>
                            <input type="text" readonly name="type" value="{{ @$user['payloads']['type'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Description</label>
                            <textarea name="description" readonly class="form-control" id="" cols="30" rows="10">{{ @$user['payloads']['description'] }}</textarea>
                        </div>
                        <div class="form-group col-12">
                            <label for="">Densus Code</label>
                            <input type="text" readonly name="densus_code" value="{{ @$user['payloads']['densus_code'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Latest Batch</label>
                            <input type="text" readonly name="batch" value="{{ @$user['payloads']['batch'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Place of Birth</label>
                            <input type="text" readonly name="place_of_birth" value="{{ @$user['payloads']['place_of_birth'] }}" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="">Date of Birth</label>
                            <input type="text" readonly name="date_of_birth" value="{{ @$user['payloads']['date_of_birth'] }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="row">
                        <div class="form-group col-12" style="margin-bottom: 0;">
                            <label for=""><strong>Identities</strong></label>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">

                        @if (isset($user['payloads']['identities'])
                            && count($user['payloads']['identities']) > 0
                            && isset($user['payloads']['identities'][0]['identity_type'])
                            )
                            @foreach ($user['payloads']['identities'] as $identity)
                                @if ($identity['identity_type'] != 'ALIAS')
                                    <div class="form-group col-4" style="margin-bottom: 0;">
                                        <label for="">{{ $identity['identity_type'] }}</label>
                                    </div>
                                    <div class="form-group col-8" style="margin-bottom: 0;">
                                        <label for="">{{ $identity['identity_value'] }}</label>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            @foreach ($user['payloads']['identities'] as $identity_type)
                                @foreach ($identity_type as $index => $identity)
                                    @if ($index != 'ALIAS')
                                    <div class="form-group col-4" style="margin-bottom: 0;">
                                        <label for="">{{ $index }}</label>
                                    </div>
                                    <div class="form-group col-8" style="margin-bottom: 0;">
                                        <label for="">{{ $identity }}</label>
                                    </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label for=""><strong>Change Request</strong></label>
                            <p>
                                {{ $user['action'] }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label for=""><strong>Approval Layer</strong></label>
                            <p>Current Status : {{ $user['status'] == '' ? 'PENDING' : $user['status'] }}</p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> Email </th>
                                        <th> Action </th>   
                                        <th> Date </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user['approvers'] as $approver)
                                        <tr>
                                            <td>{{ $approver['name'] }}</td>
                                            <td>{{ $approver['action'] == 'PENDING' || !$approver['action'] ? 'Not Yet' : $approver['action'] }}</td>
                                            <td>{{ $approver['created_at'] != null ? date('d/m/Y H:i:s', strtotime($approver['created_at'])) : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-12" style="text-align: center">
                    <div class="btn-group">
                        <a href="{{ route('cms.yukk_co.dttot_approval.list') }}" class="btn btn-dark">Cancel</a>
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
                data-attribute-action="{{ route('cms.yukk_co.dttot_approval.toggle', ':id') }}" class="modal-dialog"
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
    </div>
@endsection
@section('post_scripts')
    <script>
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
    </script>
@endsection
