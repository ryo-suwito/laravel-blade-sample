@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{--<h4><span class="font-weight-semibold">Seed</span> - Static layout</h4>--}}
                <h4>Payment Link</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("cms.merchant_branches.payment_link.list") }}" class="breadcrumb-item">Payment Link</a>
                    <span class="breadcrumb-item active">@lang("cms.Detail")</span>
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

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Merchant Branch</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$payment_link->merchant_branch->name }}">
                        </div>
                        <label class="col-lg-2 col-form-label">Order Id</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @$payment_link->order_id }}">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Payment Channel</label>
                        <div class="col-lg-4">
                            <p>@foreach($payment_link->payment_channels as $index => $channel)
                                <span style="margin:2px 1px" class="badge badge-primary">{{ @$channel->name }}</span>
                                @endforeach
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <label class="col-lg-4 col-form-label">Url</label>
                                <label class="col-lg-6 col-form-label"><a href="{{ $payment_link->url }}" target="_blank">{{ $payment_link->url }}</a></label>
                                <div class="col-lg-2">
                                    <button onclick="CopyToClipboard()" id="buttonShowUrl" class="btn btn-success btn-right form-control" style="float:right; width:90px"> Copy Url
                                    </button>
                                    <input type="text" class="form-control" style="display:none" readonly="" value="{{ @$payment_link->url }}" id="urlPaymentLink">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Amount</label>
                        <div class="col-lg-4">
                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatNumber($payment_link->amount, 2) }}">
                                <div class="form-control-feedback form-control-feedback-lg">
                                    @lang("cms.IDR")
                                </div>
                            </div>
                        </div>
                        <label class="col-lg-2 col-form-label">Expired At</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($payment_link->expired_at) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">@lang("cms.Created At")</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly="" value="{{ @\App\Helpers\H::formatDateTime($payment_link->created_at) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Payment Link Status</label>
                        <div class="col-lg-4" style="padding-top:8px">
                            @if ($payment_link->status == "USED")
                                <td><span style="margin:2px 1px" class="badge badge-success">{{ $payment_link->status }}</span></td>
                            @else
                                @if ($payment_link->status == "EXPIRED")
                                    <td><span style="margin:2px 1px" class="badge badge-danger">{{ $payment_link->status }}</span></td>
                                @else
                                    <td><span style="margin:2px 1px" class="badge badge-primary">NOT USED</span></td>
                                @endif
                            @endif
                        </div>

                        <label class="col-lg-2 col-form-label">Transaction Status</label>
                        <div class="col-lg-4" style="padding-top:8px">
                            @if ($payment_link->transaction_status == "USED")
                                <td><span style="margin:2px 1px" class="badge badge-success">{{ $payment_link->transaction_status }}</span></td>
                            @else
                                @if ($payment_link->transaction_status == "EXPIRED")
                                    <td><span style="margin:2px 1px" class="badge badge-danger">{{ $payment_link->transaction_status }}</span></td>
                                @else
                                    <td><span style="margin:2px 1px" class="badge badge-primary">{{ $payment_link->transaction_status }}</span></td>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @if ($can_delete)
            <button onclick="deletePaymentLink({{$payment_link->id}})" class="btn btn-danger btn-right form-control" style="float:right; width:90px"> Delete
            </button>
            @endif
            <button onclick="goBack()" class="btn btn-primary btn-right form-control" style="float:right; width:90px; margin:0 10px"> Back
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function CopyToClipboard() {
            var textToCopy = document.getElementById("urlPaymentLink");
            if (navigator.clipboard) {
                // Clipboard API available
                navigator.clipboard.writeText(textToCopy.value)
                    .then(function () {
                        // Clipboard write success
                        showCopySuccessMessage();
                    })
                    .catch(function () {
                        // Clipboard write failed
                        showFallbackPrompt(textToCopy.value);
                    });
            } else {
                // Clipboard API not available
                showFallbackPrompt(textToCopy.value);
            }
        }

        function showCopySuccessMessage() {
            window.alert('Success! The text was copied to your clipboard');
        }

        function showFallbackPrompt(text) {
            var promptMessage = "Copy to clipboard: Ctrl+C, Enter";
            if (isHTTPProtocol()) {
                promptMessage += "\n\nNote: Copying to clipboard is not supported on this browser. Please manually copy the text.";
            }
            window.prompt(promptMessage, text);
        }

        function isHTTPProtocol() {
            return window.location.protocol === "http:";
        }
    </script>

    <script>
        $(document).ready(function() {
            $(".dataTable").DataTable();
        });
        function deletePaymentLink(payment_id){
            let url = "{{ route('cms.merchant_branches.payment_link.list')}}"
            if (window.confirm("Apakah anda yakin ingin menghapus payment link ini ?")) {
                window.location.href = url.replace("list","delete/") + payment_id
            }
        }
        function goBack(){
            let url = "{{ route('cms.merchant_branches.payment_link.list')}}"
            window.location.href = url
        }
    </script>
@endsection