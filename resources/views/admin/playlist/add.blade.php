@extends('admin\layouts\adminpanel')

@section('title') {{ __('headers.admin-navbar-add-playlist') }} @endsection

@section('links')
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css\admin\playlist\add.css') }}"/>
@endsection

@section('content')
    <header class="small-default-header"><span>{{ __('headers.admin-navbar-add-playlist') }}</span></header>
    <form id="mainForm" class="add-playlist-form" method="post" action="" enctype="multipart/form-data">
        @csrf
        <section>
            <div class="select-profile-image no-select">
				<div class="transition">
					<img id="imageUploading" src="{{ asset('/images/static/playlist-default.png') }}"/>
					<div id="uploadFileHover" class="transition"><span id="changeSpan" class="text">{{ __('masseges.click-to-change-image') }}</span></div>
					<div class="custom-button-one"><div></div><div></div><div></div></div>
				</div>
				<div id="selectImage">
					<input id="PlaylistPosterInput" type="file" name="poster" accept=".png,.jpg,.tif,.gif" title="{{ __('masseges.select-image') }}" style="display:none;"/>
				</div>
			</div>
        </section>
        <section>
            <div><span class="no-select">{{ __('masseges.playlist-title') }}</span><input id="playlistTitleInput" type="text" name="title" autocomplete="off" placeholder="{{ __('masseges.playlist-title') }}"/></div>
            <div><span class="no-select">{{ __('masseges.playlist-price') }}</span><input id="playlistPriceInput" type="text" name="price" autocomplete="off" placeholder="{{ __('masseges.playlist-price') }}"/></div>
            <div><span class="no-select">{{ __('masseges.playlist-description') }}</span><textarea id="playlistDescInput" name="description" autocomplete="off" placeholder="{{ __('masseges.playlist-description') }}"></textarea></div>
            <div><span class="no-select">{{ __('masseges.date-of-available-playlist') }}</span><input id="playlistTimeInput" type="date" name="availability_time" autocomplete="off" /></div>
        </section>
        <div class="clear-float"></div>
        <div class="select-type">
            <span class="no-select">{{ __('masseges.select-playlist-types') }}</span>
            <select id="selectTypesOfThisPlaylist" class="no-select">
                <option value="-1" selected>{{ __('masseges.select-playlist-types') }}</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            <div id="typesOfThisPlaylist" class="types-of-this-playlist no-select">
                <span> {{ __('masseges.playlist-types') }} </span>
                <button type="button" id="openAddNewTypeTemplate" class="transition">{{ __('masseges.add-new-type') }}</button>
            </div>
        </div>
        <div id="addNewTypeTemplate" class="pop-up-template template-of-this-playlist" style="display: none;">
            <header><div><canvas id="exitButtonCanvasOfAddNewTypeTemplate" width="25" height="25"></canvas></div></header>
            <div class="add-new-type">
                <header class="small-default-header"><span>{{ __('masseges.add-new-type') }}</span></header>
                <div><span class="no-select">{{ __('masseges.type-name') }}</span><input id="nameInputOfNewType" type="text" placeholder="{{ __('masseges.type-name') }}"/></div>
                <div><span class="no-select">{{ __('masseges.type-description') }}</span><textarea id="descInputOfNewType" placeholder="{{ __('masseges.type-description') }}"></textarea></div>
                <section><button id="addNewTypeButton" type="button" class="transition">{{ __('masseges.add') }}</button></section>
            </div>
        </div>
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
                    <div><span class="no-select">{{ __('input.video-pre-title') }}</span><input id="titleOneInputOfNewVideo" type="text" placeholder="{{ __('input.video-pre-title') }}"/></div>
                    <div><span class="no-select">{{ __('input.video-title') }}</span><input id="titleTwoInputOfNewVideo" type="text" placeholder="{{ __('input.video-title') }}"/></div>
                    <div><span class="no-select">{{ __('input.date-of-available-video') }}</span><input id="dateInputOfNewVideo" type="date" /></div>
                    <div><span class="no-select">{{ __('input.video-type') }}</span><select id="selectTypeOfThisVideo" class="no-select select-type-of-this-video">
                        <option value="-1" selected>{{ __('input.video-type') }}</option>
                    </select></div>
                    <div><span class="no-select"></span><input id="srcInputOfNewVideoOpenButton" type="button" class="transition no-select" onclick="Click('srcInputOfNewVideo');" value="{{ __('input.select-video') }}" /><input id="srcInputOfNewVideo" type="file" accept=".mp4,.avi,.flv,.wmv,.mov" style="display:none !important;" /></div>
                </div>
                <section><button id="addNewVideoButton" type="button" class="transition">{{ __('masseges.add') }}</button><button id="editVideoButton" type="button" class="transition" style="display:none !important;">{{ __('masseges.update') }}</button></section>
            </div>
        </div>
        <!--    ### Add/Edit-Blob Template ###    blob : book or audio -->
        <div id="addNewBlobTemplate" class="pop-up-template template-of-this-playlist" style="display: none;">
            <header><div><canvas id="exitButtonCanvasOfAddNewBlobTemplate" width="25" height="25"></canvas></div></header>
            <div class="add-new-type add-new-video">
                <header class="small-default-header add-video-header no-select"><span id="addAndEditBlobTemplateTitle"></span></header>
                <section class="video-poster-container">
                    <div class="select-profile-image no-select">
                        <div class="transition">
                            <img id="imageOfBlobUploading" src="{{ asset('/images/static/audio-default.jpg') }}"/>
                            <div id="uploadBlobPosterHover" class="transition"><span id="changeSpanOfBlobPoster" class="text">{{ __('masseges.click-to-change-image') }}</span></div>
                            <div class="custom-button-one"><div></div><div></div><div></div></div>
                        </div>
                        <div id="selectImageOfBlob">
                            <input id="posterInputOfNewBlob" type="file" accept=".png,.jpg,.tif,.gif" title="{{ __('masseges.select-image') }}" style="display:none;"/>
                        </div>
                    </div>
                </section>
                <div class="data-of-video-div">
                    <div><span class="no-select">{{ __('input.video-pre-title') }}</span><input id="titleOneInputOfNewBlob" type="text" placeholder="{{ __('input.video-pre-title') }}"/></div>
                    <div><span class="no-select">{{ __('input.video-title') }}</span><input id="titleTwoInputOfNewBlob" type="text" placeholder="{{ __('input.video-title') }}"/></div>
                    <div><span class="no-select">{{ __('input.date-of-available') }}</span><input id="dateInputOfNewBlob" type="date" /></div>
                    <div><span class="no-select">{{ __('input.type') }}</span><select id="selectTypeOfThisBlob" class="no-select select-type-of-this-video">
                        <option value="-1" selected>{{ __('input.type') }}</option>
                    </select></div>
                    <div><span class="no-select">{{ __('input.desc') }}</span><textarea id="descInputOfNewBlob" placeholder="{{ __('input.desc') }}"></textarea></div>
                    <div><span class="no-select"></span><input id="srcInputOfNewBlobOpenButton" type="button" class="transition no-select" onclick="Click('srcInputOfNewBlob');" value="{{ __('input.select-blob') }}" /><input id="srcInputOfNewBlob" type="file" style="display:none !important;" /></div>
                </div>
                <section><button id="addNewBlobButton" type="button" class="transition">{{ __('masseges.add') }}</button><button id="editBlobButton" type="button" class="transition" style="display:none !important;">{{ __('masseges.update') }}</button></section>
            </div>
        </div>
        <!-- -->
    </form>
    <div id="videosOfThisPlaylist" class="videos-of-this-playlist types-of-this-playlist no-select">
        <span> {{ __('headers.videos') }} </span>
        <section id="selectedVideosOfThisPlaylist" class="videos"></section>
        <button type="button" id="openAddNewVideoTemplate" class="transition">{{ __('masseges.add-video') }}</button>
    </div>

    <!-- Audio Elements -->
    <div id="" class="videos-of-this-playlist types-of-this-playlist no-select">
        <span> {{ __('headers.audios') }} </span>
        <section id="selectedAudiosOfThisPlaylist" class="videos"></section>
        <button type="button" id="openAddNewAudioTemplate" class="transition">{{ __('masseges.add-audio') }}</button>
    </div>
    <!-- -->

    <!-- Book Elements -->
    <div id="" class="videos-of-this-playlist types-of-this-playlist no-select">
        <span> {{ __('headers.books') }} </span>
        <section id="selectedBooksOfThisPlaylist" class="videos"></section>
        <button type="button" id="openAddNewBookTemplate" class="transition">{{ __('masseges.add-book') }}</button>
    </div>
    <!-- -->

    <div class="footer-of-add-playlist-form"><input type="button" value="{{ __('masseges.add') }}" onclick="addPlaylist();" /></div>
    
    <div id="watingUploadDiv" class="pop-up-template template-of-this-playlist big-template" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfWatingUploadDiv" width="25" height="25"></canvas></div></header>
        <div id="watingVideosDiv">
        </div>
    </div>
    <div id="watchingDiv" class="pop-up-template template-of-this-playlist big-template watching-pop-up-template" style="display: none;">
        <header><div><canvas id="exitButtonCanvasOfWatchingDiv" width="25" height="25"></canvas></div></header>
        <video id="watchingVideoElement" controls></video>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript" lang="javascript" src="{{ asset('js/admin/playlist/playlist.js') }}"></script>
    <script type="text/javascript" lang="javascript" src="{{ asset('js/admin/playlist/blob.js') }}"></script>
    <script type="text/javascript" lang="javascript" src="{{ asset('js/admin/playlist/add.js') }}"></script>
    <script type="text/javascript" lang="javascript">
        var videos = new Array(),
            playlistTitleInput = document.getElementById('playlistTitleInput'),
            playlistPriceInput = document.getElementById('playlistPriceInput'),
            playlistDescInput = document.getElementById('playlistDescInput'),
            PlaylistPosterInput = document.getElementById('PlaylistPosterInput'),
            uploadFileHover = document.getElementById('uploadFileHover'),
            changeSpan = document.getElementById('changeSpan'),
            imageUploading = document.getElementById('imageUploading'),
            addNewTypeTemplate = document.getElementById('addNewTypeTemplate'),
            exitButtonCanvasOfAddNewTypeTemplate = document.getElementById('exitButtonCanvasOfAddNewTypeTemplate'),
            selectTypesOfThisPlaylist = document.getElementById('selectTypesOfThisPlaylist'),
            typesOfThisPlaylist = document.getElementById('typesOfThisPlaylist'),
            openAddNewTypeTemplate = document.getElementById('openAddNewTypeTemplate'),
            nameInputOfNewType = document.getElementById('nameInputOfNewType'),
            descInputOfNewType = document.getElementById('descInputOfNewType'),
            addNewTypeButton = document.getElementById('addNewTypeButton'),
            exitButtonCanvasOfAddNewVideoTemplate = document.getElementById('exitButtonCanvasOfAddNewVideoTemplate'),
            addNewVideoTemplate = document.getElementById('addNewVideoTemplate'),
            openAddNewVideoTemplate = document.getElementById('openAddNewVideoTemplate'),
            posterInputOfNewVideo = document.getElementById('posterInputOfNewVideo'),
            uploadVideoPosterHover = document.getElementById('uploadVideoPosterHover'),
            changeSpanOfVideoPoster = document.getElementById('changeSpanOfVideoPoster'),
            imageOfVideoUploading = document.getElementById('imageOfVideoUploading'),
            selectTypeOfThisVideo = document.getElementById('selectTypeOfThisVideo'),
            titleOneInputOfNewVideo = document.getElementById('titleOneInputOfNewVideo'),
            titleTwoInputOfNewVideo = document.getElementById('titleTwoInputOfNewVideo'),
            dateInputOfNewVideo = document.getElementById('dateInputOfNewVideo'),
            addNewVideoButton = document.getElementById('addNewVideoButton'),
            editVideoButton = document.getElementById('editVideoButton'),
            srcInputOfNewVideo = document.getElementById('srcInputOfNewVideo'),
            srcInputOfNewVideoOpenButton = document.getElementById('srcInputOfNewVideoOpenButton'),
            selectedVideosOfThisPlaylist = document.getElementById('selectedVideosOfThisPlaylist'),
            mainForm = document.getElementById('mainForm'),
            exitButtonCanvasOfWatingUploadDiv = document.getElementById('exitButtonCanvasOfWatingUploadDiv'),
            watingUploadDiv = document.getElementById('watingUploadDiv'),
            watingVideosDiv = document.getElementById('watingVideosDiv'),
            watchingDiv = document.getElementById('watchingDiv'),
            exitButtonCanvasOfWatchingDiv = document.getElementById('exitButtonCanvasOfWatchingDiv'),
            watchingVideoElement = document.getElementById('watchingVideoElement'),
            tempBlobUrl = '',
            errorInShowVideoAlert = '{{ __('masseges.error-in-show-video') }}';

        if(exitButtonCanvasOfAddNewTypeTemplate != null) {
            exitButtonCanvasOfAddNewTypeTemplate.width = 25;
            exitButtonCanvasOfAddNewTypeTemplate.height = 25;
            drawRemoveIconCanvas(exitButtonCanvasOfAddNewTypeTemplate,'#ffffff');
            exitButtonCanvasOfAddNewTypeTemplate.onclick = function () {
                closeBobUpTemplate(addNewTypeTemplate);
            };
        }
        if(exitButtonCanvasOfAddNewVideoTemplate != null) {
            exitButtonCanvasOfAddNewVideoTemplate.width = 25;
            exitButtonCanvasOfAddNewVideoTemplate.height = 25;
            drawRemoveIconCanvas(exitButtonCanvasOfAddNewVideoTemplate,'#ffffff');
            exitButtonCanvasOfAddNewVideoTemplate.onclick = function () {
                closeBobUpTemplate(addNewVideoTemplate);
            };
        }
        if(playlistTitleInput != null) {
            playlistTitleInput.onfocus = function () {
                makeFocusEfectOfInput(playlistTitleInput);
            };
            playlistTitleInput.onblur = function () {
                removeFocusEfectOfInput(playlistTitleInput);
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
        if(uploadFileHover != null && PlaylistPosterInput != null) {
            uploadFileHover.onclick = function () {
                PlaylistPosterInput.click();
            };
            PlaylistPosterInput.onchange = function () {
                if(PlaylistPosterInput.files.length == 1) {
                    if(PlaylistPosterInput.files[0].size >= 2000000) {
                        showPopUpMassage('{{ __('masseges.big-size-of-image') }}');
                        return;
                    }
                    if(window.File && window.FileList && window.FileReader) {
                        if(PlaylistPosterInput.files[0].type.match('image')){
                            var fileReader = new FileReader();
                            fileReader.addEventListener("load",function(event){
                                var picFile = event.target;
                                if(imageUploading != null)imageUploading.setAttribute('src',picFile.result);
                            });
                            fileReader.readAsDataURL(PlaylistPosterInput.files[0]);
                        }
                    } else {
                        uploadFileHover.setAttribute('style','top:0;right:0;bottom:0;left:0;width:200px;height:200px;');
                        if(changeSpan !== null)changeSpan.innerHtml = '{{ __('masseges.browser-not-support-read-image') }}';
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
                        showPopUpMassage('{{ __('masseges.big-size-of-image') }}');
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
                        if(changeSpanOfVideoPoster !== null)changeSpanOfVideoPoster.innerHtml = '{{ __('masseges.browser-not-support-read-image') }}';
                    }
                }
            };
        }
        
        if(playlistPriceInput != null) {
            playlistPriceInput.onfocus = function () {
                makeFocusEfectOfInput(playlistPriceInput);
            };
            playlistPriceInput.onblur = function () {
                removeFocusEfectOfInput(playlistPriceInput);
            };
        }
        if(playlistDescInput != null) {
            playlistDescInput.onfocus = function () {
                makeFocusEfectOfInput(playlistDescInput);
            };
            playlistDescInput.onblur = function () {
                removeFocusEfectOfInput(playlistDescInput);
            };
        }
        if(selectTypesOfThisPlaylist != null && typesOfThisPlaylist != null) {
            selectTypesOfThisPlaylist.onchange = changeHandler;
        }
        if(openAddNewTypeTemplate != null) {
            openAddNewTypeTemplate.onclick = function () {
                addNewTypeTemplate.setAttribute('style','');
            };
        }
        if(openAddNewVideoTemplate != null) {
            openAddNewVideoTemplate.onclick = function () {
                if(titleOneInputOfNewVideo != null) titleOneInputOfNewVideo.value = '';
                if(titleTwoInputOfNewVideo != null) titleTwoInputOfNewVideo.value = '';
                if(srcInputOfNewVideo != null) srcInputOfNewVideo.value = '';
                if(posterInputOfNewVideo != null) posterInputOfNewVideo.value = '';
                if(dateInputOfNewVideo != null)  dateInputOfNewVideo.value = '';
                if(imageOfVideoUploading != null) imageOfVideoUploading.src = '{{ asset('/images/static/video-default.jpg') }}';
                if(selectTypeOfThisVideo != null) {
                    while(selectTypeOfThisVideo.children.length > 1)selectTypeOfThisVideo.removeChild(selectTypeOfThisVideo.children[selectTypeOfThisVideo.children.length-1]);
                    var tempSelectedTypes = document.getElementsByClassName('type');
                    if(tempSelectedTypes != null && tempSelectedTypes != undefined){
                        if(tempSelectedTypes.length > 0) {
                            for(var i = 0; i < tempSelectedTypes.length;i++) {
                                var tempOptionTypeOfThisVideo = document.createElement('option');
                                if(tempSelectedTypes[i].children.length > 0) {
                                    tempOptionTypeOfThisVideo.textContent = tempSelectedTypes[i].children[0].textContent;
                                    if(tempSelectedTypes[i].children[0].children.length > 0) {
                                        tempOptionTypeOfThisVideo.setAttribute('value',tempSelectedTypes[i].children[0].children[0].value); 
                                    }
                                }
                                selectTypeOfThisVideo.appendChild(tempOptionTypeOfThisVideo);
                            }
                        }
                    }
                }
                if(addNewVideoButton != null){
                    addNewVideoButton.setAttribute('style','');
                    addNewVideoButton.style = '';
                }
                if(editVideoButton != null){
                    editVideoButton.setAttribute('style','display:none !important;');
                    editVideoButton.style = 'display:none !important;';
                }
                addNewVideoTemplate.setAttribute('style','');
            };
        }
        if(nameInputOfNewType != null) {
            nameInputOfNewType.onfocus = function () {
                makeFocusEfectOfInput(nameInputOfNewType);
            };
            nameInputOfNewType.onblur = function () {
                removeFocusEfectOfInput(nameInputOfNewType);
            };
        }
        if(descInputOfNewType != null) {
            descInputOfNewType.onfocus = function () {
                makeFocusEfectOfInput(descInputOfNewType);
            };
            descInputOfNewType.onblur = function () {
                removeFocusEfectOfInput(descInputOfNewType);
            };
        }
        if(addNewTypeButton != null && nameInputOfNewType != null && descInputOfNewType != null) {
            addNewTypeButton.onclick = function () {
                if(nameInputOfNewType.value.trim() == '') {
                    nameInputOfNewType.setAttribute('class','input-invalid');
                    return;
                } if(nameInputOfNewType.value.length > 255) {
                    showPopUpMassage('{{ __('input.character-of-name-must-min-than-255') }}');
                    nameInputOfNewType.setAttribute('class','input-invalid');
                    return;
                }
                var tempForm = document.createElement('form'),
                    tempNameInputOfNewType = document.createElement('input'),
                    tempDescInputOfNewType = document.createElement('input'),
                    tempTokenOfThisForm = document.createElement('input');
                tempNameInputOfNewType.setAttribute('name','name');
                tempNameInputOfNewType.setAttribute('value',nameInputOfNewType.value.trim());
                tempDescInputOfNewType.setAttribute('name','description');
                tempDescInputOfNewType.setAttribute('value',descInputOfNewType.value.trim());
                tempTokenOfThisForm.setAttribute('name','_token');
                tempTokenOfThisForm.setAttribute('value','{{ csrf_token() }}');
                tempForm.appendChild(tempTokenOfThisForm);
                tempForm.appendChild(tempNameInputOfNewType);
                if(tempDescInputOfNewType.value != '') tempForm.appendChild(tempDescInputOfNewType);
                ajaxRequest('post','{{ route('admin.type.store') }}',tempForm,function(jsonResponse) {
                    if(jsonResponse != null) {
                        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                            if(jsonResponse.status && jsonResponse.data.hasOwnProperty('type')) {
                                var newType = jsonResponse.data.type;
                                nameInputOfNewType.setAttribute('value','');
                                nameInputOfNewType.value = '';
                                descInputOfNewType.setAttribute('value','');
                                descInputOfNewType.value = '';
                                if(newType.hasOwnProperty('id') && newType.hasOwnProperty('name')) {
                                    if(selectTypesOfThisPlaylist != null && typesOfThisPlaylist != null) {
                                        var tempOption = document.createElement('option');
                                        tempOption.setAttribute('value',newType.id);
                                        tempOption.textContent = newType.name;
                                        tempOption.setAttribute('selected','selected');
                                        selectTypesOfThisPlaylist.appendChild(tempOption);
                                        changeHandler();
                                    }
                                }
                                if(addNewTypeTemplate != null) {
                                    closeBobUpTemplate(addNewTypeTemplate);
                                }
                                return;
                            }
                        }
                    }
                    showPopUpMassage('{{ __('masseges.general-error') }}');
                });
            };
        }
        if(addNewVideoButton != null) {
            addNewVideoButton.onclick = function() {
                if(titleOneInputOfNewVideo != null && titleTwoInputOfNewVideo != null && srcInputOfNewVideo != null) {
                    titleOneInputOfNewVideo.setAttribute('class','');
                    titleTwoInputOfNewVideo.setAttribute('class','');
                    srcInputOfNewVideoOpenButton.setAttribute('class','');
                    if(titleOneInputOfNewVideo.value.trim() == '') {
                        titleOneInputOfNewVideo.setAttribute('class','input-invalid');
                        return;
                    } 
                    if(titleOneInputOfNewVideo.value.length > 255) {
                        showPopUpMassage('{{ __('input.character-of-title-must-min-than-255') }}');
                        titleOneInputOfNewVideo.setAttribute('class','input-invalid');
                        return;
                    }
                    if(titleTwoInputOfNewVideo.value.trim() == '') {
                        titleTwoInputOfNewVideo.setAttribute('class','input-invalid');
                        return;
                    }
                    if(titleTwoInputOfNewVideo.value.length > 255) {
                        showPopUpMassage('{{ __('input.character-of-title-must-min-than-255') }}');
                        titleTwoInputOfNewVideo.setAttribute('class','input-invalid');
                        return;
                    }
                    if(srcInputOfNewVideo.files.length != 1) {
                        if(srcInputOfNewVideoOpenButton != null)srcInputOfNewVideoOpenButton.setAttribute('class','input-invalid');
                        return;
                    }
                    var title1,title2,date = null,type = null,poster = null,file;
                    title1 = titleOneInputOfNewVideo.value.trim();
                    title2 = titleTwoInputOfNewVideo.value.trim();
                    if(dateInputOfNewVideo != null)if(dateInputOfNewVideo.value.trim() != '') date = dateInputOfNewVideo.value;
                    if(selectTypeOfThisVideo != null) {
                        if(selectTypeOfThisVideo.value != -1)type = selectTypeOfThisVideo.value;
                    }
                    if(posterInputOfNewVideo != null) {
                        if(posterInputOfNewVideo.files.length  == 1)poster = posterInputOfNewVideo.files[0];
                    }
                    file = srcInputOfNewVideo.files[0];
                    var tempVideo = new Video(title1,title2,date,type,poster,file),
                        index = videos.length;
                    videos.push(tempVideo);

                    titleOneInputOfNewVideo.value = '';
                    titleTwoInputOfNewVideo.value = '';
                    selectTypeOfThisVideo.value = '';
                    srcInputOfNewVideo.value = '';
                    posterInputOfNewVideo.value = '';
                    dateInputOfNewVideo.value = '';
                    imageOfVideoUploading.src = '{{ asset('/images/static/video-default.jpg') }}';

                    if(selectedVideosOfThisPlaylist != null) {
                        var tempVideoDiv = document.createElement('div'),
                            titleOfVideoDiv = document.createElement('span'),
                            sizeOfVideoDiv = document.createElement('span'),
                            posterOfVideoDiv = document.createElement('img'),
                            removeVideoDiv = document.createElement('canvas'),
                            editButtonOfVideoDiv = document.createElement('a'),
                            showButtonOfVideoDiv = document.createElement('a');

                        tempVideoDiv.setAttribute('id','video' + index);
                        tempVideoDiv.setAttribute('class','video');

                        titleOfVideoDiv.textContent = videos[index].title1;
                        sizeOfVideoDiv.textContent = '{{ __('masseges.size') }}' + ': '+ Math.ceil(videos[index].file.size/1000000) + ' MB';
                        if(window.File && window.FileList && window.FileReader && videos[index].poster != null) {
                            if(videos[index].poster.type.match('image')) {
                                var fileReader = new FileReader();
                                fileReader.addEventListener("load",function(event){
                                    var picFile = event.target;
                                    posterOfVideoDiv.setAttribute('src',picFile.result);
                                });
                                fileReader.readAsDataURL(videos[index].poster);
                            }
                        } else {
                            posterOfVideoDiv.setAttribute('src','{{ asset('/images/static/video-default.jpg') }}');
                        }
                        removeVideoDiv.width = 25;
                        removeVideoDiv.height = 25;
                        drawRemoveIconCanvas(removeVideoDiv,'red');
                        removeVideoDiv.onclick = new Function("removeVideoFromPlaylist(" + index + ",['{{ __('masseges.general-error') }}','{{ __('masseges.ask-remove-video') }}','{{ __('masseges.delete') }}']);");
                        editButtonOfVideoDiv.textContent = '{{ __('masseges.update') }}';
                        editButtonOfVideoDiv.onclick = new Function("editVideo(" + index + ",'{{ asset('/images/static/video-default.jpg') }}',['{{ __('input.character-of-title-must-min-than-255') }}','{{ __('masseges.size') }}']);");
                        showButtonOfVideoDiv.textContent = '{{ __('masseges.show') }}';
                        showButtonOfVideoDiv.onclick = new Function("openWatchingTemplateAndshowVideo(" + index + ");");
                        tempVideoDiv.appendChild(titleOfVideoDiv);
                        tempVideoDiv.appendChild(sizeOfVideoDiv);
                        tempVideoDiv.appendChild(posterOfVideoDiv);
                        tempVideoDiv.appendChild(removeVideoDiv);
                        tempVideoDiv.appendChild(editButtonOfVideoDiv);
                        tempVideoDiv.appendChild(showButtonOfVideoDiv);

                        selectedVideosOfThisPlaylist.appendChild(tempVideoDiv);
                    }

                    if(watingVideosDiv != null) {
                        var tempWatingVideoDiv = document.createElement('div'),
                            titleOfWatingVideoDiv = document.createElement('span'),
                            posterOfWatingVideoDiv = document.createElement('img'),
                            sectionOfWatingVideoDiv = document.createElement('section'),
                            sectionDiv = document.createElement('div'),
                            sectionDivDiv = document.createElement('div'),
                            sectionDivSpan = document.createElement('span'),
                            sectionFooter = document.createElement('footer');

                        tempWatingVideoDiv.setAttribute('id','watingVideo' + index);
                        tempWatingVideoDiv.setAttribute('class','video');
                        sectionOfWatingVideoDiv.setAttribute('class','default-progress');
                        titleOfWatingVideoDiv.textContent = videos[index].title1;
                        if(window.File && window.FileList && window.FileReader && videos[index].poster != null) {
                            if(videos[index].poster.type.match('image')) {
                                var fileReader = new FileReader();
                                fileReader.addEventListener("load",function(event){
                                    var picFile = event.target;
                                    posterOfWatingVideoDiv.setAttribute('src',picFile.result);
                                });
                                fileReader.readAsDataURL(videos[index].poster);
                            }
                        } else {
                            posterOfWatingVideoDiv.setAttribute('src','{{ asset('/images/static/video-default.jpg') }}');
                        }

                        sectionDivSpan.textContent = '0%';
                        sectionFooter.textContent = (index == 0) ? '{{ __('masseges.starting-upload-video') }}' : '{{ __('masseges.wait-end-upload-first-video') }}';
                        sectionDiv.appendChild(sectionDivDiv);
                        sectionDiv.appendChild(sectionDivSpan);
                        sectionOfWatingVideoDiv.appendChild(sectionDiv);
                        sectionOfWatingVideoDiv.appendChild(sectionFooter);
                        tempWatingVideoDiv.appendChild(titleOfWatingVideoDiv);
                        tempWatingVideoDiv.appendChild(posterOfWatingVideoDiv);
                        tempWatingVideoDiv.appendChild(sectionOfWatingVideoDiv);
                        watingVideosDiv.appendChild(tempWatingVideoDiv);
                    }
                    closeBobUpTemplate(addNewVideoTemplate);
                }

            };
        }
        if(exitButtonCanvasOfWatingUploadDiv != null) {
            exitButtonCanvasOfWatingUploadDiv.width = 25;
            exitButtonCanvasOfWatingUploadDiv.height = 25;
            drawRemoveIconCanvas(exitButtonCanvasOfWatingUploadDiv,'#ffffff');
            exitButtonCanvasOfWatingUploadDiv.onclick = function () {
                showPopUpMassage('{{ __('masseges.please-wait-for-uploading-video') }}');
            };
        }
        if(exitButtonCanvasOfWatchingDiv != null && watchingDiv != null) {
            exitButtonCanvasOfWatchingDiv.width = 25;
            exitButtonCanvasOfWatchingDiv.height = 25;
            drawRemoveIconCanvas(exitButtonCanvasOfWatchingDiv,'#ffffff');
            exitButtonCanvasOfWatchingDiv.onclick = function () {
                URL.revokeObjectURL(tempBlobUrl)
                closeBobUpTemplate(watchingDiv);
            };
        }

        /*#### set Blobs settings ####*/
        var books = new Array(),
            audios = new Array(),
            addAndEditBlobTemplateTitle = document.getElementById('addAndEditBlobTemplateTitle'),
            exitButtonCanvasOfAddNewBlobTemplate = document.getElementById('exitButtonCanvasOfAddNewBlobTemplate'),
            openAddNewAudioTemplate = document.getElementById('openAddNewAudioTemplate'),
            openAddNewBookTemplate = document.getElementById('openAddNewBookTemplate');
        
        if(typeof(Blob) == "function") {
            if(Blob.hasOwnProperty('maxLengthAlert')) Blob.maxLengthAlert = '{{ __('input.character-of-name-must-min-than-255') }}';
            if(Blob.hasOwnProperty('deleteBlobUrl')) Blob.deleteBlobUrl = window.location.origin + '/admin/playlist/delete';
            if(Blob.hasOwnProperty('addAndEditBlobTemplate')) Blob.addAndEditBlobTemplate = document.getElementById('addNewBlobTemplate');
            if(Blob.hasOwnProperty('parentOfWatingElements')) Blob.parentOfWatingElements = watingVideosDiv;
            if(Blob.hasOwnProperty('imageOfBlobUploading')) Blob.imageOfBlobUploading = document.getElementById('imageOfBlobUploading');
            if(Blob.hasOwnProperty('titleOneInputOfNewBlob')) Blob.titleOneInputOfNewBlob = document.getElementById('titleOneInputOfNewBlob');
            if(Blob.hasOwnProperty('titleTwoInputOfNewBlob')) Blob.titleTwoInputOfNewBlob = document.getElementById('titleTwoInputOfNewBlob');
            if(Blob.hasOwnProperty('dateInputOfNewBlob')) Blob.dateInputOfNewBlob = document.getElementById('dateInputOfNewBlob');
            if(Blob.hasOwnProperty('selectTypeOfThisBlob')) Blob.selectTypeOfThisBlob = document.getElementById('selectTypeOfThisBlob');
            if(Blob.hasOwnProperty('descInputOfNewBlob')) Blob.descInputOfNewBlob = document.getElementById('descInputOfNewBlob');
            if(Blob.hasOwnProperty('posterInputOfNewBlob')) Blob.posterInputOfNewBlob = document.getElementById('posterInputOfNewBlob');
            if(Blob.hasOwnProperty('srcInputOfNewBlob')) Blob.srcInputOfNewBlob = document.getElementById('srcInputOfNewBlob');
            if(Blob.hasOwnProperty('srcInputOfNewBlobOpenButton')) Blob.srcInputOfNewBlobOpenButton = document.getElementById('srcInputOfNewBlobOpenButton');
            if(Blob.hasOwnProperty('addNewBlobButton')) Blob.addNewBlobButton = document.getElementById('addNewBlobButton');
            if(Blob.hasOwnProperty('editBlobButton')) Blob.editBlobButton = document.getElementById('editBlobButton');
            if(Blob.hasOwnProperty('removeAlert')) Blob.removeAlert = '{{ __('masseges.ask-remove-blob') }}';
            if(Blob.hasOwnProperty('uploadBlobUrl')) Blob.uploadBlobUrl = '{{ route('admin.blob.store') }}';
            if(Blob.hasOwnProperty('langOfBlobElement')) Blob.langOfBlobElement = [
                '{{ __('masseges.size') }}',
                '{{ __('masseges.update') }}',
                '{{ __('masseges.general-error') }}',
                '{{ __('masseges.delete') }}',
            ];
            if(Blob.hasOwnProperty('langOfWatingBlobElement')) Blob.langOfWatingBlobElement = [
                '{{ asset('/images/static/video-default.jpg') }}',
                '{{ __('masseges.starting-upload-video') }}',
                '{{ __('masseges.wait-end-upload-first-video') }}',
            ];

            if(typeof(makeFocusEfectOfInput) == "function" && typeof(removeFocusEfectOfInput) == "function") {
                if(Blob.titleOneInputOfNewBlob != null) {
                    Blob.titleOneInputOfNewBlob.onfocus = function () {
                        makeFocusEfectOfInput(Blob.titleOneInputOfNewBlob);
                    };
                    Blob.titleOneInputOfNewBlob.onblur = function () {
                        removeFocusEfectOfInput(Blob.titleOneInputOfNewBlob);
                    };
                }
                if(Blob.titleTwoInputOfNewBlob != null) {
                    Blob.titleTwoInputOfNewBlob.onfocus = function () {
                        makeFocusEfectOfInput(Blob.titleTwoInputOfNewBlob);
                    };
                    Blob.titleTwoInputOfNewBlob.onblur = function () {
                        removeFocusEfectOfInput(Blob.titleTwoInputOfNewBlob);
                    };
                }
                if(Blob.descInputOfNewBlob != null) {
                    Blob.descInputOfNewBlob.onfocus = function () {
                        makeFocusEfectOfInput(Blob.descInputOfNewBlob);
                    };
                    Blob.descInputOfNewBlob.onblur = function () {
                        removeFocusEfectOfInput(Blob.descInputOfNewBlob);
                    };
                }
            }
        }
        if(typeof(Audio) == "function") {
            if(Audio.hasOwnProperty('parentOfElements')) Audio.parentOfElements = document.getElementById('selectedAudiosOfThisPlaylist');
            if(Audio.hasOwnProperty('titleOfAddTemplate')) Audio.titleOfAddTemplate = '{{ __('masseges.add-audio') }}';
            if(Audio.hasOwnProperty('titleOfEditTemplate')) Audio.titleOfEditTemplate = '{{ __('masseges.edit-audio') }}';
            if(Audio.hasOwnProperty('deleteAlert')) Audio.deleteAlert = null;
            if(Audio.hasOwnProperty('defaultPoster')) Audio.defaultPoster = '{{ asset('/images/static/audio-default.jpg') }}';
            if(openAddNewAudioTemplate != null) {
                openAddNewAudioTemplate.onclick = function () {
                    Audio.openAddTemplate();
                };
            }
        }
        if(typeof(Book) == "function") {
            if(Book.hasOwnProperty('parentOfElements')) Book.parentOfElements = document.getElementById('selectedBooksOfThisPlaylist');
            if(Book.hasOwnProperty('titleOfAddTemplate')) Book.titleOfAddTemplate = '{{ __('masseges.add-book') }}';
            if(Book.hasOwnProperty('titleOfEditTemplate')) Book.titleOfEditTemplate = '{{ __('masseges.edit-book') }}';
            if(Book.hasOwnProperty('deleteAlert')) Book.deleteAlert = null;
            if(Book.hasOwnProperty('defaultPoster')) Book.defaultPoster = '{{ asset('/images/static/book-default.jpg') }}';
            if(openAddNewBookTemplate != null) {
                openAddNewBookTemplate.onclick = function () {
                    Book.openAddTemplate();
                };
            }
        }
        if(exitButtonCanvasOfAddNewBlobTemplate != null) {
            exitButtonCanvasOfAddNewBlobTemplate.width = 25;
            exitButtonCanvasOfAddNewBlobTemplate.height = 25;
            if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(exitButtonCanvasOfAddNewBlobTemplate,'#ffffff');
            exitButtonCanvasOfAddNewBlobTemplate.onclick = function () {
                if(Blob.hasOwnProperty('addAndEditBlobTemplate')) if(Blob.addAndEditBlobTemplate != null) {
                    if(typeof(closeBobUpTemplate) == "function") closeBobUpTemplate(Blob.addAndEditBlobTemplate);
                }
            };
        }
        var uploadBlobPosterHover = document.getElementById("uploadBlobPosterHover"),
            changeSpanOfBlobPoster = document.getElementById("changeSpanOfBlobPoster");
        if(uploadBlobPosterHover != null && Blob.posterInputOfNewBlob != null) {
            uploadBlobPosterHover.onclick = function () {
                Blob.posterInputOfNewBlob.click();
            };
            Blob.posterInputOfNewBlob.onchange = function () {
                if(Blob.posterInputOfNewBlob.files.length == 1) {
                    if(Blob.posterInputOfNewBlob.files[0].size >= 2000000) {
                        showPopUpMassage('{{ __('masseges.big-size-of-image') }}');
                        return;
                    }
                    if(window.File && window.FileList && window.FileReader) {
                        if(Blob.posterInputOfNewBlob.files[0].type.match('image')){
                            var fileReader = new FileReader();
                            fileReader.addEventListener("load",function(event){
                                var picFile = event.target;
                                if(Blob.imageOfBlobUploading != null) Blob.imageOfBlobUploading.setAttribute('src',picFile.result);
                            });
                            fileReader.readAsDataURL(Blob.posterInputOfNewBlob.files[0]);
                        }
                    } else {
                        uploadBlobPosterHover.setAttribute('style','top:0;right:0;bottom:0;left:0;width:200px;height:200px;');
                        if(changeSpanOfBlobPoster !== null) changeSpanOfBlobPoster.innerHtml = '{{ __('masseges.browser-not-support-read-image') }}';
                    }
                }
            };
        }
        /*############################*/

        function removeThisType (id) {
            var type = document.getElementById('typeOfPlaylist' + id);
            if(type == null) {
                showPopUpMassage('{{ __('masseges.general-error') }}');
                return;
            }
            typesOfThisPlaylist.removeChild(type);
        }
        function changeHandler() {
            if(selectTypesOfThisPlaylist.value != "-1") {
                var checkIfAdded = document.getElementById('typeOfPlaylist' + selectTypesOfThisPlaylist.value);
                if(checkIfAdded == null) {
                    var type = document.createElement('div'),
                        typeSpan = document.createElement('span'),
                        typeDelete = document.createElement('canvas'),
                        typeInput = document.createElement('input');
                        
                    typeInput.value = selectTypesOfThisPlaylist.value;
                    typeInput.setAttribute('type','hidden');
                    typeInput.setAttribute('name','types[]');
                    typeDelete.width = 15;
                    typeDelete.height = 15;
                    drawRemoveIconCanvas(typeDelete,'#ffffff');
                    typeDelete.onclick = new Function("removeThisType(" + selectTypesOfThisPlaylist.value + ");");
                    typeSpan.textContent = selectTypesOfThisPlaylist.options[selectTypesOfThisPlaylist.options.selectedIndex].textContent;
                    typeSpan.appendChild(typeInput);
                    type.appendChild(typeSpan);
                    type.appendChild(typeDelete);
                    type.setAttribute('class','type');
                    type.setAttribute('id','typeOfPlaylist' + selectTypesOfThisPlaylist.value);
                    if(openAddNewTypeTemplate != null)typesOfThisPlaylist.insertBefore(type,openAddNewTypeTemplate);
                    else typesOfThisPlaylist.appendChild(type);
                } else {
                    showPopUpMassage('{{ __('masseges.this-type-is-selected') }}');
                }
            }
        }
        function Click(id) {
            var temp = document.getElementById(id);
            if(temp != null)temp.click();
        }
        function createWatingVideoDiv(index) {
            if(watingVideosDiv != null && videos != null) {
                if(! Array.isArray(videos)) return;
                if(index < 0 || index > videos.length - 1) return;
                if(videos[index] == null || videos[index] == undefined) return;
                if(videos[index].constructor != Video) return;
                var tempWatingVideoDiv = document.createElement('div'),
                    titleOfWatingVideoDiv = document.createElement('span'),
                    posterOfWatingVideoDiv = document.createElement('img'),
                    sectionOfWatingVideoDiv = document.createElement('section'),
                    sectionDiv = document.createElement('div'),
                    sectionDivDiv = document.createElement('div'),
                    sectionDivSpan = document.createElement('span'),
                    sectionFooter = document.createElement('footer');

                tempWatingVideoDiv.setAttribute('id','watingVideo' + index);
                tempWatingVideoDiv.setAttribute('class','video');
                sectionOfWatingVideoDiv.setAttribute('class','default-progress');
                titleOfWatingVideoDiv.textContent = videos[index].title1;
                if(window.File && window.FileList && window.FileReader && videos[index].poster != null) {
                    if(videos[index].poster.type.match('image')) {
                        var fileReader = new FileReader();
                        fileReader.addEventListener("load",function(event){
                            var picFile = event.target;
                            posterOfWatingVideoDiv.setAttribute('src',picFile.result);
                        });
                        fileReader.readAsDataURL(videos[index].poster);
                    }
                } else {
                    posterOfWatingVideoDiv.setAttribute('src','{{ asset('/images/static/video-default.jpg') }}');
                }

                if(videos[index].uploaded == false) sectionDivSpan.textContent = '0%';
                else {
                    sectionDivSpan.textContent = '100%';
                }
                sectionFooter.textContent = (index == 0) ? '{{ __('masseges.starting-upload-video') }}' : '{{ __('masseges.wait-end-upload-first-video') }}';
                sectionDiv.appendChild(sectionDivDiv);
                sectionDiv.appendChild(sectionDivSpan);
                sectionOfWatingVideoDiv.appendChild(sectionDiv);
                sectionOfWatingVideoDiv.appendChild(sectionFooter);
                tempWatingVideoDiv.appendChild(titleOfWatingVideoDiv);
                tempWatingVideoDiv.appendChild(posterOfWatingVideoDiv);
                tempWatingVideoDiv.appendChild(sectionOfWatingVideoDiv);
                watingVideosDiv.appendChild(tempWatingVideoDiv);
            }
        }
        function getPlaylistLang() {
            return [
                '{{ __('masseges.general-error') }}',
                '{{ __('input.character-of-name-must-min-than-255') }}',
                '{{ __('input.price-not-number') }}',
                '{{ route('admin.playlist.store') }}',
                '{{ __('masseges.add-playlist-ok-wait-upload-videos') }}',
                '{{ __('masseges.uploaded-ok') }}',
                '{{ __('masseges.starting-upload-video') }}',
                '{{ __('masseges.error-in-upload-this-video') }}',
                '{{ route('admin.video.store') }}',
                '{{ csrf_token() }}',
                '{{ __('masseges.ask-to-undo-upload') }}',
            ];
        }
    </script>
@endsection

