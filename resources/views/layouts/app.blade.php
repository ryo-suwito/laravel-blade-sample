<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.png') }}" />

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" />
        <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" />

        @stack('styles')
    </head>
    <body>
        @include('layouts.navbar')

        <div class="page-content">
            @include('layouts.sidebar')

            <div class="content-wrapper">
                <div class="content-inner">
                    {{ $header ?? null }}

                    <div class="content">
                        {{ $slot }}
                    </div>

                    @include('layouts.footer')
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>

        <script src="{{asset('assets/js/plugins/forms/selects/select2.min.js')}}"></script>

        <script>
            $(document).ready(function () {
                $('#select-user-role').select2();

                $("#select-user-role").change(function() {
                    $("#form-change-user-role").submit();
                });
            });
            $("#select-user-role").change(function() {
                $("#form-change-user-role").submit();
            });
        </script>

        @stack('scripts')
    </body>
</html>
