@extends('admin\layouts\adminpanel')

@section('title') {{ __('headers.admin-navbar-home') }} @endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\home.css') }}"/>
@endsection

@section('content')
<!--
    {{ $allVisiterCount }} <br/>
    {{ $allVisitesCount }} <br/>
    {{ $todayVisitesCount }} <br/>
    {{ $usersCount }} <br/>
    {{ $allViewsCount }} <br/>
    {{ $todayViewsCount }} <br/>
    <hr />
    time is  ::: {{ date('Y-m-d H:i:s',time()) }} <br /> -->
    
    <div class="header-of-admin-home arabic"><section id="openVisitesAndProfitSection" class="header-of-admin-home-selected-section"><span class="no-select">{{ __('masseges.visites-and-profit') }}</span></section><section id="openUsersSection"><span class="no-select">{{ __('masseges.all-users') }}</span></section><section id="openSubscriptionsSection"><span class="no-select">{{ __('masseges.all-subscription-count') }}</span></section></div>

    <div id="visitesAndProfit">
        <div class="chart-container"><canvas id="visiterFlowChart" class="chart"></canvas></div>
        <div class="chart-container"><canvas id="profitFlowChart" class="chart"></canvas></div>

        <section class="arabic">
            <header class="no-select">{{ __('headers.visiters') }}</header>
            <div><span class="no-select">{{ __('masseges.visites-count-today') }}</span><span>{{ $todayVisitesCount }}</span></div>
            <div><span class="no-select">{{ __('masseges.all-visiters-count') }}</span><span>{{ $allVisiterCount }}</span></div>
            <div class="no-select"><a id="buttonShowVisiter">{{ __('masseges.show-visiters') }}</a></div>
        </section>

        <section class="arabic">
            <header class="no-select">{{ __('headers.profit') }}</header>
            <div><span class="no-select">{{ __('masseges.profit-count-today') }}</span><span>{{ $todayVisitesCount }}</span></div>
            <div><span class="no-select">{{ __('masseges.all-profit-count') }}</span><span>{{ $allVisiterCount }}</span></div>
            <div class="no-select"><a id="buttonShowProfit">{{ __('masseges.show-profit') }}</a></div>
        </section>
    </div>
    <div id="users" style="display:none;"></div>
    <div id="subscriptions" style="display:none;"></div>
    <div id="DivOfThisUser" class="pop-up-template subscriptions-of-user" style="display:none;">
        <header><div><canvas id="exitButtonCanvasOfDivOfThisUser" width="25" height="25"></canvas></div></header>
        <div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js/admin/home.js') }}"></script>
    <script type="text/javascript" lang="javascript">
        var visiterFlowChart = document.getElementById('visiterFlowChart'),
            profitFlowChart = document.getElementById('profitFlowChart'),
            visitesAndProfit = document.getElementById('visitesAndProfit'),
            users = document.getElementById('users'),
            subscriptions = document.getElementById('subscriptions'),
            openVisitesAndProfitSection = document.getElementById('openVisitesAndProfitSection'),
            openUsersSection = document.getElementById('openUsersSection'),
            openSubscriptionsSection = document.getElementById('openSubscriptionsSection'),
            DivOfThisUser = document.getElementById('DivOfThisUser'),
            exitButtonCanvasOfDivOfThisUser = document.getElementById('exitButtonCanvasOfDivOfThisUser'),
            buttonShowVisiter = document.getElementById('buttonShowVisiter'),
            buttonShowProfit = document.getElementById('buttonShowProfit');
        if(visiterFlowChart != null) {
            visiterFlowChart.width = 550;
            visiterFlowChart.height = 350;
            var visiter = @json($lastTwoWeekVisites);
            if(IsJsonString(visiter)) {
                visiter = convertJsonToIndexedArray(visiter);
            }
            drawChart(visiterFlowChart,'{{ __('headers.last-two-week-visites') }}',visiter,'#c2c2c2','#6d78ad');
        }
        if(profitFlowChart != null) {
            profitFlowChart.width = 550;
            profitFlowChart.height = 350;
            var profit = @json($profitLastTwoWeek);
            if(IsJsonString(profit)) {
                profit = convertJsonToIndexedArray(profit);
            }
            drawChart(profitFlowChart,'{{ __('headers.profit-last-two-week') }}',profit,'#c2c2c2','#6d78ad');
        }
        if(exitButtonCanvasOfDivOfThisUser != null) {
            exitButtonCanvasOfDivOfThisUser.width = 25;
            exitButtonCanvasOfDivOfThisUser.height = 25;
            drawRemoveIconCanvas(exitButtonCanvasOfDivOfThisUser,'#ffffff');
            exitButtonCanvasOfDivOfThisUser.onclick = function () {
                closeBobUpTemplate(DivOfThisUser);
            }
        }
        if(openVisitesAndProfitSection != null && openUsersSection != null && openSubscriptionsSection != null) {
            openVisitesAndProfitSection.onclick = openVisitesAndProfit;
            openUsersSection.onclick = openUsers;
            openSubscriptionsSection.onclick = openSubscriptions;
        }
        if(buttonShowVisiter != null){
            buttonShowVisiter.onclick = function () {
                ShowVisiter('../admin/home/getVisiters');
            };
        }
        if(buttonShowProfit != null) {
            buttonShowProfit.onclick = ShowProfit;
        }
        function ShowVisiter(path) {
            ajaxRequest('get',path,null,function(jsonResponse){
                if(jsonResponse == null) {
                    showPopUpMassage('{{ __('masseges.general-error') }}');
                    return;
                }
                if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
                    if(jsonResponse.status) {
                        if(DivOfThisUser != null && jsonResponse.data != null) {
                            if(jsonResponse.data.hasOwnProperty('data')) {
                                if(jsonResponse.data.data.length > 0) {
                                    renderVisiters(jsonResponse.data);
                                    DivOfThisUser.setAttribute('style','display:block;');
                                } else {
                                    showPopUpMassage('{{ __('masseges.no-visiters') }}');
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
        function ShowProfit() {
            
        }
        function openVisitesAndProfit() {
            if(visitesAndProfit != null && users != null && subscriptions != null) {
                visitesAndProfit.style = "display:block;";
                users.style = "display:none;";
                subscriptions.style = "display:none;";
                openVisitesAndProfitSection.setAttribute("class","header-of-admin-home-selected-section");
                openUsersSection.setAttribute("class","");
                openSubscriptionsSection.setAttribute("class","");
            }
        }
        function openUsers(){
            if(visitesAndProfit != null && users != null && subscriptions != null) {
                visitesAndProfit.style = "display:none;";
                users.style = "display:block;";
                subscriptions.style = "display:none;";
                openVisitesAndProfitSection.setAttribute("class","");
                openUsersSection.setAttribute("class","header-of-admin-home-selected-section");
                openSubscriptionsSection.setAttribute("class","");
                getUsers('{{ route('admin.home.show.users') }}');
            }
        }
        function openSubscriptions(){
            if(visitesAndProfit != null && users != null && subscriptions != null) {
                visitesAndProfit.style = "display:none;";
                users.style = "display:none;";
                subscriptions.style = "display:block;";
                openVisitesAndProfitSection.setAttribute("class","");
                openUsersSection.setAttribute("class","");
                openSubscriptionsSection.setAttribute("class","header-of-admin-home-selected-section");
                getSubscriptions('{{ route('admin.home.show.subscriptions') }}');
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
                if(type == 'us')a.setAttribute('href','javascript:ShowUsersOfThisPlaylist("' + path + '","{{ __('masseges.general-error') }}",["{{ __('masseges.name') }}","{{ __('masseges.email') }}","{{ __('masseges.pull-playlist') }}","{{ __('masseges.return-playlist') }}"]);');
                if(type == 'v')a.setAttribute('href','javascript:ShowVisiter("' + path + '");');
            }
            a.textContent = buttonText;
            span.appendChild(a);
            return span;
        }

        function getUsers(path) {
            var usersDiv = document.getElementById('usersDiv');
            if(usersDiv != null)users.removeChild(usersDiv);
            usersDiv = document.createElement('div');
            usersDiv.setAttribute('id','usersDiv');
            users.appendChild(usersDiv);
            ajaxRequest('get',path,null,function(jsonResponse) {
                if(jsonResponse == null) {
                    showPopUpMassage('{{ __('masseges.general-error') }}');
                    return;
                }
                if(jsonResponse.hasOwnProperty('status')){
                    if(jsonResponse.status) {
                        if(jsonResponse.hasOwnProperty('data')) {
                            if(jsonResponse.data.hasOwnProperty('data')) {
                                if(jsonResponse.data.data.length > 0) {

                                    renderUsers(usersDiv,jsonResponse.data.data);

                                    if(jsonResponse.data.hasOwnProperty('total') 
                                        && jsonResponse.data.hasOwnProperty('path')
                                        && jsonResponse.data.hasOwnProperty('current_page')) {
                                        if(jsonResponse.data.total > 1) {
                                            var userLinks = document.getElementById('userLinks');
                                            if(userLinks != null)users.removeChild(userLinks);
                                            userLinks = makePaginationLinks(jsonResponse.data);
                                            if(userLinks != null)users.appendChild(userLinks);
                                        }
                                    }
                                    return;
                                }
                            }
                        }
                            var userEmpty = document.createElement('div');
                            userEmpty.setAttribute('id','userEmpty');
                            userEmpty.setAttribute('class','empty');
                            userEmpty.textContent = '{{ __('masseges.empty-users') }}';
                            usersDiv.appendChild(userEmpty);
                        } else {
                            if(jsonResponse.hasOwnProperity('msg')) {
                                showPopUpMassage(jsonResponse.msg);
                            } else {
                                showPopUpMassage('{{ __('masseges.general-error') }}');
                            }
                        }
                    } else {
                        showPopUpMassage('{{ __('masseges.general-error') }}');
                    }
                });
        }
        function getSubscriptions(path) {
            var subscriptionsDiv = document.getElementById('subscriptionsDiv');
            if(subscriptionsDiv != null)subscriptions.removeChild(subscriptionsDiv);
            subscriptionsDiv = document.createElement('div');
            subscriptionsDiv.setAttribute('id','subscriptionsDiv');
            subscriptions.appendChild(subscriptionsDiv);
            ajaxRequest('get',path,null,function(jsonResponse) {
                if(jsonResponse == null) {
                    showPopUpMassage('{{ __('masseges.general-error') }}');
                    return;
                }
                if(jsonResponse.hasOwnProperty('status')){
                        if(jsonResponse.status) {
                            if(jsonResponse.hasOwnProperty('data')) {
                                if(jsonResponse.data.hasOwnProperty('data')) {
                                    if(jsonResponse.data.data.length > 0) {

                                        renderSubscriptions(subscriptionsDiv,jsonResponse.data.data);

                                        if(jsonResponse.data.hasOwnProperty('total') 
                                            && jsonResponse.data.hasOwnProperty('path')
                                            && jsonResponse.data.hasOwnProperty('current_page')) {
                                            if(jsonResponse.data.total > 1) {
                                                var subscriptionLinks = document.getElementById('subscriptionLinks');
                                                if(subscriptionLinks != null)subscriptions.removeChild(subscriptionLinks);
                                                subscriptionLinks = makePaginationLinks(jsonResponse.data,'s');
                                                if(subscriptionLinks != null)subscriptions.appendChild(subscriptionLinks);
                                            }
                                        }
                                        return;
                                    }
                                }
                            }
                            var subscriptionEmpty = document.createElement('div');
                            subscriptionEmpty.setAttribute('id','subscriptionEmpty');
                            subscriptionEmpty.setAttribute('class','empty');
                            subscriptionEmpty.textContent = '{{ __('masseges.empty-subscriptions') }}';
                            subscriptionsDiv.appendChild(subscriptionEmpty);
                        } else {
                            if(jsonResponse.hasOwnProperity('msg')) {
                                showPopUpMassage(jsonResponse.msg);
                            } else {
                                showPopUpMassage('{{ __('masseges.general-error') }}');
                            }
                        }
                    } else {
                        showPopUpMassage('{{ __('masseges.general-error') }}');
                    }
            });
        }
        
        function renderUsers(usersDiv,data) {
            for(user in data) {
                var temp = document.createElement('div'),
                    name = document.createElement('span'),
                    nameLabel = document.createElement('span'),
                    nameDiv = document.createElement('div'),
                    email = document.createElement('span'),
                    emailLabel = document.createElement('span'),
                    emailDiv = document.createElement('div'),
                    registerTime = document.createElement('span'),
                    registerTimeLabel = document.createElement('span'),
                    registerTimeDiv = document.createElement('div'),
                    ButtonShowSubscriptionsOfThisUser = document.createElement('a'),
                    ButtonShowOpinionsOfThisUser = document.createElement('a'),
                    ButtonShowCommentsAndReplaysOfThisUser = document.createElement('a'),
                    ButtonShowSessionsOnlineOfThisUser = document.createElement('a'),
                    ButtonShowViewsOfThisUser = document.createElement('a'),
                    ButtonShowDivicesOfThisUser = document.createElement('a'),
                    DivOfButtons = document.createElement('div');
                
                if(data[user].hasOwnProperty('first_name'))name.textContent = data[user].first_name;
                if(data[user].hasOwnProperty('second_name'))name.textContent = name.textContent + ' ' + data[user].second_name;
                if(data[user].hasOwnProperty('last_name'))name.textContent = name.textContent + ' ' + data[user].last_name;
                if(data[user].hasOwnProperty('email'))email.textContent = data[user].email;
                if(data[user].hasOwnProperty('registerTime'))registerTime.textContent = data[user].registerTime;
                
                nameLabel.textContent = '{{ __('masseges.name') }}';
                emailLabel.textContent = '{{ __('masseges.email') }}';
                registerTimeLabel.textContent = '{{ __('masseges.register-time') }}';

                nameLabel.setAttribute('class','no-select');
                emailLabel.setAttribute('class','no-select');
                registerTimeLabel.setAttribute('class','no-select');

                nameDiv.appendChild(nameLabel);
                nameDiv.appendChild(name);

                emailDiv.appendChild(emailLabel);
                emailDiv.appendChild(email);

                registerTimeDiv.appendChild(registerTimeLabel);
                registerTimeDiv.appendChild(registerTime);

                ButtonShowSubscriptionsOfThisUser.textContent =  '{{ __('masseges.show-subscriptions') }}';
                ButtonShowOpinionsOfThisUser.textContent =  '{{ __('masseges.show-opinions') }}';
                ButtonShowCommentsAndReplaysOfThisUser.textContent =  '{{ __('masseges.comments-and-replays') }}';
                ButtonShowSessionsOnlineOfThisUser.textContent =  '{{ __('masseges.sessions-online') }}';
                ButtonShowViewsOfThisUser.textContent =  '{{ __('masseges.views') }}';
                ButtonShowDivicesOfThisUser.textContent =  '{{ __('masseges.user-divices') }}';
                if(data[user].hasOwnProperty('id')) {
                    ButtonShowSubscriptionsOfThisUser.onclick = new Function("ShowSubscriptionsOfThisUser(" + data[user].id + ",'{{ __('masseges.general-error') }}','{{ __('masseges.no-subscriptions-of-this-user') }}',['{{ __('masseges.pull-playlist') }}','{{ __('masseges.return-playlist') }}','{{ __('masseges.pull-playlist-alert') }}']);");
                    ButtonShowOpinionsOfThisUser.onclick = new Function("ShowOpinionsOfThisUser(" + data[user].id + ",'{{ __('masseges.general-error') }}',['{{ __('masseges.coach-opinions') }}','{{ __('masseges.playlist-opinions') }}','{{ __('masseges.coach-opinions-empty') }}','{{ __('masseges.playlist-opinions-empty') }}','{{ __('masseges.hidden') }}','{{ __('masseges.visible') }}','{{ __('masseges.the-opinion') }}','{{ __('masseges.playlist-title') }}']);");
                    ButtonShowCommentsAndReplaysOfThisUser.onclick = new Function("ShowCommentsAndReplaysOfThisUser(" + data[user].id + ",'{{ __('masseges.general-error') }}',['{{ __('masseges.comments') }}','{{ __('masseges.replays') }}','{{ __('masseges.no-comments-of-user') }}','{{ __('masseges.no-replays-of-user') }}','{{ __('masseges.hidden') }}','{{ __('masseges.visible') }}','{{ __('masseges.the-comment') }}','{{ __('masseges.playlist-title') }}','{{ __('masseges.the-replay') }}','{{ __('masseges.replay-of-comment') }}']);");
                    ButtonShowSessionsOnlineOfThisUser.onclick = new Function("ShowSessionsOnlineOfThisUser(" + data[user].id + ",'{{ __('masseges.general-error') }}','{{ __('masseges.no-session-online-of-this-user') }}',['{{ __('masseges.session-name') }}','{{ __('masseges.session-price') }}','{{ __('masseges.session-time') }}','{{ __('masseges.session-taken') }}','{{ __('masseges.session-reverse-admission') }}','{{ __('masseges.session-set-admission') }}','{{ __('masseges.session-reverse-admission-ask') }}','{{ __('masseges.session-set-admission-ask') }}','{{ __('masseges.ok') }}','{{ __('masseges.session-taken-before-now') }}']);");
                    ButtonShowViewsOfThisUser.onclick = new Function("ShowViewsOfThisUser(" + data[user].id + ",'{{ __('masseges.general-error') }}','{{ __('masseges.no-views-of-this-user') }}',['{{ __('masseges.pre-title') }}','{{ __('masseges.title-two') }}','{{ __('masseges.views-count') }}']);");
                    ButtonShowDivicesOfThisUser.onclick = new Function("ShowDivicesOfThisUser(" + data[user].id + ",'{{ __('masseges.general-error') }}','{{ __('masseges.no-divices-of-this-user') }}');");
                }
                DivOfButtons.appendChild(ButtonShowSubscriptionsOfThisUser);
                DivOfButtons.appendChild(ButtonShowOpinionsOfThisUser);
                DivOfButtons.appendChild(ButtonShowCommentsAndReplaysOfThisUser);
                DivOfButtons.appendChild(ButtonShowSessionsOnlineOfThisUser);
                DivOfButtons.appendChild(ButtonShowViewsOfThisUser);
                DivOfButtons.appendChild(ButtonShowDivicesOfThisUser);
                DivOfButtons.setAttribute('class','no-select');
                
                temp.setAttribute('class','user-div');
                temp.appendChild(nameDiv);
                temp.appendChild(emailDiv);
                temp.appendChild(registerTimeDiv);
                temp.appendChild(DivOfButtons);
                usersDiv.appendChild(temp);
            }
        }
        function renderSubscriptions(subscriptionsDiv,data) {
            for(playlist in data) {
                var playlistDiv = document.createElement('div'),
                    div = document.createElement('div'),
                    poster = document.createElement('img'),
                    title = document.createElement('span'),
                    subscriptionsCount = document.createElement('span'),
                    showSubscriptions = document.createElement('span');
                playlistDiv.setAttribute('class','playlist video no-select');
                
                if(data[playlist].hasOwnProperty('poster'))poster.setAttribute('src','../..' + data[playlist].poster);
                else poster.setAttribute('src','../../images/static/playlist-default.png');
                if(data[playlist].hasOwnProperty('title'))title.textContent = data[playlist].title;
                if(data[playlist].hasOwnProperty('id'))showSubscriptions.onclick = new Function("ShowUsersOfThisPlaylist('" + '../../admin/home/' + data[playlist].id + '/getUsersOfThisPlaylist' + "','{{ __('masseges.general-error') }}',['{{ __('masseges.name') }}','{{ __('masseges.email') }}','{{ __('masseges.pull-playlist') }}','{{ __('masseges.return-playlist') }}']);");
                if(data[playlist].hasOwnProperty('subscriptions_count')) {
                    subscriptionsCount.textContent = '{{ __('masseges.count-of-subscriptions') }}' + ': ' + data[playlist].subscriptions_count;
                    if(data[playlist].subscriptions_count == 0) showSubscriptions.onclick = new Function("showPopUpMassage('{{ __('masseges.no-subscriptions-of-this-playlist') }}');");
                }
                showSubscriptions.textContent = '{{ __('masseges.show-user-subscription') }}';
                div.appendChild(title);
                div.appendChild(subscriptionsCount);
                div.appendChild(showSubscriptions);
                playlistDiv.appendChild(poster);
                playlistDiv.appendChild(div);
                subscriptionsDiv.appendChild(playlistDiv);
            }
        }
        
    </script>
@endsection