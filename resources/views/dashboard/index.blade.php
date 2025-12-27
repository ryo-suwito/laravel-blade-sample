@extends('layouts.master')

@section('html_head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/css/bootstrap-tour-standalone.css" />
@endsection

@section('header')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content d-sm-flex">
            <div class="page-title w-100">
                @if($env_app !== 'production')
                    @if($banner === true)
                        <div style="background-image: url('/assets/images/banner.png'); background-size: 2200px; background-repeat: no-repeat; background-position: center"  id="myTour">
                            <div class="rounded py-3 mb-4">
                                <div class="text-center">
                                    <span style="line-height: 21px; font-size: 17px">
                                        @lang("cms.One more step! Complete your registration")
                                    </span>
                                    <a class="border rounded mx-2 px-3 py-1 text-white" href="{{ $url_request_live }}">
                                        @lang("cms.here")
                                    </a>
                                </div>
                            </div>
                        </div>
                  @endif
                @endif
                <h4><span class="font-weight-semibold">{{ env("APP_NAME", "YUKK Dashboard") }}</span></h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-sm-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <span class="breadcrumb-item active">@lang("cms.Home")</span>
                </div>

                <a href="#" class="header-elements-toggle text-body d-sm-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')

    <!-- Basic card -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">@lang("cms.Home")</h5>
        </div>

        <div class="card-body">
            <h2 class="font-weight-semibold">@lang("cms.Welcome"), {{ \App\Helpers\S::getUserName() }}</h2>
        </div>
    </div>
    <!-- /basic card -->

    <!-- On-Boarding Page -->

{{--    <div class="text-white">--}}
{{--        <a href="#" data-toggle="modal" data-target="#modal-webhook-detail" class="dropdown-item"><i class="icon-search4"></i> @lang("cms.Detail")</a>--}}
{{--    </div>--}}

    @if(\App\Helpers\S::getTargetType() == "MERCHANT_BRANCH")
        @if($on_boarding !== "")
            <div id="modal-on-boarding" class="modal fade modal-fullscreen" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl w-100 h-100">
                    <div class="modal-content w-100 h-100">
                        <div class="modal-header">
                        </div>

                        <div class="modal-body">
                            <div id="OnBoardingCarousel" class="swiper">
                                @if($on_boarding === "")
                                    Inbound Not Set Yet!
                                @else
                                    <div class="swiper-wrapper">
                                        @foreach($on_boarding['data'] as $pages)
                                            <div class="swiper-slide" {{ $pages['position'] == 1 ? 'active' : ''  }}">
                                            <div class="container">
                                                <div class="d-flex flex-column">
                                                    <div class="row justify-content-center mx-auto">
                                                        {{ $pages['position'] }}
                                                    </div>
                                                    <div class="d-flex justify-content-center flex-column flex-lg-row mt-5">
                                                        <div class="w-75 w-lg-50">
                                                            <img class="show-large w-100" src="{{ config('website.base_url').'storage/'.$pages['dekstop_image_path'] }}">
                                                            <img class="show-small w-100" src="{{ config('website.base_url').'storage/'.$pages['mobile_image_path'] }}">
                                                        </div>
                                                        <div class="d-flex flex-column w-100 w-lg-50 mt-3 text-center text-lg-left mt-lg-0 ml-lg-5">
                                                            <h3>
                                                                {{ $pages['title'] }}
                                                            </h3>
                                                            <span>
                                                                {!! $pages['content'] !!}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer d-flex flex-column justify-content-center align-items-center mt-4">
                        <div class="d-flex flex-row">
                            <button id="btnPrev" type="button" class="btn px-5 mr-2" style="border-color: #CC2F8E" >
                                Previous
                            </button>

                            <button id="btnNext" type="button" class="btn px-5" style="background-color: #CC2F8E" >
                                Next
                            </button>

                            <button id="btnSkip" type="button" class="skip btn px-5" style="background-color: #CC2F8E">
                                Finish
                            </button>
                        </div>

                        <button type="button" class="btn btn-light skip px-5 mt-2" data-dismiss="modal">Skip</button>
                    </div>
                </div>
            </div>
            </div>
        @endif
    @endif
    <style>
        .popover-content {
            color: #000000; !important;
        }

        .modal.modal-fullscreen .modal-dialog {
            width:100vw;
            height:100vh;
            margin:0;
            padding:0;
            max-width:none;
        }

        .modal.modal-fullscreen .modal-content {
            height:auto;
            height:100vh;
            border-radius:0;
            border:none;
        }

        .modal.modal-fullscreen .modal-body {
            overflow-y:auto;
        }

    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-element-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/js/bootstrap-tour-standalone.min.js" ></script>

    <script type="text/javascript">
        const cache_available = {{ $cache }};
        let count = 1;
        let onBoardingMaxCount = {{ $on_boarding == '' ? 0 : $on_boarding['count'] }};

        if( cache_available !== 2 ){
            $('#modal-on-boarding').modal('show');
        }

        function showNextOrSkip(){
            if (count === onBoardingMaxCount){
                $('#btnNext').hide();
                $('#btnSkip').show();
            }else{
                $('#btnNext').show();
                $('#btnSkip').hide();
            }

            if (count == 1){
                $('#btnPrev').hide();
            }else{
                $('#btnPrev').show();
            }
        }
        showNextOrSkip();
        $(".skip").click(function (e) {
            e.onclick(
                $.ajax({
                    url: '/api/handleCache'
                }),
                location.reload(),
            );
        });

        const swiper = new Swiper('.swiper', {
           allowTouchMove: false,
        });

        $("#btnPrev").click(function (e) {
            swiper.slidePrev();
            if (count > 1){
                count--;
            }

            showNextOrSkip();
        }).delay(1000);

        $("#btnNext").click(function (e) {
            swiper.slideNext();
            if (count < onBoardingMaxCount){
                count++;
            }

            showNextOrSkip();

            $("#btnNext").hide();
            $("#btnPrev").hide();
            setTimeout(function (){
                if (count !== onBoardingMaxCount){
                    $("#btnNext").show();
                }else{

                }
                $("#btnPrev").show();
            }, 1000);
        });

        let width = window.innerWidth;
        if(width < 1024){
            $(".show-large").hide();
            $(".show-small").show();
        }else{
            $(".show-small").hide();
            $(".show-large").show();
        }
    </script>

    <script>
        let tour = new Tour({
            placement: "bottom",
            keyboard: true,
            backdropContainer: 'body',
            backdropPadding: 0,
            storage: window.sessionStorage,
            next: 0,
            prev: 0,
            debug: true,
            template: function () {
                return (
                        "<div class='popover tour'>" +
                        "<div class='arrow'></div>" +
                        "<h3 class='popover-title'></h3>" +
                        "<div class='popover-content'></div>" +
                        "<div class='popover-navigation'>" +
                        "<span data-role='separator'> </span>" +
                        "<button class='btn btn-default' data-role='end'>Tutup</button>" +
                        "</div>" +
                        "</div>"
                );
            },
            steps: [{
                element: "#myTour",
                placement: 'bottom',
                content: "Agar dapat mengakses seluruh layanan, silahkan melengkapi registrasi"
            }],
            backdrop: true,
        });

        tour.init();

        tour.start();

        $(document).ready(function() {
            $(".dataTable").dataTable({
                "columnDefs": [{ }],
                "aaSorting": [],
            });

            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function () {
            window.history.go(1);
            };
        });
    </script>

@endsection
