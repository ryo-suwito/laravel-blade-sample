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
    <link href="{{asset('assets/css/base.css')}}" rel="stylesheet" type="text/css">
{{--    <link href="{{asset('assets/css/bootstrap-rating.css')}}" rel="stylesheet" type="text/css">--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <!-- Core JS files -->
    <script type="text/javascript" src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/notifications/sweet_alert.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/loaders/blockui.min.js')}}"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    {{--<script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{asset('assets')}}//js/pages/dashboard.js"></script>--}}

    {{--<script type="text/javascript" src="{{asset('assets/js/plugins/ui/ripple.min.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('assets/js/base.js')}}"></script>
    <!-- /theme JS files -->

    @yield("html_head")

</head>

<body>

<!-- Main navbar -->
@include('layouts.navbar')
<!-- /main navbar -->


<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    @include('layouts.sidebar')
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Inner content -->
        <div class="content-inner">
            @yield('header')

            <!-- Content area -->
            <div class="content">

                @yield('content')

            </div>
            <!-- /content area -->

            <!-- Footer -->
            @include('layouts.footer')
        </div>
        <!-- /inner content -->
    </div>
    <!-- /main content -->
</div>
<!-- /page content -->

{{--@if ($current_store_user_access_primary != null)
    <div class="modal fade" id="select-current-store-modal" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <form class="" method="post" action="{{ route("cms.store_user_access.change_primary") }}" id="form-change-store-user-access">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">@lang('cms.Select Store')</h4>
                    </div>

                    <div class="modal-body">
                        @if (count($current_store_user_access_list) > 0)
                            @csrf
                            <select class="select2" id="select-current-store" name="store_user_access_id">
                                @foreach ($current_store_user_access_list as $store_user_access)
                                    <option value="{{ $store_user_access->id }}" @if ($current_store_user_access_primary->id == $store_user_access->id) selected="selected" @endif>{{ $store_user_access->store->name }}</option>
                                @endforeach
                            </select>

                            --}}{{--@foreach ($current_store_user_access_list as $store_user_access)
                                <li class="media">
                                    <a href="#" class="media-link store-user-access-trigger" data-object-id="{{ $store_user_access->id }}" data-primary="{{ $store_user_access->primary }}">
                                        <div class="media-body">
                                            <span class="media-heading">{{ $store_user_access->store->name }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach

                            <form class="hidden" method="post" action="{{ route("cms.store_user_access.change_primary") }}" id="form-change-store-user-access">
                                @csrf
                                <input type="hidden" name="store_user_access_id" id="store-user-access-id" value="0"/>
                            </form>--}}{{--
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Close')</button>
                        <input class="btn btn-primary" type="submit" value="@lang('Save Changes')"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif--}}

{{--
<script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/extensions/select.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/tags/tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/tags/tokenfield.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/prism.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/pages/datatables_basic.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/wizards/steps.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-block-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-confirm.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jasny-bootstrap.min.js') }}"></script>--}}

{{--<script type="text/javascript" src="{{ asset('js/base.js') }}"></script>--}}

@yield('scripts')

<script>
    $(document).ready(function() {
        $(".select2").select2();

        $("#select-user-role").select2({
            width: 'element',
        });

        $("#select-user-role").change(function() {
            $("#form-change-user-role").submit();
        });
    });
</script>
<script>
@if (\App\Helpers\S::getFlashSuccess(true) || \App\Helpers\S::getFlashFailed(true))
    $(document).ready(function() {
    @if (\App\Helpers\S::getFlashSuccess(true))
        Swal.fire({
            text: '{{ \App\Helpers\S::getFlashSuccess(true) }}',
            icon: 'success',
            toast: true,
            showConfirmButton: false,
            position: 'top-right'
        });
    @endif
    @if (\App\Helpers\S::getFlashFailed(true))
        Swal.fire({
            text: '{{ \App\Helpers\S::getFlashFailed(true) }}',
            icon: 'error',
            toast: true,
            showConfirmButton: false,
            position: 'top-right'
        });
    @endif
    });
@endif
</script>
@yield('post_scripts')
</body>



</html>
