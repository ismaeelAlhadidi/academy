var headerNavCanvasButton = document.getElementById('headerNavCanvasButton');
    menuOfNavList = document.getElementById('menuOfNavList'),
    headerNavButton = document.getElementById('headerNavButton');

var backgroudColor = '#3499b8',
    color = '#FFFFFF';

if(headerNavCanvasButton != null) {
    headerNavCanvasButton.width = 35;
    headerNavCanvasButton.height = 35;
    drawNavButtonOpenButton(headerNavCanvasButton,backgroudColor,color);
    if(menuOfNavList != null) {
        menuOfNavList.style = "display: none;";
        if(headerNavButton != null) {
            headerNavButton.onclick = function () {
                if(menuOfNavList.style.display == "none") {
                    menuOfNavList.style = "display: block;";
                    drawNavButtonCloseButton(headerNavCanvasButton,backgroudColor,color);
                }
                else {
                    menuOfNavList.style = "display: none;";
                    drawNavButtonOpenButton(headerNavCanvasButton,backgroudColor,color);
                }
            };
        }
    }
}
function openPlaylistTemplate(id, title) {
    if(typeof(playlistTemplate) == "undefined") {
        showPopUpMassage(playlistNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    if(playlistTemplate == null) {
        showPopUpMassage(playlistNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    setTitleInPlaylistTemplate(title);
    setRouteInPlaylistTemplate(id);
    renderDataInPlaylistTemplate(id);
    playlistTemplate.style = "";
}
function setTitleInPlaylistTemplate(title) {
    var titleInPlaylistTemplate = document.getElementById('titleInPlaylistTemplate');
    if(titleInPlaylistTemplate == null) return;
    titleInPlaylistTemplate.textContent = title;
}
function setRouteInPlaylistTemplate(id) {
    var showButtonInPlaylistTemplate = document.getElementById('showButtonInPlaylistTemplate'),
        idInputOfSelectedPlaylistToPay = document.getElementById('idInputOfSelectedPlaylistToPay');
    if(showButtonInPlaylistTemplate != null) {
        showButtonInPlaylistTemplate.setAttribute('href', window.location.origin + '/playlist/' + id);
    }
    if(idInputOfSelectedPlaylistToPay != null) {
        idInputOfSelectedPlaylistToPay.value = id;
    }
}

function openSessionTemplate(id) {
    if(typeof(SessionTemplate) != "object") {
        showPopUpMassage(SessionNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    if(SessionTemplate == null) {
        showPopUpMassage(SessionNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    if(requestThisSession != null) requestThisSession.onclick = function () {
        openRequestToSessionTemplate(id);
        SessionTemplate.style = "display: none;";
    };
    renderDataInSessionTemplate(id);
    SessionTemplate.style = "";
}
function renderDataInSessionTemplate (id) {
    var sessionDataDiv = clearAndReturnSessionDataDiv();
    if(sessionDataDiv == null) return;
    ajaxRequest('get', window.location.origin + '/ajax/session-offer/' + id, null, function(jsonResponse) {
        if(jsonResponse == null) return;
        if(jsonResponse.hasOwnProperty('status')) {
            if(jsonResponse.status) {
                if(jsonResponse.hasOwnProperty('data')) {
                    if(typeof(jsonResponse.data) == "object") {
                        setContentToSessionDataDiv(jsonResponse.data,sessionDataDiv);
                    }
                }
            }
        }
    });
}
function clearAndReturnSessionDataDiv () {
    var sessionDataDiv = document.getElementById('sessionDataDiv');
    if(sessionDataDiv == null) return null;
    var tempParent = sessionDataDiv.parentElement;
    tempParent.removeChild(sessionDataDiv);
    sessionDataDiv = document.createElement('div');
    sessionDataDiv.setAttribute('id', 'sessionDataDiv');
    if(tempParent.children.length > 0) tempParent.insertBefore(sessionDataDiv, tempParent.children[0]);
    else return null;
    return sessionDataDiv;
}
function setContentToSessionDataDiv(data, div) {
    div.innerHTML = `<h2>${data.name != null ? data.name : ''}</h2>
                    ${data.for_who != null ? (
                        `<div><span>${sessionOfferLang[0]}</span><span>${data.for_who}</span></div>`
                    ) : ''}
                    ${data.for_who_not != null ? (
                        `<div><span>${sessionOfferLang[1]}</span><span>${data.for_who_not}</span></div>`
                    ) : ''}
                    ${data.benefits != null ? (
                        `<div><span>${sessionOfferLang[2]}</span><span>${data.benefits}</span></div>`
                    ) : ''}
                    ${data.notes != null ? (
                        `<div><span>${sessionOfferLang[3]}</span><span>${data.notes}</span></div>`
                    ) : ''}`;
}
function openRequestToSessionTemplate(id) {
    if(requestToSessionTemplate == null) return;
    requestToSessionTemplate.style = '';
    if(inputDateOfRequestToSession != null) inputDateOfRequestToSession.setAttribute('class', '');
    if(inputTimeOfRequestToSession != null) inputTimeOfRequestToSession.setAttribute('class', '');
    if(buttonSendRequestToSession != null) {
        buttonSendRequestToSession.onclick = function () {
            var formData = new FormData();
            if(inputDateOfRequestToSession != null) {
                formData.append('date', inputDateOfRequestToSession.value);
            }
            if(inputTimeOfRequestToSession != null) {
                formData.append('time', inputTimeOfRequestToSession.value);
            }
            formData.append('id', id);
            formData.append('_token', TOKEN);
            ajaxRequest('post', window.location.origin + '/ajax/session-offer/request', formData, function(jsonResponse) {
                if(jsonResponse == null) {
                    showPopUpMassage(SessionNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
                    return;
                }
                if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('msg')) {
                    if(jsonResponse.status) {
                        if(jsonResponse.hasOwnProperty('data')) {
                            if(typeof(jsonResponse.data) == "object") {
                                showPopUpMassage(jsonResponse.msg,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
                                inputDateOfRequestToSession.value = '';
                                inputTimeOfRequestToSession.value = '';
                                requestToSessionTemplate.style = 'display: none !important;';
                                return;
                            }
                        }
                    } else if(jsonResponse.hasOwnProperty('msg')) {
                        showPopUpMassage(jsonResponse.msg,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
                        if(jsonResponse.hasOwnProperty('data')) {
                            if(typeof(jsonResponse.data) == "object") {
                                if(jsonResponse.data.length == 1) {
                                    if(jsonResponse.data[0] == 'invalid') {
                                        inputDateOfRequestToSession.setAttribute('class', 'input-invalid');
                                        inputTimeOfRequestToSession.setAttribute('class', 'input-invalid');
                                    }
                                }
                            }
                        }
                        return;
                    }
                    showPopUpMassage(SessionNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
                }
            });
        }
    }
}
