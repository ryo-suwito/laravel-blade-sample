@extends('layouts.master')

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
                    <span class="breadcrumb-item active">@lang("cms.Manage QRIS Settings")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card p-5">
       <div class="card-body mx-auto w-50 border border-2 p-5">
           <p>
               To: {{ $partner_login->email ? : $partner_login->username }}
               <br>
               Subject: YUKK QRIS Dynamic & Static Credentials {{ $edc_static->branch->name }}
               <br>
               CC: {{ $cc }}
           </p>

           <p>
               Dear {{ $partner_login->name ? : $partner_login->username }},
           </p>
           <p>
               Dapat kami informasikan bahwa QRIS {{ $edc_static->branch->name }} telah berhasil diaktivasi dan siap digunakan. Terlampir sticker QRIS statik sebagai referensi.
           </p>
           <p>
               Silahkan mengakses Dashboard YUKK melalui https://dashboard.yukk.co.id/ dan Aplikasi YUKK Merchant melalui Appstore atau Playstore) dengan informasi login sebagai berikut:
           </p>
           <p>
               Username: {{ $partner_login->username }}
               <br>
               Password: 123456
           </p>
           <p style="font-style: italic">
               Catatan: harap lakukan perubahan kata sandi setelah berhasil login.
           </p>
           <p>
               Berikut Credentials Production QRIS Dynamic & Static yang dapat digunakan.
           <h1 style="background-color: #ffff00">Merchant Branch </h1>
           <h1>Credentials QRIS Dynamic</h1>
           @foreach($edc_dynamic as $edc)
               <p>Client ID : {{ $edc->client_id ? : '' }}</p>
               <p>Client Secret : {{ $edc->client_secret ? : '' }}</p>
               <hr>
           @endforeach
           <h1>Credentials QRIS Static</h1>
           <p>Client ID : {{ $edc_static->client_id }}</p>
           <p>Client Secret : {{ $edc_static->client_secret }}</p>
           <p>MPAN : {{ $edc_static->mpan }}</p>
           <p>MID : {{ $edc_static->mid }}</p>
           <p>NMID : {{ $edc_static->nmid_pten }}</p>
           <br>
           Terlampir sticker QRIS Static sebagai Refrensi.
           </p>
           <p>
               Terima Kasih
           </p>
           <p>
               Best Regards,
           </p>
           <br>
           <br>
           <p>
               YUKK Acquisition Team
           </p>
       </div>
    </div>
@endsection

@section('scripts')
@endsection
