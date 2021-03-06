<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        @yield('preLinks')
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap" />
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\default.css') }}" />
        @yield('links')

        @yield('style')

        @yield('preScripts')
    </head>
    <body>
        @include('include.header')
        <div id="app">
            @yield('content')
        </div>
        <div id="loadingDotElement" class="loading-dot-container no-select" style="display: none;">
            <div class="loading-dot-inner-container" style="--n: 5;">
                <div class="loading-dot" style="--i: 0"></div>
                <div class="loading-dot" style="--i: 1"></div>
                <div class="loading-dot" style="--i: 2"></div>
                <div class="loading-dot" style="--i: 3"></div>
                <div class="loading-dot" style="--i: 4"></div>
            </div>
        </div>
        @auth
            @include('include.notification')
            <script type="text/javascript" lang="javascript" src="{{ asset('js\drawCanvas.js') }}"></script>
            <script type="text/javascript" lang="javascript" src="{{ asset('js\ajax.js') }}"></script>
            <script type="text/javascript" lang="javascript" src="{{ asset('js\styleEfect.js') }}"></script>
            <script type="text/javascript" lang="javascript" src="{{ asset('js\notification.js') }}"></script>
            <script type="text/javascript" lang="javascript" src="{{ asset('js\default.js') }}"></script>
            <script type="text/javascript" lang="javascript" src="{{ asset('js\authenticated\default.js') }}"></script>
        @endauth
        @yield('scripts')
    </body>
</html>