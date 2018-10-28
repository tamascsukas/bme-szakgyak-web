<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        @yield('header_content')

        <title>@lang('app.name')</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">

        <script type="text/javascript">
            // JS BASE URL
            var BASE_SITE_URL = "{{ URL::to('/') }}";
            if (BASE_SITE_URL.substr(-1) != '/') {
                BASE_SITE_URL += '/';
            }
        </script>
    </head>
    <body>
        @yield('body_content')

        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <script src="https://unpkg.com/ionicons@4.2.4/dist/ionicons.js"></script>

        @yield('js_content')
    </body>
</html>
