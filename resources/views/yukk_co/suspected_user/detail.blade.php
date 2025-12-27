@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>Suspected User Detail</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <a href="{{ route('cms.yukk_co.suspected_user.list') }}" class="breadcrumb-item">Suspected User</a>
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
            <h2>Suspected User</h2>
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
                                value="{{  $user['name'] }}" 
                            class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Source</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" value="{{ $user['source_type'] == 'USER' ? 'YUKK APP' : 'BENEFICIARY' }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Source ID</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" value="{{ $user['source_id'] }}" class="form-control">
                        </div>
                        @if ($user['source_type'] != 'CUSTOMER')
                        <div class="form-group col-4">
                            <label for="">YUKK ID</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" 
                                value="{{ $user['source_type'] == 'USER' ? $user['additional']['yukk_id'] : $user['reference']['id']}}" 
                            class="form-control">
                        </div>
                        @endif
                        <div class="form-group col-4">
                            <label for="">KTP no</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" value="{{ $user['identity_value'] }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">KTP Image</label>
                        </div>
                        <div class="form-group col-8">
                            <!-- file picker with square preview if identity_image exist -->
                            @if (!empty($user['identity_image']))
                                <div class="image-preview">
                                    <img src="{{ $user['identity_image'] }}" alt="KTP Image" class="img-fluid">
                                </div>
                            @endif
                        </div> 
                        @if ($user['source_type'] == 'CUSTOMER')
                            <div class="form-group col-4">
                                <label for="">NPWP no</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="name" value="{{ $user['taxpayer_identity_number'] }}" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <label for="">NPWP Image</label>
                            </div>
                            <div class="form-group col-8">
                                <!-- file picker with square preview if identity_image exist -->
                                @if (!empty($user['taxpayer_identity_image']))
                                    <div class="image-preview">
                                        <img src="{{ $user['taxpayer_identity_image'] }}" alt="NPWP Image" class="img-fluid">
                                    </div>
                                @endif
                            </div>        
                        @endif
                        
                        <!-- email -->
                        <div class="form-group col-4">
                            <label for="">Email</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="email" value="{{ $user['email'] }}" class="form-control">
                        </div>
                        <!-- phone no -->
                        <div class="form-group col-4">
                            <label for="">Phone No</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="phone_no" value="{{ $user['phone_number'] }}" class="form-control">
                        </div>
                        
                        @if ($user['source_type'] == 'CUSTOMER')
                            <div class="form-group col-12">
                                <h3> Beneficiary Information </h3>
                            </div>
                            <div class="form-group col-4">
                                <label for="">Bank Name</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="bank_name" value="{{ $user['additional']['bank_name'] }}" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <label for="">Bank Branch Name</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="branch_name" value="{{ $user['additional']['branch_name'] }}" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <label for="">Account No</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="account_number" value="{{ $user['additional']['account_number'] }}" class="form-control">
                            </div>
                            @if(isset($user['additional']['account_name']) && $user['additional']['account_name'] != '')
                                <div class="form-group col-4">
                                    <label for="">Account Name</label>
                                </div>
                                <div class="form-group col-8">
                                    <input type="text" readonly name="account_name" value="{{ $user['additional']['account_name'] }}" class="form-control">
                                </div>
                            @endif
                            <div class="form-group col-4">
                                <label for="">City</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="city" value="{{ $user['additional']['city'] }}" class="form-control">
                            </div>
                            <div class="form-group col-4">
                                <label for="">Address</label>
                            </div>
                            <div class="form-group col-8">
                                <input type="text" readonly name="address" value="{{ $user['additional']['address'] }}" class="form-control">
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
                        <div class="form-group col-4">
                            <label for="">Name</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="name" value="{{ $user['reference']['name'] }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Densus Code</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="densus_code" value="{{ $user['reference']['densus_code'] }}" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="">DTTOT BATCH</label>
                        </div>
                        <div class="form-group col-8">
                            <input type="text" readonly name="batch" value="{{ $user['reference']['batch'] }}" class="form-control">
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
                                    @foreach ($user['matching_attribute'] as $index => $score)
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
                            <input type="text" readonly name="score" value="{{ $user['score'] }}" class="form-control">
                        </div>
                        <hr/>
                        <div class="form-group col-4">
                            <label for="">DTTOT Status</label>
                        </div>
                        <div class="form-group col-8">
                            <select id="statusSelected" name="status" class="form-control">
                            @if ($user['status'] == 'SUSPECTED') 
                                <option value="SUSPECTED" selected>SUSPECTED</option>
                            @elseif ($user['status'] == 'BLOCKED')
                                <option value="BLOCKED" selected>BLOCKED</option>'
                            @elseif ($user['status'] == 'RELEASED')
                                <option value="RELEASED" selected>RELEASED</option>'
                            @endif
                            </select>
                            @hasaccess('STORE.USERS_EDIT')
                            <button type="button" class="btn btn-primary" style="float: right; margin-top: 10px;"
                                onclick="onActivationToggleConfirmation(event, '{{ $user['id'] }}', '{{ $user['status'] }}')">Change Status</a>
                            @endhasaccess
                        </div>
                        <div class="form-group col-12">
                            <label for="">Change Logs</label>
                        </div>
                        <div class="form-group col-12">
                            @if(isset($user['changelogs']) && is_array($user['changelogs']))
                                @foreach ($user['changelogs'] as $change_log)
                                    @if($change_log['status'] == 'PENDING' || $change_log['status'] == '')
                                        @if($change_log['payloads'] == [])
                                            <p>{{ $change_log['created_at'] ?
                                                date('d M Y H:i:s', strtotime($change_log['created_at'])) : ''
                                            }} - {{ $change_log['actor'] }} melakukan {{ $change_log['action'] }}</p>
                                        @else()
                                            <p>{{ $change_log['created_at'] ?
                                                date('d M Y H:i:s', strtotime($change_log['created_at'])) : ''
                                            }} - {{ $change_log['actor'] }} melakukan {{ $change_log['action'] }}</p>
                                        @endif
                                    @else 
                                        @if ($change_log['is_final'])
                                            <p>{{ $change_log['created_at'] ?
                                                date('d M Y H:i:s', strtotime($change_log['created_at'])) : ''
                                            }} - DTTOT status untuk request {{ $change_log['action'] }} menjadi {{ 
                                                $change_log['status'] == 'APPROVED' ? 'Approved' : 'Rejected'
                                            }}</p>
                                        @else 
                                            <p>{{ $change_log['created_at'] ?
                                                date('d M Y H:i:s', strtotime($change_log['created_at'])) : ''
                                            }} - {{ $change_log['actor'] }} melakukan {{ $change_log['action'] }}</p>
                                        @endif
                                    @endif
                                @endforeach
                            @else
                                <p>No data</p>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-12">
                    <a href="{{ route('cms.yukk_co.suspected_user.list') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modal-confirmation-toggle" tabindex="-1" role="dialog"
            aria-labelledby="demoModalLabel" aria-hidden="true">
            <form id="modal-confirmation-form"
                data-attribute-action="{{ route('cms.yukk_co.suspected_user.update', ':id') }}" class="modal-dialog"
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
                            @if ($user['status'] != 'BLOCKED')
                                <option value="BLOCKED">BLOCKED</option>
                            @endif
                            @if ($user['status'] != 'RELEASED')
                                <option value="RELEASED">RELEASED</option>
                            @endif
                        </select>
                        <hr/>
                        <label class="form-label">Reason</label> <br/>
                        <textarea name="reason" id="" cols="30" rows="5" class="form-control" style="margin-bottom: 10px;"></textarea>
                        <!-- supporting documents / file -->
                        <label class="form-label">Supporting Documents</label> <br/>
                        <div id="additionalFiles" style="margin-bottom: 10px;">
                            <div style="margin-bottom: 10px; display:flex; justify-content:space-between">
                                <input type="file" name="file[]" id="supportingFiles" class="form-control">
                                <!-- remove file x -->
                                <a href="#" onclick="removeSupportingFile(event, this)" class="btn btn-danger" style="margin-left: 10px; display:none">X</a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addSupportingFile()">Add Supporting File</button>
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
            status = status == "" ? "PENDING" : status;
            $('#modal-confirmation-status').html('<option value="'+status+'">'+status+'</option>');
            // if status == "BLOCKED" remove option SUSPECTED and BLOCKED
            if(status == "BLOCKED"){
                $('#modal-confirmation-new-status option[value="SUSPECTED"]').remove();
                $('#modal-confirmation-new-status option[value="BLOCKED"]').remove();
                // statusSelected
                $('#statusSelected option[value="SUSPECTED"]').remove();
                $('#statusSelected option[value="BLOCKED"]').remove();
            } else if(status == "RELEASED"){
                $('#modal-confirmation-new-status option[value="SUSPECTED"]').remove();
                $('#modal-confirmation-new-status option[value="RELEASED"]').remove();
                // statusSelected
                $('#statusSelected option[value="SUSPECTED"]').remove();
                $('#statusSelected option[value="RELEASED"]').remove();
            }
            $('#modal-confirmation-new-status').val(status);

            $('#modal-confirmation-input').val(id)

            let action = $('#modal-confirmation-form').attr('data-attribute-action')
            $('#modal-confirmation-form').attr('action', action.replace(':id', id))

            $('#modal-confirmation-toggle').modal('toggle')
        }
        // addSupportingFile
        function addSupportingFile(){
            // add new file input to additional file container
            let additionalFiles = document.getElementById("additionalFiles");
            // append new file input
            let newFileInput = document.createElement("div");
            newFileInput.style.marginBottom = "10px";
            newFileInput.style.display = "flex";
            newFileInput.style.justifyContent = "space-between";
            newFileInput.innerHTML = '<input required type="file" name="file[]" id="supportingFiles" class="form-control"> <a href="#" onclick="removeSupportingFile(event, this)" class="btn btn-danger" style="margin-left: 10px; display:none">X</a>';
            additionalFiles.appendChild(newFileInput);
            // count how many file input in additionalFiles 
            let fileInputCount = additionalFiles.getElementsByTagName("input").length;
            // if file input count > 1, show remove button
            if(fileInputCount > 1){
                let removeButton = additionalFiles.getElementsByTagName("a");
                for(let i = 0; i < removeButton.length; i++){
                    removeButton[i].style.display = "block";
                }
            }
        }
        // removeSupportingFile
        function removeSupportingFile(event, target){
            event.preventDefault();
            // remove file input from additional file container
            let additionalFiles = document.getElementById("additionalFiles");
            additionalFiles.removeChild(target.parentNode);
            // count how many file input in additionalFiles 
            let fileInputCount = additionalFiles.getElementsByTagName("input").length;
            // if file input count == 1, hide remove button
            if(fileInputCount == 1){
                let removeButton = additionalFiles.getElementsByTagName("a");
                for(let i = 0; i < removeButton.length; i++){
                    removeButton[i].style.display = "none";
                }
            }
        }
    </script>
@endsection
