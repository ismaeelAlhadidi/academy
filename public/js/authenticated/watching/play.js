( function setStorage() {
    if(typeof(Storage) !== "undefined" && typeof(localStorage) !== "undefined") {
        var tempCurrentBlobFromStorage = localStorage.getItem('currentPlay' + PLAYLIST_ID);
        if(tempCurrentBlobFromStorage != null) {
            currentBlob = JSON.parse(tempCurrentBlobFromStorage);
        } else {
            localStorage.setItem('currentPlay' + PLAYLIST_ID, JSON.stringify(currentBlob));
        }
    } else {
        var tempCookies = document.cookie.split(';');
        var ObjectOfCookies = {};
        for(var i = 0; i < tempCookies.length; i++) {
            key = tempCookies[i].split('=')[0];
            value = tempCookies[i].split('=')[1];
            ObjectOfCookies[key] = value;
        }
        if(ObjectOfCookies.hasOwnProperty('playlistWatching')) {
            if(ObjectOfCookies.playlistWatching == PLAYLIST_ID) {
                if(ObjectOfCookies.hasOwnProperty('blob_id')) currentBlob.blob_id = ObjectOfCookies.blob_id;
                if(ObjectOfCookies.hasOwnProperty('publicKey')) currentBlob.publicKey = ObjectOfCookies.publicKey;
                if(ObjectOfCookies.hasOwnProperty('video_id')) currentBlob.video_id = ObjectOfCookies.video_id;
                if(ObjectOfCookies.hasOwnProperty('poster_src')) currentBlob.poster_src = ObjectOfCookies.poster_src;
                if(ObjectOfCookies.hasOwnProperty('type')) currentBlob.type = ObjectOfCookies.type;
                return;
            }
        }
        var now = new Date();
        now.setMonth(now.getMonth() + 3);
        var afterThreeMonth = now.toUTCString();
        document.cookie = "playlistWatching=" + PLAYLIST_ID + ";expires=" + afterThreeMonth + ";";
        document.cookie = "blob_id=" + currentBlob.blob_id + ";expires=" + afterThreeMonth + ";";
        document.cookie = "publicKey=" + currentBlob.publicKey + ";expires=" + afterThreeMonth + ";";
        document.cookie = "video_id=" + currentBlob.video_id + ";expires=" + afterThreeMonth + ";";
        document.cookie = "poster_src=" + currentBlob.poster_src + ";expires=" + afterThreeMonth + ";";
        document.cookie = "type=" + currentBlob.type + ";expires="  + afterThreeMonth + ";";
    }
    openTheRightTap();
})();

var currentVideoElement = document.getElementById('currentVideoElement'),
    opendVideo = document.getElementById('opendVideo'),
    videoProgressBarContainer = document.getElementById('videoProgressBarContainer'),
    posterOfOpendVideo = document.getElementById('posterOfOpendVideo'),
    opendVideoController = document.getElementById('opendVideoController'),
    videoProgressBar = document.getElementById('videoProgressBar'),
    divForMakeEventPlayPauseOnFooter = document.getElementById('divForMakeEventPlayPauseOnFooter'),
    centerWaitingInPlayer = document.getElementById('centerWaitingInPlayer');

