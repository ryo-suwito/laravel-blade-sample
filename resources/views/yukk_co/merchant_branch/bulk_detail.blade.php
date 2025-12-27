@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Merchant Branch Bulk Preview")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Merchant Branch Bulk Preview")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">

        <div class="card-header form-group">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="col-12 card-title">@lang("cms.Merchant Branch List")</h5>
                    @if(isset($total_data_count))
                        <div class="col-12">
                            <p><strong>{{ $valid_row_count }}</strong> data will be saved, found <strong>{{ $error_count }}</strong> errors from total <strong>{{ $total_data_count }}</strong> data</p>
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div style="width:100%;display:flex;justify-content:flex-start">
                        <div class="form-group" style="min-width:200px">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                        </div>
                        <div class="form-group" style="margin-right:15px; min-width:60px">
                            <select id="filterStatus" name="filter_status" class="form-control select2">
                                <option value="" selected> All</option>
                                <option value="ok">OK</option>
                                <option value="not ok">NOT OK</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button id="exportFailedData"  class="btn btn-primary"
                                @if($error_count == 0) disabled @endif>
                                <i class="icon-file-download"></i> Export Failed Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form id="mainForm" method="post" action="{{ route("yukk_co.merchant_branch.bulk.create") }}" class="form-horizontal">
                @csrf
                <table id="table-preview" class="table table-bordered table-striped dataTable">
                    <thead>
                    <tr>
                        <th></th>
                        <th>@lang("cms.Status")</th>
                        <th>@lang("cms.Name")</th>
                        <th>@lang("cms.Merchant")</th>
                        <th>@lang("cms.Beneficiary")</th>
                        <th>@lang("cms.Owner")</th>
                        <th>@lang("cms.Partner")</th>
                        <th>@lang("cms.Merchant Type")</th>
                        <th>@lang("cms.Start Date")</th>
                        <th>@lang("cms.End Date")</th>
                        <th>@lang("cms.Longitude")</th>
                        <th>@lang("cms.Latitude")</th>
                        <th>@lang("cms.Address")</th>
                        <th>@lang("cms.QR Type")</th>
                        <th>@lang("cms.Province")</th>
                        <th>@lang("cms.City")</th>
                        <th>@lang("cms.Region")</th>
                        <th>@lang("cms.Postal Code")</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($preview_list as $index => $merchant_branch)
                        <tr>
                            <td class="td-checkbox">
                                @if(!$merchant_branch->errors && !$merchant_branch->errorServices)
                                    <input class="checkbox" type="checkbox" name="checkbox[{{ $index }}]" checked>
                                @else
                                    {{--TODO Title Change lang--}}
                                    <input class="checkbox" type="checkbox" name="checkbox[{{ $index }}]" title="Error" disabled>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$merchant_branch->errors && !$merchant_branch->errorServices)
                                    OK
                                @else
                                    Not OK
                                @endif
                            </td>
                            @if($merchant_branch->errorServices && isset($merchant_branch->errorServices['is_duplicate']))
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{$merchant_branch->errorServices['is_duplicate']}}">
                                <span class="badge badge-danger">
                                    {{ $merchant_branch->name }}
                                </span>
                                </td>
                            @else
                                <td class="text-center">
                                    <input type="hidden" name="name[{{ $index }}]" value="{{ $merchant_branch->name }}">
                                    {{ $merchant_branch->name }}
                                </td>
                            @endif
                            @if($merchant_branch->merchant)
                                <td class="text-center">
                                    <input type="hidden" name="merchant_id[{{ $index }}]" value="{{ $merchant_branch->merchant->id }}">
                                    {{ $merchant_branch->merchant->name}}
                                </td>
                            @else
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="Merchant Not Found">
                                <span class="badge badge-danger">
                                    @lang('cms.Error')
                                </span>
                                </td>
                            @endif
                            @if($merchant_branch->beneficiary)
                                @if($merchant_branch->errorServices && isset($merchant_branch->errorServices['is_valid_beneficiary']) && $merchant_branch->errorServices['is_valid_beneficiary'][0] != 'ok')
                                    <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" 
                                    title="{{$merchant_branch->errorServices['is_valid_beneficiary'][0]}}">
                                    <input type="hidden" name="beneficiary_id[{{ $index }}]" value="{{ null }}">
                                    <span class="badge badge-danger">
                                    {{ $merchant_branch->beneficiary->name}}
                                    </span>
                                @else
                                    <td class="text-center">
                                    <input type="hidden" name="beneficiary_id[{{ $index }}]" value="{{ $merchant_branch->beneficiary->id }}">
                                    {{ $merchant_branch->beneficiary->name}}
                                @endif
                                </td>
                            @else
                                @if(isset($merchant_branch->is_valid_beneficiary) && $merchant_branch->is_valid_beneficiary == 'ok')
                                    <td class="text-center">
                                    <input type="hidden" name="beneficiary_id[{{ $index }}]" value="">
                                    -
                                @else
                                    <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" 
                                    title="Beneficiary Not Found'">
                                        <input type="hidden" name="beneficiary_id[{{ $index }}]" value="{{ null }}">
                                        <span class="badge badge-info">
                                            @lang('cms.Error')
                                        </span>
                                    </td>
                                @endif
                            @endif
                            @if($merchant_branch->owner)
                                @if($merchant_branch->errorServices && isset($merchant_branch->errorServices['is_valid_owner']) && $merchant_branch->errorServices['is_valid_owner'][0] != 'ok')
                                    <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" 
                                    title="{{$merchant_branch->errorServices['is_valid_owner'][0]}}">
                                    <input type="hidden" name="owner_id[{{ $index }}]" value="{{ null }}">
                                    <span class="badge badge-danger">
                                    {{ $merchant_branch->owner->name}}
                                    </span>
                                @else
                                    <td class="text-center">
                                    <input type="hidden" name="owner_id[{{ $index }}]" value="{{ $merchant_branch->owner->id }}">
                                    {{ $merchant_branch->owner->name}}
                                @endif
                                </td>
                            @else
                                @if(isset($merchant_branch->is_valid_owner) && $merchant_branch->is_valid_owner == 'ok')
                                    <td class="text-center">
                                    <input type="hidden" name="owner_id[{{ $index }}]" value="">
                                    -
                                @else
                                    <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" 
                                    title="Owner Not Found'">
                                        <input type="hidden" name="owner_id[{{ $index }}]" value="{{ null }}">
                                        <span class="badge badge-info">
                                            @lang('cms.Error')
                                        </span>
                                    </td>
                                @endif
                            @endif
                            {{----- Partner -----}}
                            @if(isset($merchant_branch->errorServices['partner_max_assigned']))
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" 
                                    title="{{$merchant_branch->errorServices['partner_max_assigned'][0]}}">
                                    <span class="badge badge-danger">
                                        {{ @$merchant_branch->partner->name ?? 'Error'}}
                                    </span>
                                </td>
                            @elseif(isset($merchant_branch->partner))
                                <td class="text-center">
                                    <input type="hidden" name="partner_id[{{ $index }}]" value="{{ $merchant_branch->partner->id }}">
                                    {{ @$merchant_branch->partner->name ?? 'Error' }}
                                </td>
                            @else
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" 
                                    title="Partner Not Found'">
                                    <span class="badge badge-info">
                                        {{ @$merchant_branch->partner->name ?? 'Error'}}
                                    </span>
                                </td>
                            @endif
                            @if(!isset($merchant_branch->errorServices['merchant_type']))
                                <td class="text-center">
                                    <input type="hidden" name="merchant_type[{{ $index }}]" value="{{ $merchant_branch->merchant_type }}">
                                    {{ Str::upper($merchant_branch->merchant_type) }}
                                </td>
                            @else
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{$merchant_branch->errorServices['merchant_type']}}">
                                    <span class="badge badge-danger">
                                        {{ $merchant_branch->errorServices['merchant_type'] ?? 'Error' }}
                                    </span>
                                </td>
                            @endif
                            {{-------------------}}
                            @if($merchant_branch)
                                <td class="text-center">
                                    <input type="hidden" name="start_date[{{ $index }}]" value="{{ $merchant_branch->start_date }}">
                                    {{ \App\Helpers\H::formatDateTimeWithoutTime($merchant_branch->start_date) }}
                                </td>
                            @else
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{$merchant_branch->errorServices['invalid_start_date']}}">
                                    <span class="badge badge-danger">
                                        @lang('cms.Error')
                                    </span>
                                </td>
                            @endif
                            @if($merchant_branch)
                                <td class="text-center">
                                    <input type="hidden" name="end_date[{{ $index }}]" value="{{ $merchant_branch->end_date }}">
                                    {{ \App\Helpers\H::formatDateTimeWithoutTime($merchant_branch->end_date) }}
                                </td>
                            @else
                            <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{$merchant_branch->errorServices['invalid_end_date']}}">
                                <span class="badge badge-danger">
                                    @lang('cms.Error')
                                </span>
                                </td>
                            @endif
                            @if($merchant_branch->errorServices && isset($merchant_branch->errorServices['longitude']))
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{$merchant_branch->errorServices['longitude']}}">
                                <span class="badge badge-danger">
                                    @lang('cms.Error')
                                </span>
                                </td>
                            @else
                                <input type="hidden" name="longitude[{{ $index }}]" value="{{ $merchant_branch->longitude }}">
                                <td class="text-center">{{ $merchant_branch->longitude }}</td>
                            @endif
                            @if($merchant_branch->errorServices && isset($merchant_branch->errorServices['latitude']))
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{$merchant_branch->errorServices['latitude']}}">
                                <span class="badge badge-danger">
                                    @lang('cms.Error')
                                </span>
                                </td>                            
                            @else
                                <input type="hidden" name="latitude[{{ $index }}]" value="{{ $merchant_branch->latitude }}">
                                <td class="text-center">{{ $merchant_branch->latitude }}</td>
                            @endif
                            <input type="hidden" name="address[{{ $index }}]" value="{{ $merchant_branch->address }}">
                            <td class="text-center">{{ $merchant_branch->address }}</td>
                            @if($merchant_branch->errors && isset($merchant_branch->errors['qr_type']))
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{$merchant_branch->errors['qr_type'][0]}}">
                                <span class="badge badge-danger">
                                    @lang('cms.Error')
                                </span>
                                </td>   
                            @else
                                <td class="text-center">
                                    @if ($merchant_branch->qr_type == 'BOTH')
                                        <input type="hidden" name="qr_type[{{ $index }}]" value="b">
                                    @elseif($merchant_branch->qr_type == 'STATIC')
                                        <input type="hidden" name="qr_type[{{ $index }}]" value="s">
                                    @elseif($merchant_branch->qr_type == 'DYNAMIC')
                                        <input type="hidden" name="qr_type[{{ $index }}]" value="d">
                                    @endif
                                    {{ $merchant_branch->qr_type }}
                                </td>
                            @endif
                            @if($merchant_branch->province)
                                <td class="text-center">
                                    <input type="hidden" name="province_name[{{ $index }}]" value="{{ $merchant_branch->province->name }}">
                                    {{ $merchant_branch->province->name }}
                                </td>
                            @else
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="Province Name Not Found">
                                    <span class="badge badge-danger">
                                        @lang('cms.Error')
                                    </span>
                                </td>
                            @endif
                            @if($merchant_branch->city)
                                <td class="text-center">
                                    <input type="hidden" name="city_name[{{ $index }}]" value="{{ $merchant_branch->city->name }}">
                                    {{ $merchant_branch->city->name }}
                                </td>
                            @else
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="City Name Not Found">
                                    <span class="badge badge-danger">
                                        @lang('cms.Error')
                                    </span>
                                </td>
                            @endif
                            @if($merchant_branch->region)
                                <td class="text-center">
                                    <input type="hidden" name="region_name[{{ $index }}]" value="{{ $merchant_branch->region->name }}">
                                    {{ $merchant_branch->region->name }}
                                </td>
                            @else
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{ $merchant_branch->errorServices['region_name'][0] }}">
                                    <span class="badge badge-danger">
                                        @lang('cms.Error')
                                    </span>
                                </td>
                            @endif
                            @if($merchant_branch->postal_code && !isset($merchant_branch->errorServices['postal_code']))
                                <td class="text-center">
                                    <input type="hidden" name="postal_code[{{ $index }}]" value="{{ $merchant_branch->postal_code }}">
                                    {{ $merchant_branch->postal_code }}
                                </td>
                            @else
                                <td class="td-tooltip text-center" data-toggle="tooltip" data-placement="bottom" title="{{ $merchant_branch->errorServices['postal_code'][0] }}">
				                    <span class="badge badge-danger">
                                        @lang('cms.Error')
                                    </span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button style="margin-top:15px" id="submitButton" type="submit" class="btn btn-block btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#exportFailedData').on('click', function() {
                exportFailedData(table);
            });
            function appendAllDataToForm() {
                var table = $(".dataTable").DataTable();
                var form = $("#mainForm");
                // Get all rows from DataTables (including all pages)
                var params = table.$('input,select,textarea').serializeArray();
                
                // Iterate over all form elements
                $.each(params, function(){
                    // Check if element with the same name already exists in the form
                    if (form.find('input[name="' + this.name + '"]').length === 0) {
                        // Create and append hidden element if it doesn't exist
                        $(form).append(
                            $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', this.name)
                            .val(this.value)
                        );
                    }
                });
            }

            // Attach function to the submit button click event
            $('#submitButton').on('click', function (e) {
                appendAllDataToForm();
            });
            function checkAllCheckboxes() {
                var allDisabled = true;
                $('.checkbox').each(function() {
                    if ($(this).prop('checked')) {
                        allDisabled = false;
                        return false;
                    }              
                });
                
                if (allDisabled) {
                    $('#btn-submit').prop('disabled', true);
                } else {
                    $('#btn-submit').prop('disabled', false);
                }
            }

            // Run the check on page load
            checkAllCheckboxes();

            // Run the check whenever a checkbox changes
            $('.checkbox').on('change', checkAllCheckboxes);

            $(".checkbox").click(function(event){
                event.stopPropagation();
            });
            
            $("#table-preview .td-checkbox").click(function(){
                $(this).find(".checkbox").click();
            });

            $('.td-tooltip').tooltip();

            $(".date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
                timePicker: false,
                timePicker24Hour: false,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });            
            var originalData = $('#table-preview tbody').html(); // Store the original table data
            var submitButton = $('#submitButton'); // Assuming your submit button has an id of submitBtn
            var searchInput = $('#searchInput'); // Assuming your search input field has an id of searchInput
            var filterStatus = $('#filterStatus');
            var table = $(".dataTable").DataTable({
                "paging": true,
                "ordering": false,
                "info": false,
                "searching": false,
                "lengthChange": true,
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 10
            });

            var originalData = table.rows().nodes().toArray(); // Store the original table data
            var submitButton = $('#submitButton');
            var searchInput = $('#searchInput');
            var filterStatus = $('#filterStatus');

            $('#filterStatus').on('change', function () {
                var status = $('#filterStatus').val();
                var searchText = searchInput.val().trim().toLowerCase();

                table.clear().draw(); // Clear the DataTables content

                if (status === '') {
                    table.rows.add(originalData).draw(); // Restore original data
                } else {
                    $.each(originalData, function (index, row) {
                        var rowStatus = $(row).find('td:eq(1)').text().trim().toLowerCase();
                        console.log(rowStatus, status, rowStatus == status)
                        if ((rowStatus == status || status == '')) {
                            if(searchText){
                                if(row.textContent.toLowerCase().indexOf(searchText) !== -1){
                                    table.row.add(row);
                                }
                            } else {
                                table.row.add(row);
                            }
                        }
                    });
                    table.draw()
                }
            });

            // Debounce search input
            searchInput.on('keyup', debounce(function () {
                var searchText = searchInput.val().toLowerCase();

                table.clear().draw(); // Clear the DataTables content

                $.each(originalData, function (index, row) {
                    if (row.textContent.toLowerCase().indexOf(searchText) !== -1) {
                        table.row.add(row);
                    }
                });
                table.draw()
            }, 300));

            $("#mainForm").submit(function () {
                $("#submitButton").attr("disabled", true);
                return true;
            });
        });
        function debounce(func, delay) {
                let timeoutId;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        func.apply(context, args);
                    }, delay);
                };
            }
        var failedData = @json($invalid); 
        function exportFailedData(table) {
            if (!failedData || failedData.length === 0) {
                alert('No failed data to export.');
                return;
            }

            // Define the Excel header using Blade to insert localized strings
            var excelHeader = [
                'Name',
                'Merchant ID',
                'Customer ID',
                'Owner ID',
                'Partner ID',
                'Merchant Type',
                'Start Date',
                'End Date',
                'Longitude',
                'Latitude',
                'Address',
                'Province Name',
                'City Name',
                'Region Name',
                'Postal Code'
            ];

            // Initialize an array to hold all rows including the header
            var excelData = [];
            excelData.push(excelHeader);

            // Iterate over the filtered data and push to excelData
            failedData.forEach(function(rowArray) {
                var row = [
                    rowArray.name,
                    rowArray.merchant_id,
                    rowArray.customer_id,
                    rowArray.owner_id,
                    rowArray.partner_id,
                    rowArray.merchant_type,
                    rowArray.start_date,
                    rowArray.end_date,
                    rowArray.longitude,
                    rowArray.latitude,
                    rowArray.address,
                    rowArray.province_name,
                    rowArray.city_name,
                    rowArray.region_name,
                    rowArray.postal_code
                ];
                excelData.push(row);
            });

            // Create a worksheet from the excelData
            var worksheet = XLSX.utils.aoa_to_sheet(excelData);

            // Create a new workbook and append the worksheet
            var workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Failed Data");

            // Generate a binary string representation of the workbook
            var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'binary' });

            // Function to convert the binary string to an ArrayBuffer
            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            // Create a Blob from the ArrayBuffer
            var blob = new Blob([s2ab(wbout)], { type: 'application/octet-stream' });

            // Create a link to trigger the download
            var link = document.createElement("a");
            if (link.download !== undefined) { // Feature detection
                // Create a URL for the blob
                var url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                // Define the filename
                var currentDate = new Date();
                var filename = "failed_data_" + currentDate.toISOString().slice(0,10) + ".xlsx";
                link.setAttribute("download", filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert('Your browser does not support exporting to Excel.');
            }
        }

    </script>
@endsection
