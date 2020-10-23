@extends('layouts.app')

@section('title'){{ config('app.name') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\default.css') }}" />
@endsection

@section('content')
    <div class="container no-select">
        <div class="welcome-playlists">
            @if($isMylist && $playlists->count() > 0) <section class="mylist-header">{{ __('masseges.your-list') }}</section>@endif
            @forelse($playlists as $playlist)
                <div class="welcome-playlist no-select" onclick="openPlaylistTemplate('{{ $playlist->id }}', '{{ $playlist->title }}');">
                    <img style="background-image: url({{ asset($playlist->poster) }})"/>
                    <div>
                        <h3>{{ $playlist->title }}</h3>
                        <p>{{ $playlist->description }}</p>
                    </div>
                    <span class="playlist-price">{{ $playlist->price }}</span>
                    <span class="playlist-time">{{ $playlist->availability_time }}</span>
                </div>
            @empty 
                <div class="empty-playlists">{{ __('masseges.no-playlists-available-now') }}</div>
            @endforelse
            {{ $playlists->links() }}
        </div>
    </div>
    <div id="playlistTemplate" class="pop-up-template template-of-this-playlist big-template no-select" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfPlaylistTemplate" width="25" height="25"></canvas></div></header>
        <div id="contentOfPlaylistTemplate" class="welcome-playlist-opend-template">
            <section>
                <p id="titleInPlaylistTemplate"></p>
                @if(! $isMylist)<div><a id="subscriptionButtonInPlaylistTemplate"> {{ __('input.subscriptions-of-this-playlist') }} </a></div>@endif<div><a id="showButtonInPlaylistTemplate" href="{{ route('playlist.show') }}"> {{ __('input.show-playlist') }} </a></div>
            </section>
            <h2 id="opinionsHeader">{{ __('masseges.users-opinions-of-this-playlist') }}</h2>
            <div id="opinionsContainer" class="opinions-outer-container">
                <section id="opinionsSection" class="opinions-inner-container"></section>
            </div>
        </div>
    </div>
    @if(! $isMylist)
    <form id="payPlaylistForm" method="post" action="{{ asset('/pay-playlist') }}" style="display:none !important">
        @csrf
        <input type="hidden" id="idInputOfSelectedPlaylistToPay" name="id"/>
    </form>
    @endif
    @include('include.authenticatedFooter')
@endsection
@section('scripts')
        <script type="text/javascript" lang="javascript">
            var exitButtonCanvasOfPlaylistTemplate = document.getElementById('exitButtonCanvasOfPlaylistTemplate'),
                playlistTemplate = document.getElementById('playlistTemplate'),
                playlistNotForUseAlert = '{{ __('masseges.playlist-deleted') }}';

            if(exitButtonCanvasOfPlaylistTemplate != null) {
                exitButtonCanvasOfPlaylistTemplate.width = 25;
                exitButtonCanvasOfPlaylistTemplate.height = 25;
                if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfPlaylistTemplate,'#ffffff');
                exitButtonCanvasOfPlaylistTemplate.onclick = function () {
                    if(typeof(closeBobUpTemplate) == "function") closeBobUpTemplate(playlistTemplate);
                };
            }

            @if(! $isMylist)
            var subscriptionButtonInPlaylistTemplate = document.getElementById('subscriptionButtonInPlaylistTemplate'),
                payPlaylistForm = document.getElementById('payPlaylistForm');
            if(subscriptionButtonInPlaylistTemplate != null) {
                subscriptionButtonInPlaylistTemplate.onclick = function () {
                    payPlaylistForm.submit();
                };
            }
            @endif
            @if(session()->has('error'))
                showPopUpMassage('{{ session()->get('error') }}',null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
                {{ session()->forget('error') }}
            @endif
        </script>
@endsection
