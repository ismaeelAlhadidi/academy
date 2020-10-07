<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0">
        <title>{{ config('app.name') }}</title>
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\default.css') }}" />
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\welcome.css') }}" />

    </head>
    <body>
        @include('include.header')
        <section id="topSection" class="top-section section-borders no-select">
            <div>
                <h1>Title</h1>
                <p>this statment can fixed it or change it</p>
                <a href="{{ route('register') }}"> {{ __('title.Register') }} </a><a href="{{ route('login') }}"> {{ __('title.adminLogin') }} </a>
            </div>
        </section>
        <div class="main section-borders">
            <section class="welcome-playlists section-borders no-select">
                @foreach($playlists as $playlist)
                    <div class="welcome-playlist no-select" onclick="openPlaylistTemplate('{{ $playlist->id }}');">
                        <img style="background-image: url({{ asset($playlist->poster) }})"/>
                        <div>
                            <h3>{{ $playlist->title }}</h3>
                            <p>{{ $playlist->description }}</p>
                        </div>
                        <span class="playlist-price">{{ $playlist->price }}</span>
                        <span class="playlist-time">{{ $playlist->availability_time }}</span>
                    </div>
                @endforeach
            </section>
            <div class="clear-float"></div>
            <section class="about-coach section-borders no-select">
                <div class="no-select"><img/></div><p><span>Title</span> this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it</p>
            </section>
            @if($coachOpinions->count() > 0)
            <section class="coach-opinions section-borders no-select">
                <h2>{{ __('masseges.users-coach-opinions') }}</h2>
                <div class="opinions-outer-container">
                    <div class="opinions-inner-container">
                        @foreach($coachOpinions as $coachOpinion)
                        <div class="opinion">
                            <div class="image"><img src="{{ $coachOpinion->user->image }}" title="{{ $coachOpinion->user->first_name . ' ' . $coachOpinion->user->last_name }}"/></div>
                            <div class="content">
                                <h3>{{ $coachOpinion->user->first_name . ' ' . $coachOpinion->user->last_name }}</h3>
                                <p>{{ $coachOpinion->content }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
        </div>
        <footer class="section-borders no-select">
            <section class="social-midea"><a><span class="fa">&#xf082;</span></a><a><span class="fa">&#xf16d;</span></a><a><span class="fa">&#xf232;</span></a></section>
            <section class="footer-details"><span>&copy; for this site</span> <a>{{ __('auth.privacy') }}</a> <a>{{ __('auth.security') }}</a></section>
        </footer>
        <div id="playlistTemplate" class="pop-up-template template-of-this-playlist big-template no-select" style="display: none;">
            <header><div><canvas id="exitButtonCanvasOfPlaylistTemplate" width="25" height="25"></canvas></div></header>
            <div id="contentOfPlaylistTemplate" class="welcome-playlist-opend-template">
                <section>
                    <p>{{ __('masseges.to-show-playlist-signIn') }}</p>
                    <div><a href="{{ route('register') }}"> {{ __('title.Register') }} </a></div><div><a href="{{ route('login') }}"> {{ __('title.adminLogin') }} </a></div>
                </section>
                <h2 id="opinionsHeader">{{ __('masseges.users-opinions-of-this-playlist') }}</h2>
                <div id="opinionsContainer" class="opinions-outer-container">
                    <section id="opinionsSection" class="opinions-inner-container"></section>
                </div>
            </div>
        </div>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\drawCanvas.js') }}"></script>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\ajax.js') }}"></script>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\styleEfect.js') }}"></script>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\default.js') }}"></script>
        <script type="text/javascript" lang="javascript" src="{{ asset('js\welcome.js') }}"></script>
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

        </script>
    </body>
</html>