class Video {
    constructor(title1,title2,date,type,poster,file,jsonObject = null) {
        if(jsonObject == null) {
            this._title1 = title1;
            this._title2 = title2;
            this._date = date;
            this._type = type;
            this._poster = poster;
            this._file = file;
            this._uploaded = false;
            this._playlistId = -1;
            this._tryUpload = 0;
            this._id = -1;
            this._posterUploaded = false;
            this._updateFormData = null;
            this._updated = false;
        } else this.uploadedVideoConstuctor(jsonObject);
    }
    uploadedVideoConstuctor(jsonObject) {
        if(jsonObject == null || jsonObject == undefined) return;
        if(jsonObject.hasOwnProperty('id')) {
            this._id = jsonObject.id;
            this._uploaded = true;
            this._tryUpload = 1;
        }
        if(jsonObject.hasOwnProperty('pre_title')) this._title1 = jsonObject.pre_title;
        if(jsonObject.hasOwnProperty('title')) this._title2 = jsonObject.title;
        if(jsonObject.hasOwnProperty('availability_time')) this._date = jsonObject.availability_time;
        if(jsonObject.hasOwnProperty('file')) this._file = jsonObject.file;
        if(jsonObject.hasOwnProperty('type_id')) this._type = jsonObject.type_id;
        if(jsonObject.hasOwnProperty('playlist_id')) this._playlistId = jsonObject.playlist_id;
        if(jsonObject.hasOwnProperty('poster_src')) {
            this._poster = jsonObject.poster_src;
            this._posterUploaded = true;
        }
        if(jsonObject.hasOwnProperty('size')) this._size = jsonObject.size;
        else this._size = 0;
        this._updated = false;
        this._updateFormData = new FormData();
        this.createVideoElement();
    }
    createVideoElement() {
        if(langOfVideoElement == undefined || langOfVideoElement == null) return;
        if(selectedVideosOfThisPlaylist == undefined || selectedVideosOfThisPlaylist == null) return;
        if(videos == undefined || videos == null) return;

        var tempVideoDiv = document.createElement('div'),
            titleOfVideoDiv = document.createElement('span'),
            sizeOfVideoDiv = document.createElement('span'),
            posterOfVideoDiv = document.createElement('img'),
            removeVideoDiv = document.createElement('canvas'),
            editButtonOfVideoDiv = document.createElement('a'),
            showButtonOfVideoDiv = document.createElement('a');
        
        var index = videos.length;
        tempVideoDiv.setAttribute('id','video' + index);
        tempVideoDiv.setAttribute('class','video');

        titleOfVideoDiv.textContent = this.title1;
        sizeOfVideoDiv.textContent = langOfVideoElement[0] + ': '+ Math.ceil(this.size/1000000) + ' MB';
        if(this.poster != null) {
            posterOfVideoDiv.setAttribute('src',window.location.origin + this.poster);
        } else {
            posterOfVideoDiv.setAttribute('src',langOfVideoElement[1]);
        }
        removeVideoDiv.width = 25;
        removeVideoDiv.height = 25;
        drawRemoveIconCanvas(removeVideoDiv,'red');
        removeVideoDiv.onclick = new Function("removeVideoFromPlaylist(" + index + ",['" + langOfVideoElement[3] + "','" + langOfVideoElement[4] + "','" + langOfVideoElement[5] + "']);");
        editButtonOfVideoDiv.textContent = langOfVideoElement[2];
        editButtonOfVideoDiv.onclick = new Function("editVideo(" + index + ",'" + langOfVideoElement[1] + "',['" + langOfVideoElement[7] + "','" + langOfVideoElement[0] + "']);");
        showButtonOfVideoDiv.textContent = langOfVideoElement[7];
        showButtonOfVideoDiv.onclick = new Function("openWatchingTemplateAndshowVideo(" + index + ");");
        tempVideoDiv.appendChild(titleOfVideoDiv);
        tempVideoDiv.appendChild(sizeOfVideoDiv);
        tempVideoDiv.appendChild(posterOfVideoDiv);
        tempVideoDiv.appendChild(removeVideoDiv);
        tempVideoDiv.appendChild(editButtonOfVideoDiv);
        tempVideoDiv.appendChild(showButtonOfVideoDiv);

        selectedVideosOfThisPlaylist.appendChild(tempVideoDiv);
    }
    createWatingVideoElement() {
        if(langOfWatingVideoElement == undefined || langOfWatingVideoElement == null) return;
        if(watingVideosDiv == undefined || watingVideosDiv == null) return;
        if(videos == undefined || videos == null) return;
        
        var tempWatingVideoDiv = document.createElement('div'),
            titleOfWatingVideoDiv = document.createElement('span'),
            posterOfWatingVideoDiv = document.createElement('img'),
            sectionOfWatingVideoDiv = document.createElement('section'),
            sectionDiv = document.createElement('div'),
            sectionDivDiv = document.createElement('div'),
            sectionDivSpan = document.createElement('span'),
            sectionFooter = document.createElement('footer');
            
        if(videos != undefined && videos != null) var index = videos.length;
        else var index = 0;
        tempWatingVideoDiv.setAttribute('id','watingVideo' + index);
        tempWatingVideoDiv.setAttribute('class','video');
        sectionOfWatingVideoDiv.setAttribute('class','default-progress');
        titleOfWatingVideoDiv.textContent = this.title1;
        if(this.poster != null) {
            if(this.posterUploaded) {
                posterOfWatingVideoDiv.setAttribute('src',window.location.origin + this.poster);
            } else {
                if(typeof(this.poster) == "object") {
                    if(window.File && window.FileList && window.FileReader) {
                        if(this.poster.hasOwnProperty('type')) {
                            if(this.poster.type.match('image')) {
                                var fileReader = new FileReader();
                                fileReader.addEventListener("load",function(event){
                                    var picFile = event.target;
                                    posterOfWatingVideoDiv.setAttribute('src',picFile.result);
                                });
                                fileReader.readAsDataURL(this.poster);
                            } else {
                                posterOfWatingVideoDiv.setAttribute('src',langOfWatingVideoElement[0]);
                            }
                        } else {
                            posterOfWatingVideoDiv.setAttribute('src',langOfWatingVideoElement[0]);
                        }
                    } else {
                        posterOfWatingVideoDiv.setAttribute('src',langOfWatingVideoElement[0]);
                    }
                } else {
                    posterOfWatingVideoDiv.setAttribute('src',this.poster);
                }
            }
        } else {
            posterOfWatingVideoDiv.setAttribute('src',langOfWatingVideoElement[0]);
        }
        

        sectionDivSpan.textContent = '0%';
        sectionFooter.textContent = (index == 0) ? langOfWatingVideoElement[1] : langOfWatingVideoElement[2];
        sectionDiv.appendChild(sectionDivDiv);
        sectionDiv.appendChild(sectionDivSpan);
        sectionOfWatingVideoDiv.appendChild(sectionDiv);
        sectionOfWatingVideoDiv.appendChild(sectionFooter);
        tempWatingVideoDiv.appendChild(titleOfWatingVideoDiv);
        tempWatingVideoDiv.appendChild(posterOfWatingVideoDiv);
        tempWatingVideoDiv.appendChild(sectionOfWatingVideoDiv);
        watingVideosDiv.appendChild(tempWatingVideoDiv);
    }
    getFormData () {
        if(this.id != -1 && this.updated) {
            return this.getUpdateFormData();
        }
        var tempFormData = new FormData();
        tempFormData.append('video',this.file);
        if(this.poster != null) tempFormData.append('poster_src',this.poster);
        tempFormData.append('pre_title',this.title1);
        tempFormData.append('title',this.title2);
        if(this.date != null) tempFormData.append('availability_time',this.date);
        if(this.type != null) tempFormData.append('type_id',this.type);
        if(this.playlistId != -1) tempFormData.append('playlist_id',this.playlistId);
        return tempFormData;
    }
    getUpdateFormData() {
        if(this.id != -1) {
            this.updateFormData.append('id',this.id);
            return this.updateFormData;
        }
        return null;
    }
    removeVideoRequest () {
        if(this.id != -1) {
            ajaxRequest('get', window.location.origin + '/admin/playlist/delete/video/' + this.id, null);
        }
    }
    get size() {
        return this._size;
    }
    set size(value) {
        this._size = value;
    }
    get id() {
        return this._id;
    }
    set id(value) {
        this._id = value;
    }
    get playlistId() {
        return this._playlistId;
    }
    set playlistId(value) {
        this._playlistId = value;
    }
    get tryUpload() {
        return this._tryUpload;
    }
    set tryUpload(value) {
        this._tryUpload = value;
    }
    get uploaded() {
        return this._uploaded;
    }
    set uploaded(value) {
        this._uploaded = value;
        if(value) {
            if(this.updateFormData == null) this.updateFormData = new FormData();
        }
    }
    get title1() {
        return this._title1;
    }
    set title1(value) {
        this._title1 = value;
        if(this.id != -1 && this.updateFormData != null) this.updateFormData.append('pre_title',value);
    }
    get title2() {
        return this._title2;
    }
    set title2(value) {
        this._title2 = value;
        if(this.id != -1 && this.updateFormData != null) this.updateFormData.append('title',value);
    }
    get date() {
        return this._date;
    }
    set date(value) {
        this._date = value;
        if(this.id != -1 && this.updateFormData != null) this.updateFormData.append('availability_time',value);
    }
    get type() {
        return this._type;
    }
    set type(value) {
        this._type = value;
        if(this.id != -1 && this.updateFormData != null && value != null) this.updateFormData.append('type_id',value);
    }
    get poster() {
        return this._poster;
    }
    set poster(value) {
        this._poster = value;
        if(this.id != -1 && this.updateFormData != null && typeof(value) == "object") {
            this.updateFormData.append('poster_src',value);
            this.posterUploaded = false;
        }
    }
    get file() {
        return this._file;
    }
    set file(value) {
        this._file = value;
        if(this.id != -1 && this.updateFormData != null && typeof(value) == "object") {
            this.updateFormData.append('video',value);
            this.uploaded = false;
            this.tryUpload = 0;
        }
    }
    get updateFormData() {
        if(! this.updated) this.updated = true;
        return this._updateFormData;
    }
    set updateFormData(value) {
        this._updateFormData = value;
    }
    get updated() {
        return this._updated;
    }
    set updated(value) {
        this._updated = value;
    }
}
function openWatingUploadDiv() {
    document.onkeydown = ignoreRefreshKey;
    document.onkeypress = ignoreRefreshKey;
    window.onbeforeunload = function(e) {
        e.preventDefault();
        return "you won't stop uploading video ?";
    };
    if(watingUploadDiv != null) watingUploadDiv.style = '';
}
function allIsUpload () {
    for(var i = 0; i < videos.length; i++) {
        if(videos[i] != null && videos[i] != undefined) {
            if(videos[i].constructor == Video) {
                if(videos[i].uploaded == false) {
                    return false;
                }
            }
        }
    }
    return true;
}

function getNextVideo(currentUpload) {
    if(videos.length - 1 < currentUpload) {
        return null;
    }
    while(videos[currentUpload] == null || typeof(videos[currentUpload]) != "object") {
        currentUpload++;
        if(videos.length - 1 < currentUpload) {
            return null;
        }
    }
    if(videos[currentUpload].uploaded) return getNextVideo(currentUpload + 1);
    if(videos[currentUpload].tryUpload > 0) return getNextVideo(currentUpload + 1);
    return currentUpload;
}

function ignoreRefreshKey(e) {
    switch (event.keyCode) {
        case 116 :
            event.returnValue = false;
            event.keyCode = 0;
            return false;
        case 82 :
            if (event.ctrlKey) { 
                event.returnValue = false;
                event.keyCode = 0;
                return false;
            }
    }
}
function removeVideoFromPlaylist(index,lang) {
    showPopUpMassage(lang[1],null,function(exitThis,popUpMassageDiv) {
        if(Array.isArray(videos)) {
            if(index >= 0 && index < videos.length) {
                if(videos[index].uploaded)videos[index].removeVideoRequest();
                delete videos[index];
                var tempVideoElement = document.getElementById('video' + index);
                if(tempVideoElement != null) {
                    if(tempVideoElement.parentElement != null)tempVideoElement.parentElement.removeChild(tempVideoElement);
                    tempVideoElement = document.getElementById('watingVideo' + index);
                    if(tempVideoElement != null) {
                        if(tempVideoElement.parentElement != null)tempVideoElement.parentElement.removeChild(tempVideoElement);
                    }
                    exitThis(popUpMassageDiv);
                    return;
                }
            }
        }
        exitThis(popUpMassageDiv);
        showPopUpMassage(lang[0]);
    },lang[2]);
}
function editVideo(index,defaultPoster,lang) {
    if(Array.isArray(videos)) {
        if(index >= 0 && index < videos.length) {
            if(titleOneInputOfNewVideo != null)titleOneInputOfNewVideo.value = videos[index].title1;
            if(titleTwoInputOfNewVideo != null)titleTwoInputOfNewVideo.value = videos[index].title2;
            if(imageOfVideoUploading != null) {
                if(videos[index].poster != null) {
                    if(videos[index].poster != null) {
                        if(typeof(videos[index].poster) == "object") {
                            if(window.File && window.FileList && window.FileReader) {
                                if(videos[index].poster.type.match('image')) {
                                    var fileReader = new FileReader();
                                    fileReader.addEventListener("load",function(event){
                                        var picFile = event.target;
                                        imageOfVideoUploading.setAttribute('src',picFile.result);
                                    });
                                    fileReader.readAsDataURL(videos[index].poster);
                                }
                            } else {
                                imageOfVideoUploading.setAttribute('src',defaultPoster);
                            }
                        } else {
                            imageOfVideoUploading.setAttribute('src',window.location.origin + videos[index].poster);
                        }
                    } else {
                        imageOfVideoUploading.setAttribute('src',defaultPoster);
                    }
                } else {
                    imageOfVideoUploading.setAttribute('src',defaultPoster);
                }
            }
            if(dateInputOfNewVideo != null)if(videos[index].date != null)dateInputOfNewVideo.value = videos[index].date;
            if(selectTypeOfThisVideo != null) {
                while(selectTypeOfThisVideo.children.length > 1)selectTypeOfThisVideo.removeChild(selectTypeOfThisVideo.children[selectTypeOfThisVideo.children.length-1]);
                var tempSelectedTypes = document.getElementsByClassName('type');
                if(tempSelectedTypes != null && tempSelectedTypes != undefined) {
                    if(tempSelectedTypes.length > 0) {
                        for(var i = 0; i < tempSelectedTypes.length;i++) {
                            var tempOptionTypeOfThisVideo = document.createElement('option');
                            if(tempSelectedTypes[i].children.length > 0) {
                                tempOptionTypeOfThisVideo.textContent = tempSelectedTypes[i].children[0].textContent;
                                if(tempSelectedTypes[i].children[0].children.length > 0) {
                                    tempOptionTypeOfThisVideo.setAttribute('value',tempSelectedTypes[i].children[0].children[0].value); 
                                }
                            }
                            if(videos[index].type != null) {
                                if(tempOptionTypeOfThisVideo.value == videos[index].type) {
                                    var selected = true;
                                    tempOptionTypeOfThisVideo.setAttribute('selected','selected');
                                }
                            }
                            selectTypeOfThisVideo.appendChild(tempOptionTypeOfThisVideo);
                        }
                    }
                    if(videos[index].type != null) {
                        if(! selected) {
                            var tempOptionTypeOfThisVideo = document.createElement('option');
                            tempOptionTypeOfThisVideo.setAttribute('value', videos[index].type);
                            ajaxRequest('get', window.location.origin + '/admin/playlist/getTypeNameFromId/' + videos[index].type, null, function(jsonObject) {
                                if(jsonObject == null) return;
                                if(jsonObject.hasOwnProperty('name')) {
                                    tempOptionTypeOfThisVideo.textContent = jsonObject.name;
                                }
                            });
                            selectTypeOfThisVideo.appendChild(tempOptionTypeOfThisVideo);
                        }
                    }
                }
            }
            if(videos[index].type != null)selectTypeOfThisVideo.value = videos[index].type;
            if(addNewVideoButton != null) {
                addNewVideoButton.setAttribute('style','display:none !important;');
                addNewVideoButton.style = 'display:none !important;';
            }
            if(editVideoButton != null) {
                editVideoButton.setAttribute('style','');
                editVideoButton.style = '';
                editVideoButton.onclick = new Function("saveEditVideo(" + index + ",'" + defaultPoster + "',['" + lang[0] + "','" + lang[1] + "']);");
            }
            if(addNewVideoTemplate != null)addNewVideoTemplate.setAttribute('style','');
        }
    }
}
function saveEditVideo(index,defaultPoster,lang) {
    if(Array.isArray(videos)) {
        if(index >= 0 && index < videos.length) {
            if(titleOneInputOfNewVideo != null && titleTwoInputOfNewVideo != null && srcInputOfNewVideo != null) {
                titleOneInputOfNewVideo.setAttribute('class','');
                titleTwoInputOfNewVideo.setAttribute('class','');
                srcInputOfNewVideoOpenButton.setAttribute('class','');
                if(titleOneInputOfNewVideo.value.trim() == '') {
                    titleOneInputOfNewVideo.setAttribute('class','input-invalid');
                    return;
                }
                if(titleOneInputOfNewVideo.value.length > 255) {
                    showPopUpMassage(lang[0]);
                    titleOneInputOfNewVideo.setAttribute('class','input-invalid');
                    return;
                }
                if(titleTwoInputOfNewVideo.value.trim() == '') {
                    titleTwoInputOfNewVideo.setAttribute('class','input-invalid');
                    return;
                }
                if(titleTwoInputOfNewVideo.value.length > 255) {
                    showPopUpMassage(lang[0]);
                    titleTwoInputOfNewVideo.setAttribute('class','input-invalid');
                    return;
                }
                var title1,title2,date = null,type = null,poster = null,file;
                title1 = titleOneInputOfNewVideo.value.trim();
                title2 = titleTwoInputOfNewVideo.value.trim();
                if(dateInputOfNewVideo != null) if(dateInputOfNewVideo.value.trim() != '') date = dateInputOfNewVideo.value;
                if(selectTypeOfThisVideo != null) {
                    if(selectTypeOfThisVideo.value != -1) type = selectTypeOfThisVideo.value;
                }
                if(posterInputOfNewVideo != null) {
                    if(posterInputOfNewVideo.files.length  == 1) poster = posterInputOfNewVideo.files[0];
                }
                if(srcInputOfNewVideo.files.length == 1) {
                    file = srcInputOfNewVideo.files[0];
                    videos[index].file = file;
                }

                videos[index].title1 = title1;
                videos[index].title2 = title2;
                videos[index].date = date;
                videos[index].type = type;
                if(poster != null) videos[index].poster = poster;

                var tempVideoElement = document.getElementById('video' + index);
                if(tempVideoElement != null) {
                    if(tempVideoElement.children.length > 3) {
                        if(tempVideoElement.children[0] != null) tempVideoElement.children[0].textContent = videos[index].title1;
                        if(tempVideoElement.children[1] != null) {
                            if(! videos[index].uploaded)var size = videos[index].file.size;
                            else var size = videos[index].size;
                            if(isNaN(size)) size = 0;
                            tempVideoElement.children[1].textContent = lang[1] + ': ' + Math.ceil(size/1000000) + ' MB';
                        }
                        if(! videos[index].posterUploaded  && videos[index].poster != null) {
                            if(typeof(videos[index].poster) == "object") {
                                if(tempVideoElement.children[2] != null) {
                                    if(window.File && window.FileList && window.FileReader) {
                                        if(videos[index].poster.type.match('image')) {
                                            var fileReader = new FileReader();
                                            fileReader.addEventListener("load",function(event){
                                                var picFile = event.target;
                                                tempVideoElement.children[2].setAttribute('src',picFile.result);
                                            });
                                            fileReader.readAsDataURL(videos[index].poster);
                                        }
                                    }
                                }
                            } else tempVideoElement.children[2].setAttribute('src', videos[index].poster);
                        } else if(videos[index].poster != null) {
                            tempVideoElement.children[2].setAttribute('src', videos[index].poster);
                        }
                    }
                }
                var tempVideoWatingElement = document.getElementById('watingVideo' + index);
                if(tempVideoWatingElement != null) {
                    if(tempVideoElement.children.length > 2) {
                        if(tempVideoWatingElement.children[0] != null) tempVideoWatingElement.children[0].textContent = videos[index].title1;
                        if(tempVideoWatingElement.children[1] != null) {
                            if(! videos[index].posterUploaded  && videos[index].poster != null) {
                                if(typeof(videos[index].poster) == "object") {
                                    if(window.File && window.FileList && window.FileReader && videos[index].poster != null) {
                                        if(videos[index].poster.type.match('image')) {
                                            var fileReader = new FileReader();
                                            fileReader.addEventListener("load",function(event){
                                                var picFile = event.target;
                                                tempVideoWatingElement.children[1].setAttribute('src',picFile.result);
                                            });
                                            fileReader.readAsDataURL(videos[index].poster);
                                        }
                                    }
                                } else tempVideoWatingElement.children[1].setAttribute('src', videos[index].poster);
                            } else tempVideoWatingElement.children[1].setAttribute('src', videos[index].poster);
                        }
                    }
                }
                titleOneInputOfNewVideo.value = '';
                titleTwoInputOfNewVideo.value = '';
                selectTypeOfThisVideo.value = '';
                srcInputOfNewVideo.value = '';
                posterInputOfNewVideo.value = '';
                dateInputOfNewVideo.value = '';
                imageOfVideoUploading.src = defaultPoster;
                closeBobUpTemplate(addNewVideoTemplate);
            }
        }
    }
}
function openWatchingTemplateAndshowVideo(index) {
    if(watchingVideoElement == null || watchingDiv == null || ! Array.isArray(videos)) return;
    if(index > videos.length-1 || index < 0) return;
    if(videos[index].file != null) {
        if(typeof(videos[index].file) == "object" && videos[index].file != null) {
            tempBlobUrl = URL.createObjectURL(videos[index].file);
            watchingVideoElement.setAttribute('src', tempBlobUrl);
        } else watchingVideoElement.setAttribute('src', videos[index].file);
        watchingDiv.setAttribute('style', '');
        return;
    }
    showPopUpMassage(errorInShowVideoAlert);
}
function continueUploading() {
    var lang = getPlaylistLang();
    if(typeof(Storage) !== "undefined" && typeof(localStorage) !== "undefined") {
        openWatingUploadDiv();
        for(var i = 0; i < videos.length; i++) {
            videos[i].tryUpload = 0;
            createWatingVideoDiv(i);
        }
        StartUploadingVideos({'id' : localStorage.getItem('playlistId')},lang,localStorage.getItem('seccess'));
    }
}