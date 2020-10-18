class player {
    constructor(publicKey, posterSrc, videoElement) {
        this._videoElement = videoElement;
        this._poster = posterSrc;
        this._url = window.location.origin + '/blob/video/' + publicKey;
        this.checkPermision(window.location.origin + '/blob/video/check-permision' + publicKey);
    }
    preparationWatch() {

    }
    setVideo(publicKey, posterSrc) {
        this.clear();
        this.poster = posterSrc;
        this.url = window.location.origin + '/blob/video/' + publicKey;
        this.checkPermision(window.location.origin + '/blob/video/check-permision' + publicKey);
    }
    checkPermision(url) {
        var ok = true;
        var status = 0;
        /*
            (( status ))
            0 => error
            1 => user not Subscription in this playlist
            2 => video not avillable now
        */
        ajaxRequest('get', url, null, function(jsonResponse) {
            if(jsonResponse == null) {
                ok = false;
                return;
            }
            if(jsonResponse.hasOwnProperty('status')) {
                if(jsonResponse.status) {
                    ok = true;
                    return;
                } else if(jsonResponse.hasOwnProperty('data')) {
                    if(jsonResponse.data == 'needSub') status = 1;
                    else if(jsonResponse.data == 'videoTime') status = 2;
                }
            }
            ok = false;
        });
        if(ok) {
            this.preparationWatch();
        } else {
            if(status == 1) this.askToSubscription();
            else {
                showMassageOnPlayer(status);
            }
        }
    }
    askToSubscription() {

    }
    showMassageOnPlayer() {
        
    }
    showPoster() {

    }
    clear() {
        /* revoke Object URL */
    }
    start() {

    }
    resume() {
        
    }
    pause() {

    }
    get videoElement() { return this._videoElement; }
    set videoElement(value) { this._videoElement = value; }
    get poster() { return this._poster; }
    set poster(value) { this._poster = value; }
    get url() { return this.url; }
    set url(value) { this.url = value; }
}