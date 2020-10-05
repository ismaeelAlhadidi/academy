@extends('layouts.app')

@section('title'){{ config('app.name') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\default.css') }}" />
@endsection

@section('content')
    <div class="container no-select">
        <div class="welcome-playlists">
            @forelse($sessions as $session)
                <div class="welcome-playlist no-select" onclick="openSessionTemplate('{{ $session->id }}');">
                    <img style="background-image: url({{ asset($session->poster) }})"/>
                    <div>
                        <h3>{{ $session->name }}</h3>
                        <p>{{ $session->for_who }}</p>
                    </div>
                    <span class="playlist-price">{{ $session->price . ' $' }}</span>
                    <span class="playlist-time">{{ $session->duration }}</span>
                </div>
            @empty 
                <div class="empty-playlists">{{ __('masseges.no-sessions-available-now') }}</div>
            @endforelse
            {{ $sessions->links() }}
        </div>
    </div>
    <div id="SessionTemplate" class="pop-up-template template-of-this-playlist big-template no-select" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfSessionTemplate" width="25" height="25"></canvas></div></header>
        <div id="contentOfSessionTemplate" class="welcome-playlist-opend-template offer-template">
            <div id="sessionDataDiv"></div>
            <footer><div><a id="requestThisSession">{{ __('input.request-the-session') }}</a></div></footer>
        </div>
    </div>
    <div id="requestToSessionTemplate" class="pop-up-template template-of-this-playlist no-select" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfRequestToSessionTemplate" width="25" height="25"></canvas></div></header>
        <div id="contentOfRequestToSessionTemplate" class="welcome-playlist-opend-template offer-template request-session">
            <section><section><span>{{ __('input.date') }}</span><input id="inputDateOfRequestToSession" type="date" name="date" autocomplete="off" /></section><section><span>{{ __('input.time') }}</span><input id="inputTimeOfRequestToSession" type="time" name="time" autocomplete="off" /></section></section>
            <footer><div><a id="buttonSendRequestToSession">{{ __('masseges.ok') }}</a></div></footer>
        </div>
    </div>
    @include('include.authenticatedFooter')
@endsection
@section('scripts')
        <script type="text/javascript" lang="javascript">
            var exitButtonCanvasOfSessionTemplate = document.getElementById('exitButtonCanvasOfSessionTemplate'),
                SessionTemplate = document.getElementById('SessionTemplate'),
                SessionNotForUseAlert = '{{ __('masseges.session-deleted') }}',
                sessionOfferLang = [
                        '{{ __('input.for-who') }}',
                        '{{ __('input.for-who-not') }}',
                        '{{ __('input.benefits') }}',
                        '{{ __('input.notes') }}',
                    ],
                requestThisSession = document.getElementById('requestThisSession'),
                requestToSessionTemplate =document.getElementById('requestToSessionTemplate'),
                exitButtonCanvasOfRequestToSessionTemplate = document.getElementById('exitButtonCanvasOfRequestToSessionTemplate'),
                buttonSendRequestToSession = document.getElementById('buttonSendRequestToSession'),
                inputDateOfRequestToSession = document.getElementById('inputDateOfRequestToSession'),
                inputTimeOfRequestToSession = document.getElementById('inputTimeOfRequestToSession'),
                TOKEN = '{{ csrf_token() }}';
            
            if(exitButtonCanvasOfSessionTemplate != null) {
                exitButtonCanvasOfSessionTemplate.width = 25;
                exitButtonCanvasOfSessionTemplate.height = 25;
                if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfSessionTemplate,'#ffffff');
                exitButtonCanvasOfSessionTemplate.onclick = function () {
                    if(typeof(closeBobUpTemplate) == "function") closeBobUpTemplate(SessionTemplate);
                };
            }
            if(exitButtonCanvasOfRequestToSessionTemplate != null) {
                exitButtonCanvasOfRequestToSessionTemplate.width = 25;
                exitButtonCanvasOfRequestToSessionTemplate.height = 25;
                if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfRequestToSessionTemplate,'#ffffff');
                exitButtonCanvasOfRequestToSessionTemplate.onclick = function () {
                    if(typeof(closeBobUpTemplate) == "function") closeBobUpTemplate(requestToSessionTemplate);
                };
            }
        </script>
@endsection