var player = new Player(currentBlob.publicKey, currentBlob.poster_src, currentVideoElement, currentBlob.blob_id, PLAYLIST_ID, currentBlob.type);
function setWatchingEvents() {
    var tempPosterOfOpendVideo = document.getElementById('posterOfOpendVideo'),
        tempSmallPlayPauseSVGButton = document.getElementById('smallPlayPauseSVGButton');
        tempOpendVideoFullScrrenButton = document.getElementById('opendVideoFullScrrenButton');

    var startVideo = function startVideo() {
        player.start();
        tempPosterOfOpendVideo.onclick = playPauseHandler;
        divForMakeEventPlayPauseOnFooter.onclick = playPauseHandler;
        tempSmallPlayPauseSVGButton.onclick = playPauseHandler;
    };
    var playPauseHandler = function clickOnPlayedVideoHandler () {
        player.playPause();
    };
    var toggleFullScreenHandler = function toggleFullScreenHandler() {
        player.toggleFullScreen();
    };
    if(typeof(player) == "object") {
        if(tempOpendVideoFullScrrenButton != null) tempOpendVideoFullScrrenButton.onclick = toggleFullScreenHandler;
        if(player.permision) {
            if(tempPosterOfOpendVideo != null) tempPosterOfOpendVideo.onclick = startVideo;
            if(divForMakeEventPlayPauseOnFooter != null) divForMakeEventPlayPauseOnFooter.onclick = startVideo;
            if(tempSmallPlayPauseSVGButton != null) tempSmallPlayPauseSVGButton.onclick = startVideo;
        } else {
            if(tempPosterOfOpendVideo != null) tempPosterOfOpendVideo.onclick = function(){};
            if(divForMakeEventPlayPauseOnFooter != null) divForMakeEventPlayPauseOnFooter.onclick = function(){};
            if(tempSmallPlayPauseSVGButton != null) tempSmallPlayPauseSVGButton.onclick = function(){};
        }
    }
    var spaceHandlerOnWatch = function spaceHandlerOnWatch(e) {
        if(e.code == "Space") {
            if(tempPosterOfOpendVideo != null) tempPosterOfOpendVideo.click();
            e.preventDefault();
        }
    };
    var spaceHandlerOnWrite = function spaceHandlerOnWrite(e) { };
    window.onkeypress = spaceHandlerOnWatch;
    var allInputsInPage = document.getElementsByTagName('input'),
        allTextAreasInPage = document.getElementsByTagName('textarea');
    for(var i = 0; i < allInputsInPage.length; i++) {
        allInputsInPage[i].onfocus = function () {
            window.onkeypress = spaceHandlerOnWrite;
        };
        allInputsInPage[i].onblur = function () {
            window.onkeypress = spaceHandlerOnWatch;
        };
    }
    for(var i = 0; i < allTextAreasInPage.length; i++) {
        allTextAreasInPage[i].onfocus = function () {
            window.onkeypress = spaceHandlerOnWrite;
        };
        allTextAreasInPage[i].onblur = function () {
            window.onkeypress = spaceHandlerOnWatch;
        };
    }
};

function changeStorage(publicKey, posterSrc, blobId, videoId, type) {
    if(typeof(currentBlob) != "undefined") {
        currentBlob = {
            'blob_id': blobId,
            'publicKey': publicKey,
            'video_id': videoId,
            'poster_src': posterSrc,
            'type': type,
        };
    } else {
        var currentBlob = {
            'blob_id': blobId,
            'publicKey': publicKey,
            'video_id': videoId,
            'poster_src': posterSrc,
            'type': type,
        };
    }
    if(typeof(Storage) !== "undefined" && typeof(localStorage) !== "undefined") {
        localStorage.setItem('currentPlay' + PLAYLIST_ID, JSON.stringify(currentBlob));
    } else {
        var now = new Date();
        now.setMonth(now.getMonth() + 3);
        var afterThreeMonth = now.toUTCString();
        document.cookie = "playlistWatching=" + PLAYLIST_ID + ";expires=" + afterThreeMonth + ";";
        document.cookie = "blob_id=" + currentBlob.blob_id + ";expires=" + afterThreeMonth + ";";
        document.cookie = "publicKey=" + currentBlob.publicKey + ";expires=" + afterThreeMonth + ";";
        document.cookie = "video_id=" + currentBlob.video_id + ";expires=" + afterThreeMonth + ";";
        document.cookie = "poster_src=" + currentBlob.poster_src + ";expires=" + afterThreeMonth + ";";
        document.cookie = "type=" + currentBlob.type + ";expires="  + afterThreeMonth + ";";
    }
    openTheRightTap(type);
}
function openTheRightTap(type = null) {
    if(type == null && typeof(currentBlob) != "object") return;
    if(type == null && ! currentBlob.hasOwnProperty('type')) return;
    switch((type == null) ? currentBlob.type : type) {
        case 'video':
            var tempOpenVideosButton = document.getElementById('openVideosButton');
            if(tempOpenVideosButton != null) tempOpenVideosButton.click();
            break;
        case 'audio':
            var tempOpenAudiosButton = document.getElementById('openAudiosButton');
            if(tempOpenAudiosButton != null) tempOpenAudiosButton.click();
            break;
        case 'book':
            var tempOpenBooksButton = document.getElementById('openBooksButton');
            if(tempOpenBooksButton != null) tempOpenBooksButton.click();
            break;
    }
}