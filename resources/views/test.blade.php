<div style="text-align: center;">
    <video id="myVideo" controls width="500" height="500">
    </video>
</div>
<div><form method="post" enctype="multipart/form-data">@csrf<input type="file" name="image"/><input type="submit"/></form></div>
<script src="{{ asset('js/hls.js') }}"></script>
<script>
    var video = document.getElementById('myVideo');
    var url = 'http://127.0.0.1:8000/test/video';
    if (Hls.isSupported()) {
    var hls = new Hls();
    hls.loadSource(url);
    hls.attachMedia(video);
    hls.on(Hls.Events.MANIFEST_PARSED, function() {
      video.play();
    });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = url;
        video.addEventListener('loadedmetadata', function() {
            video.play();
        });
    }
    /* 
    $2y$10$HiyRi1Lyi0RKA/APBNRx0.XIh9SDFFETJ22Uo5E6oDG/NbPjT5hXu => esmaeel123
    test :
http://127.0.0.1:8000/object/video/kfJZpW5fa0714cb92cd4-81989122-1604350284_0_250_00000.ts
http://127.0.0.1:8000/object/video/x1crzm-1604350284-Oxyq44CSogBE-5fa0714cbe5604-93468597
    */
</script>