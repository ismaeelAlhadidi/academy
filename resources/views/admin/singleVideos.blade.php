@extends('admin\layouts\adminpanel')

@section('title') {{ __('headers.admin-single-videos') }} @endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\playlists.css') }}"/>
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\playlist\add.css') }}"/>
@endsection

@section('style')
    <style type="text/css">
        @media (max-width: 400px) {
            .form-users-tabel {
                font-size: 10px;
                padding-left: 5px;
                padding-right: 5px;
            }
            .form-users-tabel th:nth-of-type(4) {
                display: none;
            }
            .form-users-tabel td:nth-of-type(4) {
                display: none;
            }
        }
        @media (max-width: 576px) {
            .main * {
                font-size: 14px;
            }
            .main > header h3, .main > header a {
                font-size: 16px;
            }
            .main > header a {
                width: 130px;
            }
            .form-users-tabel {
                font-size: 12px;
            }
            .form-users-tabel th:nth-of-type(2) {
                display: none;
            }
            .form-users-tabel td:nth-of-type(2) {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    <header class="header-of-main-div no-select"><h3>{{ __('headers.admin-single-videos') }}</h3><a href="javascript:openAddTemplate();">{{ __('masseges.add-video') }}</a></header>
    @forelse($videos as $video)
        <single-video data="{{ $video }}"></single-video>
    @empty
        <div id="emptyDivOfSingleVideos" class="empty">{{ __('masseges.no-videos') }}</div>
    @endforelse 
    <div class="clear-float"></div>
    {{ $videos->links() }}
    <form id="addAndUpdateFormElement" class="add-playlist-form">
        @csrf
        <div id="addNewVideoTemplate" class="pop-up-template template-of-this-playlist" style="display: none;">
            <header><div><canvas id="exitButtonCanvasOfAddNewVideoTemplate" width="25" height="25"></canvas></div></header>
            <div class="add-new-type add-new-video">
                <header class="small-default-header add-video-header no-select"><span>{{ __('masseges.add-video') }}</span></header>
                <section class="video-poster-container">
                    <div class="select-profile-image no-select">
                        <div class="transition">
                            <img id="imageOfVideoUploading" src="{{ asset('/images/static/video-default.jpg') }}"/>
                            <div id="uploadVideoPosterHover" class="transition"><span id="changeSpanOfVideoPoster" class="text">{{ __('masseges.click-to-change-image') }}</span></div>
                            <div class="custom-button-one"><div></div><div></div><div></div></div>
                        </div>
                        <div id="selectImageOfVideo">
                            <input id="posterInputOfNewVideo" type="file" accept=".png,.jpg,.tif,.gif" title="{{ __('masseges.select-image') }}" style="display:none;"/>
                        </div>
                    </div>
                </section>
                <div class="data-of-video-div">
                    <div><span class="no-select">{{ __('input.video-pre-title') }}</span><input name="pre_title" id="titleOneInputOfNewVideo" type="text" placeholder="{{ __('input.video-pre-title') }}"/></div>
                    <div><span class="no-select">{{ __('input.video-title') }}</span><input name="title" id="titleTwoInputOfNewVideo" type="text" placeholder="{{ __('input.video-title') }}"/></div>
                    <div><span class="no-select">{{ __('input.date-of-available-video') }}</span><input id="dateInputOfNewVideo" type="date" /></div>
                    <div><span class="no-select">{{ __('input.video-type') }}</span><select id="selectTypeOfThisVideo" class="no-select select-type-of-this-video">
                        <option value="-1" selected>{{ __('input.video-type') }}</option>
                        @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select></div>
                    <div><span class="no-select"></span><input id="srcInputOfNewVideoOpenButton" type="button" class="transition no-select" onclick="Click('srcInputOfNewVideo');" value="{{ __('input.select-video') }}" /><input name="video" id="srcInputOfNewVideo" type="file" accept=".mp4,.avi,.flv,.wmv,.mov" style="display:none !important;" /></div>
                </div>
                <section><button id="addNewVideoButton" type="button" class="transition">{{ __('masseges.add') }}</button><button id="editVideoButton" type="button" class="transition" style="display:none !important;">{{ __('masseges.update') }}</button></section>
            </div>
        </div>
    </form>
    <div id="watchingDiv" class="pop-up-template template-of-this-playlist big-template watching-pop-up-template" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfWatchingDiv" width="25" height="25"></canvas></div></header>
        <video id="watchingVideoElement" controls></video>
    </div>
    <div id="usersDataTemplate" class="pop-up-template template-of-this-playlist big-template" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfUsersDataTemplate" width="25" height="25"></canvas></div></header>
        <div id="contentOfUsersDataTemplate"></div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js\admin\singleVideo.js') }}"></script>
    <script type="text/javascript" lang="javascript">
        if(typeof(SingleVideo) == "function") {
            if(SingleVideo.hasOwnProperty('langArrayFromServer')) {
                SingleVideo.langArrayFromServer = [
                    /* 00 */ '{{ __('input.video-title') }}',
                    /* 01 */ '{{ __('input.date-of-available-video') }}',
                    /* 02 */ '{{ __('input.form-url') }}',
                    /* 03 */ '{{ __('masseges.starting-upload-video') }}',
                    /* 04 */ '{{ __('masseges.not-fixed') }}',
                    /* 05 */ '{{ __('masseges.delete') }}',
                    /* 06 */ '{{ __('masseges.update') }}',
                    /* 07 */ '{{ __('masseges.show') }}',
                    /* 08 */ '{{ __('masseges.show-user-subscription') }}',
                    /* 09 */ '{{ __('masseges.ask-remove-single-video') }}',
                    /* 10 */ '{{ __('masseges.ok') }}',
                ];
            }
            if(SingleVideo.hasOwnProperty('userFormColumn')) {
                SingleVideo.userFormColumn = [
                    'email',
                    'name',
                    'timeOfFullForm',
                    'isUser',
                    'send_mail',
                ];
            }
            if(SingleVideo.hasOwnProperty('userFormColumnLang')) {
                SingleVideo.userFormColumnLang = {
                    'email': "{{ __('input.email') }}",
                    'name': "{{ __('masseges.name') }}",
                    'timeOfFullForm': "{{ __('masseges.register-time')}}",
                    'isUser': "{{ __('masseges.status') }}",
                    'send_mail': "{{ __('masseges.reseve') }}",
                };
            }
        }
        window.customElements.define('single-video', SingleVideo);

        /********************* Add new video template *********************/
        var exitButtonCanvasOfAddNewVideoTemplate = document.getElementById("exitButtonCanvasOfAddNewVideoTemplate"),
            addNewVideoTemplate = document.getElementById("addNewVideoTemplate"),
            uploadVideoPosterHover = document.getElementById("uploadVideoPosterHover"),
            posterInputOfNewVideo = document.getElementById("posterInputOfNewVideo"),
            changeSpanOfVideoPoster = document.getElementById("changeSpanOfVideoPoster"),
            titleOneInputOfNewVideo = document.getElementById("titleOneInputOfNewVideo"),
            titleTwoInputOfNewVideo = document.getElementById("titleTwoInputOfNewVideo"),
            addNewVideoButton = document.getElementById("addNewVideoButton"),
            editVideoButton = document.getElementById("editVideoButton"),
            srcInputOfNewVideo = document.getElementById("srcInputOfNewVideo"),
            srcInputOfNewVideoOpenButton = document.getElementById("srcInputOfNewVideoOpenButton"),
            dateInputOfNewVideo = document.getElementById("dateInputOfNewVideo"),
            selectTypeOfThisVideo = document.getElementById("selectTypeOfThisVideo"),
            imageOfVideoUploading = document.getElementById("imageOfVideoUploading"),
            exitButtonCanvasOfWatchingDiv = document.getElementById("exitButtonCanvasOfWatchingDiv"),
            watchingDiv = document.getElementById("watchingDiv"),
            watchingVideoElement = document.getElementById("watchingVideoElement"),
            exitButtonCanvasOfUsersDataTemplate = document.getElementById("exitButtonCanvasOfUsersDataTemplate");

        if(exitButtonCanvasOfAddNewVideoTemplate != null) {
            exitButtonCanvasOfAddNewVideoTemplate.width = 25;
            exitButtonCanvasOfAddNewVideoTemplate.height = 25;
            if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfAddNewVideoTemplate,'#ffffff');
            exitButtonCanvasOfAddNewVideoTemplate.onclick = function () {
                if(typeof(closeBobUpTemplate) == "function") {
                    if(typeof(SingleVideo) == "function") {
                        if(SingleVideo.tempBlobUrl != null && SingleVideo.tempBlobUrl != undefined) {
                            URL.revokeObjectURL(SingleVideo.tempBlobUrl);
                        }
                    }
                    closeBobUpTemplate(addNewVideoTemplate);
                }
            };
        }
        if(exitButtonCanvasOfWatchingDiv != null) {
            exitButtonCanvasOfWatchingDiv.width = 25;
            exitButtonCanvasOfWatchingDiv.height = 25;
            if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfWatchingDiv,'#ffffff');
            exitButtonCanvasOfWatchingDiv.onclick = function () {
                if(typeof(closeBobUpTemplate) == "function") closeBobUpTemplate(watchingDiv);
            };
        }
        if(exitButtonCanvasOfUsersDataTemplate != null) {
            exitButtonCanvasOfUsersDataTemplate.width = 25;
            exitButtonCanvasOfUsersDataTemplate.height = 25;
            if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfUsersDataTemplate,'#ffffff');
            exitButtonCanvasOfUsersDataTemplate.onclick = function () {
                if(typeof(SingleVideo) == "function") {
                    if(SingleVideo.hasOwnProperty('usersDataTemplate')){
                        if(typeof(closeBobUpTemplate) == "function") closeBobUpTemplate(SingleVideo.usersDataTemplate);
                    }
                }
            };
        }
        
        if(uploadVideoPosterHover != null && posterInputOfNewVideo != null) {
            uploadVideoPosterHover.onclick = function () {
                posterInputOfNewVideo.click();
            };
            posterInputOfNewVideo.onchange = function () {
                if(posterInputOfNewVideo.files.length == 1){
                    if(posterInputOfNewVideo.files[0].size >= 2000000){
                        if(typeof("showPopUpMassage") == "function") showPopUpMassage('{{ __('masseges.big-size-of-image') }}');
                        return;
                    }
                    if(window.File && window.FileList && window.FileReader) {
                        if(posterInputOfNewVideo.files[0].type.match('image')){
                            var fileReader = new FileReader();
                            fileReader.addEventListener("load",function(event){
                                var picFile = event.target;
                                if(imageOfVideoUploading != null)imageOfVideoUploading.setAttribute('src',picFile.result);
                            });
                            fileReader.readAsDataURL(posterInputOfNewVideo.files[0]);
                        }
                    } else {
                        uploadVideoPosterHover.setAttribute('style','top:0;right:0;bottom:0;left:0;width:200px;height:200px;');
                        if(changeSpanOfVideoPoster !== null) changeSpanOfVideoPoster.innerHtml = '{{ __('masseges.browser-not-support-read-image') }}';
                    }
                }
            };
        }
        if(titleOneInputOfNewVideo != null) {
            titleOneInputOfNewVideo.onfocus = function () {
                makeFocusEfectOfInput(titleOneInputOfNewVideo);
            };
            titleOneInputOfNewVideo.onblur = function () {
                removeFocusEfectOfInput(titleOneInputOfNewVideo);
            };
        }
        if(titleTwoInputOfNewVideo != null) {
            titleTwoInputOfNewVideo.onfocus = function () {
                makeFocusEfectOfInput(titleTwoInputOfNewVideo);
            };
            titleTwoInputOfNewVideo.onblur = function () {
                removeFocusEfectOfInput(titleTwoInputOfNewVideo);
            };
        }
        if(typeof(SingleVideo) == "function") {
            if(SingleVideo.hasOwnProperty('addAndUpdateTemplate')) SingleVideo.addAndUpdateTemplate = addNewVideoTemplate;
            if(SingleVideo.hasOwnProperty('urlOfAddNewVideo')) SingleVideo.urlOfAddNewVideo = "{{ route("admin.single-videos.add") }}";
            if(SingleVideo.hasOwnProperty('urlOfUpdateVideo')) SingleVideo.urlOfUpdateVideo = "{{ asset("/admin/playlist/update/video") }}";
            if(SingleVideo.hasOwnProperty('defaultPoster')) SingleVideo.defaultPoster = "{{ asset("/images/static/video-default.jpg") }}";
            if(SingleVideo.hasOwnProperty('massegeOfLengthMoreThanMaxSize')) SingleVideo.massegeOfLengthMoreThanMaxSize = "{{ __('input.character-of-name-must-min-than-255') }}";
            if(SingleVideo.hasOwnProperty('massegeOfGeneralError')) SingleVideo.massegeOfGeneralError = "{{ __('masseges.general-error') }}";
            if(SingleVideo.hasOwnProperty('addAndUpdateFormElement')) SingleVideo.addAndUpdateFormElement = document.getElementById("addAndUpdateFormElement");
            if(SingleVideo.hasOwnProperty('formRoute')) SingleVideo.formRoute = "";
            if(SingleVideo.hasOwnProperty('massegeOfErrorInShowVideoAlert')) SingleVideo.massegeOfErrorInShowVideoAlert = '{{ __('masseges.error-in-show-video') }}';
            if(SingleVideo.hasOwnProperty('usersDataTemplate')) SingleVideo.usersDataTemplate = document.getElementById("usersDataTemplate");
        }
        if(addNewVideoButton != null) {
            addNewVideoButton.onclick = function () {
                if(typeof(SingleVideo) == "function") {
                    if(typeof(SingleVideo.addNewSingleVideo) == "function") SingleVideo.addNewSingleVideo();
                }
            };
        }
        function openAddTemplate() {
            if(typeof(SingleVideo) == "function") {
                if(typeof(SingleVideo.openAddTemplate) == "function") SingleVideo.openAddTemplate();
            }
        }
        function Click(id) {
            var temp = document.getElementById(id);
            if(temp != null)temp.click();
        }
        /********************* End add video template *********************/
    </script>
@endsection