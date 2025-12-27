@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{-- <h4><span class="font-weight-semibold">Seed</span> - Static layout</h4> --}}
                <h4>Payment Link List</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{-- <button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button> --}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route('cms.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        @lang('cms.Home')</a>
                    <span class="breadcrumb-item active">Payment Link List</span>
                </div>

                {{-- <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a> --}}
            </div>

            {{-- <div class="header-elements d-none">
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
            </div> --}}
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-9">
                    <form action="{{ route('cms.merchant_branches.payment_link.list') }}" method="get">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>@lang('cms.Order ID')</label>
                                    <input type="text" name="order_id" class="form-control"
                                        placeholder="@lang('cms.Search Order ID')" value="{{ $order_id }}">
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label>@lang('cms.Date Range')</label>
                                    <input type="text" id="date_range" name="date_range" class="form-control"
                                        placeholder="@lang('cms.Search Date Range')"
                                        value="{{ $start_time->format('d-M-Y H:i:s') }} - {{ $end_time->format('d-M-Y H:i:s') }}">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="input-group input-group-append position-static">
                                        <button class="btn btn-primary form-control" type="submit"><i
                                                class="icon-search4"></i> @lang('cms.Search')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3">
                    @if ($can_create)
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <a href="{{ route('cms.merchant_branches.payment_link.create') }}">
                                        <button style="margin-left:10px; float:right" class="btn btn-success form-control">
                                            Create</button>
                                    </a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <a href="{{ route('cms.merchant_branches.payment_link.import') }}">
                                        <button style="margin-left:10px; float:right" class="btn btn-warning form-control">
                                            Import</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>



            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>Amount</th>
                        <th>Url</th>
                        <th>Payment Channels</th>
                        <th>Expired At</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($payment_link_list as $index => $payment_link)
                        <tr>
                            <td>{{ @$payment_link->order_id }}</td>
                            <td>{{ @number_format($payment_link->amount) }}</td>
                            <td>
                                @if ($payment_link->status == 'EXPIRED')
                                    Expired
                                @else
                                    <a href="{{ @$payment_link->url }}" target="_blank">{{ @$payment_link->url }}</a>
                                @endif
                            </td>
                            <td>
                                @foreach ($payment_link->channels as $index => $channel)
                                    <span style="margin:2px 1px" class="badge badge-primary">{{ @$channel->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ @\App\Helpers\H::formatDateTime($payment_link->expired_at) }}</td>
                            <td>{{ @\App\Helpers\H::formatDateTime($payment_link->created_at) }}</td>
                            @if ($payment_link->status == 'USED')
                                <td><span style="margin:2px 1px"
                                        class="badge badge-success">{{ $payment_link->status }}</span></td>
                            @else
                                @if ($payment_link->status == 'EXPIRED')
                                    <td><span style="margin:2px 1px"
                                            class="badge badge-danger">{{ $payment_link->status }}</span></td>
                                @else
                                    <td><span style="margin:2px 1px"
                                            class="badge badge-primary">{{ $payment_link->status }}</span></td>
                                @endif
                            @endif
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('cms.merchant_branches.payment_link.detail', $payment_link->id) }}"
                                                class="dropdown-item"><i class="icon-search4"></i> @lang('cms.Detail')</a>
                                            @if ($can_delete && $payment_link->status != 'USED')
                                                <span onclick="deletePaymentLink({{ $payment_link->id }})"
                                                    class="dropdown-item" style="cursor:pointer"><i
                                                        class="icon-trash"></i> Delete</span>
                                            @endif
                                        </div>
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
                <div class="col-lg-12">
                    <div style="float:right; width:100%">
                        <p>
                            Page {{ $current_page }} of {{ $last_page }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination pagination-flat justify-content-end">
                        @if ($current_page == 1)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-left12"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('cms.merchant_branches.payment_link.list', array_merge(request()->all(), ['merchant_branch_id' => $merchant_branch_id, 'page' => $current_page - 1])) }}"
                                    class="page-link"><i class="icon-arrow-left12"></i></a>
                            </li>
                        @endif
                        @if ($current_page - $plus_minus_range > 1)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @for ($i = max(1, $current_page - $plus_minus_range); $i <= min($current_page + $plus_minus_range, $last_page); $i++)
                            @if ($i == $current_page)
                                <li class="page-item active"><a href="#" class="page-link">{{ $i }}</a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a href="{{ route('cms.merchant_branches.payment_link.list', array_merge(request()->all(), ['merchant_branch_id' => $merchant_branch_id, 'page' => $i])) }}"
                                        class="page-link">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        @if ($current_page + $plus_minus_range < $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link">...</a></li>
                        @endif
                        @if ($current_page == $last_page)
                            <li class="page-item disabled"><a href="#" class="page-link"><i
                                        class="icon-arrow-right13"></i></a></li>
                        @else
                            <li class="page-item">
                                <a href="{{ route('cms.merchant_branches.payment_link.list', array_merge(request()->all(), ['merchant_branch_id' => $merchant_branch_id, 'page' => $current_page + 1])) }}"
                                    class="page-link">
                                    <i class="icon-arrow-right13"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @error('status_message')
            Swal.fire({
                text: '{{ $message }}',
                icon: 'error',
                toast: true,
                showConfirmButton: false,
                position: 'top-end'
            });
        @enderror
        @if (session('success_message'))
            Swal.fire({
                text: `{{ session('success_message') }}`,
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'top-end'
            });
        @endif
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
                    format: 'DD-MMM-YYYY HH:mm:ss',
                    firstDay: 1,
                },
                timePicker: true,
                timePicker24Hour: true,
            });

            $(".pagination .page-item.active").click(function(e) {
                e.preventDefault();
            });
        });

        function deletePaymentLink(payment_id) {
            let url = "{{ route('cms.merchant_branches.payment_link.list') }}"
            if (window.confirm("Apakah anda yakin ingin menghapus payment link ini ?")) {
                window.location.href = url.replace("list", "delete/") + payment_id
            }
        }
    </script>
@endsection
