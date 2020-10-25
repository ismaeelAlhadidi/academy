function ajaxRequest(method,url,formElement,onResponse = null, loadingRequired = true, autoStopOfLoading = true) {
    if(formElement != null) {
        if(formElement.constructor == HTMLFormElement) var formData = new FormData(formElement);
    }
    var request = new XMLHttpRequest();
    request.open(method,url);
    request.onload = function() {
        if(onResponse != null) {
            var temp = null;
            if(IsJsonString(request.responseText)){
                temp = JSON.parse(request.responseText);
            } else console.log('not JSON'); /* remove in product */
            if(autoStopOfLoading && loadingRequired) {
                if(loadingDotElement != null) {
                    loadingDotElement.style = 'display: none;';
                    loadingDotElement.setAttribute('style', 'display: none;');
                }
            }
            onResponse(temp);
        }
    }
    if(formElement != null) {
        if(formElement.constructor == HTMLFormElement) request.send(formData);
        else if(formElement.constructor == FormData) request.send(formElement);
        else request.send();
    }
    else request.send();
    if(typeof(loadingDotElement) == "undefined") var loadingDotElement = document.getElementById('loadingDotElement');
    if(loadingRequired) {
        if(loadingDotElement != null && typeof(onResponse) == "function") {
            loadingDotElement.style = '';
            loadingDotElement.setAttribute('style', '');
        }
    }
}
function IsJsonString(temp) {
    "use strict"
    try {
        temp = JSON.parse(temp);
    }
    catch (e) {
        return false;
    }
    return (temp.constructor === Object);
}
function convertJsonToIndexedArray(json) {
    json = JSON.parse(json);
    var temp = [];
    for(var i in json) temp.push(json[i]);
    return temp;
}
function ajaxUploadVideo(method,url,formElement,onResponse = null,uploadProgressHandler,errorHandler,timeOutHandler = null, timeout = null) {
    if(formElement == null) return;
    if(formElement.constructor != FormData) return;
    var request = new XMLHttpRequest();
    request.open(method,url);
    request.onload = function() {
        if(onResponse != null) {
            var temp = null;
            if(IsJsonString(request.responseText)){
                temp = JSON.parse(request.responseText);
            }
            onResponse(temp);
        }
    }
    if(timeout != null) {
        request.timeout = timeout;
    }
    request.upload.onprogress = uploadProgressHandler;
    request.upload.onerror = errorHandler;
    request.upload.ontimeout = timeOutHandler;
    request.send(formElement);
}
function ajaxGetVideoRequest(url, onResponse, TOKEN) {
    var request = new XMLHttpRequest();
    var formData = new FormData();
    formData.append('_token', TOKEN);
    request.open('post', url);
    request.responseType = 'arraybuffer';
    request.onload = function () {
        onResponse(request.response);
    };
    request.send(formData);
}
/*
function test () {
    var temp = document.createElement('video');
    document.body.appendChild(temp);
    var URL = this.window.URL || this.window.webkitURL;
    var file = new Blob(["http://127.0.0.1:8000/blob/video/RtEK1a-1597323526-hd3s18tJxtSN-5f3539069c3983-07854345"],{ "type" : "video/mp4"});
    var value = URL.createObjectURL(file);
    temp.src = value;

    var request = new XMLHttpRequest();
    request.open('get','http://127.0.0.1:8000/blob/video/RtEK1a-1597323526-hd3s18tJxtSN-5f3539069c3983-07854345');
    request.onload = function() {
        var blob = new Blob(request.responseText, {type: 'video/mp4'});
        temp.src = URL.createObjectURL(blob);
    }
    request.send();

}*/