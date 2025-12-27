@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang('cms.Request List')</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">@lang('cms.Request List')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <form id="data-verification-form" method="get" action="{{ route('yukk_co.data_verification.list') }}">
                <div class="row justify-content-between">
                    <div class="row col-lg-10">
                        <div class="col-lg-12 row">
                            <div class="form-group col-4">
                                <label>@lang("cms.Status")</label>
                                <div class="form-group">
                                    <select class="select2 form-group" name="filter_status" onchange='if(this.value != 0) { this.form.submit(); }'>
                                        <option value="ALL" @if($status == 'ALL') selected @endif>@lang('cms.ALL')</option>
                                        <option value="APPROVED" @if($status == 'APPROVED') selected @endif>@lang('cms.APPROVED')</option>
                                        <option value="DELETED" @if($status == 'DELETED') selected @endif>@lang('cms.DELETE')</option>
                                        <option value="REVIEWED" @if($status == 'REVIEWED') selected @endif>@lang('cms.REVIEWED')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-4">
                                <label>@lang("cms.Source")</label>
                                <div class="form-group">
                                    <select class="select2 form-group" name="source" onchange='if(this.value != 0) { this.form.submit(); }'>
                                        <option value="ALL" @if($source == 'ALL') selected @endif>@lang('cms.ALL')</option>
                                        <option value="API" @if($source == 'API') selected @endif>@lang('cms.API')</option>
                                        <option value="WEBSITE" @if($source == 'WEBSITE') selected @endif>@lang('cms.WEBSITE')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-4">
                                <label>@lang("cms.Search")</label>
                                <div class="form-group">
                                    <input type="text" id="search-keyword" name="search" class="form-control" placeholder="@lang("cms.Search")" value="{{ $search }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-1 justify-content-end mr-1">
                        <div class="flex flex-row">
                            <label>@lang("cms.Per page")&nbsp;</label>
                            <div class="form-group">
                                <select class="select2 form-group" name="per_page" onchange='if(this.value != 0) { this.form.submit(); }'>
                                    <option @if($per_page == 10) selected @endif>10</option>
                                    <option @if($per_page == 25) selected @endif>25</option>
                                    <option @if($per_page == 50) selected @endif>50</option>
                                    <option @if($per_page == 100) selected @endif>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>@lang('cms.Product')</th>
                        <th>@lang('cms.Merchant Branch Name')</th>
                        <th>@lang('cms.Merchant Name')</th>
                        <th>@lang('cms.Owner Name')</th>
                        <th>@lang('cms.KTP Number')</th>
                        <th>@lang('cms.Phone Number')</th>
                        <th>@lang('cms.Email')</th>
                        <th>@lang('cms.Bank Type')</th>
                        <th>@lang('cms.Source')</th>
                        <th>@lang('cms.Created At')</th>
                        <th>@lang('cms.Status Changed At')</th>
                        <th>@lang('cms.Status')</th>
                        <th>@lang('cms.Actions')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr>
                        <td>{!! @$data->product !!}</td>
                        <td>{{ @$data->branch_name }}</td>
                        <td>{{ @$data->merchant_name }}</td>
                        <td>{{ @$data->owner_name }}</td>
                        <td>{{ @$data->ktp_number }}</td>
                        <td>{{ @$data->phone_number }}</td>
                        <td class="w-50">{{ @$data->email }}</td>
                        <td>{{ @$data->bank_type }}</td>
                        <td>{{ @$data->source }}</td>
                        <td>{{ @$data->created_at }}</td>
                        <td>{{ @$data->status_changed_at }}</td>
                        @if($data->remark && $data->status == 'REVIEWED')
                            <td>{!! @$data->remark !!}</td>
                        @elseif($data->remark == '' && $data->status == 'REVIEWED')
                            <td>
                                <button type="submit" id="btn-approval" onclick="approve({{ $data->id }}, {{ $data->ktp_number }}, {{ $data->is_whitelist }})" class="bg-secondary text-white p-1 rounded-lg">APPROVE</button>
                            </td>
                        @elseif($data->status == 'APPROVED' && $data->complete_state == 'true')
                            <td>APPROVED</td>
                        @elseif($data->status == 'DELETED')
                            <td>DELETED</td>
                        @else
                            <td></td>
                        @endif
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    @if($data->status == 'DELETED' || $data->status == 'APPROVED')
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('yukk_co.data_verification.detail', ['id' => $data->id, 'type' => 'show'] ) }}" class="dropdown-item"><i class="icon-info22"></i>
                                                @lang("cms.Detail")
                                            </a>
                                        </div>
                                    @else
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('yukk_co.data_verification.detail', ['id' => $data->id, 'type' => 'show'] ) }}" class="dropdown-item"><i class="icon-info22"></i>
                                                @lang("cms.Detail")
                                            </a>
                                            <a href="{{ route('yukk_co.data_verification.detail', ['id' => $data->id, 'type' => 'edit']) }}" class="dropdown-item"><i class="icon-pencil7"></i>
                                                @lang("cms.Edit")
                                            </a>
                                            <form method="POST" action="{{ route('yukk_co.data_verification.status_update', ['id' => $data->id, 'type' => 'Delete']) }}">
                                                @csrf
                                                <button id="btn-delete" onclick='return confirm("@lang("cms.general_confirmation_dialog_content")");' class="dropdown-item"><i class="icon-bin"></i>
                                                    @lang("cms.Delete")
                                                </button>
                                            </form>
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

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6">
                    @if ($showing_data['total'] > 0)
                        <p>
                            Showing {{ $showing_data['from'] }} to {{ $showing_data['to'] }} of {{ $showing_data['total'] }} entries
                        </p>
                    @endif
                </div>
                <div class="col-lg-6">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 4)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("yukk_co.data_verification.list", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("yukk_co.data_verification.list", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("yukk_co.data_verification.list", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>

        <!-- #kyc-logs-modal -->
        <div class="modal fade" id="kyc-logs-modal" tabindex="-1" role="dialog" aria-labelledby="kyc-logs-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">KYM Logs</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ">
                        <table class="table table-bordered table-striped"  id="kyc-logs-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody id="kyc-logs-table-body">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <!-- approve anyway -->
                        <button type="button" id="approveModal" class="btn btn-primary" onclick="approveModal()">@lang('cms.Approve')</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('cms.Close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#search-keyword").change(function() {
                $('#data-verification-form').delay(1000).submit();
            });
        });
        var modalRequestId = null;
        var modalIdentityNumber = null;
        var canApprove = false;
        function approve(reqid, identityNumber, is_whitelist) {
            $.ajax({
                url: "{{ route('electronic_certificate.get_kyc_logs') }}",
                type: "GET",
                data: {
                    request_id: reqid,
                    identity_number: identityNumber
                },
                success: function(response) {
                    if (response.status == 'success') {
                        let result = response.data.result;
                        let verihubsResult = result.verihubs;
                        let dttotResult = result.dttot;
                        console.log('verihubsResult', verihubsResult)
                        console.log('dttotResult', dttotResult)

                        if (!verihubsResult && !dttotResult) {
                            canApprove = false;
                        } else {
                            canApprove = true;
                        }
                        if(is_whitelist == 1) {
                            canApprove = true;
                        }
                        modalRequestId = reqid;
                        modalIdentityNumber = identityNumber;
                        var kycLogsHtml = '';
                        if(is_whitelist == 1) {
                            kycLogsHtml += '<tr><td>Verihubs</td><td>Unchecked</td><td>-</td></tr>';
                        }
                        else if (verihubsResult) {
                            kycLogsHtml += '<tr><td>Verihubs</td><td>' + verihubsResult.status + '</td><td>' + verihubsResult.reason + '</td></tr>';
                        } else {
                            kycLogsHtml += '<tr><td>Verihubs</td><td>Unchecked</td><td>Unchecked</td></tr>';
                        }

                        if(is_whitelist == 1) {
                            kycLogsHtml += '<tr><td>DTTOT</td><td>Unchecked</td><td>-</td></tr>';
                        }
                        else if (dttotResult) {
                            kycLogsHtml += '<tr><td>DTTOT</td><td>' + dttotResult.status + '</td><td>' + dttotResult.reason + '</td></tr>';
                        } else {
                            kycLogsHtml += '<tr><td>DTTOT</td><td>Unchecked</td><td>User Object is not created yet</td></tr>';
                        }
                        $('#kyc-logs-table-body').html(kycLogsHtml);

                        if(!canApprove) {
                            $('#kycWarning').remove();
                            $('#approveModal').prop('disabled', true);
                            let warningMessage = '<div id="kycWarning" class="alert alert-warning" role="alert">KYC logs are not complete, you can not approve this request</div>';
                            $('#kyc-logs-table').before(warningMessage);
                        } else {
                            $('#approveModal').prop('disabled', false);
                            $('#kycWarning').remove();
                        }

                        $('#kyc-logs-modal').modal('show');
                    } else {
                        alert('Failed to get KYC logs');
                    }
                },
                error: function() {
                    alert('Failed to get KYC logs');
                }
            });
        }

        // approve anyway
        function approveModal() {
            let requestData = {
                _token: '{{ csrf_token() }}',
                type: 'Approve',
                id: modalRequestId
            };
            let aprroveEndpoint = "{{ route('yukk_co.data_verification.status_update', ['id' => 0, 'type' => 'Approve']) }}";
            // change the id to the selected id
            aprroveEndpoint = aprroveEndpoint.replace('/data_verification/0', '/data_verification/' + modalRequestId);
            // endpoint will return html page, make dynamic form to submit
            let form = $('<form>', {
                'method': 'POST',
                'action': aprroveEndpoint
            });
            $.each(requestData, function(key, value) {
                form.append($('<input>', {
                    'name': key,
                    'value': value,
                    'type': 'hidden'
                }));
            });
            form.appendTo('body').submit();
        }
    </script>
@endsection
