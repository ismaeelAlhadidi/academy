var header = document.getElementById('header'),
    topSection = document.getElementById('topSection')
    madeHeaderFixed = false;
window.onload = function () {
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
function renderDataInPlaylistTemplate(id) {
    ajaxRequest (
        // ajax request to get opinoins of this play list and ask user to authenticate if it not
        // i need to make route of this request after make controller of welcome page 
        // then i need set data on template by perfect design and good UX 
        // then i will set hyper text refrence of links in this page 
        // then i will make default auth layout to make it very good 
        // then i will test it faster 
        // then i will push it to remotly repostory on git hub to save it 
        // and i need to learning new videos of ES 6 
        // then i will try to sleep deeply and long time 
        // if i can't sleep i will watch engilsh videos 
        // in general i am go to best way by develop myself  
    );
}