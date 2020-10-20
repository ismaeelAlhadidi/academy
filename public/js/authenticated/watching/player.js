class Player {
    static defaultPoster = '';

    constructor(publicKey, posterSrc, videoElement, blobId, playlistId) {
        this._videoElement = videoElement;
        this._poster = posterSrc;
        this._url = window.location.origin + '/blob/video/' + publicKey;
        this._blobId = blobId;
        this._playlistId = playlistId;
        this._blobUrl = null;
        this.setEventsOfProgress();
        this.checkPermision(window.location.origin + '/ajax/' + playlistId + '/blob/check-permision/' + blobId);
    }
    setEventsOfProgress() {
        var tempThis = this;
        var opendVideoTime = document.getElementById('opendVideoTime'),
            tempSmallPlayPauseSVGButton = document.getElementById('smallPlayPauseSVGButton'),
            videoProgressBar = document.getElementById('videoProgressBar'),
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
        var progressActiveHandler = function () {
            if(videoProgressBar != null && mouseX != null) {
                if(progressRedPar != null) progressRedPar.style.width = mouseX - videoProgressBar.getBoundingClientRect().x + 'px';
                if(progressPointer != null && progressRedPar != null) progressPointer.style.left =  (progressRedPar.getBoundingClientRect().width - 1) + 'px';
                if(progressPointer != null) progressPointer.style.display = "block";
            }
        };
        var tempProgressMovingHandler = progressMovingHandler;
        var tempProgressActiveHandler = function(){};
        var videoProgressOnclickHandler = function videoProgressOnclickHandler (e) {
            if(tempThis.videoElement != null) {
                if(! isNaN(tempThis.videoElement.duration))tempThis.videoElement.currentTime = ((e.x - videoProgressBar.getBoundingClientRect().x) / videoProgressBar.getBoundingClientRect().width) * tempThis.videoElement.duration;
            }
            tempProgressActiveHandler = function(){};
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
            }
        }
        if(videoProgressBar != null) {
            if(progressPointer != null) {
                videoProgressBar.onmouseover = function() {
                    if(progressRedPar != null) progressPointer.style.left =  (progressRedPar.getBoundingClientRect().width - 1) + 'px';
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
                    progressHoverPar.style.width = e.x - videoProgressBar.getBoundingClientRect().x + 'px';
                    tempProgressActiveHandler();
                };
            }
            videoProgressBar.onclick = videoProgressOnclickHandler;
            videoProgressBar.onmousedown = function() {    
                tempProgressMovingHandler = function(){};
                tempProgressActiveHandler = progressActiveHandler;
                tempProgressActiveHandler();
            };
            videoProgressBar.onmouseup = function (e) {
                tempProgressMovingHandler = progressMovingHandler;
                tempProgressActiveHandler = function(){};
                videoProgressOnclickHandler(e);
            };
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
    setVideo(publicKey, posterSrc, blobId, mimiType) {
        this.clear();
        this.poster = posterSrc;
        this.url = window.location.origin + '/blob/video/' + publicKey;
        this.blobId = blobId;
        this.mimiType = mimiType;
        this.checkPermision(window.location.origin + '/ajax/' + this.playlistId + '/blob/check-permision/' + blobId);
    }
    checkPermision(url) {
        var ok = false;
        var status = 0;
        var tempThis = this;
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
            }
            if(ok) {
                tempThis.preparationWatch();
            } else {
                if(status == 1) tempThis.askToSubscription();
                else if(status == 2) {
                    tempThis.showNotAvillableMassageOnPlayer();
                } else {
                    showPopUpMassage(lang.errorInPlayVideo, null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
                }
            }
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

    }
    showPoster() {

    }
    clear() {
        var imageOfOpendVideo = document.getElementById('imageOfOpendVideo');
        if(imageOfOpendVideo != null) imageOfOpendVideo.src = Player.defaultPoster;
        this.videoElement.src = '';
        this.blobUrl = null;
        URL.revokeObjectURL(this.currentFile);
        this.currentFile = null;
    }
    start() {
        var imageOfOpendVideo = document.getElementById('imageOfOpendVideo'),
            centerMassegeInPlayer = document.getElementById('centerMassegeInPlayer'),
            centerSubButtonInPlayer = document.getElementById('centerMassegeInPlayer'),
            opendVideoConrols = document.getElementById('opendVideoConrols');

        if(imageOfOpendVideo != null) imageOfOpendVideo.style = "display: none;";
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
                setInterval(function () {
                    centerMainButtonInPlayer.style = "display: none;";
                }, 60);
            }
            if(smallPlayPauseSVGButton != null) {
                if(smallPlayPauseSVGButton.children.length > 0) {
                    smallPlayPauseSVGButton.children[0].setAttribute('d', svgPaths.smallPause);
                    smallPlayPauseSVGButton.children[0].removeAttribute('transform');
                }
            }
            if(! isNaN(this.videoElement.duration)) this.videoElement.play();
        } else {
            if(centerMainButtonInPlayer != null) {
                if(centerMainButtonInPlayer.children.length > 0) {
                    centerMainButtonInPlayer.children[0].setAttribute('d', svgPaths.bigPlay);
                    centerMainButtonInPlayer.style = "";
                }
                setInterval(function () {
                    centerMainButtonInPlayer.style = "display: none;";
                }, 60);
            }
            if(smallPlayPauseSVGButton != null) {
                if(smallPlayPauseSVGButton.children.length > 0) {
                    smallPlayPauseSVGButton.children[0].setAttribute('d', svgPaths.smallPlay);
                    smallPlayPauseSVGButton.children[0].removeAttribute('transform');
                }
            }
            this.videoElement.pause();
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
                setInterval(function () {
                    centerMainButtonInPlayer.style = "display: none;";
                }, 60);
            }
            if(smallPlayPauseSVGButton != null) {
                if(smallPlayPauseSVGButton.children.length > 0) {
                    smallPlayPauseSVGButton.children[0].setAttribute('d', svgPaths.smallPause);
                    smallPlayPauseSVGButton.children[0].removeAttribute('transform');
                }
            }
            if(! isNaN(this.videoElement.duration)) this.videoElement.play();
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