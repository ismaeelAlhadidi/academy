@extends('admin\layouts\adminpanel')

@section('title')
 {{ __('headers.admin-navbar-playlists')  }}
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\playlists.css') }}"/>
@endsection

@section('content')
    <header class="header-of-main-div no-select"><h3>{{ __('headers.admin-navbar-playlists') }}</h3><a href="{{ route('admin.playlist.add') }}">{{ __('headers.admin-navbar-add-playlist') }}</a></header>
    @forelse($playlists as $playlist)
        <div class="playlist-div" id="playlistDiv{{ $playlist->id }}">
            <section><img src="{{ asset($playlist->poster) }}"/></section>
            <div>
                <div><span class="no-select">{{ __('masseges.playlist-title') }}</span><span>{{ $playlist->title }}</span></div>
                <div><span class="no-select">{{ __('masseges.video-count') }}</span><span>{{ $playlist->blobs->count() }}</span></div>
                <div><span class="no-select">{{ __('masseges.count-of-subscriptions') }}</span><span>{{ $playlist->subscriptions->count() }}</span></div>
                <div class="no-select">
                    <a href="javascript:ShowUsersOfThisPlaylist('{{ '../admin/home/' . $playlist->id . '/getUsersOfThisPlaylist'  }}');">{{ __('masseges.show-user-subscription') }}</a>
                    <a href="javascript:ShowOpinionOfThisPlaylist('{{ '../admin/playlist/' . $playlist->id . '/getOpinionOfThisPlaylist'  }}');">{{ __('masseges.show-opinions') }}</a>
                    <a href="javascript:ShowCommentsWithReplaysOfThisPlaylist('{{ '../admin/playlist/' . $playlist->id . '/getCommentsWithReplaysOfThisPlaylist'  }}');">{{ __('masseges.comments-and-replays') }}</a>
                    <a href="{{ route('admin.playlist.update',$playlist->id) }}">{{ __('masseges.update') }}</a>
                    <a id="availablePlaylistButton{{ $playlist->id }}" href="javascript:RequestToggleAvailablePlaylist({{ $playlist->id }});" style="{{ $playlist->available ? '' : 'background-color: red;color: #ffffff;'}}">{{ $playlist->available ? __('masseges.playlist-make-not-available') : __('masseges.playlist-make-available') }}</a>
                    <a href="javascript:deletePlaylist({{ $playlist->id }});">{{ __('masseges.delete') }}</a>
                </div>
            </div>
        </div>
    @empty
        <div class="empty">{{ __('masseges.no-playlists') }}</div>
    @endforelse
    <div class="clear-float"></div>
    {{ $playlists->links() }}
    <div id="DivOfThisPlaylist" class="pop-up-template template-of-this-playlist" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfDivOfThisPlaylist" width="25" height="25"></canvas></div></header>
        <div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript">
        var DivOfThisPlaylist = document.getElementById('DivOfThisPlaylist'),
            exitButtonCanvasOfDivOfThisPlaylist = document.getElementById('exitButtonCanvasOfDivOfThisPlaylist'),
            main = document.getElementById('main');
        
        if(exitButtonCanvasOfDivOfThisPlaylist != null) {
            exitButtonCanvasOfDivOfThisPlaylist.width = 25;
            exitButtonCanvasOfDivOfThisPlaylist.height = 25;
            drawRemoveIconCanvas(exitButtonCanvasOfDivOfThisPlaylist,'#ffffff');
            exitButtonCanvasOfDivOfThisPlaylist.onclick = function () {
                closeBobUpTemplate(DivOfThisPlaylist);
            }
        }
        
        function makePaginationLinks(paginator,type = 'u') {
            if(paginator.last_page == 1)return null;
            var userLinks = document.createElement('div');
            userLinks.setAttribute('id','userLinks');
            if(type == 's')userLinks.setAttribute('id','subscriptionLinks');
            if(type == 'us')userLinks.setAttribute('id','userSubLinks');
            if(type == 'v')userLinks.setAttribute('id','visiterLinks');
            userLinks.setAttribute('class','pagination no-select');
            var buttonText = '<';
            var active = false;
            var disabled = false;
            var element = null;
            for(var i = paginator.last_page + 1; i >= 0; i--) {
                var path = paginator.path + '?page=';
                disabled = false;
                if(i == paginator.current_page) active = true;
                else {
                    active = false;
                }
                if(i == 0) {
                    path = paginator.first_page_url;
                    buttonText = '<';
                    if(paginator.current_page == 1)disabled = true;
                }
                else if(i == paginator.last_page + 1) {
                    path = paginator.last_page_url;
                    buttonText = '>';
                    if(paginator.current_page == paginator.last_page)disabled = true;
                } else {
                    buttonText = i;
                    path += i;
                }
                element = createLinkPageItem(path,buttonText,active,disabled,type);
                if(element != null && element != false)userLinks.appendChild(element);
            }
            return userLinks;
        }
        function createLinkPageItem(path,buttonText,active,disabled,type = 'u') {
            var span = document.createElement('span'),
                a = document.createElement('a');
            span.setAttribute('class','page-item');
            if(active)span.setAttribute('class','page-item active');
            if(disabled)span.setAttribute('class','page-item disabled');
            a.setAttribute('class','page-link');
            if(! disabled) {
                a.setAttribute('href','javascript:getUsers("' + path + '");');
                if(type == 's')a.setAttribute('href','javascript:getSubscriptions("' + path + '");');
                if(type == 'us')a.setAttribute('href','javascript:ShowUsersOfThisPlaylist("' + path + '");');
                if(type == 'v')a.setAttribute('href','javascript:ShowVisiter("' + path + '");');
            }
            a.textContent = buttonText;
            span.appendChild(a);
            return span;
        }
        function ShowUsersOfThisPlaylist(path) {
            ajaxRequest('get',path,null,function(jsonResponse){
                if(jsonResponse == null) {
                    showPopUpMassage('{{ __('masseges.general-error') }}');
                    return;
                }
                if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                    if(jsonResponse.status) {
                        if(DivOfThisPlaylist != null && jsonResponse.data != null) {
                            if(jsonResponse.data.hasOwnProperty('data')) {
                                if(jsonResponse.data.data.length > 0) {
                                    renderUsersOfThisPlaylist(jsonResponse.data);
                                    DivOfThisPlaylist.setAttribute('style','display:block;');
                                } else {
                                    showPopUpMassage('{{ __('masseges.no-subscriptions-of-this-playlist') }}');
                                }
                            } else {
                                showPopUpMassage('{{ __('masseges.general-error') }}');
                            }
                        } else {
                            showPopUpMassage('{{ __('masseges.general-error') }}');
                        }
                        return;
                    }
                    if(jsonResponse.hasOwnProperty('msg')) {
                        showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
                showPopUpMassage('{{ __('masseges.general-error') }}');
                return;
            });
        }
        function renderUsersOfThisPlaylist(data) {
            var temp = document.getElementById('OfThisPlaylist');
            if(temp != null) {
                if(DivOfThisPlaylist.children.length > 1)DivOfThisPlaylist.children[1].removeChild(temp);
            }
            temp = document.createElement('div');
            temp.setAttribute('id','OfThisPlaylist');

            for(userSub in data.data) {
                var userSubDiv = document.createElement('div'),
                    userSubDiv1 = document.createElement('div'),
                    userSubDiv1Span1 = document.createElement('span'),
                    userSubDiv1Span2 = document.createElement('span'),
                    userSubDiv2 = document.createElement('div'),
                    userSubDiv2Span1 = document.createElement('span'),
                    userSubDiv2Span2 = document.createElement('span'),
                    userSubDiv3 = document.createElement('div'),
                    pullPlaylistOfThisUser = document.createElement('a');
                userSubDiv.setAttribute('class','user-div');

                userSubDiv1Span1.textContent = "{{ __('masseges.name') }}";
                if(data.data[userSub].hasOwnProperty('user')) {
                    if(data.data[userSub].user.hasOwnProperty('first_name'))userSubDiv1Span2.textContent = data.data[userSub].user.first_name;
                    else userSubDiv1Span2.textContent = '';
                    if(data.data[userSub].user.hasOwnProperty('second_name'))userSubDiv1Span2.textContent += ' ' + data.data[userSub].user.second_name;;
                    if(data.data[userSub].user.hasOwnProperty('last_name'));userSubDiv1Span2.textContent += ' ' + data.data[userSub].user.last_name;
                    
                    userSubDiv2Span1.textContent = "{{ __('masseges.email') }}";
                    if(data.data[userSub].user.hasOwnProperty('email'))userSubDiv2Span2.textContent = data.data[userSub].user.email;
                }
                if(data.data[userSub].hasOwnProperty('access')) {
                    pullPlaylistOfThisUser.textContent = data.data[userSub].access ? "{{ __('masseges.pull-playlist') }}" : "{{ __('masseges.return-playlist') }}";
                    if(data.data[userSub].hasOwnProperty('id')) {
                        pullPlaylistOfThisUser.setAttribute('id','subscriptionAccess' + data.data[userSub].id);
                        pullPlaylistOfThisUser.onclick = new Function("RequestToggleUserPlaylistAccess(" + data.data[userSub].id + ");");
                    }
                }
                userSubDiv1.appendChild(userSubDiv1Span1);
                userSubDiv1.appendChild(userSubDiv1Span2);
                userSubDiv2.appendChild(userSubDiv2Span1);
                userSubDiv2.appendChild(userSubDiv2Span2);

                userSubDiv3.appendChild(pullPlaylistOfThisUser);

                userSubDiv.appendChild(userSubDiv1);
                userSubDiv.appendChild(userSubDiv2);
                userSubDiv.appendChild(userSubDiv3);
                temp.appendChild(userSubDiv);
            }

            if(data.hasOwnProperty('total') && data.hasOwnProperty('path') && data.hasOwnProperty('current_page')) {
                if(data.total > 1) {
                    var userSubLinks = document.getElementById('userSubLinks');
                    if(userSubLinks != null)temp.removeChild(userSubLinks);
                    userSubLinks = makePaginationLinks(data,'us');
                    if(userSubLinks != null)temp.appendChild(userSubLinks);
                }
            }
            if(DivOfThisPlaylist.children.length > 1)DivOfThisPlaylist.children[1].appendChild(temp);
        }
        function RequestToggleUserPlaylistAccess(subscriptionId) {
            ajaxRequest('get','../admin/home/' + subscriptionId + '/toggleUserPlaylistAccess',null,function(jsonResponse) {
                if(jsonResponse != null) {
                    if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                        if(jsonResponse.status && jsonResponse.data.hasOwnProperty('access')) {
                            result = jsonResponse.data.access;
                            var access = document.getElementById('subscriptionAccess' + subscriptionId);
                            if(result != null) {
                                if(access != null) {
                                    if(result) {
                                        access.textContent = "{{ __('masseges.pull-playlist') }}";
                                        access.setAttribute('style','');
                                    } else {
                                        access.textContent = "{{ __('masseges.return-playlist') }}";
                                        access.setAttribute('style','background-color:red;');
                                    }
                                }
                                return;
                            }
                        }
                    }
                }
                showPopUpMassage('{{ __('masseges.general-error') }}');
            });
        }
        function ShowOpinionOfThisPlaylist(path) {
            ajaxRequest('get',path,null,function(jsonResponse){
                if(jsonResponse == null) {
                    showPopUpMassage('{{ __('masseges.general-error') }}');
                    return;
                }
                if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                    if(jsonResponse.status) {
                        if(DivOfThisPlaylist != null && jsonResponse.data != null) {
                            if(jsonResponse.data.length > 0) {
                                renderOpinionOfThisPlaylist(jsonResponse.data);
                                DivOfThisPlaylist.setAttribute('style','display:block;');
                            } else {
                                showPopUpMassage('{{ __('masseges.no-opinion-of-this-playlist') }}');
                            }
                        } else {
                            showPopUpMassage('{{ __('masseges.general-error') }}');
                        }
                        return;
                    }
                    if(jsonResponse.hasOwnProperty('msg')) {
                        showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
                showPopUpMassage('{{ __('masseges.general-error') }}');
                return;
            });
        }
        function renderOpinionOfThisPlaylist(data) {
            var temp = document.getElementById('OfThisPlaylist');
            if(temp != null) {
                if(DivOfThisPlaylist.children.length > 1)DivOfThisPlaylist.children[1].removeChild(temp);
            }
            temp = document.createElement('div');
            temp.setAttribute('id','OfThisPlaylist');

            for(opinion in data) {
                var opinionDiv = document.createElement('div'),
                    img = document.createElement('img'),
                    subDiv = document.createElement('div'),
                    name = document.createElement('span'),
                    content = document.createElement('p'),
                    time = document.createElement('span'),
                    allowButton = document.createElement('a');
                
                opinionDiv.setAttribute('class','playlist-opinion');
                img.setAttribute('class','no-select');
                time.setAttribute('class','no-select');
                name.setAttribute('class','no-select');
                allowButton.setAttribute('class','no-select');

                if(data[opinion].hasOwnProperty('user_image'))img.setAttribute('src','../..' + data[opinion].user_image);
                else img.setAttribute('src','../../images/static/user-default.jpg');
                if(data[opinion].hasOwnProperty('user_first_name'))name.textContent = data[opinion].user_first_name;
                if(data[opinion].hasOwnProperty('content'))content.textContent = data[opinion].content;
                if(data[opinion].hasOwnProperty('time'))time.textContent = data[opinion].time;

                opinionDiv.appendChild(img);
                subDiv.appendChild(name);
                subDiv.appendChild(content);

                if(data[opinion].hasOwnProperty('allow') && data[opinion].hasOwnProperty('id')){
                    allowButton.textContent = data[opinion].allow ? '{{ __('masseges.hidden') }}' : '{{ __('masseges.visible') }}';
                    allowButton.setAttribute('id','allowButtonplaylistOpinionId' + data[opinion].id);
                    allowButton.onclick = new Function("RequestToggleAllowPlaylistOpinion(" + data[opinion].id + ");");
                    subDiv.appendChild(allowButton);
                }
                subDiv.appendChild(time);
                opinionDiv.appendChild(subDiv);

                temp.appendChild(opinionDiv);
            }

            if(DivOfThisPlaylist.children.length > 1)DivOfThisPlaylist.children[1].appendChild(temp);
        }
        function RequestToggleAllowPlaylistOpinion(playlistOpinionId) {
            ajaxRequest('get','../../admin/home/' + playlistOpinionId + '/toggleAllowPlaylistOpinion',null,function(jsonResponse) {
                if(jsonResponse != null) {
                    if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                        if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                            result = jsonResponse.data.allow;
                            var allowButton = document.getElementById('allowButtonplaylistOpinionId' + playlistOpinionId);
                            if(result != null) {
                                if(result) {
                                    allowButton.textContent = '{{ __('masseges.hidden') }}';
                                } else {
                                    allowButton.textContent = '{{ __('masseges.visible') }}';
                                }
                                return;
                            }
                        }
                    }
                }
                showPopUpMassage('{{ __('masseges.general-error') }}');
            });
        }
        function ShowCommentsWithReplaysOfThisPlaylist(path) {
            ajaxRequest('get',path,null,function(jsonResponse) {
                if(jsonResponse == null) {
                    showPopUpMassage('{{ __('masseges.general-error') }}');
                    return;
                }
                if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
                    if(jsonResponse.status) {
                        if(DivOfThisPlaylist != null && jsonResponse.data != null) {
                            if(jsonResponse.data.length > 0) {
                                renderCommentsWithReplaysOfThisPlaylist(jsonResponse.data);
                                DivOfThisPlaylist.setAttribute('style','display:block;');
                            } else {
                                showPopUpMassage('{{ __('masseges.no-comments-of-this-playlist') }}');
                            }
                        } else {
                            showPopUpMassage('{{ __('masseges.general-error') }}');
                        }
                        return;
                    }
                    if(jsonResponse.hasOwnProperty('msg')) {
                        showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
                showPopUpMassage('{{ __('masseges.general-error') }}');
                return;
            });
        }
        function renderCommentsWithReplaysOfThisPlaylist(data) {
            var temp = document.getElementById('OfThisPlaylist');
            if(temp != null) {
                if(DivOfThisPlaylist.children.length > 1)DivOfThisPlaylist.children[1].removeChild(temp);
            }
            temp = document.createElement('div');
            temp.setAttribute('id','OfThisPlaylist');
            for(comment in data) {
                var commentDiv = document.createElement('div'),
                    img = document.createElement('img'),
                    subDiv = document.createElement('div'),
                    name = document.createElement('span'),
                    content = document.createElement('p'),
                    time = document.createElement('span'),
                    allowButton = document.createElement('a');
                
                commentDiv.setAttribute('class','playlist-opinion');
                img.setAttribute('class','no-select');
                time.setAttribute('class','no-select');
                name.setAttribute('class','no-select');
                allowButton.setAttribute('class','no-select');

                if(data[comment].hasOwnProperty('user_image'))img.setAttribute('src','../..' + data[comment].user_image);
                else img.setAttribute('src','../../images/static/user-default.jpg');
                if(data[comment].hasOwnProperty('user_first_name'))name.textContent = data[comment].user_first_name;
                if(data[comment].hasOwnProperty('content'))content.textContent = data[comment].content;
                if(data[comment].hasOwnProperty('time'))time.textContent = data[comment].time;

                commentDiv.appendChild(img);
                subDiv.appendChild(name);
                subDiv.appendChild(content);

                if(data[comment].hasOwnProperty('allow') && data[comment].hasOwnProperty('id')){
                    allowButton.textContent = data[comment].allow ? '{{ __('masseges.hidden') }}' : '{{ __('masseges.visible') }}';
                    allowButton.setAttribute('id','allowButtonComment' + data[comment].id);
                    allowButton.onclick = new Function("RequestToggleAllowComment(" + data[comment].id + ");");
                    subDiv.appendChild(allowButton);
                }
                subDiv.appendChild(time);
                commentDiv.appendChild(subDiv);
                if(data[comment].hasOwnProperty('replays')){
                    for(replay in data[comment].replays) {
                        var replayDiv = document.createElement('section'),
                            imgReplay = document.createElement('img'),
                            subDivReplay = document.createElement('div'),
                            nameReplay = document.createElement('span'),
                            contentReplay = document.createElement('p'),
                            timeReplay = document.createElement('span'),
                            allowButtonReplay = document.createElement('a');
                        
                        replayDiv.setAttribute('class','playlist-opinion playlist-replay');
                        imgReplay.setAttribute('class','no-select');
                        timeReplay.setAttribute('class','no-select');
                        nameReplay.setAttribute('class','no-select');
                        allowButtonReplay.setAttribute('class','no-select');

                        if(data[comment].replays[replay].hasOwnProperty('user_image'))imgReplay.setAttribute('src','../..' + data[comment].replays[replay].user_image);
                        else imgReplay.setAttribute('src','../../images/static/user-default.jpg');
                        if(data[comment].replays[replay].hasOwnProperty('user_first_name'))nameReplay.textContent = data[comment].replays[replay].user_first_name;
                        if(data[comment].replays[replay].hasOwnProperty('content'))contentReplay.textContent = data[comment].replays[replay].content;
                        if(data[comment].replays[replay].hasOwnProperty('time'))timeReplay.textContent = data[comment].replays[replay].time;

                        replayDiv.appendChild(imgReplay);
                        subDivReplay.appendChild(nameReplay);
                        subDivReplay.appendChild(contentReplay);

                        if(data[comment].replays[replay].hasOwnProperty('allow') && data[comment].replays[replay].hasOwnProperty('id')){
                            allowButtonReplay.textContent = data[comment].replays[replay].allow ? '{{ __('masseges.hidden') }}' : '{{ __('masseges.visible') }}';
                            allowButtonReplay.setAttribute('id','allowButtonReplay' + data[comment].replays[replay].id);
                            allowButtonReplay.onclick = new Function("RequestToggleAllowReplay(" + data[comment].replays[replay].id + ");");
                            subDivReplay.appendChild(allowButtonReplay);
                        }
                        subDivReplay.appendChild(timeReplay);
                        replayDiv.appendChild(subDivReplay);
                        commentDiv.appendChild(replayDiv);
                    }
                }
                temp.appendChild(commentDiv);
            }

            if(DivOfThisPlaylist.children.length > 1)DivOfThisPlaylist.children[1].appendChild(temp);
        }
        function RequestToggleAllowComment(commentId) {
            ajaxRequest('get','../../admin/home/' + commentId + '/toggleAllowComment',null,function(jsonResponse) {
                if(jsonResponse != null) {
                    if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                        if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                            var allowButtonComment = document.getElementById('allowButtonComment' + commentId);
                            result = jsonResponse.data.allow;
                            if(result != null) {
                                if(allowButtonComment != null) {
                                    if(result) {
                                        allowButtonComment.textContent = '{{ __('masseges.hidden') }}';
                                    } else {
                                        allowButtonComment.textContent = '{{ __('masseges.visible') }}';
                                    }
                                }
                                return;
                            }
                        }
                    }
                }
                showPopUpMassage('{{ __('masseges.general-error') }}');
            });
        }
        function RequestToggleAllowReplay(replayId) {
            ajaxRequest('get','../../admin/home/' + replayId + '/toggleAllowReplay',null,function(jsonResponse) {
                if(jsonResponse != null) {
                    if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                        if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                            var allowButtonReplay = document.getElementById('allowButtonReplay' + replayId);
                            result = jsonResponse.data.allow;
                            if(result != null) {
                                if(allowButtonReplay != null) {
                                    if(result) {
                                        allowButtonReplay.textContent = '{{ __('masseges.hidden') }}';
                                    } else {
                                        allowButtonReplay.textContent = '{{ __('masseges.visible') }}';
                                    }
                                }
                                return;
                            }
                        }
                    }
                }
                showPopUpMassage('{{ __('masseges.general-error') }}');
            });
        }
        function RequestToggleAvailablePlaylist(playlistId) {
            ajaxRequest('get','../../admin/playlist/' + playlistId + '/ToggleAvailablePlaylist',null,function(jsonResponse) {
                if(jsonResponse != null) {
                    if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                        if(jsonResponse.status && jsonResponse.data.hasOwnProperty('available')) {
                            var availableButton = document.getElementById('availablePlaylistButton' + playlistId);
                            result = jsonResponse.data.available;
                            if(result != null) {
                                if(availableButton != null) {
                                    if(result) {
                                        availableButton.textContent = '{{ __('masseges.playlist-make-not-available') }}';
                                        availableButton.style = "";
                                    } else {
                                        availableButton.textContent = '{{ __('masseges.playlist-make-available') }}';
                                        availableButton.style = "background-color: red;color: #ffffff;";
                                    }
                                }
                                return;
                            }
                        }
                    }
                }
                showPopUpMassage('{{ __('masseges.general-error') }}');
            });
        }
        function deletePlaylist(playlistId) {
            showPopUpMassage('{{ __('masseges.ask-delete-playlist') }}',null,function (exitThis,popUpMassageDiv) {
                ajaxRequest('get','../../admin/playlist/' + playlistId + '/deletePlaylist',null,function(jsonResponse) {
                    if(jsonResponse != null) {
                        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                            if(jsonResponse.status && jsonResponse.data != null) {
                                if(jsonResponse.data.hasOwnProperty('id')) {
                                    var id = jsonResponse.data.id,
                                        playlistDiv = document.getElementById('playlistDiv' + id);
                                    if(playlistDiv != null && main != null) {
                                        main.removeChild(playlistDiv);
                                        showPopUpMassage('{{ __('masseges.delete-playlist-ok') }}');
                                    }
                                    return;
                                }
                            }
                        }
                    }
                    showPopUpMassage('{{ __('masseges.general-error') }}');
                });
                exitThis(popUpMassageDiv);
            },'{{ __('masseges.delete') }}');
        }
    </script>
@endsection

