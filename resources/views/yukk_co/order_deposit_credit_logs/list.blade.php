@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Order Deposit Logs")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                <div class="breadcrumb-elements-item dropdown p-0">
                    <button href="#" class="form-control breadcrumb-elements-item dropdown-toggle justify-content-center" data-toggle="dropdown" style="width: 100px; height: 40px">
                        <i class="icon-add mr-1"></i>@lang("cms.Add")
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a id="button-add-log-modal" href="#" class="dropdown-item">Add Log</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Order Deposit Logs")</span>
                </div>

                {{--<a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>--}}
            </div>

            {{--<div class="header-elements d-none">
                <div class="breadcrumb justify-content-center">
                    <a href="#" class="breadcrumb-elements-item">
                        Link
                    </a>

                    <div class="breadcrumb-elements-item dropdown p-0">
                        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                            Dropdown
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">One more action</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Separate action</a>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Order Deposit Logs")</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card bg-success text-center p-3">
                        <div>
                            <h1 class="mb-3 mt-1">
                                IDR {{ @\App\Helpers\H::formatNumber($last_end_credit, 2) }}
                            </h1>
                        </div>

                        <blockquote class="blockquote mb-0">
                            <h3>@lang("Last End Credit Deposit")</h3>
                            <footer class="blockquote-footer">
                                <span>
                                    @lang("cms.last_end_credit_description")
                                </span>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <form action="{{ route("cms.yukk_co.transaction.credit_logs.index") }}" method="get">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y") }} - {{ $end_time->format("d-M-Y") }}">
                        </div>
                    </div>
                    @if($platforms)
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label>@lang("cms.Platform")</label>
                            <br/>
                            <select id="platform_id" data-live-search="true" name="platform_id" data-live-search-style="contains" class="selectpicker" class="form-control">
                                <option value="">Select Platform</option>
                                @foreach($platforms as $platform)
                                @if($platform_id && in_array(@$platform->id, $platform_id))
                                <option value="{{ @$platform->id }}" selected>{{@$platform->name}}</option>
                                @endif
                                @endforeach
                                @foreach($platforms as $platform)
                                @if(!($platform_id && in_array(@$platform->id, $platform_id)))
                                <option value="{{ @$platform->id }}">{{@$platform->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang("cms.Search")</label>
                            <input type="text" id="search" name="search" class="form-control" placeholder="@lang("cms.Search")" value="{{ $search }}">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="input-group input-group-append position-static">
                                <button class="btn btn-primary form-control" type="submit"><i class="icon-search4"></i> @lang("cms.Search")</button>
                                <button type="button" class="btn btn-primary dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false"></button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <button class="dropdown-item" name="export_to_csv" value="1"><i class="icon-file-download"></i> @lang("cms.Export to CSV")</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped dataTable">
                <thead>
                <tr>
                    <th>@lang("cms.Ref Code")</th>
                    <th>@lang("cms.Object ID")</th>
                    <th>@lang("cms.Created At")</th>
                    <th>@lang("cms.Title")</th>
                    <th>@lang("cms.Description")</th>
                    <th>@lang("cms.Start Credit")</th>
                    <th>@lang("cms.Value")</th>
                    <th>@lang("cms.End Credit")</th>
                    <th>@lang("cms.Created By")</th>
                    <th>@lang("cms.Type")</th>
                    {{--<th>@lang("cms.Actions")</th>--}}
                </tr>
                </thead>

                <tbody>
                @foreach($logs as $i => $log)
                    <tr>
                        <td>{{ $log->ref_code }}</td>
                        <td>{{ $log->object_id }}</td>
                        {{--<td>{{ date('Y-m-d H:i:s', strtotime($log->created_at)) }}</td>--}}
                        <td>{{ @\App\Helpers\H::formatDateTime($log->created_at) }}</td>
                        <td>{{ $log->title }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($log->start_credit, 2) }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($log->value, 2) }}</td>
                        <td>{{ @\App\Helpers\H::formatNumber($log->end_credit, 2) }}</td>
                        <td>{{ $log->created_by }}</td>
                        <td>{{ $log->type }}</td>
                        {{--<td class="text-center">--}}
                            {{--<div class="list-icons">--}}
                                {{--<div class="dropdown">--}}
                                    {{--<a href="#" class="list-icons-item" data-toggle="dropdown">--}}
                                        {{--<i class="icon-menu9"></i>--}}
                                    {{--</a>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @php($plus_minus_range = 3)
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route("cms.yukk_co.transaction.credit_logs.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.transaction.credit_logs.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.transaction.credit_logs.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("TRANSACTION_PLATFORM_DEPOSIT.CREDIT_LOGS_CREATE", "AND"))
        <div id="add-credit-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form if="form-add-credit" method="POST" action="{{ route("cms.yukk_co.transaction.credit_logs.store") }}">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang("cms.Add Credit")</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            @csrf
                            
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="platform_add_id">@lang("cms.Platform")</label>
                                <div class="col-lg-8">
                                    <select id="platform_add_id" data-live-search="true" name="platform_add_id" data-live-search-style="contains" class="selectpicker" class="form-control">
                                        <option value="">Select Platform</option>
                                        @foreach($platforms as $platform)
                                        @if($platform_id && in_array(@$platform->id, $platform_id))
                                        <option value="{{ @$platform->id }}" selected>{{@$platform->name}}</option>
                                        @endif
                                        @endforeach
                                        @foreach($platforms as $platform)
                                        @if(!($platform_id && in_array(@$platform->id, $platform_id)))
                                        <option value="{{ @$platform->id }}">{{@$platform->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="object_id">@lang("cms.Object ID")</label>
                                <div class="col-lg-8">
                                    <input type="text" id="object_id" name="object_id" class="form-control" placeholder="@lang("cms.Object ID")" value="{{ old('object_id') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="title">@lang("cms.Title")</label>
                                <div class="col-lg-8">
                                    <input type="text" id="title" name="title" class="form-control" placeholder="@lang("cms.Title")" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="description">@lang("cms.Description")</label>
                                <div class="col-lg-8">
                                    <textarea type="text" id="description" name="description" class="form-control" placeholder="@lang("cms.Description")" required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="type">@lang("cms.Type")</label>
                                <div class="col-lg-8">
                                    <select id="type" name="type" class="form-control" value="{{ old('type') }}">
                                        <option value="REPLENISH">@lang("cms.Replenish")</option>
                                        <option value="TOP_UP">@lang("cms.Top Up")</option>
                                        <option value="ADJUSTMENT">@lang("cms.Adjustment")</option>
                                        {{--<option value="ORDER_DEPOSIT">Order Deposit</option>--}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="value">@lang("cms.Value")</label>
                                <div class="col-lg-8">
                                    <input id="value" name="value" class="form-control" placeholder="@lang("cms.Value")" value="0" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="start_credit">@lang("cms.Start Credit")</label>
                                <div class="col-lg-8">
                                    <input type="text" id="start_credit" name="start_credit" class="form-control" placeholder="@lang("cms.Start Credit")" value="{{ $start_credit }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="end_credit">@lang("cms.End Credit")</label>
                                <div class="col-lg-8">
                                    <input type="text" id="end_credit" name="end_credit" class="form-control" placeholder="@lang("cms.End Credit")" value="{{ old('end_credit') }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-12 col-form-label text-danger" style="display:none" id="warning-negative-credit"></label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">@lang("cms.Cancel")</button>
                            <button type="submit" class="btn btn-primary" id="btn-add-credit">@lang("cms.Add")</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    </div>
@endsection

@section('scripts')
    <script>

        function sum(a, b) {
            a = String(a).replaceAll(".", "");
            b = String(b).replaceAll(".", "");

            a = parseInt(a);
            b = parseInt(b);

            return a + b
        }

        function formatRupiah(angka, prefix){
            let minus_sign = '';
            if (angka[0] == '-') {
                // remove minus sign
                angka = angka.substr(1);
                // set minus sign to prefix
                if($('#type').val() == 'ADJUSTMENT') {
                    minus_sign = '-';
                }
            }
            else if(!isNaN(angka) && angka < 0) {
                minus_sign = '-';
            }

			var number_string = String(angka).replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split('.'),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
			return prefix == undefined ? minus_sign + rupiah : (minus_sign + rupiah ? 'Rp. ' + minus_sign + rupiah : '');
		}

        function calculate(e){
            let value = $('#value').val().replaceAll(".", "");
            // check if type is adjustment allow negative value else remove minus sign from value
            if($('#type').val() != 'ADJUSTMENT') {
                value = value.replace('-', '');
            }
            if (value == '') {
                value = 0;
            } else if (value[0]=='0' && value.length > 1) {
                value = value.substr(1);
            } else if (value[0]=='-' && value[1]=='0' && value.length > 2) {
                value = '-' + value.substr(2);
            } else if (value[0]=='-' && value.length > 1) {
                value = '-' + value.substr(1);
            } else if (value[0]=='0' && value.length == 1) {
                value = 0;
            }

            let total = sum(value, $('#start_credit').val());

            if (total < 0) {
                $('#warning-negative-credit').text('End Credit cannot be negative').show();
                $('#btn-add-credit').prop('disabled', true);
            } else {
                $('#warning-negative-credit').hide();
                $('#btn-add-credit').prop('disabled', false);
            }
            $('#end_credit').val(formatRupiah(total));
            $('#value').val(formatRupiah(value))
        }
        $(document).ready(function() {
            // intercept form submit validate the value and submit as usual not using ajax
            $('#form-add-credit').submit(function(e) {
                e.preventDefault();
                let value = $('#value').val().replaceAll(".", "");
                // check if type is adjustment allow negative value else remove minus sign from value
                if($('#type').val() != 'ADJUSTMENT') {
                    value = value.replace('-', '');
                }
                if (value == '') {
                    value = 0;
                } else if (value[0]=='0' && value.length > 1) {
                    value = value.substr(1);
                } else if (value[0]=='-' && value[1]=='0' && value.length > 2) {
                    value = value.substr(2);
                    value = -value;
                } else if (value[0]=='-' && value.length > 1) {
                    value = value.substr(1);
                    value = -value;
                }
                let total = sum(value, $('#start_credit').val());
                if (total < 0) {
                    $('#warning-negative-credit').text('End Credit cannot be negative').show();
                } else {
                    $('#form-add-credit').unbind('submit').submit();
                }
            });


            // store php $platforms as javascript variable
            var platforms = {!! json_encode($last_rows) !!};
            // if input platform_id is changed, set the end_credit and start_credit based on the selected platform
            $('#platform_id').change(function() {
                let platform_id = $(this).val();
                let platform = platforms.find(platform => platform.id == platform_id);
                console.log($(this).val());
                console.table(platform);
                $('#start_credit').val(platform.last_credit_log ? formatRupiah(platform.last_credit_log.end_credit) : 0);
                $('#end_credit').val(platform.last_credit_log ? formatRupiah( platform.last_credit_log.end_credit) : 0);
            });

            $('#platform_add_id').change(function() {
                let platform_id = $(this).val();
                let platform = platforms.find(platform => platform.id == platform_id);
                console.log($(this).val());
                console.table(platform);
                $('#start_credit').val(platform.last_credit_log ? formatRupiah(platform.last_credit_log.end_credit) : 0);
                $('#start_credit').val(platform.last_credit_log ? formatRupiah( platform.last_credit_log.end_credit) : 0);
            });
            
            $(".dataTable").DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
            });

            $("#date_range").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'DD-MMM-YYYY',
                    firstDay: 1,
                },
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });

            $('#button-add-log-modal').click(function(e) {
                e.preventDefault();

                $('#add-credit-modal').modal('show');
            });

            $('#value').on('input', function(e) {
                calculate();    
            });

            $('#type').on('change', function(e) {
                calculate();    
            });

            $("#btn-add-credit").click(function(e) {
                if (window.confirm('@lang("cms.general_confirmation_dialog_content")')) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection

