class Player {
    static defaultPoster = '';

    constructor(publicKey, posterSrc, videoElement, blobId, playlistId, type = "video") {
        this._videoElement = videoElement;
        this._poster = posterSrc;
        this._type = type;
        this._url = window.location.origin + '/object/' + this._type + '/' + publicKey;
        this._blobId = blobId;
        this._playlistId = playlistId;
        this._isFullScreen = false;
        this._permision = false;
        this._mimeType = '';
        this._currentHls = null;
        this.setEventsOfProgress();
        this.drawCanvasElements();
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
            progressRedPar = document.getElementById('progressRedPar'),
            settingOfOpendVideo = document.getElementById('settingOfOpendVideo'),
            settingButton = document.getElementById('settingButton'),
            posterOfOpendVideo = document.getElementById('posterOfOpendVideo');
        
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
            window.onkeypress = function (e) {
                if(e.code == "Space") {
                    if(posterOfOpendVideo != null) posterOfOpendVideo.click();
                    e.preventDefault();
                }
            };
        }
        if(settingButton != null) {
            settingButton.onclick = function () {
                if(settingOfOpendVideo == null) return;
                if(settingOfOpendVideo.style.display == "none") {
                    settingOfOpendVideo.setAttribute('style', 'display: block;');
                } else {
                    settingOfOpendVideo.setAttribute('style', 'display: none;');
                }
            };
        }
    }
    drawCanvasElements() {
        var selectedQualiteCanvas = document.getElementsByClassName('selected-quality-canvas');
        for(var i = 0; i < selectedQualiteCanvas.length; i++) drawCorrectSign(selectedQualiteCanvas[i], '#ffffffcc', false);
    }
    preparationWatch() {
        var tempThis = this;
        var imageOfOpendVideo = document.getElementById('imageOfOpendVideo');
        var progressBufferd = document.getElementById('progressBufferd');
        var changeQualityButtons = document.getElementsByClassName('change-quality'),
            settingButton = document.getElementById('settingButton');        
        if(tempThis.videoElement != null) {
            if(settingButton != null) settingButton.setAttribute('style', '');
            if(this.type == "video") {
                if(Hls.isSupported()) {
                    tempThis.currentHls = new Hls();
                    tempThis.currentHls.loadSource(tempThis.url);
                    tempThis.currentHls.attachMedia(tempThis.videoElement);
                    tempThis.currentHls.loadLevel = -1;
                    tempThis.currentHls.nextLevel = -1;
                    if(changeQualityButtons[0].children.length > 1) {
                        if(changeQualityButtons[0].children[0].children.length > 0) {
                            changeQualityButtons[0].children[0].children[0].setAttribute('style', 'display: inline-block;');
                        }
                    }
                    for(var i = 0; i < changeQualityButtons.length; i++) {
                        changeQualityButtons[i].onclick = function() {
                            tempThis.currentHls.loadLevel = this.dataset.value;
                            tempThis.currentHls.nextLevel = this.dataset.value;
                            for(var j = 0; j < changeQualityButtons.length; j++)  {
                                var isSelectedQuality = ( this.dataset.value == -1 ) ? changeQualityButtons[j].dataset.value == -1 : tempThis.currentHls.loadLevel == changeQualityButtons[j].dataset.value;
                                if(isSelectedQuality) {
                                    if(changeQualityButtons[j].children.length > 1) {
                                        if(changeQualityButtons[j].children[0].children.length > 0) {
                                            changeQualityButtons[j].children[0].children[0].setAttribute('style', 'display: inline-block;');
                                        }
                                    }
                                } else {
                                    if(changeQualityButtons[j].children.length > 1) {
                                        if(changeQualityButtons[j].children[0].children.length > 0) {
                                            changeQualityButtons[j].children[0].children[0].setAttribute('style', '');
                                        }
                                    }
                                }
                            }
                        };
                    }
                } else if (tempThis.videoElement.canPlayType('application/vnd.apple.mpegurl')) {
                    tempThis.videoElement.src = tempThis.url;
                }
            } else {
                tempThis.videoElement.src = tempThis.url;
                tempThis.videoElement.load();
                if(settingButton != null) settingButton.setAttribute('style', 'display: none;');
            }
            tempThis.videoElement.onwaiting = function() {
                if(centerWaitingInPlayer != null) {
                    centerWaitingInPlayer.style = "";
                    centerWaitingInPlayer.setAttribute('style', '');
                }
            };
            tempThis.videoElement.onplaying = function() {
                if(centerWaitingInPlayer != null) {
                    centerWaitingInPlayer.style = "display: none;";
                    centerWaitingInPlayer.setAttribute('style', 'display: none;');
                }
            };
            tempThis.videoElement.onprogress = function() {
                var bufferedEnd = tempThis.videoElement.buffered.end(tempThis.videoElement.buffered.length - 1),
                    width = Math.floor(bufferedEnd / tempThis.videoElement.duration * 100 );
                progressBufferd.setAttribute('style', 'width: ' + width + '%;');
            };
            tempThis.videoElement.onseeked = function() {
                console.log('seeked');
            };
            tempThis.videoElement.onseeking = function() {
                console.log('seeking');
            };
            tempThis.videoElement.onstalled = function() {
                console.log('stalled');
            };
        }
        if(imageOfOpendVideo != null) imageOfOpendVideo.src = tempThis.poster;
    }
    setVideo(publicKey, posterSrc, blobId, type = "video") {
        this.clear();
        this.poster = posterSrc;
        this.type = type;
        this.url = window.location.origin + '/object/' + this.type + '/' + publicKey;
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
                        if(jsonResponse.hasOwnProperty('data')) {
                            if(jsonResponse.data.hasOwnProperty('mimi')) {
                                tempThis.mimeType = jsonResponse.data.mimi;
                                if(jsonResponse.data.hasOwnProperty('size')) tempThis.size = jsonResponse.data.size;
                            }
                        }
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
        if ('srcObject' in this.videoElement) {
            this.videoElement.srcObject = null;
        }
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
            if(centerWaitingInPlayer != null) {
                centerWaitingInPlayer.style = "display: none;";
                centerWaitingInPlayer.setAttribute('style', 'display: none;');
            }
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
            if(centerWaitingInPlayer != null) {
                centerWaitingInPlayer.style = "display: none;";
                centerWaitingInPlayer.setAttribute('style', 'display: none;');
            }
            this.setVisibltyEventsOfProgressPar();
        }
    }
    toggleFullScreen() {
        if(opendVideo == null) return;
        var opendVideoFullScrrenButton = document.getElementById('opendVideoFullScrrenButton');
        var settingButton = document.getElementById('settingButton');
        if(opendVideo.getAttribute('class') == 'opened-video') {
            opendVideo.setAttribute('class', 'opened-video video-full-screen');
            if(opendVideoFullScrrenButton) {
                if(opendVideoFullScrrenButton.children.length > 1) {
                    opendVideoFullScrrenButton.children[1].setAttribute('d', svgPaths.exitFullScreen);
                }
            }
            if(settingButton) {
                if(settingButton.children.length > 0) {
                    settingButton.children[0].setAttribute('transform', 'matrix( 1.1, 0, 0, 1.1, -1, -6.1)');
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
            if(settingButton) {
                if(settingButton.children.length > 0) {
                    settingButton.children[0].setAttribute('transform', 'matrix( 1.1, 0, 0, 1.1, 3, -0.5)');
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
                        if(divForMakeEventPlayPauseOnFooter != null) {
                            divForMakeEventPlayPauseOnFooter.style = "displae: none;";
                            divForMakeEventPlayPauseOnFooter.setAttribute('style', 'display: none;');
                        }

                    };
                    posterOfOpendVideo.onmouseover = function() {
                        videoProgressBarContainer.style = "";
                        if(divForMakeEventPlayPauseOnFooter != null){
                            divForMakeEventPlayPauseOnFooter.style = "";
                            divForMakeEventPlayPauseOnFooter.setAttribute('style', '');
                        }
                    };
                    opendVideoController.onmouseout = function () {
                        videoProgressBarContainer.style = "display: none;";
                        if(divForMakeEventPlayPauseOnFooter != null) {
                            divForMakeEventPlayPauseOnFooter.style = "displae: none;";
                            divForMakeEventPlayPauseOnFooter.setAttribute('style', 'display: none;');
                        }
                    };
                    opendVideoController.onmouseover = function() {
                        videoProgressBarContainer.style = "";
                        if(divForMakeEventPlayPauseOnFooter != null){
                            divForMakeEventPlayPauseOnFooter.style = "";
                            divForMakeEventPlayPauseOnFooter.setAttribute('style', '');
                        }
                    };
                } else {
                    videoProgressBarContainer.style = "";
                    if(divForMakeEventPlayPauseOnFooter != null){
                        divForMakeEventPlayPauseOnFooter.style = "";
                        divForMakeEventPlayPauseOnFooter.setAttribute('style', '');
                    }
                    posterOfOpendVideo.onmouseout = function () {};
                    posterOfOpendVideo.onmouseover = function() {};
                    opendVideoController.onmouseout = function () {};
                    opendVideoController.onmouseover = function() {};
                }
                posterOfOpendVideo.onmousemove = function() {};
                opendVideoController.onmousemove = function() {};
            } else {
                videoProgressBarContainer.style = "";
                if(divForMakeEventPlayPauseOnFooter != null){
                    divForMakeEventPlayPauseOnFooter.style = "";
                    divForMakeEventPlayPauseOnFooter.setAttribute('style', '');
                }
                posterOfOpendVideo.onmouseout = function () {};
                posterOfOpendVideo.onmouseover = function() {};
                opendVideoController.onmouseout = function () {};
                opendVideoController.onmouseover = function() {};
                if(! this.videoElement.paused && ! this.videoElement.ended) {
                    var IntervalToHiddenProgressPar;
                    var fullScreenMovingHandler = function () {
                        videoProgressBarContainer.style = "";
                        if(divForMakeEventPlayPauseOnFooter != null) {
                            divForMakeEventPlayPauseOnFooter.style = "";
                            divForMakeEventPlayPauseOnFooter.setAttribute('style', '');
                        }
                        clearTimeout(IntervalToHiddenProgressPar);
                        IntervalToHiddenProgressPar = setTimeout( function() {
                            videoProgressBarContainer.style = "display: none;";
                            if(divForMakeEventPlayPauseOnFooter != null) {
                                divForMakeEventPlayPauseOnFooter.style = "displae: none;";
                                divForMakeEventPlayPauseOnFooter.setAttribute('style', 'display: none;');
                            }
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
    get playlistId() { return this._playlistId; }
    set playlistId(value) { this._playlistId = value; }
    get videoElement() { return this._videoElement; }
    set videoElement(value) { this._videoElement = value; }
    get currentHls() { return this._currentHls; }
    set currentHls(value) { this._currentHls = value; }
    get poster() { return this._poster; }
    set poster(value) { this._poster = value; }
    get url() { return this._url; }
    set url(value) { this._url = value; }
    get permision() { return this._permision; }
    set permision(value) { this._permision = value; }
    get type() { return this._type; }
    set type(value) { this._type = value; }
    get mimeType() { return this._mimeType; }
    set mimeType(value) { this._mimeType = value; }
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
/*
var video = tempThis.videoElement;
        var prevBuffer = {
            "buffer": null,
            "time": null
        };
        var isBuffering = function(){
        
            if(video && video.buffered && video.buffered.end && video.buffered.length > 0){
                var buffer = video.buffered.end(0);
                var time   = video.currentTime;
        
                // Check if the video hangs because of issues with e.g. performance
                if(prevBuffer.buffer === buffer && prevBuffer.time === time && !video.paused){
                    return true;
                }
                prevBuffer = {
                    "buffer": buffer,
                    "time": time
                };
                // Check if video buffer is less
                // than current time (tolerance 3 sec)
                if((buffer - 3) < time){
                    return true;
                }
            }
            return false;
        
        };
        video.addEventListener("play", function(e){
            // Make sure this handler is only called once
            e.target.removeEventListener(e.type, arguments.called);
            // Give browsers 3secs time to buffer
            setTimeout(function(){
                // As "progress", "stalled" or "waiting" aren't fired
                // reliable, we need to use an interval
                var interval = setInterval(function(){
                    if(isBuffering()){
                        clearInterval(interval);
                        console.log("Buffering");
                    }
                }, 500);
            }, 3000);
        });
        var addFrameToUrlObject = function addFrameToUrlObject() {
    
};
var downloadFrame = function downloadFrame() {

};
var checkBuffer = function checkBuffer() {

};
var threeSecondsInterval = setInterval(function() {
    downloadNextFrame();
});
var halfSecondInterval = setInterval(function () {
    checkBuffer();
});
        */
