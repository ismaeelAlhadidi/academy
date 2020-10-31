<header class="header arabic no-select"><nav>
        <div class="nav-button"><canvas id="navButton"></canvas></div>
        <a class="header-profile-image" href="{{ route('admin.profile') }}"><img src="{{ isset(auth('admin')->user()->image) ? asset(auth('admin')->user()->image) : asset('images/static/adminLogin.png') }}"/></a>
        <ul><a class="list-item" href="{{ route('admin.profile') }}"><li class="admin-name-in-header">{{ (strlen(auth('admin')->user()->username) <= 17) ? auth('admin')->user()->username : substr(auth('admin')->user()->username, 0, 14) . '...' }}</li></a>
        <a class="list-item notifcations-button" id="notifcationButton" ><li><span class="fa">&#xf0f3;</span>@if(session()->has('newNotifcations')) @if(session()->get('newNotifcations') > 0)<span id="countOfNewNotifcations" class="count-of-new-notifcations">{{ session()->get('newNotifcations') }}</span>@endif @endif</li></a></ul>
    </nav><div class="search" style="display: none;">
        <form id="searchForm" action="" method="get">
            @csrf
            <input id="searchInput" type="text" name="key" autocomplete="off"/>
        </form>
    </div><aside id="menuOfNavList" style="display: none;">
            <ul class="list-overflow">
                <a class="list-item list-overflow-item" href="{{ route('admin.home') }}"><li>{{ __('headers.admin-navbar-home') }}</li></a>
                <a class="list-item list-overflow-item" href="{{ route('admin.playlist') }}"><li>{{ __('headers.admin-navbar-playlists') }}</li></a>
                <a class="list-item list-overflow-item" href="{{ route('admin.playlist.add') }}"><li>{{ __('headers.admin-navbar-add-playlist') }}</li></a>
                <a class="list-item list-overflow-item" href="{{ route('admin.session-offer') }}"><li>{{ __('headers.admin-navbar-session-offers') }}</li></a>
                <a class="list-item list-overflow-item" href="{{ route('admin.sessions') }}"><li>{{ __('headers.admin-navbar-sessions') }}</li></a>
                <a class="list-item list-overflow-item" href="{{ route('admin.single-videos') }}"><li>{{ __('headers.admin-single-videos') }}</li></a>
                <a class="list-item list-overflow-item" href="{{ route('admin.app.settings') }}"><li>{{ __('headers.admin-navbar-app-settings') }}</li></a>
                <a class="list-item list-overflow-item" href="{{ route('admin.logout') }}"><li>{{ __('auth.logout') }}</li></a>
            </ul>
    </aside></header><div class="fix-header-space-in-workflow"></div>