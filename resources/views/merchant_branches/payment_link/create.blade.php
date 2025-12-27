@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <style>
        .unselected_badge {
            background-color: transparent !important;
            border: 1px solid #268bd2 !important;
        }
    </style>
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                {{-- <h4><span class="font-weight-semibold">Seed</span> - Static layout</h4> --}}
                <h4>Create Payment Link</h4>
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
                    <a href="{{ route('cms.merchant_branches.payment_link.list') }}" class="breadcrumb-item">Create Payment
                        Link</a>
                    <span class="breadcrumb-item active">@lang('cms.Detail')</span>
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
            <form id="form_payment_link">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <input style="display:none" type="text" name="merchant_branch_id"
                                value="{{ @$payment_link->merchant_branch_id }}">
                            <label class="col-lg-2 col-form-label">Order Id</label>
                            <div class="col-lg-4">
                                <input type="text" name="order_id" required="true" class="form-control"
                                    placeholder="{{ @$payment_link->order_id }}" value="{{ @$payment_link->order_id }}">
                                <p style="color:red; display:none" id="order_id_error_message"></p>
                            </div>
                            <label class="col-lg-2 col-form-label">Use Customer Address</label>
                            <div class="col-lg-4 col-form-label">
                                <span style="margin:2px 5px">
                                    <input type="radio" id="customer_address_yes" name="use_customer_address"
                                        value="true">
                                    <label for="customer_address_yes">Yes</label>
                                </span>
                                <span style="margin:2px 5px">
                                    <input type="radio" id="customer_address_no" name="use_customer_address"
                                        value="false" checked>
                                    <label for="customer_address_no">No</label>
                                </span>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Payment Channel</label>
                            <div class="col-lg-6">
                                @if (count($payment_link->payment_channels) > 0)
                                    <p>
                                        @foreach ($payment_link->payment_channels as $index => $channel)
                                            <span id="channel_{{ @$channel->id }}" onclick="enableChannel(this)"
                                                style="margin:2px 1px; cursor:pointer"
                                                class="unselected_badge badge badge-primary">{{ @$channel->name }}</span>
                                        @endforeach
                                    </p>
                                @else
                                    <p style="color:red; padding:6px 2px">You don't have any payment channel yet. Please
                                        contact Administrator</p>
                                @endif
                                <p style="color:red; display:none" id="payment_channel_error_message"></p>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Amount</label>
                            <div class="col-lg-4">
                                <div class="form-group form-group-feedback form-group-feedback-left">
                                    <input type="number" name="amount" required="true" class="form-control">
                                    <div class="form-control-feedback form-control-feedback-lg">
                                        @lang('cms.IDR')
                                    </div>
                                </div>
                                <p style="color:red; display:none" id="amount_error_message"></p>
                            </div>
                            <label class="col-lg-2 col-form-label">Use Expiration Date</label>
                            <div class="col-lg-4">
                                <div class="col-form-label">
                                    <span style="margin:2px 5px">
                                        <input onchange="checkUpdatedAt()" type="radio" id="expired_at_yes"
                                            name="use_expired_at" value="true">
                                        <label for="expired_at_yes">Yes</label>
                                    </span>
                                    <span style="margin-left:2px 5px">
                                        <input onchange="checkUpdatedAt()" type="radio" id="expired_at_no"
                                            name="use_expired_at" value="false" checked>
                                        <label for="expired_at_no">No</label>
                                    </span>
                                    <span>(Default H+1)</span>
                                </div>
                                <div class="form-group" id="expired_at_container" style="display:none">
                                    <input type="text" id="expired_at" name="expired_at" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button onclick="submitForm()" class="btn btn-primary btn-right form-control" style="float:right; width:90px">
                Submit</button>
            <button onclick="goBack()" class="btn btn-primary btn-right form-control"
                style="float:right; width:90px; margin:0 10px"> Back
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var selected_channel = []
        $(document).ready(function() {
            $(".dataTable").DataTable();
            let date = new Date();
            date.setDate(date.getDate() + 1);
            let now = new Date();
            $("#expired_at").daterangepicker({
                parentEl: '.content-inner',
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss',
                    firstDay: 1,
                },
                startDate: date, // after open picker you'll see this dates as picked
                endDate: date,
                timePicker: true,
                minDate: now,
                timePicker24Hour: true,
                singleDatePicker: true,
                showDropdowns: true,
                drops: 'up'
            });
        });

        function submitForm() {
            let is_valid = true
            $("#payment_channel_error_message").css('display', 'none');
            $("#order_id_error_message").css('display', 'none');
            $("#amount_error_message").css('display', 'none');
            var formData = new FormData(document.getElementById('form_payment_link'))
            if (selected_channel.length == 0) {
                is_valid = false
                $("#payment_channel_error_message").css('display', 'block');
                $("#payment_channel_error_message").html("Please select one/more payment channel to use.")
            }
            if (!formData.get('order_id')) {
                is_valid = false
                $("#order_id_error_message").css('display', 'block');
                $("#order_id_error_message").html("Please check order id you've inputted.")
            }
            if (!formData.get('amount')) {
                is_valid = false
                $("#amount_error_message").css('display', 'block');
                $("#amount_error_message").html("Please check amount you've inputted.")
            }
            if (formData.get('use_customer_details') == 'false') {
                formData.set('use_customer_details', false)
            } else {
                formData.set('use_customer_details', true)
            }
            if (formData.get('use_customer_address') == 'false') {
                formData.set('use_customer_address', false)
            } else {
                formData.set('use_customer_address', true)
            }
            if (formData.get('use_expired_at') == 'false') {
                formData.set('expired_at', '')
            } else if (!formData.get('expired_at')) {
                is_valid = false
                if (window.confirm("Please check expired date you've inputted.")) {
                    return
                }
            }
            if (!is_valid) {
                return
            }
            formData.set("payment_channels", selected_channel)
            formData.set('_token', '{{ csrf_token() }}')
            $.ajax({
                url: "{{ route('cms.merchant_branches.payment_link.submit') }}",
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.error) {
                        console.log(data)
                        if (data.error_data && Object.keys(data.error_data).length > 0) {
                            for (key in data.error_data) {
                                console.log(key)
                                console.log(data.error_data[key])
                                let message = data.error_data[key]
                                if (key == 'order_id') {
                                    $("#order_id_error_message").css('display', 'block');
                                    $("#order_id_error_message").html(message.join("\n"))
                                } else if (key == 'payment_channel') {
                                    $("#payment_channel_error_message").css('display', 'block');
                                    $("#payment_channel_error_message").html(message.join("\n"))
                                } else if (key == 'amount') {
                                    $("#amount_error_message").css('display', 'block');
                                    $("#amount_error_message").html(message.join("\n"))
                                } else {
                                    if (window.confirm("Create Payment Link Failed. \n" + data.error_message)) {
                                        window.location.href = "#"
                                    }
                                }

                            }
                        } else {
                            if (window.confirm("Create Payment Link Failed. \n" + data.error_message)) {
                                window.location.href = "#"
                            }
                        }
                    } else {
                        showAlert('success', 'Payment link created successfully');
                    }
                    window.location.href = data.url ? data.url : "#"
                }
            });
        }

        function checkUpdatedAt() {
            $("input[name='use_expired_at']").each(function() {
                if (this.value == 'true' && this.checked) {
                    $("#expired_at_container").css('display', 'block');
                } else if (this.value == 'true' && !this.checked) {
                    $("#expired_at_container").css('display', 'none');
                }
            });
        }

        function showAlert(type, message) {
            Swal.fire({
                icon: type,
                title: 'Success',
                text: message,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000 // Duration for the alert to be visible (in milliseconds)
            });
        }

        function showUrl() {
            $("#urlPaymentLink").css('display', 'block');
            $("#buttonShowUrl").css('display', 'none');
        }

        function goBack() {
            let url = "{{ route('cms.merchant_branches.payment_link.list') }}"
            window.location.href = url
        }

        function enableChannel(e) {
            var id = $(e).attr("id");
            if (selected_channel.indexOf(id.replace("channel_", "")) == -1) {
                $(e).removeClass("unselected_badge");
                selected_channel.push(id.replace("channel_", ""))
            } else {
                $(e).addClass("unselected_badge");
                selected_channel.splice(selected_channel.indexOf(id.replace("channel_", "")), 1)
            }
        }
    </script>
@endsection
