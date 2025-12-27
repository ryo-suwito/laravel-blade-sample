@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h5 class="card-title">@lang("cms.Partner Fee Edit")</h5>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.partner_fee.list") }}" class="breadcrumb-item">@lang("cms.Partner Fee List")</a>
                    <span class="breadcrumb-item active">@lang("cms.Partner Fee Edit")</span>
                </div>
            </div>
        </div>

    </div>
    <style>
        .d-flex {
        display: flex;
        }

        .mr-2 {
        margin-right: 0.5rem; /* Adds space between date and time inputs */
        }

        .align-items-center {
        align-items: center; /* Ensures both inputs are vertically aligned */
        }

    </style>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <form method="post" id="mainForm" enctype="multipart/form-data" action="{{ route('yukk_co.partner_fee.update', $partner_fee->id) }}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Name")</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="name" value="{{ $partner_fee->name }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Description")</label>
                        <div class="col-sm-4">
                            <textarea type="text" class="form-control" name="description" required>{{ $partner_fee->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Short Description")</label>
                        <div class="col-sm-4">
                            <textarea type="text" class="form-control text-left" name="short_description" required>{{ $partner_fee->short_description }}</textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Sort Number")</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="sort_number" value="{{ $partner_fee->sort_number }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Display Status")</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="display_status" id="display_status" required>
                                <option value="SHOWN" @if(old("display_status", $partner_fee->display_status) == "SHOWN") selected @endif>SHOWN</option>
                                <option value="HIDDEN" @if(old("display_status", $partner_fee->display_status) == "HIDDEN") selected @endif>HIDDEN</option>
                            </select>
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in %")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="fee_partner_percentage" value="{{ $partner_fee->fee_partner_percentage }}" required>
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Fee Partner in IDR")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="fee_partner_fixed" value="{{ $partner_fee->fee_partner_fixed }}" required>
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK Additional in %")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="fee_yukk_additional_percentage" value="{{ $partner_fee->fee_yukk_additional_percentage }}" required>
                        </div>

                        <label class="col-form-label col-sm-2">@lang("cms.Fee YUKK Additional in IDR")</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" name="fee_yukk_additional_fixed" value="{{ $partner_fee->fee_yukk_additional_fixed }}" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mb-5">
                    <div class="btn-group btn-block col-6 justify-content-center position-static">
                        <button class="btn btn-primary col-4" id="btn-submit" type="submit" data-toggle="tooltip" data-placement="top"  id="btn-submit" title="Submit immediately">Submit</button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split col-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu" style="">
                        <a class="dropdown-item" href="#">Schedule Apply</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

 <div id="scheduleModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Schedule Apply</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="scheduleDatePicker">Schedule Apply</label>
            <div class="d-flex align-items-center">
                <input type="text" id="scheduleDatePicker" class="form-control mr-2" placeholder="Select Date">
                <input type="text" id="fixedTimeDisplay" class="form-control" value="{{ $time_threshold }}" readonly>
            </div>
                <p class="form-text"><i>Data changes will be effective on the selected date.</i></p>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmScheduleBtn">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog  modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Schedule Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="confirmationMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmationOkBtn">OK</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
    <script>
        $("#btn-submit").click(function(e) {
            $('#bank_type').removeAttr('disabled');
            if (window.confirm("Are you sure you want to do this action?")) {

            } else {
                e.preventDefault();
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var originalFeePartnerPercentage = $("input[name='fee_partner_percentage']").val();
            var originalFeePartnerFixed = $("input[name='fee_partner_fixed']").val();
            var originalFeeYukkAdditionalPercentage = $("input[name='fee_yukk_additional_percentage']").val();
            var originalFeeYukkAdditionalFixed = $("input[name='fee_yukk_additional_fixed']").val();
            var timeThreshold = '{{ $time_threshold }}';
            var timeMessage = '{{ $time_message }}'; 
            var sessionError = `{{ session('error') }}`;
            @if(session('error'))
                alert(`{{ session('error') }}`);
            @endif
            // Check if timeThreshold is empty or invalid
            if (!sessionError && (!timeThreshold || !/^\d{2}:\d{2}$/.test(timeThreshold))) {
                alert(timeMessage || 'Invalid time threshold format. Ask admin to provide a valid "HH:MM" format.');
                $('.dropdown-item:contains("Schedule Apply")').addClass('disabled').attr('title', timeMessage || 'Time threshold is missing or invalid.');
                return; // Exit if timeThreshold is invalid
            }

            function enableScheduleButton() {
                let currentFeePartnerPercentage = $("input[name='fee_partner_percentage']").val();
                let currentFeePartnerFixed = $("input[name='fee_partner_fixed']").val();
                let currentFeeYukkAdditionalPercentage = $("input[name='fee_yukk_additional_percentage']").val();
                let currentFeeYukkAdditionalFixed = $("input[name='fee_yukk_additional_fixed']").val();

                if (currentFeePartnerPercentage !== originalFeePartnerPercentage || 
                    currentFeePartnerFixed !== originalFeePartnerFixed ||
                    currentFeeYukkAdditionalPercentage !== originalFeeYukkAdditionalPercentage || 
                    currentFeeYukkAdditionalFixed !== originalFeeYukkAdditionalFixed) {
                    // If timeThreshold is empty and there's a timeMessage, alert the user
                    if (timeThreshold === '' && timeMessage !== '') {
                        alert(timeMessage);
                        $('.dropdown-toggle').prop('disabled', true); // Keep the button disabled
                    } else {
                        $('.dropdown-toggle').prop('disabled', false); // Enable the button
                    }
                } else {
                    $('.dropdown-toggle').prop('disabled', true);
                }
            }

            var [hour, minute] = timeThreshold.split(':').map(Number);

            function checkTimeAndDisableButtons() {
                let currentTime = moment();
                let disableStartTime = moment().set({ hour: hour, minute: minute });
                let disableEndTime = disableStartTime.clone().add(10, 'minutes'); // 10 minutes window

                if (currentTime.isBetween(disableStartTime, disableEndTime)) {
                    $('#btn-submit').prop('disabled', true).attr('title', `Cannot submit until ${disableEndTime.format('HH:mm')}, data is currently locked.`);
                    $('.dropdown-item:contains("Schedule Apply")').addClass('disabled').attr('title', `Cannot schedule until ${disableEndTime.format('HH:mm')}, data is currently locked.`);
                } else {
                    $('#btn-submit').prop('disabled', false).attr('title', 'Submit immediately');
                    $('.dropdown-item:contains("Schedule Apply")').removeClass('disabled').attr('title', '');
                }
            }

            checkTimeAndDisableButtons();

            setInterval(checkTimeAndDisableButtons, 60000);

            $('.dropdown-toggle').prop('disabled', true);

            $("input[name='fee_partner_percentage'], input[name='fee_partner_fixed'], input[name='fee_yukk_additional_percentage'], input[name='fee_yukk_additional_fixed']").on('input', function() {
                enableScheduleButton();
            });

            $('#scheduleDatePicker').daterangepicker({
                singleDatePicker: true,
                timePicker: false,
                autoUpdateInput: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                minDate: moment().hour() >= hour && moment().minute() >= minute ? moment().add(1, 'days') : moment(),
                startDate: moment().hour() >= hour && moment().minute() >= minute ? moment().add(1, 'days') : moment()
            });

            $('#scheduleDatePicker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $('.dropdown-item').click(function() {
                $('#scheduleModal').modal('show');
            });

            $('#confirmScheduleBtn').click(function() {
                const selectedDate = $('#scheduleDatePicker').val();
                const fixedTime = '{{$time_threshold}}';
                if (selectedDate) {
                    $('#scheduleModal').modal('hide');
                    
                    // Add the selected date and time to hidden form inputs
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'schedule_date',
                        value: selectedDate
                    }).appendTo('#mainForm');

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'schedule_time',
                        value: fixedTime
                    }).appendTo('#mainForm');

                    $('#mainForm').submit();
                } else {
                    alert("Please select a valid date.");
                }
            });

            $('#confirmationOkBtn').click(function() {
                window.location.href = "{{ route('yukk_co.partner_fee.list') }}";
            });
        });
    </script>
@endsection
