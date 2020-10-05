var header = document.getElementById('header'),
    topSection = document.getElementById('topSection')
    madeHeaderFixed = false;
window.onload = function () {
    header.setAttribute('class', 'no-select');
    handlerScrollWindow();
    if(header != null && topSection != null) {
        window.onscroll = handlerScrollWindow;
    }
};
function handlerScrollWindow () {
    if(window.scrollY - topSection.scrollHeight > 0 && ! madeHeaderFixed) {
        header.setAttribute('class', 'header-after-scroll-down no-select');
        madeHeaderFixed = true;
    } else if(window.scrollY - topSection.scrollHeight <= 0 && madeHeaderFixed) {
        header.setAttribute('class', 'no-select');
        madeHeaderFixed = false;
    }
}
function openPlaylistTemplate(id) {
    if(typeof(playlistTemplate) == "undefined") {
        showPopUpMassage(playlistNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    if(playlistTemplate == null) {
        showPopUpMassage(playlistNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    renderDataInPlaylistTemplate(id);
    playlistTemplate.style = "";
}