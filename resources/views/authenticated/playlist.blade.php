@extends('layouts.app')

@section('title'){{ $playlist->title }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\default.css') }}" />
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\playlist.css') }}" />
@endsection

@section('content')
    <div class="margin-to-main-header"></div>
    <div class="selected-video no-select" oncontextmenu="return false;">
        <div id="opendVideo" class="opened-video">
            <div class="play">
                <video preload="none" tabindex="-1" id="currentVideoElement" controlslist="nodownload" ></video>
                <div id="opendVideoController" class="video-controller">
                    <div id="posterOfOpendVideo" class="video-poster">
                        <div class="start-button"><section>
                            <svg id="centerMainButtonInPlayer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="78px" height="78px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 13.5v-7c0-.41.47-.65.8-.4l4.67 3.5c.27.2.27.6 0 .8l-4.67 3.5c-.33.25-.8.01-.8-.4z"/></svg>
                            <svg style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="78px" height="78px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14c-.55 0-1-.45-1-1V9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1zm4 0c-.55 0-1-.45-1-1V9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1z"/></svg>
                            <p id="centerMassegeInPlayer" style="display: none;"></p>
                            <div id="centerSubButtonInPlayer" class="default-authenticated-button" style="display: none;"><button id="subscriptionButton">{{ __('input.subscriptions-of-this-playlist') }}</button></div>
                        </section></div>
                        <img id="imageOfOpendVideo" src="{{ asset('/images/static/video-default.jpg') }}" alt=".."/>
                    </div>
                    <footer id="opendVideoConrols" style="display: none;">
                        <div id="divForMakeEventPlayPauseOnFooter" class="background-of-footer"></div>
                        <div id="videoProgressBarContainer" class="video-progress-bar-container">
                            <div id="videoProgressBar" class="video-progress-bar"><section id="progressRedPar"></section><div id="progressHoverPar" class="video-progress-hover-bar"></div><div id="progressPointer" class="video-progress-bar-pointer"></div></div>
                            <div class="video-progress-bar-left-buttons">
                                <span class="controls-button play-pause-button">
                                    <svg id="smallPlayPauseSVGButton" xmlns="http://www.w3.org/2000/svg" class="center" height="35" width="100%"><path fill="#ffffffcc" d="M 12,26 18.5,22 18.5,14 12,10 z M 18.5,22 25,18 25,18 18.5,14 z"></path></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="center" style="display: none;" height="35" width="100%"><path fill="#ffffffcc" d="M 12,26 16,26 16,10 12,10 z M 21,26 25,26 25,10 21,10 z"></path></svg>
                                </span>
                                <span class="controls-text">
                                    <section id="opendVideoTime" class="center">00:00:00 / 00:00:00</section>
                                </span>
                            </div>
                            <div class="video-progress-bar-right-buttons">
                                <span class="controls-button">
                                    <svg id="opendVideoFullScrrenButton" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" class="center" fill="#ffffffcc" width="35" height="35"><path d="M0 0h24v24H0V0z" fill="none"/><path transform="matrix( 0.8, 0, 0, 0.8, 2.5, 2.5)" d="M6 14c-.55 0-1 .45-1 1v3c0 .55.45 1 1 1h3c.55 0 1-.45 1-1s-.45-1-1-1H7v-2c0-.55-.45-1-1-1zm0-4c.55 0 1-.45 1-1V7h2c.55 0 1-.45 1-1s-.45-1-1-1H6c-.55 0-1 .45-1 1v3c0 .55.45 1 1 1zm11 7h-2c-.55 0-1 .45-1 1s.45 1 1 1h3c.55 0 1-.45 1-1v-3c0-.55-.45-1-1-1s-1 .45-1 1v2zM14 6c0 .55.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1V6c0-.55-.45-1-1-1h-3c-.55 0-1 .45-1 1z"/></svg>
                                    <svg style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" class="center" fill="#ffffffcc" width="35" height="35"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6 16h2v2c0 .55.45 1 1 1s1-.45 1-1v-3c0-.55-.45-1-1-1H6c-.55 0-1 .45-1 1s.45 1 1 1zm2-8H6c-.55 0-1 .45-1 1s.45 1 1 1h3c.55 0 1-.45 1-1V6c0-.55-.45-1-1-1s-1 .45-1 1v2zm7 11c.55 0 1-.45 1-1v-2h2c.55 0 1-.45 1-1s-.45-1-1-1h-3c-.55 0-1 .45-1 1v3c0 .55.45 1 1 1zm1-11V6c0-.55-.45-1-1-1s-1 .45-1 1v3c0 .55.45 1 1 1h3c.55 0 1-.45 1-1s-.45-1-1-1h-2z"/></svg>
                                </span>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
        <div class="playlist-description">
            <h1 id="mainTitleElement">{{ $playlist->title }}</h1>
            <p id="mainDescriptionElement">{{ $playlist->description }}</p>
        </div>
    </div>
    <div class="videos no-select" oncontextmenu="return false;">
        <div class="some-buttons"><section><div class="default-authenticated-button"><button id="addPlaylistOpinionButton">{{ __('masseges.what-your-opinion-in-playlist') }}</button></div><div class="default-authenticated-button"><button id="addCoachOpinionButton">{{ __('masseges.what-your-opinion-in-coach') }}</button></div></section></div>
        <div class="blob-nav"><div class="default-authenticated-button blob-button"><button id="openVideosButton" class="selected-blob">{{ __('headers.videos') }}</button></div><div class="default-authenticated-button blob-button"><button id="openAudiosButton">{{ __('headers.audios') }}</button></div><div class="default-authenticated-button blob-button"><button id="openBooksButton">{{ __('headers.books') }}</button></div></div>
        <div id="videos" class="videos-contianer">
            @forelse($videos as $type)
                @if(count($type) > 0 && $type[0]->type_id != null && is_object($type[0]->type))
                    <section class="blob-type">{{ $type[0]->type->name }}</section>
                @endif
                @foreach($type as $blob)
                    <div class="video" onclick="openThisVideo('{{ $blob->public_route }}', '{{ asset($blob->blobable->poster_src) }}', {{ $blob->id }}, {{ $blob->blobable->id }});"><div class="video-poster"><img data-src="{{ asset($blob->blobable->poster_src) }}" class="lazyload" loading="lazy" /></div><div class="video-data">
                            <h3 class="edit-overflow-text">{{ ($isSubscription ? $blob->blobable->title : $blob->blobable->pre_title) }}</h3>
                            <span>{{ $blob->time }}</span>
                        </div></div>
                @endforeach
            @empty
                <div class="video blob-empty">{{ __('masseges.no-videos-on-this-playlist-now') }}</div>
            @endforelse
        </div>
        <div id="audios" class="videos-contianer" style="display: none !important;">
            @forelse($audios as $type)
                @if(count($type) > 0 && $type[0]->type_id != null && is_object($type[0]->type))
                    <section class="blob-type">{{ $type[0]->type->name }}</section>
                @endif
                @foreach($type as $blob)
                    <div class="video" onclick="openThisAudio('{{ $blob->public_route }}', '{{ asset($blob->blobable->poster_src) }}', {{ $blob->id }}, {{ $blob->blobable->id }});"><div class="video-poster"><img data-src="{{ asset($blob->blobable->poster_src) }}" class="lazyload" loading="lazy" /></div><div class="video-data">
                            <h3 class="edit-overflow-text">{{ ($isSubscription ? $blob->blobable->title : $blob->blobable->pre_title) }}</h3>
                            <span>{{ $blob->time }}</span>
                        </div></div>
                @endforeach
            @empty
                <div class="video blob-empty">{{ __('masseges.no-audios-on-this-playlist-now') }}</div>
            @endforelse
        </div>
        <div id="books" class="videos-contianer" style="display: none !important;">
            @forelse($books as $type)
                @if(count($type) > 0 && $type[0]->type_id != null && is_object($type[0]->type))
                    <section class="blob-type">{{ $type[0]->type->name }}</section>
                @endif
                @foreach($type as $blob)
                    <div class="video" onclick="openThisBook('{{ $blob->public_route }}', {{ $isSubscription }})"><div class="video-poster"><img data-src="{{ asset($blob->blobable->poster_src) }}" class="lazyload" loading="lazy" /></div><div class="video-data">
                            <h3 class="edit-overflow-text">{{ ($isSubscription ? $blob->blobable->title : $blob->blobable->pre_title) }}</h3>
                            <span>{{ $blob->time }}</span>
                        </div></div>
                @endforeach
            @empty
                <div class="video blob-empty">{{ __('masseges.no-books-on-this-playlist-now') }}</div>
            @endforelse
        </div>
    </div>
    <div class="comments" oncontextmenu="return false;">
        <header class="no-select">
            <div><span id="spanOfCountOfComment">{{ 
                    ($comments) ? ( $comments->total() == 1 ) 
                        ? ( __('masseges.one-comment') )
                        : ( ($comments->total() == 2)
                                ? __('masseges.two-comment') 
                                : ( ($comments->total() < 11 && $comments->total() > 2 ) 
                                        ? $comments->total() . ' ' . __('masseges.many-comments') 
                                        : ( ($comments->total() > 10) 
                                                ? $comments->total() . ' ' . __('masseges.one-comment')
                                                : __('masseges.no-comments-on-this-playlist')
                                        )
                                )
                        ) : __('masseges.no-comments-on-this-playlist')
                }}</span>
            </div>
            <div class="post-comment"><div class="comment">
                <div><a><img data-src="{{ asset(auth()->user()->image) }}" class="lazyload" loading="lazy" /></a></div>
                <div>
                    <header>{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</header>
                    <div><textarea id="inputContentOfComment" class="add-opinion-textarea" placeholder="{{ __('masseges.post-comment') . ' ...' }}"></textarea></div>
                    <footer><button id="postCommentButton" class="post-comment-button">{{ __('masseges.add-comment') . ' ' }}<li class="material-icons">add</li></button></footer>
                </div>
            </div></div>
        </header>
        <div id="commentsContianer" class="comments-contianer">
            @if($comments)
            @foreach($comments as $comment)
            @if($comment)
            <div class="comment">
                <div class="no-select"><a href="{{ route('user.profile') . '/' . $comment['userId'] }}"><img loading="lazy" data-src="{{ asset($comment['image']) }}" class="lazyload" /></a></div>
                <div>
                    <header class="no-select">{{ $comment['name'] }}</header>
                    <div><p>{{ $comment['content'] }}</p></div>
                    <footer class="footer-with-replays no-select"><button id="replaysButtonOfComment{{ $comment['id'] }}" onclick="openReplayOf('{{ $comment['id'] }}')" class="open-replays-button">{{  __('masseges.replays') }}</button><span>{{ $comment['time'] }}</span></footer>
                    <div id="replaysOfComment{{ $comment['id'] }}" class="replays-contianer" style="display: none !important;">
                        @foreach($comment['replays'] as $replay)
                        <div class="comment replay">
                            <div class="no-select"><a href="{{ route('user.profile') . '/' . $replay['userId'] }}"><img loading="lazy" data-src="{{ asset($replay['image']) }}" class="lazyload" /></a></div>
                            <div>
                                <header class="no-select">{{ $replay['name'] }}</header>
                                <div><p>{{ $replay['content'] }}</p></div>
                                <footer class="footer-with-replays no-select"><span>{{ $replay['time'] }}</span></footer>
                            </div>
                        </div>
                        @endforeach
                        <div id="postReplayElement{{ $comment['id'] }}" class="post-comment"><div class="comment replay post-replay">
                            <div class="no-select"><a><img loading="lazy" data-src="{{ asset(auth()->user()->image) }}" class="lazyload" /></a></div>
                            <div>
                                <div><textarea id="inputContentOfReplay{{ $comment['id'] }}" class="add-opinion-textarea" placeholder="{{ __('masseges.post-replay') . ' ...' }}"></textarea></div>
                                <footer class="no-select"><button onclick="postReplay('{{ $comment['id'] }}');" class="post-comment-button">{{ __('masseges.add-replay') . ' ' }}<li class="material-icons">add</li></button></footer>
                            </div>
                        </div></div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            <section id="loadingComment" class="comment-arrow-loading">
                <span>↓</span><span style="--delay: 0.1s">↓</span><span style="--delay: 0.3s">↓</span><span style="--delay: 0.4s">↓</span><span style="--delay: 0.5s">↓</span>
            </section>
            @endif
        </div>
    </div>
    <div id="PlaylistOpinionTemplate" class="pop-up-template template-of-this-playlist no-select" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfPlaylistOpinionTemplate" width="25" height="25"></canvas></div></header>
        <div id="contentOfPlaylistOpinionTemplate" class="welcome-playlist-opend-template offer-template">
            <div>
                <h2>{{ __('masseges.what-your-opinion-in-playlist') }}</h2>
                <textarea id="inputOpinionOfPlaylist" class="add-opinion-textarea"></textarea>
            </div>
            <footer><div><a id="sendOpinionOfPlaylistButton">{{ __('input.send') }}</a></div></footer>
        </div>
    </div>
    <div id="CoachOpinionTemplate" class="pop-up-template template-of-this-playlist no-select" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfCoachOpinionTemplate" width="25" height="25"></canvas></div></header>
        <div id="contentOfCoachOpinionTemplate" class="welcome-playlist-opend-template offer-template">
            <div>
                <h2>{{ __('masseges.what-your-opinion-in-coach') }}</h2>
                <textarea id="inputOpinionOfCoach" class="add-opinion-textarea"></textarea>
            </div>
            <footer><div><a id="sendOpinionOfCoachButton">{{ __('input.send') }}</a></div></footer>
        </div>
    </div>
    <form id="payPlaylistForm" method="post" action="{{ asset('/pay-playlist') }}" style="display:none !important">
        @csrf
        <input type="hidden" name="id" value="{{ $playlist->id }}"/>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js\authenticated\playlist.js') }}"></script>
    <script type="text/javascript" lang="javascript" src="{{ asset('js\authenticated\watching\player.js') }}"></script>
    <script type="text/javascript" lang="javascript">
        ( function loadingImage() {
            var lazyURL = '{{ asset('js/lazysizes.min.js') }}';
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
        var TOKEN = '{{ csrf_token() }}',
            PLAYLIST_ID = '{{ $playlist->id }}',
            PLAYLIST_TITLE = '{{ $playlist->title }}',
            PLAYLIST_DESCRIPTION = '{{ $playlist->description }}',
            lang = {
                'thanksForShareYourOpinion': '{{ __('masseges.thanks-for-share-your-opinion') }}',
                'generalError': '{{ __('masseges.general-error') }}',
                'alertOfMaxSizeOfCharacter': '{{ __('masseges.length-must-be-less-than-max') }}',
                'newComment': '{{ __('masseges.new-comment') }}',
                'newReplay': '{{ __('masseges.new-replay') }}',
                'comment': '{{ __('masseges.one-comment') }}',
                'twoComment': '{{ __('masseges.two-comment') }}',
                'comments': '{{ __('masseges.no-comments') }}',
                'replays': '{{  __('masseges.replays') }}',
                'now': '{{ __('time.now') }}',
                'postReplay': '{{ __('masseges.post-replay') }}' + " ..." ,
                'addReplay': '{{ __('masseges.add-replay') . ' ' }}',
                'errorInPlayVideo': '{{ __('masseges.error-in-start-file') }}',
                'needSubscriptionMassege': '{{ __('masseges.need-subscription-to-show-blobs') }}',
                'notAvillableMassage': '{{ __('masseges.file-not-avillable-now') }}'
            },
            thisUser = {
                'image': '{{ asset(auth()->user()->image) }}',
                'name': '{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}',
            },
            countOfComments = '{{ ($comments) ? $comments->total() : 0 }}',
            commentsCountOnOneScroll = '{{ $commentsCountOnOneScroll }}',
            profilePageURL = '{{ route('user.profile') }}',
            currentBlob = {
                'blob_id': '{{ ($firstBlob) ? $firstBlob->id : '' }}',
                'publicKey': '{{ ($firstBlob) ? $firstBlob->public_route : '' }}',
                'video_id': '{{ ($firstBlob) ? $firstBlob->blobable->id : '' }}',
                'poster_src': '{{ ($firstBlob) ? $firstBlob->blobable->poster_src : '' }}',
                'type': '{{ ($firstBlob) ? $firstBlob->blobType : '' }}',
            },
            svgPaths = {
                'bigPause': 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14c-.55 0-1-.45-1-1V9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1zm4 0c-.55 0-1-.45-1-1V9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1z',
                'bigPlay': 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 13.5v-7c0-.41.47-.65.8-.4l4.67 3.5c.27.2.27.6 0 .8l-4.67 3.5c-.33.25-.8.01-.8-.4z',
                'smallPause': 'M 12,26 16,26 16,10 12,10 z M 21,26 25,26 25,10 21,10 z',
                'smallPlay': 'M 12,26 18.5,22 18.5,14 12,10 z M 18.5,22 25,18 25,18 18.5,14 z',
                'smallReplay': 'M12 5V2.21c0-.45-.54-.67-.85-.35l-3.8 3.79c-.2.2-.2.51 0 .71l3.79 3.79c.32.31.86.09.86-.36V7c3.73 0 6.68 3.42 5.86 7.29-.47 2.27-2.31 4.1-4.57 4.57-3.57.75-6.75-1.7-7.23-5.01-.07-.48-.49-.85-.98-.85-.6 0-1.08.53-1 1.13.62 4.39 4.8 7.64 9.53 6.72 3.12-.61 5.63-3.12 6.24-6.24C20.84 9.48 16.94 5 12 5z',
                'fullScreen': 'M6 14c-.55 0-1 .45-1 1v3c0 .55.45 1 1 1h3c.55 0 1-.45 1-1s-.45-1-1-1H7v-2c0-.55-.45-1-1-1zm0-4c.55 0 1-.45 1-1V7h2c.55 0 1-.45 1-1s-.45-1-1-1H6c-.55 0-1 .45-1 1v3c0 .55.45 1 1 1zm11 7h-2c-.55 0-1 .45-1 1s.45 1 1 1h3c.55 0 1-.45 1-1v-3c0-.55-.45-1-1-1s-1 .45-1 1v2zM14 6c0 .55.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1V6c0-.55-.45-1-1-1h-3c-.55 0-1 .45-1 1z',
                'exitFullScreen': 'M6 16h2v2c0 .55.45 1 1 1s1-.45 1-1v-3c0-.55-.45-1-1-1H6c-.55 0-1 .45-1 1s.45 1 1 1zm2-8H6c-.55 0-1 .45-1 1s.45 1 1 1h3c.55 0 1-.45 1-1V6c0-.55-.45-1-1-1s-1 .45-1 1v2zm7 11c.55 0 1-.45 1-1v-2h2c.55 0 1-.45 1-1s-.45-1-1-1h-3c-.55 0-1 .45-1 1v3c0 .55.45 1 1 1zm1-11V6c0-.55-.45-1-1-1s-1 .45-1 1v3c0 .55.45 1 1 1h3c.55 0 1-.45 1-1s-.45-1-1-1h-2z',
            };
        if(typeof(Player) == "function") if(Player.hasOwnProperty('defaultPoster')) Player.defaultPoster = "{{ asset('/images/static/video-default.jpg') }}";
        
        var subscriptionButton = document.getElementById('subscriptionButton'),
                payPlaylistForm = document.getElementById('payPlaylistForm');
        if(subscriptionButton != null) {
            subscriptionButton.onclick = function () {
                if(payPlaylistForm != null) payPlaylistForm.submit();
            };
        }
        @if(session()->has('error'))
            showPopUpMassage('{{ session()->get('error') }}',null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
            {{ session()->forget('error') }}
        @endif
    </script>
    <script type="text/javascript" lang="javascript" src="{{ asset('js\authenticated\watching\play.js') }}"></script>
@endsection