class Player {
    static defaultPoster = '';

    constructor(publicKey, posterSrc, videoElement, blobId, playlistId, type = "video") {
        this._videoElement = videoElement;
        this._poster = posterSrc;
        this._type = type;
        this._url = window.location.origin + '/blob/' + this._type + '/' + publicKey;
        this._blobId = blobId;
        this._playlistId = playlistId;
        this._blobUrl = null;
        this._isFullScreen = false;
        this._permision = false;
        this.setEventsOfProgress();
        this.checkPermision(window.location.origin + '/ajax/' + playlistId + '/blob/check-permision/' + blobId);
    }
    setTitleAndDescription(data) {
        var mainTitleElement = document.getElementById('mainTitleElement'),
            mainDescriptionElement = document.getElementById('mainDescriptionElement');
        
        if(mainTitleElement != null) mainTitleElement.textContent = PLAYLIST_TITLE;
        if(mainDescriptionElement != null) mainDescriptionElement.textContent = PLAYLIST_DESCRIPTION;
        if(typeof(data) != "object" || data == null) return;

        if(mainTitleElement != null && data.hasOwnProperty('title')) mainTitleElement.textContent = data.title;
        if(mainDescriptionElement != null && data.hasOwnProperty('desc')) {
            if(data.desc != null) if(data.desc.trim() != '') mainDescriptionElement.textContent = data.desc;
        }
    }
    setEventsOfProgress() {
        var tempThis = this;
        var opendVideoTime = document.getElementById('opendVideoTime'),
            tempSmallPlayPauseSVGButton = document.getElementById('smallPlayPauseSVGButton'),
            progressPointer = document.getElementById('progressPointer'),
            progressHoverPar = document.getElementById('progressHoverPar'),
            progressRedPar = document.getElementById('progressRedPar');
        
        var progressMovingHandler = function progressMovingHandler() {
            if(progressRedPar != null && tempThis.videoElement != null) {
                var widthOfProgress = Math.floor(tempThis.videoElement.currentTime * (100 / tempThis.videoElement.duration));
                progressRedPar.style = "width: " + widthOfProgress + "%;";
                if(progressPointer != null) {
                    progressPointer.style.left =  (progressRedPar.getBoundingClientRect().width - 1) + 'px';
                }
            }
        };
        var mouseX = null;
        var mouseMoveHandler =  function mouseMoveHandler (e) {
            mouseX = e.x;
        };
        document.onmouseenter = mouseMoveHandler;
        document.onmousemove = mouseMoveHandler;
        document.ontouchstart = mouseMoveHandler;
        document.ontouchmove = mouseMoveHandler;
        var progressActiveHandler = function () {
            if(videoProgressBar != null && mouseX != null) {
                var videoProgressBarWidth = videoProgressBar.getBoundingClientRect().width;
                var tempProgressRedParWidth = mouseX - videoProgressBar.getBoundingClientRect().x;
                if(progressRedPar != null) progressRedPar.style.width =  ((tempProgressRedParWidth > videoProgressBarWidth) ? videoProgressBarWidth : tempProgressRedParWidth ) + 'px';
                if(progressPointer != null && progressRedPar != null) progressPointer.style.left =  (progressRedPar.getBoundingClientRect().width - 1) + 'px';
                if(progressPointer != null) progressPointer.style.display = "block";
            }
        };
        var tempProgressMovingHandler = progressMovingHandler;
        var tempProgressActiveHandler = function(){};
        var videoProgressOnclickHandler = function videoProgressOnclickHandler (e) {
            if(tempThis.videoElement != null) {
                if(! isNaN(tempThis.videoElement.duration)) tempThis.videoElement.currentTime = ((e.x - videoProgressBar.getBoundingClientRect().x) / videoProgressBar.getBoundingClientRect().width) * tempThis.videoElement.duration;
            }
            tempProgressActiveHandler = function(){};
            tempProgressMovingHandler();
            tempThis.play();
        };
        if(opendVideoTime != null) {
            tempThis.videoElement.ontimeupdate = function () {
                opendVideoTime.textContent = timeHMSFormat(tempThis.videoElement.currentTime) + ' / '  + timeHMSFormat(tempThis.videoElement.duration);
                tempProgressMovingHandler();
            };
        }
        if(tempSmallPlayPauseSVGButton != null) {
            tempThis.videoElement.onended = function() {
                if(tempSmallPlayPauseSVGButton.children.length > 0) {
                    tempSmallPlayPauseSVGButton.children[0].setAttribute('d', svgPaths.smallReplay);
                    tempSmallPlayPauseSVGButton.children[0].setAttribute('transform', "translate(6,7)");
                }
                if(progressPointer != null) progressPointer.style.display = "none";
                tempThis.setVisibltyEventsOfProgressPar();
            }
        }
        if(videoProgressBar != null) {
            if(progressPointer != null) {
                videoProgressBar.onmouseover = function() {
                    progressPointer.style.display = "block";
                    tempProgressActiveHandler();
                };
                videoProgressBar.onmouseout = function() {
                    progressPointer.style.display = "none";
                    progressHoverPar.style.width = 0;
                };
            }
            if(progressHoverPar != null) {
                videoProgressBar.onmousemove = function(e) {
                    progressHoverPar.style.width = (e.x - videoProgressBar.getBoundingClientRect().x) + 'px';
                    tempProgressActiveHandler();
                };
            }
            videoProgressBar.onclick = videoProgressOnclickHandler;
            videoProgressBar.onmousedown = function(event) { 
                tempProgressMovingHandler = function(){};
                var widthOfProgress = (event.x - videoProgressBar.getBoundingClientRect().x) + 'px';
                if(progressRedPar != null) {
                    progressRedPar.style.width = widthOfProgress;
                    if(progressPointer != null) {
                        progressPointer.style.left =  (progressRedPar.getBoundingClientRect().width - 1) + 'px';
                    }
                }
                tempProgressActiveHandler = progressActiveHandler;
                tempProgressActiveHandler();
                document.onmouseup = function (e) {
                    tempProgressMovingHandler = progressMovingHandler;
                    tempProgressActiveHandler = function(){};
                    videoProgressOnclickHandler(e);
                    document.onmouseup = function(){};
                    document.onmousemove = mouseMoveHandler;
                };
                document.onmousemove = function(e) {
                    mouseMoveHandler(e);
                    tempProgressActiveHandler();
                };
            };

            videoProgressBar.ontouchstart = function (event) {
                tempProgressMovingHandler = function(){};
                var widthOfProgress = (event.x - videoProgressBar.getBoundingClientRect().x) + 'px';
                if(progressRedPar != null) {
                    progressRedPar.style.width = widthOfProgress;
                    if(progressPointer != null) {
                        progressPointer.style.left =  (progressRedPar.getBoundingClientRect().width - 1) + 'px';
                    }
                }
                tempProgressActiveHandler = progressActiveHandler;
                tempProgressActiveHandler();
                document.ontouchend = function (e) {
                    tempProgressMovingHandler = progressMovingHandler;
                    tempProgressActiveHandler = function(){};
                    videoProgressOnclickHandler(e);
                    document.ontouchend = function(){};
                    document.ontouchmove = mouseMoveHandler;
                };
                document.ontouchmove = function(e) {
                    mouseMoveHandler(e);
                    tempProgressActiveHandler();
                };
            }
            this.setVisibltyEventsOfProgressPar();
        }
    }
    preparationWatch() {
        var tempThis = this;
        var imageOfOpendVideo = document.getElementById('imageOfOpendVideo');
        ajaxGetVideoRequest(tempThis.url, function (response) {
            tempThis.currentFile = response;
            if(tempThis.videoElement != null) {
                tempThis.blobUrl = URL.createObjectURL(tempThis.currentFile);
                tempThis.videoElement.src = tempThis.blobUrl;
            }
        });
        if(imageOfOpendVideo != null) imageOfOpendVideo.src = tempThis.poster;
    }
    setVideo(publicKey, posterSrc, blobId, type = "video") {
        this.clear();
        this.poster = posterSrc;
        this.type = type;
        this.url = window.location.origin + '/blob/' + this.type + '/' + publicKey;
        this.blobId = blobId;
        this.checkPermision(window.location.origin + '/ajax/' + this.playlistId + '/blob/check-permision/' + blobId);
        var imageOfOpendVideo = document.getElementById('imageOfOpendVideo'),
            centerMassegeInPlayer = document.getElementById('centerMassegeInPlayer'),
            centerSubButtonInPlayer = document.getElementById('centerMassegeInPlayer'),
            opendVideoConrols = document.getElementById('opendVideoConrols'),
            centerMainButtonInPlayer = document.getElementById('centerMainButtonInPlayer');

        if(imageOfOpendVideo != null) imageOfOpendVideo.style = "";
        if(centerMassegeInPlayer != null) centerMassegeInPlayer.style = "";
        if(centerSubButtonInPlayer != null) centerSubButtonInPlayer.style = "";
        if(opendVideoConrols != null) opendVideoConrols.style = "display: none;";
        if(centerMainButtonInPlayer != null && typeof(svgPaths) == "object") {
            if(centerMainButtonInPlayer.children.length > 0 && svgPaths.hasOwnProperty('bigPlay')) {
                centerMainButtonInPlayer.children[0].setAttribute('d', svgPaths.bigPlay);
                centerMainButtonInPlayer.style = "";
            }
        }
    }
    checkPermision(url) {
        var ok = false;
        var status = 0;
        var tempThis = this;
        tempThis.permision = false;
        /*
            (( status ))
            0 => error
            1 => user not Subscription in this playlist
            2 => video not avillable now
        */
        ajaxRequest('get', url, null, function(jsonResponse) {
            if(jsonResponse != null) {
                if(jsonResponse.hasOwnProperty('status')) {
                    if(jsonResponse.status) {
                        ok = true;
                    } else if(jsonResponse.hasOwnProperty('msg')) {
                        if(jsonResponse.msg == 'needSub') status = 1;
                        else if(jsonResponse.msg == 'videoTime') status = 2;
                    }
                }
                if(jsonResponse.hasOwnProperty('data')) {
                    tempThis.setTitleAndDescription(jsonResponse.data);
                } else {
                    tempThis.setTitleAndDescription(null);
                }
            }
            if(ok) {
                tempThis.permision = true;
                tempThis.preparationWatch();
            } else {
                if(status == 1) tempThis.askToSubscription();
                else if(status == 2) {
                    tempThis.showNotAvillableMassageOnPlayer();
                } else {
                    showPopUpMassage(lang.errorInPlayVideo, null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
                }
            }
            if(typeof(setWatchingEvents) == "function") setWatchingEvents();
        });
    }
    askToSubscription() {
        var tempCenterMainButton = document.getElementById('centerMainButtonInPlayer'),
            tempCenterMassege = document.getElementById('centerMassegeInPlayer'),
            tempCenterSubButton = document.getElementById('centerSubButtonInPlayer');
        
        if(tempCenterMainButton != null ) tempCenterMainButton.style = 'display: none;';
        if(tempCenterMassege != null) {
            tempCenterMassege.textContent = lang.needSubscriptionMassege;
            tempCenterMassege.style = '';
        }
        if(tempCenterSubButton != null) tempCenterSubButton.style = '';
    }
    showNotAvillableMassageOnPlayer() {
        var tempCenterMainButton = document.getElementById('centerMainButtonInPlayer'),
            tempCenterMassege = document.getElementById('centerMassegeInPlayer'),
            tempCenterSubButton = docuemnt.getElementById('centerSubButtonInPlayer');
    
        if(tempCenterMainButton != null ) tempCenterMainButton.style = 'display: none;';
        if(tempCenterMassege != null) {
            tempCenterMassege.textContent = lang.notAvillableMassage;
            tempCenterMassege.style = 'background-color: rgba(0,0,0,0.8); padding: 10px;';
        }
        if(tempCenterSubButton != null) tempCenterSubButton.style = 'display: none;';
    }
    clear() {
        var imageOfOpendVideo = document.getElementById('imageOfOpendVideo'),
            tempCenterMassege = document.getElementById('centerMassegeInPlayer'),
            tempCenterSubButton = document.getElementById('centerSubButtonInPlayer');

        if(tempCenterMassege != null) {
            tempCenterMassege.style = 'display: none;';
            tempCenterMassege.setAttribute('style', 'display: none;');
        }
        if(tempCenterSubButton != null) {
            tempCenterSubButton.style = 'display: none;';
            tempCenterSubButton.setAttribute('style', 'display: none;');
        }
        if(imageOfOpendVideo != null) imageOfOpendVideo.src = Player.defaultPoster;
        this.videoElement.src = '';
        this.blobUrl = null;
        URL.revokeObjectURL(this.currentFile);
        this.currentFile = null;
    }
    start() {
        var imageOfOpendVideo = document.getElementById('imageOfOpendVideo'),
            centerMassegeInPlayer = document.getElementById('centerMassegeInPlayer'),
            centerSubButtonInPlayer = document.getElementById('centerSubButtonInPlayer'),
            opendVideoConrols = document.getElementById('opendVideoConrols');

        if(this.type == "video") if(imageOfOpendVideo != null) imageOfOpendVideo.style = "display: none;";
        if(centerMassegeInPlayer != null) centerMassegeInPlayer.style = "display: none;";
        if(centerSubButtonInPlayer != null) centerSubButtonInPlayer.style = "display: none;";
        if(opendVideoConrols != null) opendVideoConrols.style = "";

        this.playPause();
    }
    playPause() {
        var centerMainButtonInPlayer = document.getElementById('centerMainButtonInPlayer');
        var smallPlayPauseSVGButton = document.getElementById('smallPlayPauseSVGButton');
        if(this.videoElement.paused) {
            if(centerMainButtonInPlayer != null) {
                if(centerMainButtonInPlayer.children.length > 0) {
                    centerMainButtonInPlayer.children[0].setAttribute('d', svgPaths.bigPause);
                    centerMainButtonInPlayer.style = "";
                }
                setTimeout(function () {
                    centerMainButtonInPlayer.style = "display: none;";
                }, 0.3 * 1000);
            }
            if(smallPlayPauseSVGButton != null) {
                if(smallPlayPauseSVGButton.children.length > 0) {
                    smallPlayPauseSVGButton.children[0].setAttribute('d', svgPaths.smallPause);
                    smallPlayPauseSVGButton.children[0].removeAttribute('transform');
                }
            }
            if(! isNaN(this.videoElement.duration)) this.videoElement.play();
            this.setVisibltyEventsOfProgressPar();
        } else {
            if(centerMainButtonInPlayer != null) {
                if(centerMainButtonInPlayer.children.length > 0) {
                    centerMainButtonInPlayer.children[0].setAttribute('d', svgPaths.bigPlay);
                    centerMainButtonInPlayer.style = "";
                }
                setTimeout(function () {
                    centerMainButtonInPlayer.style = "display: none;";
                }, 0.3 * 1000);
            }
            if(smallPlayPauseSVGButton != null) {
                if(smallPlayPauseSVGButton.children.length > 0) {
                    smallPlayPauseSVGButton.children[0].setAttribute('d', svgPaths.smallPlay);
                    smallPlayPauseSVGButton.children[0].removeAttribute('transform');
                }
            }
            this.videoElement.pause();
            this.setVisibltyEventsOfProgressPar();
        }
    }
    play() {
        var centerMainButtonInPlayer = document.getElementById('centerMainButtonInPlayer');
        var smallPlayPauseSVGButton = document.getElementById('smallPlayPauseSVGButton');
        if(this.videoElement.paused) {
            if(centerMainButtonInPlayer != null) {
                if(centerMainButtonInPlayer.children.length > 0) {
                    centerMainButtonInPlayer.children[0].setAttribute('d', svgPaths.bigPause);
                    centerMainButtonInPlayer.style = "";
                }
                setTimeout(function () {
                    centerMainButtonInPlayer.style = "display: none;";
                }, 0.3 * 1000);
            }
            if(smallPlayPauseSVGButton != null) {
                if(smallPlayPauseSVGButton.children.length > 0) {
                    smallPlayPauseSVGButton.children[0].setAttribute('d', svgPaths.smallPause);
                    smallPlayPauseSVGButton.children[0].removeAttribute('transform');
                }
            }
            if(! isNaN(this.videoElement.duration)) this.videoElement.play();
            this.setVisibltyEventsOfProgressPar();
        }
    }
    toggleFullScreen() {
        if(opendVideo == null) return;
        var opendVideoFullScrrenButton = document.getElementById('opendVideoFullScrrenButton');
        if(opendVideo.getAttribute('class') == 'opened-video') {
            opendVideo.setAttribute('class', 'opened-video video-full-screen');
            if(opendVideoFullScrrenButton) {
                if(opendVideoFullScrrenButton.children.length > 1) {
                    opendVideoFullScrrenButton.children[1].setAttribute('d', svgPaths.exitFullScreen);
                }
            }
            document.body.style = "overflow: hidden !important;";
            this.isFullScreen = true;
            this.setVisibltyEventsOfProgressPar();
            if(document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if(document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if(document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen();
            } else if(document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        } else {
            opendVideo.setAttribute('class', 'opened-video');
            if(opendVideoFullScrrenButton) {
                if(opendVideoFullScrrenButton.children.length > 1) {
                    opendVideoFullScrrenButton.children[1].setAttribute('d', svgPaths.fullScreen);
                }
            }
            document.body.style = "";
            this.isFullScreen = false;
            this.setVisibltyEventsOfProgressPar();
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }
    setVisibltyEventsOfProgressPar() {
        if(opendVideoController != null && posterOfOpendVideo != null && videoProgressBarContainer != null) {
            if(! this.isFullScreen) {
                if(! this.videoElement.paused && ! this.videoElement.ended) {
                    posterOfOpendVideo.onmouseout = function () {
                        videoProgressBarContainer.style = "display: none;";
                    };
                    posterOfOpendVideo.onmouseover = function() {
                        videoProgressBarContainer.style = "";
                    };
                    opendVideoController.onmouseout = function () {
                        videoProgressBarContainer.style = "display: none;";
                    };
                    opendVideoController.onmouseover = function() {
                        videoProgressBarContainer.style = "";
                    };
                } else {
                    videoProgressBarContainer.style = "";
                    posterOfOpendVideo.onmouseout = function () {};
                    posterOfOpendVideo.onmouseover = function() {};
                    opendVideoController.onmouseout = function () {};
                    opendVideoController.onmouseover = function() {};
                }
                posterOfOpendVideo.onmousemove = function() {};
                opendVideoController.onmousemove = function() {};
            } else {
                videoProgressBarContainer.style = "";
                posterOfOpendVideo.onmouseout = function () {};
                posterOfOpendVideo.onmouseover = function() {};
                opendVideoController.onmouseout = function () {};
                opendVideoController.onmouseover = function() {};
                if(! this.videoElement.paused && ! this.videoElement.ended) {
                    var IntervalToHiddenProgressPar;
                    var fullScreenMovingHandler = function () {
                        videoProgressBarContainer.style = "";
                        clearTimeout(IntervalToHiddenProgressPar);
                        IntervalToHiddenProgressPar = setTimeout( function() {
                            videoProgressBarContainer.style = "display: none;";
                        }, 1 * 1000 * 4);
                    };
                    fullScreenMovingHandler();
                    posterOfOpendVideo.onmousemove = fullScreenMovingHandler;
                    opendVideoController.onmousemove = fullScreenMovingHandler;
                }
            }
        }
    }
    get isFullScreen() { return this._isFullScreen; }
    set isFullScreen(value) { this._isFullScreen = value; }
    get blobId() { return this._blobId; }
    set blobId(value) { this._blobId = value; }
    get blobUrl() { return this._blobUrl; }
    set blobUrl(value) { this._blobUrl = value; }
    get playlistId() { return this._playlistId; }
    set playlistId(value) { this._playlistId = value; }
    get videoElement() { return this._videoElement; }
    set videoElement(value) { this._videoElement = value; }
    get currentFile() { return this._currentFile; }
    set currentFile(value) { this._currentFile = value; }
    get poster() { return this._poster; }
    set poster(value) { this._poster = value; }
    get url() { return this._url; }
    set url(value) { this._url = value; }
    get permision() { return this._permision; }
    set permision(value) { this._permision = value; }
    get type() { return this._type; }
    set type(value) { this._type = value; }
}
function timeHMSFormat(timeInSeconds) {
    /* H:M:S */
    if(isNaN(timeInSeconds)) return '0:00';
    var target = Math.floor(timeInSeconds);
    var tempTime = {
        's': target,
        'm': 0,
        'h': 0,
    }
    tempTime.m = Math.floor(tempTime.s / 60);
    tempTime.h = Math.floor(tempTime.m / 60);
    tempTime.m = tempTime.m - (tempTime.h * 60);
    tempTime.s = tempTime.s - ((tempTime.m * 60) + (tempTime.h * 60 * 60));

    target = tempTime.m + ( (tempTime.s < 10) ? ( ':0' + tempTime.s ) : ( ':' + tempTime.s ) );
    if(tempTime.h > 0) target = tempTime.h + ':' + target;
    return target;
}