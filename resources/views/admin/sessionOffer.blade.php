@extends('admin\layouts\adminpanel')

@section('title')
    {{ __('headers.admin-navbar-session-offers') }}
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\playlists.css') }}"/>
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\playlist\add.css') }}"/>
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\offers.css') }}"/>
@endsection

@section('content')
    <header class="header-of-main-div no-select"><h3>{{ __('headers.admin-navbar-session-offers') }}</h3><a href="javascript:openAddTemplate();">{{ __('headers.admin-navbar-add-offer-session') }}</a></header>
    <div id="offers">
        @forelse($offers as $offer)
            <div class="playlist-div" id="offerDiv{{ $offer->id }}">
                <section><img src="{{ asset($offer->poster) }}"/></section>
                <div>
                    <div><span class="no-select">{{ __('masseges.session-name') }}</span><span>{{ $offer->name }}</span></div>
                    <div><span class="no-select">{{ __('masseges.session-price') }}</span><span>{{ $offer->price }}</span></div>
                    <div><span class="no-select">{{ __('masseges.session-duration') }}</span><span>{{ $offer->duration }}</span></div>
                    <div class="no-select">
                        <a href="javascript:deleteOffer('{{ $offer->id }}');">{{ __('masseges.delete') }}</a>
                        <a href="javascript:updateOffer('{{ $offer->id }}');">{{ __('masseges.update') }}</a>
                        <a href="javascript:showSession('{{ $offer->id }}');">{{ __('headers.admin-navbar-sessions') }}</a>
                    </div>
                </div>
            </div>
        @empty
            <div id="offersEmpty" class="empty">{{ __('masseges.no-offers') }}</div>
        @endforelse
    </div>
    <div class="clear-float"></div>
    {{ $offers->links() }}
    <div id="divOfThisSession" class="pop-up-template template-of-this-playlist big-template" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfDivOfThisSession" width="25" height="25"></canvas></div></header>
        <div id="sessionsOfOffer">
            <header id="sessionsDivHeader" class="small-default-header add-video-header no-select"><span>{{ __('headers.admin-navbar-add-offer-session') }}</span></header>
            <table id="tableOfSessions" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('masseges.name') }}</th>
                        <th>{{ __('masseges.session-time') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tbodyOfSessions">

                </tbody>
            </table>
        </div>
    </div>
    <div id="addAndEditTemplate" class="pop-up-template template-of-this-playlist big-template" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfAddAndEditTemplate" width="25" height="25"></canvas></div></header>
        <div class="add-new-type add-new-video">
            <header class="small-default-header add-video-header no-select"><span>{{ __('headers.admin-navbar-add-offer-session') }}</span></header>
            <section class="video-poster-container">
                    <div class="select-profile-image no-select">
                        <div class="transition">
                            <img id="imageOfOfferUploading" src="{{ asset('/images/static/offer-default.jpg') }}"/>
                            <div id="uploadOfferPosterHover" class="transition"><span id="changeSpanOfOfferPoster" class="text">{{ __('masseges.click-to-change-image') }}</span></div>
                            <div class="custom-button-one"><div></div><div></div><div></div></div>
                        </div>
                        <div id="selectImageOfOffer">
                            <input id="posterInputOfNewOffer" type="file" accept=".png,.jpg,.tif,.gif" title="{{ __('masseges.select-image') }}" style="display:none;"/>
                        </div>
                    </div>
            </section>
            <div class="data-of-video-div">
                <div><span class="no-select">{{ __('masseges.session-name') }}</span><input id="sessionNameInput" type="text" placeholder="{{ __('masseges.session-name') }}"/></div>
                <div><span class="no-select">{{ __('masseges.session-price') }}</span><input id="sessionPriceInput" type="text" placeholder="{{ __('masseges.session-price') }}"/></div>
                <div><span class="no-select">{{ __('masseges.session-duration') }}</span><input id="sessionDurationInput" type="text" placeholder="{{ __('masseges.session-duration') }}"/></div>
                <div><span class="no-select">{{ __('input.for-who') }}</span><textarea id="forWhoInput" placeholder="{{ __('input.for-who') }}"></textarea></div>
                <div><span class="no-select">{{ __('input.for-who-not') }}</span><textarea id="forWhoNotInput" placeholder="{{ __('input.for-who') }}"></textarea></div>
                <div><span class="no-select">{{ __('input.benefits') }}</span><textarea id="benefitsInput" placeholder="{{ __('input.benefits') }}"></textarea></div>
                <div><span class="no-select">{{ __('input.notes') }}</span><textarea id="notesInput" placeholder="{{ __('input.notes') }}"></textarea></div>
            </div>
            <section><button id="addNewOfferButton" type="button" class="transition">{{ __('masseges.add') }}</button><button id="editOfferButton" type="button" class="transition" style="display:none !important;">{{ __('masseges.update') }}</button></section>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js\admin\sessionOffer.js') }}"></script>
    <script type="text/javascript" lang="javascript">
        var divOfThisSession = document.getElementById('divOfThisSession'),
            sessionsDivHeader = document.getElementById('sessionsDivHeader'),
            tbodyOfSessions = document.getElementById('tbodyOfSessions'),
            tableOfSessions = document.getElementById('tableOfSessions'),
            sessionsOfOffer = document.getElementById('sessionsOfOffer'),
            exitButtonCanvasOfDivOfThisSession = document.getElementById('exitButtonCanvasOfDivOfThisSession'),
            addAndEditTemplate = document.getElementById('addAndEditTemplate'),
            exitButtonCanvasOfAddAndEditTemplate = document.getElementById('exitButtonCanvasOfAddAndEditTemplate'),
            sessionNameInput = document.getElementById('sessionNameInput'),
            sessionPriceInput = document.getElementById('sessionPriceInput'),
            sessionDurationInput = document.getElementById('sessionDurationInput'),
            forWhoInput = document.getElementById('forWhoInput'),
            forWhoNotInput = document.getElementById('forWhoNotInput'),
            benefitsInput = document.getElementById('benefitsInput'),
            notesInput = document.getElementById('notesInput'),
            addNewOfferButton = document.getElementById('addNewOfferButton'),
            editOfferButton = document.getElementById('editOfferButton'),
            uploadOfferPosterHover = document.getElementById('uploadOfferPosterHover'),
            posterInputOfNewOffer = document.getElementById('posterInputOfNewOffer'),
            changeSpanOfOfferPoster = document.getElementById('changeSpanOfOfferPoster'),
            maxLengthAlert = '{{ __('input.character-of-name-must-min-than-255') }}';
        
        if(typeof(Offer) == "function") {
            if(Offer.hasOwnProperty('defaultPoster')) Offer.defaultPoster = '{{ asset('/images/static/offer-default.jpg') }}';
            if(Offer.hasOwnProperty('defaultUserImage')) Offer.defaultUserImage = '{{ asset('/images/static/user-default.jpg') }}';
            if(Offer.hasOwnProperty('deleteUrl')) Offer.deleteUrl = '{{ route('admin.delete-session-offer') }}';
            if(Offer.hasOwnProperty('getDataUrl')) Offer.getDataUrl = '';
            if(Offer.hasOwnProperty('updateUrl')) Offer.updateUrl = '{{ route('admin.update-session-offer') }}';
            if(Offer.hasOwnProperty('addUrl')) Offer.addUrl = '{{ route('admin.store-session-offer') }}';
            if(Offer.hasOwnProperty('getSessionUrl')) Offer.getSessionUrl = '{{ route('admin.get-sessions-of-session-offer') }}';
            if(Offer.hasOwnProperty('token')) Offer.token = '{{ csrf_token() }}';
            if(Offer.hasOwnProperty('lang')) {
                Offer.lang = [
                    /* 00 => */ '{{ __('masseges.session-name') }}',
                    /* 01 => */ '{{ __('masseges.session-price') }}',
                    /* 02 => */ '{{ __('masseges.session-duration') }}',
                    /* 03 => */ '{{ __('masseges.delete') }}',
                    /* 04 => */ '{{ __('masseges.update') }}',
                    /* 05 => */ '{{ __('headers.admin-navbar-sessions') }}',
                    /* 06 => */ '{{ __('masseges.no-sessions-of-this-offer') }}',
                    /* 07 => */ '{{ __('masseges.general-error') }}',
                    /* 08 => */ '{{ __('masseges.add-ok') }}',
                    /* 09 => */ '{{ __('masseges.ask-to-delete-offer-with-sessions') }}',
                    /* 10 => */ '{{ __('masseges.ok') }}',
                    /* 11 => */ '{{ __('masseges.ask-to-delete-offer') }}',
                    /* 12 => */ '{{ __('masseges.no-offers') }}',
                    /* 13 => */ '{{ __('masseges.update-ok') }}',
                    /* 14 => */ '{{ __('masseges.session-reverse-admission') }}',
                    /* 15 => */ '{{ __('masseges.session-set-admission') }}',
                    /* 16 => */ '{{ __('masseges.session-set-admission-ask') }}',
                    /* 17 => */ '{{ __('masseges.session-reverse-admission-ask') }}',
                ];
            }
        }

        if(exitButtonCanvasOfDivOfThisSession != null && divOfThisSession != null) {
            exitButtonCanvasOfDivOfThisSession.width = 25;
            exitButtonCanvasOfDivOfThisSession.height = 25;
            if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfDivOfThisSession,'#ffffff');
            exitButtonCanvasOfDivOfThisSession.onclick = function () {
                if(typeof(closeBobUpTemplate) == "function") closeBobUpTemplate(divOfThisSession);
            }
        }

        if(exitButtonCanvasOfAddAndEditTemplate != null && addAndEditTemplate != null) {
            exitButtonCanvasOfAddAndEditTemplate.width = 25;
            exitButtonCanvasOfAddAndEditTemplate.height = 25;
            if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfAddAndEditTemplate,'#ffffff');
            exitButtonCanvasOfAddAndEditTemplate.onclick = function () {
                if(typeof(closeBobUpTemplate) == "function") closeBobUpTemplate(addAndEditTemplate);
            }
        }

        if(typeof(Offer) == "function" && typeof(OfferCollection) == "function") {
            @foreach($offers as $offer)
                new Offer(null, null, null, null, null, null, @json($offer));
            @endforeach
        }
        if(sessionNameInput != null) {
            sessionNameInput.onfocus = function () {
                makeFocusEfectOfInput(sessionNameInput);
            };
            sessionNameInput.onblur = function () {
                removeFocusEfectOfInput(sessionNameInput);
            };
        }
        if(sessionPriceInput != null) {
            sessionPriceInput.onfocus = function () {
                makeFocusEfectOfInput(sessionPriceInput);
            };
            sessionPriceInput.onblur = function () {
                removeFocusEfectOfInput(sessionPriceInput);
            };
        }
        if(sessionDurationInput != null) {
            sessionDurationInput.onfocus = function () {
                makeFocusEfectOfInput(sessionDurationInput);
            };
            sessionDurationInput.onblur = function () {
                removeFocusEfectOfInput(sessionDurationInput);
            };
        }
        if(forWhoInput != null) {
            forWhoInput.onfocus = function () {
                makeFocusEfectOfInput(forWhoInput);
            };
            forWhoInput.onblur = function () {
                removeFocusEfectOfInput(forWhoInput);
            };
        }
        if(forWhoNotInput != null) {
            forWhoNotInput.onfocus = function () {
                makeFocusEfectOfInput(forWhoNotInput);
            };
            forWhoNotInput.onblur = function () {
                removeFocusEfectOfInput(forWhoNotInput);
            };
        }
        if(benefitsInput != null) {
            benefitsInput.onfocus = function () {
                makeFocusEfectOfInput(benefitsInput);
            };
            benefitsInput.onblur = function () {
                removeFocusEfectOfInput(benefitsInput);
            };
        }
        if(notesInput != null) {
            notesInput.onfocus = function () {
                makeFocusEfectOfInput(notesInput);
            };
            notesInput.onblur = function () {
                removeFocusEfectOfInput(notesInput);
            };
        }
        if(addNewOfferButton != null) {
            addNewOfferButton.onclick = addNewOfferButtonClickHandler;
        }
        if(editOfferButton != null) {
            editOfferButton.onclick = editOfferButtonClickHandler;
        }
        if(uploadOfferPosterHover != null && posterInputOfNewOffer != null) {
            uploadOfferPosterHover.onclick = function () {
                posterInputOfNewOffer.click();
            };
            posterInputOfNewOffer.onchange = function () {
                if(posterInputOfNewOffer.files.length == 1){
                    if(posterInputOfNewOffer.files[0].size >= 2000000){
                        showPopUpMassage('{{ __('masseges.big-size-of-image') }}');
                        return;
                    }
                    if(window.File && window.FileList && window.FileReader) {
                        if(posterInputOfNewOffer.files[0].type.match('image')){
                            var fileReader = new FileReader();
                            fileReader.addEventListener("load",function(event){
                                var picFile = event.target;
                                if(imageOfOfferUploading != null) imageOfOfferUploading.setAttribute('src',picFile.result);
                            });
                            fileReader.readAsDataURL(posterInputOfNewOffer.files[0]);
                        }
                    } else {
                        uploadOfferPosterHover.setAttribute('style','top:0;right:0;bottom:0;left:0;width:200px;height:200px;');
                        if(changeSpanOfOfferPoster !== null) changeSpanOfOfferPoster.innerHtml = '{{ __('masseges.browser-not-support-read-image') }}';
                    }
                }
            };
        }
    </script>
@endsection

