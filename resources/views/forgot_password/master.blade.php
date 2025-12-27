<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env("APP_NAME") }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.png') }}" />

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/icons/fontawesome/styles.min.css')}}" rel="stylesheet" type="text/css">
    {{--<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">--}}
    <link href="{{asset('assets/css/all.min.css')}}" rel="stylesheet" type="text/css">
    {{--<link href="{{asset('assets/css/components.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('assets/css/toastr.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('assets/css/jquery-confirm.min.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('assets/css/jasny-bootstrap.min.css')}}" rel="stylesheet" type="text/css">--}}
    <link href="{{asset('css/base.css')}}" rel="stylesheet" type="text/css">
    {{--    <link href="{{asset('assets/css/bootstrap-rating.css')}}" rel="stylesheet" type="text/css">--}}

<!-- Core JS files -->
    <script type="text/javascript" src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom.js')}}"></script>
    {{--<script type="text/javascript" src="{{asset('assets/js/plugins/loaders/blockui.min.js')}}"></script>--}}
<!-- /core JS files -->

    <!-- Theme JS files -->
    {{--<script type="text/javascript" src="{{asset('assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/styling/switch.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/uploaders/fileinput/plugins/purify.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/uploaders/fileinput/fileinput.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/notifications/bootbox.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/inputs/typeahead/handlebars.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/inputs/alpaca/price_format.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/inputs/alpaca/alpaca.min.js')}}"></script>--}}

    {{--<script type="text/javascript" src="{{asset('assets/js/plugins/star-rating/star-rating.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{asset('assets/js/plugins/bootstrap-rating/bootstrap-rating.js')}}"></script>--}}

    {{--<script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{asset('assets')}}//js/pages/dashboard.js"></script>--}}

    {{--<script type="text/javascript" src="{{asset('assets/js/plugins/ui/ripple.min.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('js/base.js')}}"></script>
    <!-- /theme JS files -->
</head>

<style>
    .alert-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #ED272730;
        border: 1px solid;
        border-radius: 8px;
        border-color: #ED2727;
        padding: 10px 15px;
        margin-bottom: 20px;
        position: relative;
    }
    .close-custom {
        background: none;
        border: none;
        color: white;
        font-size: 1.5em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
</style>

<body>

<!-- Main navbar -->
<div class="navbar navbar-expand-lg navbar-dark navbar-static">
    <div class="navbar-brand ml-2 ml-lg-0">
        <a href="https://yukk.co.id/" target="_blank" class="d-inline-block">
            <img src="{{ asset("assets/images/logo_with_background.png") }}" alt="">
        </a>
    </div>

    <div class="d-flex justify-content-end align-items-center ml-auto">
        <ul class="navbar-nav flex-row">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link">
                    <i class="icon-user-plus"></i>
                    <span class="d-none d-lg-inline-block ml-2">@lang("cms.Register")</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Inner content -->
        <div class="content-inner">

            @yield("content")

            <!-- Footer -->
            <div class="navbar navbar-expand-lg navbar-light border-bottom-0 border-top">
                <span class="navbar-text">
                    &copy; {{date('Y')}} - {{ env("APP_NAME") }} by <a href="https://yukk.co.id/" target="_blank">YUKK Kreasi Indonesia</a>
                </span>

            </div>
            <!-- /footer -->

        </div>
        <!-- /inner content -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->

@yield("script")
</body>

</html>
