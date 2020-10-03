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
    var opinionsSection = cleanAndReturnOpinionsSection();
    if(opinionsSection == null) {
        return;
    }
    ajaxRequest('get', window.location.origin + '/getOpinionsOfPlaylist/' + id, null, function(jsonResponse) {
        if(jsonResponse == null) return;
        if(jsonResponse.hasOwnProperty('status')) {
            if(jsonResponse.status) {
                if(jsonResponse.hasOwnProperty('data')) {
                    if(typeof(jsonResponse.data) == "object") {
                        if(jsonResponse.data.length > 0) {
                            var opinionsHeader = document.getElementById("opinionsHeader");
                            if(opinionsHeader != null) opinionsHeader.setAttribute('style', 'display: block;');
                        }
                        for(var i = 0; i < jsonResponse.data.length; i++) {
                            var temp = createPlaylistOpinionElement (
                                window.location.origin + jsonResponse.data[i].profileImage, 
                                jsonResponse.data[i].name, 
                                jsonResponse.data[i].content
                            );
                            opinionsSection.appendChild(temp);
                        }
                    }
                }
            }
        }
    });
}
function cleanAndReturnOpinionsSection() {
    var opinionsSection = document.getElementById("opinionsSection");
        opinionsHeader = document.getElementById("opinionsHeader");
    if(opinionsSection != null) {
        var tempParent = opinionsSection.parentElement;
        tempParent.removeChild(opinionsSection);
    } else {
        var tempParent = document.getElementById('opinionsContainer');
        if(tempParent == null) return null;
    }
    opinionsSection = document.createElement('div');
    opinionsSection.setAttribute('id', 'opinionsSection');
    opinionsSection.setAttribute('class', 'opinions-inner-container');
    tempParent.appendChild(opinionsSection);
    if(opinionsHeader != null) opinionsHeader.setAttribute('style', 'display: none;');
    return opinionsSection;
}
function createPlaylistOpinionElement(profileImage, name, content) {
    var temp = document.createElement('div');
    temp.setAttribute('class', 'opinion');
    temp.innerHTML = `<div class="image"><img src="${profileImage}" title="${name}"/></div>
        <div class="content">
            <h3>${name}</h3>
            <p>${content}</p>
        </div>`;
   return temp;
}