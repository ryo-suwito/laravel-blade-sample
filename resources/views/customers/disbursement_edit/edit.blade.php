@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Edit Bank Account")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Edit Bank Account")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-center">@lang("cms.Edit Bank Account")</h5>
            <hr>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route("cms.customer.disbursement_edit.edit") }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Bank Name")</label>
                            <div class="col-lg-4">
                                <select form="confirmationModal" class="form-control" name="bank_name" id="bank_name">
                                    @foreach ($bank_list as $bank)
                                        <option value="{{ @$bank->id }}" @if(@$bank->id == $response->bank->id) selected @endif>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="col-lg-2 col-form-label">@lang("cms.Account Name")</label>
                            <div class="col-lg-4">
                                <input form="confirmationModal" name="account_name" type="text" class="form-control" value="{{ $response->account_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Branch Name")</label>
                            <div class="col-lg-4">
                                <input form="confirmationModal" name="branch_name" type="text" class="form-control" value="{{ $response->branch_name }}">
                            </div>

                            <label class="col-lg-2 col-form-label">@lang("cms.Account Number")</label>
                            <div class="col-lg-2">
                                <input form="confirmationModal" name="account_number" type="text" class="form-control" value="{{ $response->account_number }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Email")</label>
                            <div class="col-lg-4">
                                <input form="confirmationModal" name="email" type="text" class="form-control" readonly="" value="{{ \App\Helpers\S::getUser() ? \App\Helpers\S::getUser()->username : "" }}">
                            </div>

                            <label class="col-lg-2 col-form-label">@lang("cms.OTP")</label>
                            <div class="col-lg-2">
                                <input form="confirmationModal" name="otp" type="text" class="form-control" value="">
                            </div>
                            <div class="col-lg-2">
                                <button id="button_otp" type="button" class="btn btn-info border-secondary">@lang("cms.Request OTP")</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">@lang("cms.Bank Cover")</label>
                            <div class="col-lg-4">
                                <input form="confirmationModal" id="cover_bank" name="cover_bank" type="file" class="form-control">
                            </div>
                            <div class="col">
                                <label id="otp_text" class="col-lg-6 d-none">*Tidak Menerima Kode OTP? Tunggu xx untuk dikirim ulang</label>
                                <label id="otp_alert" class="col-lg-6 text-danger d-none">'Silahkan masukan kode OTP yang kami kirimkan ke alamat email {{ \App\Helpers\S::getUser()->username }}'</label>
                            </div>
                            </label>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-2">
                            </div>
                            <div class="col-lg-4">
                                <img id="image_preview" class="img-fluid img-thumbnail d-none">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="justify-content-center row">
                <a class="btn btn-secondary col-sm-1 mr-1" href="{{ route("cms.customer.disbursement_edit.setting") }}">@lang("cms.Cancel")</a>
                <button id="submit" type="button" class="col-sm-1 btn btn-primary justify-content-center" data-toggle="modal" data-target="#confirmation_modal">@lang("cms.Submit")</button>
            </div>
        </div>

        <div class="modal fade" id="confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="confimationModal" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <form action="{{ route("cms.customer.disbursement_edit.edit") }}" method="POST" id="confirmationModal" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="demoModalLabel">Peringatan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                Apakah anda yakin ingin mengubah data bank disbursement yang akan memakan waktu 1x24 Jam Kerja?
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang("cms.No")</button>
                            <button type="submit" class="btn btn-primary">@lang("cms.Yes")</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('post_scripts')
    <script defer>
        var countdown = null;

        $('#button_otp').click(function(e) {


            $.ajax({
                url: "{{ route("cms.customer.disbursement_edit.request_otp") }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                method: "POST",
                beforeSend: function() {
                    $("#button_otp").attr("disabled", "disabled");
                },
                complete: function() {
                    $("#button_otp").removeAttr("disabled");
                },
                success: function(dataString) {
                    var data = JSON.parse(dataString);
                    if (data.status_code == "6000") {
                        $("#otp_text").removeClass("d-none");
                        $("#otp_alert").removeClass("d-none")
                        $("#button_otp").addClass("d-none");

                        timer = 2 * 60;
                        countdown = setInterval(function() {
                            $("#otp_text").html(`*Tidak Menerima Kode OTP? Tunggu ${timer} untuk dikirim ulang`);
                            timer--;

                            if (timer <= 0) {
                                clearInterval(countdown);

                                $("#otp_text").addClass("d-none");
                                $("#button_otp").removeClass("d-none");
                            }
                        }, 1000);
                    } else {
                        Swal.fire({
                            text: data.status_message,
                            icon: 'error',
                            toast: true,
                            showConfirmButton: false,
                            position: 'top-right'
                        });
                    }
                },
            });
        });

        cover_bank.onchange = evt => {
            const [file] = cover_bank.files
            if (file) {
                image_preview.src = URL.createObjectURL(file);
                $("#image_preview").removeClass("d-none");
                }
        }
    </script>
@endsection

