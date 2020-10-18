@extends('layouts.app')

@section('title'){{ $playlist->title }}@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\default.css') }}" />
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\authenticated\playlist.css') }}" />
@endsection

@section('content')
    <div class="margin-to-main-header"></div>
    <div class="selected-video no-select" oncontextmenu="return false;">
        <div class="opened-video"><div class="play"><video preload="none" src="{{ ($playlist->blobs->count() > 0) ? asset('/blob/video/' . $playlist->blobs->first()->public_route) : '' }}" id="currentVideoElement" controls controlslist="nodownload" ></video></div></div>
        <div class="playlist-description">
            <h3>{{ $playlist->title }}</h3>
            <p>{{ $playlist->description }}</p>
        </div>
    </div>
    <div class="videos no-select" oncontextmenu="return false;">
        <div class="some-buttons"><section><div class="default-authenticated-button"><button id="addPlaylistOpinionButton">{{ __('masseges.what-your-opinion-in-playlist') }}</button></div><div class="default-authenticated-button"><button id="addCoachOpinionButton">{{ __('masseges.what-your-opinion-in-coach') }}</button></div></section></div>
        <div class="videos-contianer">
            @foreach($playlist->blobs as $blob)
                <div class="video"><div class="video-poster"><img data-src="{{ asset($blob->blobable->poster_src) }}" class="lazyload" loading="lazy" /></div><div class="video-data">
                        <h3 class="edit-overflow-text">{{ $blob->blobable->title }}</h3>
                        <span>after 2 day</span>
                    </div></div>
            @endforeach
        </div>
        <div class="videos-contianer">
            @foreach($playlist->blobs as $blob)
                <div class="video"><div class="video-poster"><img data-src="{{ asset($blob->blobable->poster_src) }}" class="lazyload" loading="lazy" /></div><div class="video-data">
                        <h3 class="edit-overflow-text">{{ $blob->blobable->title }}</h3>
                        <span>after 2 day</span>
                    </div></div>
            @endforeach
        </div>
        <div class="videos-contianer">
            @foreach($playlist->blobs as $blob)
                <div class="video"><div class="video-poster"><img data-src="{{ asset($blob->blobable->poster_src) }}" class="lazyload" loading="lazy" /></div><div class="video-data">
                        <h3 class="edit-overflow-text">{{ $blob->blobable->title }}</h3>
                        <span>after 2 day</span>
                    </div></div>
            @endforeach
        </div>
        <div class="videos-contianer">
            @foreach($playlist->blobs as $blob)
                <div class="video"><div class="video-poster"><img data-src="{{ asset($blob->blobable->poster_src) }}" class="lazyload" loading="lazy" /></div><div class="video-data">
                        <h3 class="edit-overflow-text">{{ $blob->blobable->title }}</h3>
                        <span>after 2 day</span>
                    </div></div>
            @endforeach
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
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js\authenticated\playlist.js') }}"></script>
    <script type="text/javascript" lang="javascript" src="{{ asset('js\authenticated\watching\play.js') }}"></script>
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
            },
            thisUser = {
                'image': '{{ asset(auth()->user()->image) }}',
                'name': '{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}',
            },
            countOfComments = '{{ ($comments) ? $comments->total() : 0 }}',
            commentsCountOnOneScroll = '{{ $commentsCountOnOneScroll }}',
            profilePageURL = '{{ route('user.profile') }}';
    </script>
@endsection