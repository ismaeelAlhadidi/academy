<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0">
        <title>Laravel</title>
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\default.css') }}" />
        <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\welcome.css') }}" />

    </head>
    <body>
        <header id="header" class="no-select"><nav><ul><a href="{{ route('login') }}"><li>{{ __('title.adminLogin') }}</li></a><a href="{{ route('register') }}"><li>{{ __('title.Register') }}</li></a></ul></nav><div class="logo">logo</div></header>
        <section id="topSection" class="top-section section-borders no-select">
            <div>
                <h1>Title</h1>
                <p>this statment can fixed it or change it</p>
                <a href="{{ route('register') }}"> {{ __('title.Register') }} </a><a href="{{ route('login') }}"> {{ __('title.adminLogin') }} </a>
            </div>
        </section>
        <div class="main section-borders">
            <section class="welcome-playlists section-borders">
                @foreach($playlists as $playlist)
                    <div class="welcome-playlist no-select" onclick="openPlaylistTemplate('{{ $playlist->id }}');">
                        <img style="background-image: url({{ asset($playlist->poster) }})"/>
                        <div>
                            <h3>{{ $playlist->title }}</h3>
                            <p>{{ $playlist->description }}</p>
                        </div>
                        <span class="playlist-price">{{ $playlist->price . ' $' }}</span>
                        <span class="playlist-time">{{ $playlist->availability_time }}</span>
                    </div>
                @endforeach
            </section>
            <div class="clear-float"></div>
            <section class="about-coach section-borders">
                <div class="no-select"><img/></div><p><span>Title</span> this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it this data can fixed it or change it</p>
            </section>
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
<!-- 
    <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>s
<div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
-->