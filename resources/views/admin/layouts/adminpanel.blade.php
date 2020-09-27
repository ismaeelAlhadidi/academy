<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0">
        @yield('preLinks')
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\default.css') }}" />
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\default.css') }}"/>
        @yield('links')
        @yield('style')
        @yield('preScripts')
    </head>
    <body>
        @auth('admin')
            @include('admin\include\header')
            @include('include\notification')
        @endauth
        <div class="main" id="main">
            @yield('content')
        </div>
        <!-- @auth('admin')
            @include('admin\include\footer')
        @endauth -->
        <script type="text/javascript" lang="javascript" src="{{ asset('js\drawCanvas.js') }}"></script>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\ajax.js') }}"></script>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\styleEfect.js') }}"></script>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\notification.js') }}"></script>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\admin\default.js') }}"></script>
        @yield('scripts')
    </body>
</html>