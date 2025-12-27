@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>@lang("cms.Qoin Logs")</h4>
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
                    <span class="breadcrumb-item active">@lang("cms.Qoin Logs")</span>
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
            <h5 class="card-title">@lang("cms.Qoin Logs")</h5>
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
                            <h3>@lang("cms.Last End Credit QOIN")</h3>
                            <footer class="blockquote-footer">
                                <span>
                                    @lang("cms.last_end_credit_qoin_description")
                                </span>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <form action="{{ route("cms.yukk_co.transaction_qoin.credit_logs.index") }}" method="get">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="form-group">
                            <label>@lang("cms.Date Range")</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" placeholder="@lang("cms.Search Date Range")" value="{{ $start_time->format("d-M-Y") }} - {{ $end_time->format("d-M-Y") }}">
                        </div>
                    </div>

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

                                <div class="dropdown-menu dropdown-menu-right" style="">
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
                                <a href="{{ route("cms.yukk_co.transaction_qoin.credit_logs.index", array_merge(request()->all(), ["page" => $current_page-1])) }}" class="page-link"><i class="icon-arrow-left12"></i></a>
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
                                    <a href="{{ route("cms.yukk_co.transaction_qoin.credit_logs.index", array_merge(request()->all(), ["page" => $i])) }}" class="page-link">{{ $i }}</a>
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
                                <a href="{{ route("cms.yukk_co.transaction_qoin.credit_logs.index", array_merge(request()->all(), ["page" => $current_page+1])) }}" class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        @if (\App\Helpers\AccessControlHelper::checkCurrentAccessControl("TRANSACTION_QOIN.CREDIT_LOGS_CREATE", "AND"))
        <div id="add-credit-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route("cms.yukk_co.transaction_qoin.credit_logs.store") }}">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang("cms.Add Credit")</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            @csrf
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
                                        {{--<option value="ORDER_DEPOSIT">Order Deposit</option>--}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="value">@lang("cms.Value")</label>
                                <div class="col-lg-8">
                                    <input id="value" name="value" class="form-control" placeholder="@lang("cms.Value")" value="" required>
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
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

        $(document).ready(function() {
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
                let value = e.target.value;
                let total = sum(value, $('#start_credit').val());

                $('#end_credit').val(formatRupiah(total));

                e.target.value = formatRupiah(value)
            });

            $("#btn-add-credit").click(function(e) {
                if (window.confirm("@lang("cms.general_confirmation_dialog_content")")) {

                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection

