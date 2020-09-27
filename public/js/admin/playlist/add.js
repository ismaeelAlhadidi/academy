function addPlaylist() {
    var lang = getPlaylistLang();
    if(playlistTitleInput == null) {
        window.scrollTo(0,0);
        showPopUpMassage(lang[0]);
        return;
    }
    playlistTitleInput.setAttribute('class','');
    if(playlistTitleInput.value.trim() == '') {
        window.scrollTo(0,0);
        playlistTitleInput.setAttribute('class','input-invalid');
        return;
    }
    if(playlistTitleInput.value.length > 255) {
        window.scrollTo(0,0);
        showPopUpMassage(lang[1]);
        playlistTitleInput.setAttribute('class','input-invalid');
        return;
    }

    if(playlistPriceInput == null) {
        window.scrollTo(0,0);
        showPopUpMassage(lang[0]);
        return;
    }
    playlistPriceInput.setAttribute('class','');
    if(playlistPriceInput.value.trim() == '') {
        window.scrollTo(0,0);
        playlistPriceInput.setAttribute('class','input-invalid');
        return;
    } 
    if(isNaN(playlistPriceInput.value)) {
        window.scrollTo(0,0);
        showPopUpMassage(lang[2]);
        playlistPriceInput.setAttribute('class','input-invalid');
        return;
    }
    if(mainForm == null) {
        window.scrollTo(0,0);
        showPopUpMassage(lang[0]);
        return;
    }

    ajaxRequest('post',lang[3],mainForm,function(jsonResponse) {
        if(jsonResponse == null) {
            showPopUpMassage(lang[0]);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && watingUploadDiv != null) {
            if(jsonResponse.status && jsonResponse.hasOwnProperty('data')) {
                if(jsonResponse.data != null) {
                    if(jsonResponse.data.hasOwnProperty('playlist') && jsonResponse.hasOwnProperty('msg')) {
                        if(videos != null && audios != null && books != null) {
                            if(Array.isArray(videos) && Array.isArray(audios) && Array.isArray(books)) {
                                if(videos.length > 0 || audios.length > 0 || books.length > 0) {
                                    showPopUpMassage(lang[4]);
                                    openWatingUploadDiv();
                                    StartUploadingVideos(jsonResponse.data.playlist,lang,jsonResponse.msg);
                                    return;
                                }
                            }
                        }
                        if(jsonResponse.hasOwnProperty('msg')) showPopUpMassage(jsonResponse.msg,function () {
                            window.location.href = window.location.origin + '/admin/playlist';
                        }, function() {
                            window.location.href = window.location.origin + '/admin/playlist';
                        });
                        return;
                    }
                }
            } else {
                if(jsonResponse.hasOwnProperty('msg'))showPopUpMassage(jsonResponse.msg);
                else showPopUpMassage(lang[0]);
            }
        } else {
            showPopUpMassage(lang[0]);
        }
    });
}

function StartUploadingVideos(playlist,lang,secsses) {
    var currentUpload = getNextVideo(0);
    if(currentUpload == null) {
        if(allIsUpload()) {
            if(typeof(StartUploadingFiles) == "function") StartUploadingFiles(playlist,lang,secsses);
        } else {
            showPopUpMassage(lang[10] ,function () {
                window.location.href = window.location.origin + '/admin/playlist';
            }, function(exitThis,popUpMassageDiv) {
                for(var i = 0; i < videos.length; i++)if(typeof(videos[i]) == "object")if(videos[i].uploaded == false)videos[i].tryUpload = 0;
                StartUploadingVideos(playlist,lang,secsses);
                exitThis(popUpMassageDiv);
            });
        }
        return;
    }
    if(playlist.hasOwnProperty('id')) {
        videos[currentUpload].playlistId = playlist.id;
    } else {
        showPopUpMassage(lang[0]);
        return;
    }
    var tempForm = videos[currentUpload].getFormData();
    tempForm.append('_token',lang[9]);

    var tempWatingVideo = document.getElementById('watingVideo' + currentUpload);
    if(tempWatingVideo.children.length > 2) {
        if(tempWatingVideo.children[2].children.length > 1) {
            tempWatingVideo.children[2].children[1].textContent = lang[6];
            if(tempWatingVideo.children[2].children[0].children.length > 1) {
                tempWatingVideo.children[2].children[0].children[0].class = "transition";
            }
        }
    }
    videos[currentUpload].tryUpload += 1;

    ajaxUploadVideo('post',lang[8],tempForm,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('id')) {
                    videos[currentUpload].uploaded = true;
                    videos[currentUpload].id = jsonResponse.data.id;
                    videos[currentUpload].posterUploaded = true;
                    if(tempWatingVideo != null) {
                        if(tempWatingVideo.children.length > 2) {
                            if(tempWatingVideo.children[2].children.length > 1) {
                                if(tempWatingVideo.children[2].children[0].children.length > 1) {
                                    tempWatingVideo.children[2].children[0].children[0].style = 'width: 100%;';
                                    tempWatingVideo.children[2].children[0].children[1].textContent = '100%';
                                }
                                tempWatingVideo.children[2].children[1].textContent = lang[5];
                                StartUploadingVideos(playlist,lang,secsses);
                                return;
                            }
                        }
                    }
                }
            }
        }
        showPopUpMassage(lang[7]);
        StartUploadingVideos(playlist,lang,secsses);
    },function (e) {
        if(tempWatingVideo.children.length > 2) {
            if(tempWatingVideo.children[2].children.length > 1) {
                if(tempWatingVideo.children[2].children[0].children.length > 1) {
                    var uploaded_percent = Math.round((e.loaded / e.total)*100);
                    tempWatingVideo.children[2].children[0].children[0].style = 'width: ' + uploaded_percent + '%;';
                    tempWatingVideo.children[2].children[0].children[1].textContent = uploaded_percent + '%';
                }
            }
        }
    },function () {
        showPopUpMassage(lang[7]);
        StartUploadingVideos(playlist,lang,secsses);
    });
}

