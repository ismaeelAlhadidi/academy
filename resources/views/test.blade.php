<div style="text-align: center;"><video id="myVideo" controls src="http://127.0.0.1:8000/test/video" preload="auto" width="500" height="500">
</video></div>
<script>
    var video = document.getElementById('myVideo');
    var url = 'http://127.0.0.1:8000/test/video';
    var mediaSource = new MediaSource();
    if('MediaSource' in window) { //  && MediaSource.isTypeSupported()
        var blobUrl = window.URL.createObjectURL(mediaSource);
        video.src = blobUrl;
        mediaSource.onsourceopen = function () {
            var sourceBuffer = mediaSource.addSourceBuffer('video/mp4;codecs="avc1.4d001e,mp4a.40.2');
            ajaxGetVideoRequest(url, function (response) {
                sourceBuffer.addEventListener('updateend', function (_) {
                    if(mediaSource.readyState == "opend")mediaSource.endOfStream();
                });
                sourceBuffer.appendBuffer(response);
                video.play();
            }, 'askdnaijsnfjiasndiall');
        };
    } else {
        console.error('Unsupported MIME type or codec: ', mimeCodec);
    }
    function ajaxGetVideoRequest(url, onResponse, TOKEN) {
        var request = new XMLHttpRequest();
        var formData = new FormData();
        formData.append('_token', TOKEN);
        request.open('get', url);
        request.responseType = 'arraybuffer';
        request.onload = function () {
            onResponse(request.response);
        };
        request.send(formData);
    }
</script>