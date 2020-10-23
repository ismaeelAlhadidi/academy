@extends('layouts.app')

@section('title'){{ config('app.name') }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\default.css') }}" />
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\mySessions.css') }}" />
@endsection

@section('style')
    <style type="text/css">
        .main {
            padding-top: 0px;
        }
        .pagination {
            direction: rtl;
        }
        @media (max-width: 390px) {
            th:nth-of-type(2), td:nth-of-type(2) {
                display: none;
            }
        }
        @media (max-width: 568px) {
            .main {
                font-size: 12px;
            }
            .user-image-in-sessions-table {
                width: 30px;
                height: 30px;
            }
            .text-feild-in-sessions-table, .toggleAcceptButton-in-sessions-table {
                margin-top: 5px;
            }
            .toggleAcceptButton-in-sessions-table {
                padding: 4px 6px;
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container no-select">
    @if($sessions->count() > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('masseges.name') }}</th>
                        <th>{{ __('masseges.session-name') }}</th>
                        <th>{{ __('masseges.session-time') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        <tr>
                            <td><img class="user-image-in-sessions-table lazyload" data-src="{{ asset($session->sessionOffer->poster) }}" loading="lazy" /></td>
                            <td><span class="text-feild-in-sessions-table">{{ $session->user->first_name }}</span></td>
                            <td><span class="text-feild-in-sessions-table">{{ $session->sessionOffer->name }}</span></td>
                            <td><span class="text-feild-in-sessions-table">{{ $session->time}}</span></td>
                            <td>
                                @if(! $session->taken)
                                    <button id="SessionOnlineAdmissionButton{{ $session->id }}" 
                                            class="toggleAcceptButton-in-sessions-table">
                                    {{ $session->admission ? __('masseges.admission-ok') : __('masseges.admission-wating') }}
                                    </button>
                                @else 
                                    <canvas class="correct-sign" width="40" height="40"></canvas>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $sessions-> links() }}
        @else
            <div class="empty-playlists">{{ __('masseges.no-sessions-in-your-list') }}</div>
        @endif
    </div>
    @include('include.authenticatedFooter')
@endsection
@section('scripts')
        <script type="text/javascript" lang="javascript">
            ( function loadingImage() {
                var lazyURL = "{{ asset('js/lazysizes.min.js') }}";
                if('loading' in HTMLImageElement.prototype) {
                    const images = document.querySelectorAll('img.lazyload');
                    images.forEach(img => {
                        img.src = img.dataset.src;
                    });
                } else {
                    const script = document.createElement('script');
                    script.src = lazyURL;
                    document.body.appendChild(script);
                }
            } )();

            ( function drawCanvas() {
                if(typeof(drawCorrectSign) == "function") {
                    var canvasOfTakenSessions = document.getElementsByClassName('correct-sign');
                    if(canvasOfTakenSessions != null && typeof(canvasOfTakenSessions) == "object") {
                        if(canvasOfTakenSessions.constructor = HTMLCollection) {
                            for(var i = 0; i < canvasOfTakenSessions.length; i++) {
                                    canvasOfTakenSessions[i].width = '40';
                                    canvasOfTakenSessions[i].height = '40';
                                    drawCorrectSign(canvasOfTakenSessions[i], 'red', true);
                            }
                        }
                    }
                }
            } )();
        </script>
@endsection