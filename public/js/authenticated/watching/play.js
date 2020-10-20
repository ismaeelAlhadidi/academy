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
})();

var currentVideoElement = document.getElementById('currentVideoElement'),
    opendVideo = document.getElementById('opendVideo');
var player = new Player(currentBlob.publicKey, currentBlob.poster_src, currentVideoElement, currentBlob.blob_id, PLAYLIST_ID);
( function setWatchingEvents() {
    var tempPosterOfOpendVideo = document.getElementById('posterOfOpendVideo'),
        tempDivForMakeEventPlayPauseOnFooter = document.getElementById('divForMakeEventPlayPauseOnFooter'),
        tempSmallPlayPauseSVGButton = document.getElementById('smallPlayPauseSVGButton');
        tempOpendVideoFullScrrenButton = document.getElementById('opendVideoFullScrrenButton');

    var startVideo = function startVideo() {
        player.start();
        tempPosterOfOpendVideo.onclick = playPauseHandler;
        tempDivForMakeEventPlayPauseOnFooter.onclick = playPauseHandler;
        tempSmallPlayPauseSVGButton.onclick = playPauseHandler;
    };
    var playPauseHandler = function clickOnPlayedVideoHandler () {
        player.playPause();
    };
    var toggleFullScreenHandler = function toggleFullScreenHandler() {
        player.toggleFullScreen();
    };
    if(typeof(player) == "object") {
        if(tempPosterOfOpendVideo != null) tempPosterOfOpendVideo.onclick = startVideo;
        if(tempDivForMakeEventPlayPauseOnFooter != null) tempDivForMakeEventPlayPauseOnFooter.onclick = startVideo;
        if(tempSmallPlayPauseSVGButton != null) tempSmallPlayPauseSVGButton.onclick = startVideo;
        if(tempOpendVideoFullScrrenButton != null) tempOpendVideoFullScrrenButton.onclick = toggleFullScreenHandler;
    }
})();
