<header id="header" class="header-after-scroll-down no-select"><nav>
            <ul>
                @guest
                    <a href="{{ route('login') }}"><li>{{ __('title.adminLogin') }}</li></a><a href="{{ route('register') }}"><li>{{ __('title.Register') }}</li></a>
                @else
                    <a id="headerNavButton"><li class="nav-button"><canvas id="headerNavCanvasButton" width="35" height="35"></canvas></li></a>
                    <a><li class="nav-image"><img src="{{ asset(auth()->user()->image) }}"/></li></a>
                    <a><li>{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</li></a>
                    <a class="notifcations-button" id="notifcationButton" ><li><span class="fa">&#xf0f3;</span>@if(session()->has('newNotifcations')) @if(session()->get('newNotifcations') > 0)<span class="count-of-new-notifcations">{{ session()->get('newNotifcations') }}</span>@endif @endif</li></a>
                @endguest
            </ul>
        </nav><div class="logo">logo</div></header>@auth<aside id="menuOfNavList" style="display: none;"><ul class="list-overflow">
            <a class="list-item list-overflow-item" href="{{ route('home') }}"><li>{{ __('headers.admin-navbar-home') }}</li></a>
            <a class="list-item list-overflow-item" href="{{ route('admin.playlist') }}"><li>{{ __('headers.navbar-my-account') }}</li></a>
            <a class="list-item list-overflow-item" href="{{ route('home') }}"><li>{{ __('headers.admin-navbar-playlists') }}</li></a>
            <a class="list-item list-overflow-item" href="{{ route('admin.playlist') }}"><li>{{ __('headers.navbar-my-playlists') }}</li></a>
            <a class="list-item list-overflow-item" href="{{ route('sessions') }}"><li>{{ __('headers.admin-navbar-sessions') }}</li></a>
            <a c class="list-item list-overflow-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <li>{{ __('auth.Logout') }}</li>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none !important;">
                @csrf
            </form>
        </ul>
    </aside>@endauth