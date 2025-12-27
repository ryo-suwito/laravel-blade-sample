<x-app-layout>
    <x-page.header :title="__('cms.Manage Approval')">
        <x-slot name="breadcrumb">
            <x-breadcrumb.home />
            <x-breadcrumb.link :link="$mainMenuUrl" :text="__('cms.' . $title)"/>
            <x-breadcrumb.active>
                @if (isset($approval['status']) && $approval['status'] == "PENDING")
                    Approved & Rejected
                @else
                    {{ __("cms.Detail") }}
                @endif
            </x-breadcrumb.active>
        </x-slot>
    </x-page.header>

    <x-page.content :title="__('cms.' . $title)">
        <div class="row">
            @if ($approval['type'] != 'CREATE')
                <x-approval-card title="Old Data" typeData="old" :approval="$approval" />
            @endif

            <x-approval-card title="New Data" :approval="$approval" />
        </div>

        @if (array_key_exists('image_proof', $approval['properties'] ?? []) && array_key_exists('reason', $approval['properties'] ?? []))
        <div class="row mt-3">
            <div class="col p-2 mr-1 bg-card">
                <div class="row">
                    <div class="col">
                        <h5>Evidence</h5>
                    </div>
                </div>
                <hr class="divider">
                <div class="row mb-1">
                    <div class="col-md-4 my-auto">
                        <label>Reason</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control input" value="{{ $approval['properties']['reason'] }}" readonly>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-4 my-auto">
                        <label>Supporting Document</label>
                    </div>
                    <div class="col">
                        <img src="{{ $approval['properties']['image_proof']['url'] }}" alt="supporting_document" class="img">
                        <div class="d-flex align-items-end">
                            <a href="{{ $approval['properties']['image_proof']['url'] }}" target="_blank" class="btn btn-outline-secondary mt-2">Full View</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif

        @if ($approval['status'] == 'PENDING')
        <div class="row mt-3">
            <div class="col">
                <button class="btn btn-danger form-control" id="btnReject" type="submit" data-toggle="modal" data-target="#warningModal"data-label="Reject">REJECT</button>        
            </div>
            <div class="col">
                <button class="btn btn-primary form-control" type="submit" id="btnApprove" data-toggle="modal" data-target="#warningModal"data-label="Approve">APPROVE</button>
            </div>
        </div>
        @endif
    </x-page.content>

    @swal

@push('styles')
<div class="modal" id="warningModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="formWarningModal" action="{{ url()->current() }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attention!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure to <span id="actionBody"></span>?</p>
                <input type="hidden" id="action" name="action" value="REJECTED">
            </div>
            <div class="modal-footer">
                <button id="actionBtn" type="submit" class="btn btn-danger"></button>
                <button id="actionBtn2"type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>       
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const btnListIdForModalWarning = ["btnReject", "btnApprove"];

            $("#" + btnListIdForModalWarning.join(",#")).click(function(){
                $("#actionBody").html($(this).data('label'));
                $("#actionBtn").html($(this).data('label'));
                $("#action").val($(this).data('label') == 'Reject' ? 'REJECTED' : 'APPROVED')
                var btn1 = $('#actionBtn');
                var btn2 = $('#actionBtn2');

                if($(this).data('label') == 'Approve'){
                    btn1.before(btn2);
                    $('#actionBtn').removeClass('btn-danger').addClass('btn-primary');
                    $('#actionBtn2').removeClass('btn-primary').addClass('btn-danger');
                } else {
                    btn2.before(btn1);
                    $('#actionBtn').removeClass('btn-primary').addClass('btn-danger'); 
                    $('#actionBtn2').removeClass('btn-danger').addClass('btn-primary');
                }
            });

            console.log($(this).data('label'));

            $('#formWarningModal').submit(function() {
                $(this).find(':button[type=submit]').prop('disabled', true);
                $(this).find(':button[type=submit]').html('Loading..');
            })
        });
    </script>
@endpush
</x-app-layout>
