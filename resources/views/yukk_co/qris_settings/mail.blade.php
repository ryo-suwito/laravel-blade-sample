@extends('layouts.master')

@section("html_head")
    <style>
        .table-no-padding td {
            padding: 0 4px !important;
        }
    </style>
@endsection
@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Mail")</h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <a href="{{ route("yukk_co.qris_setting.list") }}" class="breadcrumb-item">@lang("cms.Manage QRIS Settings")</a>
                    <span class="breadcrumb-item active">@lang("cms.Mail Preview")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <form action="{{ route("yukk_co.qris_setting.mail", $branch->id) }}" method="get">
        <div class="card p-5">
            <div class="card-body mx-auto w-50 border border-2 p-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Email To")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="email_to" name="email_to">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Email CC")</label>
                            <div class="col-lg-8">
                                <input type="text" name="email_cc" id="email_cc" class="form-control" value="{{ @$cc }}">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">@lang("cms.Recipient Name")</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="recipient_name" name="recipient_name">
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                @if($branch->qr_type == 'b')
                    <div class="col-lg-12 row">
                        <div class="form-group col">
                            <label class="col-form-label mr-1">@lang("cms.Static")</label>
                            <input type="checkbox" id="is_static" name="is_static" class="form-group mt-1 checkbox-static" checked onchange="hiddenStatic()">
                        </div>
                        <div class="col">
                            @foreach($edc_dynamic as $index => $edc)
                                <div class="form-group col">
                                    <label class="col-form-label mr-1">@lang("cms.Dynamic") {{ $index }}</label>
                                    <input type="checkbox" id="is_dynamic-{{ $index }}" name="is_dynamic[{{$edc->id}}]" class="form-group mt-1 checkbox-dynamic" checked onchange="hiddenDynamic({{$index}})">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($branch->qr_type == 'd')
                    @foreach($edc_dynamic as $index => $edc)
                        <div class="form-group col">
                            <label class="col-form-label mr-1">@lang("cms.Dynamic") {{ $index + 1 }}</label>
                            <input type="checkbox" id="is_dynamic-{{ $index }}" name="is_dynamic[{{$edc->id}}]" class="form-group mt-1 checkbox-dynamic" checked onchange="hiddenDynamic({{$index}})">
                        </div>
                    @endforeach
                @elseif($branch->qr_type == 's')
                    <div class="col-lg-12 row">
                        <div class="form-group col">
                            <label class="col-form-label mr-1">@lang("cms.Static")</label>
                            <input type="checkbox" id="is_static" name="is_static" class="form-group mt-1 checkbox-static" checked onchange="hiddenStatic()">
                        </div>
                    </div>
                @endif

                <hr>

                <div class="col-lg-12">
                    <div class="row">
                        <div class="justify-content-center mx-auto">
                            <button class="form-group mr-2" type="submit">Email Now</button>
                            <a href="#" class="form-group" id="preview-trigger">See Preview</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-preview" class="modal form-group" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-preview">
            <div class="mx-auto w-50 border border-2 p-5 my-5 bg-dark">
                <p>
                    To:
                    <span id="preview-email">
                        {{ $partner_login->email ? : $partner_login->username }}
                    </span>
                    <br>
                    <span>
                        Subject: YUKK QRIS Credentials {{ \Illuminate\Support\Str::upper($branch->name) }}
                    </span>
                    <br>
                    CC: <span id="preview-cc">{{ $cc }}</span>
                </p>

                <br>

                <p class="font-weight-bold">
                    Dear <span id="preview-name">{{ $partner_login->name ? : $partner_login->username }}</span>,
                </p>
                <p>
                    Dapat kami informasikan bahwa QRIS {{ $branch->name }} telah berhasil diaktivasi dan siap digunakan.
                </p>
                <p>
                    Silahkan mengakses Dashboard YUKK melalui <a href="https://dashboard.yukk.co.id/">https://dashboard.yukk.co.id/</a>
                    dan Aplikasi YUKK Merchant melalui <a href="https://play.google.com/store/apps/details?id=com.teltics.yukkers.android">Playstore</a> atau <a href="https://itunes.apple.com/id/app/yukkers/id1338653047">Appstore</a> dengan informasi login sebagai berikut:
                </p>
                <p>
                    Username: <a class="font-weight-bold">{{ $partner_login->username }}</a>
                    <br>
                    Password: 123456
                </p>
                <p style="font-style: italic">
                    Catatan: harap lakukan perubahan kata sandi setelah berhasil login.
                </p>
                <br>
                <div id="email-non-edc">
                    <div >
                        Berikut Credentials yang dapat digunakan.
                    </div>
                    <div dir="ltr">
                        <table class="table table-bordered table-no-padding">
                            {{-- Branch Name --}}
                            <thead>
                            <tr style="background-color: yellow">
                                <td style="color: #000000;font-weight: 500">
                                    Merchant Branch
                                </td>
                                <td style="color: #000000;font-weight: 500">
                                    : {{ \Illuminate\Support\Str::upper($branch->name) }}
                                </td>
                            </tr>
                            </thead>
                            {{-- QRIS Dynamic --}}
                            @foreach($edc_dynamic as $index => $edc)
                                <thead id="head-dynamic-{{$index}}">
                                    <tr style="background-color: #333333;">
                                        <td colspan="2" style="font-weight: 500">
                                            Credentials QRIS Dynamic
                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="body-dynamic-{{$index}}">
                                @foreach($edc->partner_logins as $partner_login)
                                    @if($partner_login->grant_type == 'CLIENT_ID_SECRET')
                                        <tr>
                                            <td>
                                                Client ID
                                            </td>
                                            <td>
                                                : {{ $partner_login->username }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Client Secret
                                            </td>
                                            <td>
                                                : {{ $partner_login->password }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            @endforeach
                            {{-- QRIS Static --}}
                            @foreach($edc_static as $edc)
                                <thead id="head-static">
                                <tr style="background-color: #333333;">
                                    <td colspan="2" style="font-weight: 500">
                                        Credentials QRIS Static
                                    </td>
                                </tr>
                                </thead>
                                <tbody id="body-static">
                                <tr>
                                    <td>
                                        Client ID
                                    </td>
                                    <td>
                                        : {{ $edc->client_id }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Client Secret
                                    </td>
                                    <td>
                                        : {{ $edc->client_secret }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        MPAN
                                    </td>
                                    <td>
                                        : {{ $edc->mpan }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        MID
                                    </td>
                                    <td>
                                        : {{ $edc->mid }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        NMID
                                    </td>
                                    <td>
                                        : {{ $edc->nmid_pten }}
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                    <br>
                    <span id="preview-text-sticker">Terlampir sticker QRIS Static sebagai Refrensi.</span>
                    <p>
                        Terima Kasih
                    </p>
                </div>
                <br>
                <p>
                    Best Regards,
                </p>
                <p>
                    Yukk Team
                </p>

                <br>

                <div class="row">
                    <div class="justify-content-center mx-auto mt-5">
                        <button type="submit" class="form-group mr-2">Send Email</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        function hiddenStatic() {
            var x = document.querySelector('#is_static').checked;
            if (x === false){
                $('#head-static').hide();
                $('#body-static').hide();
                $('#preview-text-sticker').hide();
            }else{
                $('#head-static').show()
                $('#body-static').show()
                $('#preview-text-sticker').show();
            }
        }

        function hiddenDynamic(numberx) {
            var x = document.querySelector('#is_dynamic-'+numberx).checked;
            if (x === false){
                $('#head-dynamic-'+numberx).hide();
                $('#body-dynamic-'+numberx).hide();
            }else{
                $('#head-dynamic-'+numberx).show()
                $('#body-dynamic-'+numberx).show()
            }
        }

        $(function() {
            $("#preview-trigger").click(function(e) {
                e.preventDefault();
                $("#preview-email").html($("#email_to").val());
                $("#preview-name").html($("#recipient_name").val());
                $("#preview-cc").html($("#email_cc").val());
                $("#modal-preview").modal();

                let dynamic = $(".checkbox-dynamic").is(":checked");
                let statics = $(".checkbox-static").is(":checked");

                if (dynamic || statics){
                    $('#email-non-edc').show();
                }else{
                    $('#email-non-edc').hide();
                }

            });
        });
    </script>
@endsection
