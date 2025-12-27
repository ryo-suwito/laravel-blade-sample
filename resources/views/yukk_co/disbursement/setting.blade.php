@extends('layouts.master')

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title">
                <h4>@lang("cms.Split Disbursement Amount")</h4>
            </div>

            <div class="my-sm-auto ml-sm-auto mb-3 mb-sm-0">
                {{--<button type="button" class="btn btn-primary w-100 w-sm-auto">Button</button>--}}
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ route("cms.index") }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang("cms.Home")</a>
                    <span class="breadcrumb-item active">@lang("cms.Split Disbursement Amount")</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Split Disbursement Amount")</h5>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route("cms.yukk_co.disbursement.edit") }}">
                @csrf
                <div class="card-header">
                    <h5 class="card-title">@lang("cms.NON-BCA")</h5>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="ml-4">Merchant portion dipecah per:</span></label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="non_bca_max_transfer_amount" name="non_bca_max_transfer_amount" value="{{ number_format(@$non_bca_max_transfer_amount, 2, ',', '.') }}">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="ml-4">Jika nominal pecahan pada:</span></label>
                            <div class="col-lg-2">
                                <label id="non_bca_max_transfer"></label>
                            </div>
                            <div class="col-lg-1">
                                <label> dan </label>
                            </div>
                            <div class="col-lg-2">
                                <label id="non_bca_max_transfer_2"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="ml-4">Maka dilakukan pemecahan per:</span></label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="non_bca_max_transfer_fraction_amount" name="non_bca_max_transfer_fraction_amount" value="{{ number_format(@$non_bca_transfer_fraction_amount, 2, ',', '.') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h5 class="card-title">@lang("cms.BCA")</h5>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="ml-4">Merchant portion dipecah per:</span></label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="bca_max_transfer_amount" name="bca_max_transfer_amount" value="{{ number_format(@$bca_max_transfer_amount, 2, ',', '.') }}">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="ml-4">Jika nominal pecahan pada:</span></label>
                            <div class="col-lg-2">
                                <label id="bca_max_transfer"></label>
                            </div>
                            <div class="col-lg-1">
                                <label> dan </label>
                            </div>
                            <div class="col-lg-2">
                                <label id="bca_max_transfer_2"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"><span class="ml-4">Maka dilakukan pemecahan per:</span></label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" id="bca_transfer_fraction_amount" name="bca_transfer_fraction_amount" value="{{ number_format(@$bca_transfer_fraction_amount, 2, ',', '.') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary btn-block" type="submit">@lang("cms.Submit")</button>

            </form>
        </div>


    </div>
@endsection

@section('post_scripts')

<script defer>
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^.\d]/g, "").replace(/[^,\d]/g, "").toString()

        var digitAkhir = number_string.substr(-2)
        var digitAwal = number_string.substr(0, number_string.length - 2)
        number_string = digitAwal.concat(",", digitAkhir)

        split = number_string.split(",")
        sisa = split[0].length % 3
        rupiah = split[0].substr(0, sisa)
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return rupiah;
    }

    function parse (amount){
        let value = String(amount).replaceAll(".","")
        value = String(value).replaceAll(",",".")

        value = parseFloat(value) + 15000

        value = value.toFixed(2)
        value = String(value).replaceAll(".",",")

        return value;
    }

    var non_bca_transfer_amount = document.getElementById("non_bca_max_transfer_amount")
    var non_bca_fraction_transfer_amount = document.getElementById("non_bca_max_transfer_fraction_amount")

    var bca_transfer_amount = document.getElementById("bca_max_transfer_amount")
    var bca_fraction_transfer_amount = document.getElementById("bca_transfer_fraction_amount")

    non_bca_transfer_amount.addEventListener("input",updaterForNonBca)
    non_bca_fraction_transfer_amount.addEventListener("input",updaterForNonBca)
    if(non_bca_transfer_amount.value && non_bca_transfer_amount.value != undefined){
        var amount = non_bca_transfer_amount.value.replaceAll(".","");
        document.getElementById("non_bca_max_transfer").innerHTML = "> "+ amount
        document.getElementById("non_bca_max_transfer_2").innerHTML = "< " + parse(non_bca_transfer_amount.value)
    }

    function updaterForNonBca() {
        non_bca_transfer_amount.value = formatRupiah(non_bca_transfer_amount.value)
        non_bca_fraction_transfer_amount.value = formatRupiah(non_bca_fraction_transfer_amount.value)
        var x = non_bca_transfer_amount.value
        var amount = x.replaceAll(".","")
        if (non_bca_transfer_amount.value && non_bca_transfer_amount.value != undefined) {
            document.getElementById("non_bca_max_transfer").innerHTML = "> " + amount
            const total = parse(amount)
            document.getElementById("non_bca_max_transfer_2").innerHTML = "> " + total;
        }
    }

    bca_transfer_amount.addEventListener("input",updaterForBca)
    bca_fraction_transfer_amount.addEventListener("input",updaterForBca)
    if(bca_transfer_amount.value && bca_transfer_amount.value != undefined){
        var amount = bca_transfer_amount.value.replaceAll(".","");
        document.getElementById("bca_max_transfer").innerHTML = "> "+ amount
        document.getElementById("bca_max_transfer_2").innerHTML = "< " + parse(bca_transfer_amount.value)
    }

    function updaterForBca() {
        bca_transfer_amount.value = formatRupiah(bca_transfer_amount.value)
        bca_fraction_transfer_amount.value = formatRupiah(bca_fraction_transfer_amount.value)
        var x = bca_transfer_amount.value
        var amount = x.replaceAll(".","")
        if (bca_transfer_amount.value && bca_transfer_amount.value != undefined) {
            document.getElementById("bca_max_transfer").innerHTML = "> " + amount
            const total = parse(amount)
            document.getElementById("bca_max_transfer_2").innerHTML = "> " + total
        }
    }
</script>

@endsection
