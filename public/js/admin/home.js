function ShowSubscriptionsOfThisUser(id,generelErorrMsg,emptyMassege,lang) {
    ajaxRequest('get','../../admin/home/' + id + '/getSubscriptionsOfUser',null,function(jsonResponse){
        if(jsonResponse == null) {
            showPopUpMassage(generelErorrMsg);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
            if(jsonResponse.status) {
                if(DivOfThisUser != null && jsonResponse.data != null) {
                    if(jsonResponse.data.length > 0) {
                        renderSubscriptionsOfThisUser(jsonResponse.data,lang,generelErorrMsg);
                        DivOfThisUser.setAttribute('style','display:block;');
                    } else {
                        showPopUpMassage(emptyMassege);
                    }
                } else {
                    showPopUpMassage(generelErorrMsg);
                }
                return;
            }
            if(jsonResponse.hasOwnProperty('msg')) {
                showPopUpMassage(jsonResponse.msg);
                return;
            }
        }
        showPopUpMassage(generelErorrMsg);
        return;
    });
}
function renderSubscriptionsOfThisUser(data,lang,generelErorrMsg) {
    var temp = document.getElementById('OfThisUser');
    if(temp != null) {
        if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].removeChild(temp);
    }
    temp = document.createElement('div');
    temp.setAttribute('id','OfThisUser');
    for(subscription in data) {
        var playlist = document.createElement('div'),
            poster = document.createElement('img'),
            playlistData = document.createElement('div'),
            title = document.createElement('span'),
            price = document.createElement('span'),
            access = document.createElement('span');
        if(data[subscription].hasOwnProperty('playlist')){
            if(data[subscription].playlist.hasOwnProperty('poster'))poster.setAttribute('src',data[subscription].playlist.poster);
            else poster.setAttribute('src','../../images/static/playlist-default.png');
            if(data[subscription].playlist.hasOwnProperty('title'))title.textContent = data[subscription].playlist.title;
            if(data[subscription].playlist.hasOwnProperty('price'))price.textContent = data[subscription].playlist.price;
            if(data[subscription].hasOwnProperty('access') && data[subscription].hasOwnProperty('id')) {
                access.textContent = data[subscription].access ? (lang[0]) : (lang[1]);
                access.setAttribute('id','subscriptionAccess' + data[subscription].id);
                if(data[subscription].access) {
                    access.setAttribute('style','');
                } else {
                    access.setAttribute('style','background-color:red;');
                }
                access.onclick = new Function("RequestToggleUserPlaylistAccess(" + data[subscription].id + ",'" + generelErorrMsg + "',['" + lang[0] + "','" + lang[1] + "']);");
            }
        }
        playlistData.appendChild(title);
        playlistData.appendChild(price);
        playlistData.appendChild(access);
        playlist.appendChild(poster);
        playlist.appendChild(playlistData);
        playlist.setAttribute('class','playlist no-select');
        temp.appendChild(playlist);
    }

    if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].appendChild(temp);
}
function RequestToggleUserPlaylistAccess(subscriptionId,generelErorrMsg,lang) {
    ajaxRequest('get','../../admin/home/' + subscriptionId + '/toggleUserPlaylistAccess',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('access')) {
                    result = jsonResponse.data.access;
                    var access = document.getElementById('subscriptionAccess' + subscriptionId);
                    if(result != null) {
                        if(access != null) {
                            if(result) {
                                access.textContent = lang[0];
                                access.setAttribute('style','');
                            } else {
                                access.textContent = lang[1];
                                access.setAttribute('style','background-color:red;');
                            }
                        }
                        return;
                    }
                }
            }
        }
        showPopUpMassage(generelErorrMsg);
    });
}


function ShowOpinionsOfThisUser(id,generelErorrMsg,lang) {
    ajaxRequest('get','../../admin/home/' + id + '/getOpinionsOfUser',null,function(jsonResponse){
        if(jsonResponse == null) {
            showPopUpMassage(generelErorrMsg);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
            if(jsonResponse.status) {
                if(DivOfThisUser != null && jsonResponse.data != null) {
                    if(jsonResponse.data.hasOwnProperty('coachOpinions') && jsonResponse.data.hasOwnProperty('playlistOpinions')) {
                        renderOpinionsOfThisUser(jsonResponse.data,lang,generelErorrMsg);
                        DivOfThisUser.setAttribute('style','display:block;');
                    } else {
                        showPopUpMassage(generelErorrMsg);
                    }
                } else {
                    showPopUpMassage(generelErorrMsg);
                }
                return;
            }
            if(jsonResponse.hasOwnProperty('msg')) {
                showPopUpMassage(jsonResponse.msg);
                return;
            }
        }
        showPopUpMassage(generelErorrMsg);
        return;
    });
}
function ShowCommentsAndReplaysOfThisUser(id,generelErorrMsg,lang) {
    ajaxRequest('get','../../admin/home/' + id + '/getCommentsAndReplaysOfThisUser',null,function(jsonResponse){
        if(jsonResponse == null) {
            showPopUpMassage(generelErorrMsg);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
            if(jsonResponse.status) {
                if(DivOfThisUser != null && jsonResponse.data != null) {
                    if(jsonResponse.data.hasOwnProperty('comments') && jsonResponse.data.hasOwnProperty('replays')) {
                        renderCommentsAndReplaysOfThisUser(jsonResponse.data,lang,generelErorrMsg);
                        DivOfThisUser.setAttribute('style','display:block;');
                    } else {
                        showPopUpMassage(generelErorrMsg);
                    }
                } else {
                    showPopUpMassage(generelErorrMsg);
                }
                return;
            }
            if(jsonResponse.hasOwnProperty('msg')) {
                showPopUpMassage(jsonResponse.msg);
                return;
            }
        }
        showPopUpMassage(generelErorrMsg);
        return;
    });
}
function ShowViewsOfThisUser(id,generelErorrMsg,emptyMassege,lang) {
    ajaxRequest('get','../../admin/home/' + id + '/getViewsOfUser',null,function(jsonResponse){
        if(jsonResponse == null) {
            showPopUpMassage(generelErorrMsg);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
            if(jsonResponse.status) {
                if(DivOfThisUser != null && jsonResponse.data != null) {
                    if(jsonResponse.data.length > 0) {
                        renderViewsOfThisUser(jsonResponse.data,lang);
                        DivOfThisUser.setAttribute('style','display:block;');
                    } else {
                        showPopUpMassage(emptyMassege);
                    }
                } else {
                    showPopUpMassage(generelErorrMsg);
                }
                return;
            }
            if(jsonResponse.hasOwnProperty('msg')) {
                showPopUpMassage(jsonResponse.msg);
                return;
            }
        }
        showPopUpMassage(generelErorrMsg);
        return;
    });
}
function renderViewsOfThisUser(data,lang) {
    var temp = document.getElementById('OfThisUser');
    if(temp != null) {
        if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].removeChild(temp);
    }
    temp = document.createElement('div');
    temp.setAttribute('id','OfThisUser');
    for(view in data) {
        var video = document.createElement('div'),
            poster = document.createElement('img'),
            videoData = document.createElement('div'),
            title1 = document.createElement('span'),
            title2 = document.createElement('span'),
            views = document.createElement('span');

        if(data[view].hasOwnProperty('poster'))poster.setAttribute('src','../..' + data[view].poster);
        else poster.setAttribute('src','../../images/static/video-default.jpg');
        if(data[view].hasOwnProperty('title1'))title1.textContent = lang[0] + ': '  + data[view].title1;
        if(data[view].hasOwnProperty('title2'))title2.textContent = lang[1] + ': '  + data[view].title2;
        if(data[view].hasOwnProperty('count')) {
            views.textContent = lang[2] + ': ' + data[view].count;
        }
        videoData.appendChild(title1);
        videoData.appendChild(title2);
        videoData.appendChild(views);
        video.appendChild(poster);
        video.appendChild(videoData);
        video.setAttribute('class','playlist video no-select');
        temp.appendChild(video);
    }
    if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].appendChild(temp);
}
function ShowDivicesOfThisUser(id,generelErorrMsg,emptyMassege) {
    ajaxRequest('get','../../admin/home/' + id + '/getDivicesOfUser',null,function(jsonResponse){
        if(jsonResponse == null) {
            showPopUpMassage(generelErorrMsg);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
            if(jsonResponse.status) {
                if(DivOfThisUser != null && jsonResponse.data != null) {
                    if(jsonResponse.data.length > 0) {
                        renderDivicesOfThisUser(jsonResponse.data);
                        DivOfThisUser.setAttribute('style','display:block;');
                    } else {
                        showPopUpMassage(emptyMassege);
                    }
                } else {
                    showPopUpMassage(generelErorrMsg);
                }
                return;
            }
            if(jsonResponse.hasOwnProperty('msg')) {
                showPopUpMassage(jsonResponse.msg);
                return;
            }
        }
        showPopUpMassage(generelErorrMsg);
        return;
    });
}
function renderDivicesOfThisUser(data) {
    var temp = document.getElementById('OfThisUser');
    if(temp != null) {
        if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].removeChild(temp);
    }
    temp = document.createElement('div');
    temp.setAttribute('id','OfThisUser');
    temp.setAttribute('class','table-responsive');
    var table = document.createElement('table'),
        thead = document.createElement('thead'),
        tr = document.createElement('tr'),
        th1 = document.createElement('th'),
        th2 = document.createElement('th'),
        th3 = document.createElement('th'),
        tbody = document.createElement('tbody');
    table.setAttribute('class','table table-striped');
    th1.textContent = 'ip_address';
    th2.textContent = 'mac_address';
    th3.textContent = 'device_data';
    tr.appendChild(th1);
    tr.appendChild(th2);
    tr.appendChild(th3);
    thead.appendChild(tr);
    table.appendChild(thead);
    for(divice in data) {
        var tr = document.createElement('tr'),
            td1 = document.createElement('td'),
            td2 = document.createElement('td'),
            td3 = document.createElement('td');
        if(data[divice].hasOwnProperty('ip_address'))td1.textContent = data[divice].ip_address;
        if(data[divice].hasOwnProperty('mac_address'))td2.textContent = data[divice].mac_address;
        if(data[divice].hasOwnProperty('device_data'))td3.textContent = data[divice].device_data;
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tbody.appendChild(tr);
    }
    table.appendChild(tbody);
    temp.appendChild(table);
    if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].appendChild(temp);
}
function ShowSessionsOnlineOfThisUser(id,generelErorrMsg,emptyMassege,lang) {
    ajaxRequest('get','../../admin/home/' + id + '/getSessionsOnlineOfUser',null,function(jsonResponse){
        if(jsonResponse == null) {
            showPopUpMassage(generelErorrMsg);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
            if(jsonResponse.status) {
                if(DivOfThisUser != null && jsonResponse.data != null) {
                    if(jsonResponse.data.length > 0) {
                        renderSessionsOnlinesOfThisUser(jsonResponse.data,lang,generelErorrMsg);
                        DivOfThisUser.setAttribute('style','display:block;');
                    } else {
                        showPopUpMassage(emptyMassege);
                    }
                } else {
                    showPopUpMassage(generelErorrMsg);
                }
                return;
            }
            if(jsonResponse.hasOwnProperty('msg')) {
                showPopUpMassage(jsonResponse.msg);
                return;
            }
        }
        showPopUpMassage(generelErorrMsg);
        return;
    });
}
function renderSessionsOnlinesOfThisUser(data,lang,generelErorrMsg) {
    var temp = document.getElementById('OfThisUser');
    if(temp != null) {
        if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].removeChild(temp);
    }
    temp = document.createElement('div');
    temp.setAttribute('id','OfThisUser');

    for(session in data) {
        var sessionDiv = document.createElement('div'),
            time = document.createElement('p'),
            offerName = document.createElement('p'),
            offerPrice = document.createElement('p'),
            admissionButton = document.createElement('button');
        
        if(data[session].hasOwnProperty('offerName')) {
            offerName.textContent = lang[0] + ': ' + data[session].offerName;
        }
        if(data[session].hasOwnProperty('offerPrice')) {
            offerPrice.textContent = lang[1] + ': ' + data[session].offerPrice;
        }
        if(data[session].hasOwnProperty('time')) {
            time.textContent = lang[2] + ': ' + data[session].time;
        }
        if(data[session].hasOwnProperty('admission') && data[session].hasOwnProperty('id')){
            admissionButton.setAttribute('id','SessionOnlineAdmissionButton' + data[session].id);
            admissionButton.textContent = data[session].admission ? lang[4] : lang[5];
            admissionButton.onclick = new Function("RequestSetAdmission(" + data[session].id + "," + data[session].admission + ",'" + generelErorrMsg + "',['" + lang[4] + "','" + lang[5] + "','" + lang[6] + "','" + lang[7] + "','" + lang[8] + "']);");
        }
        sessionDiv.appendChild(offerName);
        sessionDiv.appendChild(offerPrice);
        sessionDiv.appendChild(time);
        sessionDiv.appendChild(admissionButton);
        if(data[session].hasOwnProperty('taken')) {
            if(data[session].taken) {
                var taken = document.createElement('span')
                    first = document.createElement('span');
                
                first.setAttribute('style','display:none;');
                taken.setAttribute('style','color:red;');
                taken.textContent = lang[3];
                sessionDiv.appendChild(first);
                sessionDiv.appendChild(taken);
                admissionButton.onclick = function() {
                    showPopUpMassage(lang[9]);
                };
            }
        }
        sessionDiv.setAttribute('class','playlist-opinion-of-user no-select');
        temp.appendChild(sessionDiv);
    }

    if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].appendChild(temp);
}
function RequestSetAdmission(SessionOnlineId,admission,generelErorrMsg,lang) {
    if(admission) msg = lang[3];
    else msg = lang[2];
    showPopUpMassage(msg,null,function (exitThis,popUpMassageDiv) {
        ajaxRequest('get','../../admin/home/' + SessionOnlineId + '/setAdmission',null,function(jsonResponse) {
            if(jsonResponse != null) {
                if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                    if(jsonResponse.status && jsonResponse.data.hasOwnProperty('admission')) {
                        var admissionButton = document.getElementById('SessionOnlineAdmissionButton' + SessionOnlineId);
                        result = jsonResponse.data.admission;
                        if(result != null) {
                            if(admissionButton != null) {
                                if(result) {
                                    admissionButton.textContent = lang[0];
                                } else {
                                    admissionButton.textContent = lang[1];
                                }
                                admissionButton.onclick = new Function("RequestSetAdmission(" + SessionOnlineId + "," + result + ",'" + generelErorrMsg + "',['" + lang[0] + "','" + lang[1] + "','" + lang[2] + "','" + lang[3] + "','" + lang[4] + "']);");
                            }
                            return;
                        }
                    } else if( jsonResponse.status == false && jsonResponse.hasOwnProperty('msg')) {
                        showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
            }
            showPopUpMassage(generelErorrMsg);
        });
        exitThis(popUpMassageDiv);
    },lang[4]);
}


function renderOpinionsOfThisUser(data,lang,generelErorrMsg) {
    var temp = document.getElementById('OfThisUser');
    if(temp != null) {
        if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].removeChild(temp);
    }
    temp = document.createElement('div');
    temp.setAttribute('id','OfThisUser');

    var nav = document.createElement('nav'),
        section1 = document.createElement('section'),
        section1span = document.createElement('span'),
        section2 = document.createElement('section'),
        section2span = document.createElement('span'),
        div1 = document.createElement('div'),
        div2 = document.createElement('div'),
        emptyCoachOpinions = document.createElement('div'),
        emptyPlaylistOpinions = document.createElement('div') ;

    emptyCoachOpinions.setAttribute('class','empty no-select');
    emptyPlaylistOpinions.setAttribute('class','empty no-select');

    section1.setAttribute('class','template-section header-of-admin-home-selected-section no-select');
    section2.setAttribute('class','template-section no-select');
    nav.setAttribute('class','header-of-admin-home');

    section1span.textContent = lang[0];
    section2span.textContent = lang[1];

    emptyCoachOpinions.textContent = lang[2];
    emptyPlaylistOpinions.textContent = lang[3];

    if(data.coachOpinions != null) {
        if(data.coachOpinions.length > 0) {
            for(coachOpinion in data.coachOpinions) {
                var opinion = document.createElement('div'),
                    content = document.createElement('p'),
                    time = document.createElement('span'),
                    allowButton = document.createElement('button');
                opinion.setAttribute('class','coach-opinion-of-user');
                if(data.coachOpinions[coachOpinion].hasOwnProperty('content')) {
                    content.textContent = data.coachOpinions[coachOpinion].content;
                }
                if(data.coachOpinions[coachOpinion].hasOwnProperty('time')) {
                    time.textContent = data.coachOpinions[coachOpinion].time;
                }
                opinion.appendChild(content);
                opinion.appendChild(time);
                if(data.coachOpinions[coachOpinion].hasOwnProperty('allow') && data.coachOpinions[coachOpinion].hasOwnProperty('id')){
                    allowButton.textContent = data.coachOpinions[coachOpinion].allow ? (lang[4]) : (lang[5]);
                    allowButton.setAttribute('id','allowButtonCoachOpinion' + data.coachOpinions[coachOpinion].id);
                    allowButton.onclick = new Function("RequestToggleAllowCoachOpinion(" + data.coachOpinions[coachOpinion].id + ",'" + generelErorrMsg +  "',['" + lang[4] + "','" + lang[5] + "']);");
                    opinion.appendChild(allowButton);
                }
                div1.appendChild(opinion);
            }
        } else {
            div1.appendChild(emptyCoachOpinions);
        }
    } else {
        div1.appendChild(emptyCoachOpinions);
    }
    if(data.playlistOpinions != null) {
        if(data.playlistOpinions.length > 0) {
            for(playlistOpinion in data.playlistOpinions) {
                var opinion = document.createElement('div'),
                    poster = document.createElement('img'),
                    playlistName = document.createElement('span'),
                    content = document.createElement('p'),
                    time = document.createElement('span'),
                    allowButton = document.createElement('button');
                
                opinion.setAttribute('class','playlist-opinion-of-user');
                if(data.playlistOpinions[playlistOpinion].hasOwnProperty('playlist')) {
                    if(data.playlistOpinions[playlistOpinion].playlist.hasOwnProperty('title')){
                        playlistName.textContent = lang[7] + ': ' + data.playlistOpinions[playlistOpinion].playlist.title;
                    }
                    if(data.playlistOpinions[playlistOpinion].playlist.hasOwnProperty('poster'))poster.setAttribute('src','../..' + data.playlistOpinions[playlistOpinion].playlist.poster);
                    else poster.setAttribute('src','../../images/static/playlist-default.png');
                }
                if(data.playlistOpinions[playlistOpinion].hasOwnProperty('content')) {
                    content.textContent = lang[6] + ': ' + data.playlistOpinions[playlistOpinion].content;
                }
                
                if(data.playlistOpinions[playlistOpinion].hasOwnProperty('time')) {
                    time.textContent = data.playlistOpinions[playlistOpinion].time;
                }
                opinion.appendChild(poster);
                opinion.appendChild(playlistName);
                opinion.appendChild(content);
                if(data.playlistOpinions[playlistOpinion].hasOwnProperty('allow') && data.playlistOpinions[playlistOpinion].hasOwnProperty('id')){
                    allowButton.textContent = data.playlistOpinions[playlistOpinion].allow ? (lang[4]) : (lang[5]);
                    allowButton.setAttribute('id','allowButtonplaylistOpinionId' + data.playlistOpinions[playlistOpinion].id);
                    allowButton.onclick = new Function("RequestToggleAllowPlaylistOpinion(" + data.playlistOpinions[playlistOpinion].id + ",'" + generelErorrMsg + "',['" + lang[4] + "','" + lang[5] + "']);");
                    opinion.appendChild(allowButton);
                }

                opinion.appendChild(time);

                div2.appendChild(opinion);
            }
        } else {
            div2.appendChild(emptyPlaylistOpinions);
        }
    } else {
        div2.appendChild(emptyPlaylistOpinions);
    }

    div2.setAttribute('style','display:none;');
    section1.onclick = function() {
        section1.setAttribute('class','template-section header-of-admin-home-selected-section no-select');
        section2.setAttribute('class','template-section no-select');
        div1.setAttribute('style','');
        div2.setAttribute('style','display:none;');
    }
    section2.onclick = function() {
        section1.setAttribute('class','template-section no-select');
        section2.setAttribute('class','template-section header-of-admin-home-selected-section no-select');
        div1.setAttribute('style','display:none;');
        div2.setAttribute('style','');
    }
    section1.appendChild(section1span);
    section2.appendChild(section2span);

    nav.appendChild(section1);
    nav.appendChild(section2);

    temp.appendChild(nav);
    temp.appendChild(div1);
    temp.appendChild(div2);

    if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].appendChild(temp);
}

function renderCommentsAndReplaysOfThisUser(data,lang,generelErorrMsg) {
    var temp = document.getElementById('OfThisUser');
    if(temp != null) {
        if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].removeChild(temp);
    }
    temp = document.createElement('div');
    temp.setAttribute('id','OfThisUser');
    var nav = document.createElement('nav'),
        section1 = document.createElement('section'),
        section1span = document.createElement('span'),
        section2 = document.createElement('section'),
        section2span = document.createElement('span'),
        div1 = document.createElement('div'),
        div2 = document.createElement('div'),
        emptyComments = document.createElement('div'),
        emptyReplays = document.createElement('div') ;

        emptyComments.setAttribute('class','empty no-select');
        emptyReplays.setAttribute('class','empty no-select');

    section1.setAttribute('class','template-section header-of-admin-home-selected-section no-select');
    section2.setAttribute('class','template-section no-select');
    nav.setAttribute('class','header-of-admin-home');

    section1span.textContent = lang[0];
    section2span.textContent = lang[1];

    emptyComments.textContent = lang[2];
    emptyReplays.textContent = lang[3];

    if(data.comments != null) {
        if(data.comments.length > 0) {
            for(comment in data.comments) {
                var commentDiv = document.createElement('div'),
                    content = document.createElement('p'),
                    poster = document.createElement('img'),
                    playlistName = document.createElement('span'),
                    time = document.createElement('span'),
                    allowButton = document.createElement('button');
                
                commentDiv.setAttribute('class','playlist-opinion-of-user');
                if(data.comments[comment].hasOwnProperty('playlist')) {
                    if(data.comments[comment].playlist.hasOwnProperty('title')){
                        playlistName.textContent = lang[7] + ': ' + data.comments[comment].playlist.title;
                    }
                    if(data.comments[comment].playlist.hasOwnProperty('poster'))poster.setAttribute('src','../..' + data.comments[comment].playlist.poster);
                    else poster.setAttribute('src','../../images/static/playlist-default.png');
                }
                
                if(data.comments[comment].hasOwnProperty('content')) {
                    content.textContent = lang[6] + ': ' + data.comments[comment].content;
                }
                if(data.comments[comment].hasOwnProperty('time')) {
                    time.textContent = data.comments[comment].time;
                }
                commentDiv.appendChild(poster);
                commentDiv.appendChild(playlistName);
                commentDiv.appendChild(content);
                if(data.comments[comment].hasOwnProperty('allow') && data.comments[comment].hasOwnProperty('id')){
                    allowButton.textContent = data.comments[comment].allow ? (lang[4]) : (lang[5]);
                    allowButton.setAttribute('id','allowButtonComment' + data.comments[comment].id);
                    allowButton.onclick = new Function("RequestToggleAllowComment(" + data.comments[comment].id + ",'" + generelErorrMsg +  "',['" + lang[4] + "','" + lang[5] + "']);");
                    commentDiv.appendChild(allowButton);
                }
                commentDiv.appendChild(time);

                div1.appendChild(commentDiv);
            }
        } else {
            div1.appendChild(emptyComments);
        }
    } else {
        div1.appendChild(emptyComments);
    }
    if(data.replays != null) {
        if(data.replays.length > 0) {
            for(replay in data.replays) {
                var replayDiv = document.createElement('div'),
                    content = document.createElement('p'),
                    userOfCommentName = document.createElement('span'),
                    time = document.createElement('span'),
                    allowButton = document.createElement('button');
                
                replayDiv.setAttribute('class','playlist-opinion-of-user');
                if(data.replays[replay].hasOwnProperty('userOfCommentName')){
                    userOfCommentName.textContent = lang[9] + ': ' + data.replays[replay].userOfCommentName;
                }
                if(data.replays[replay].hasOwnProperty('content')) {
                    content.textContent = lang[8] + ': ' + data.replays[replay].content;
                }
                if(data.replays[replay].hasOwnProperty('time')) {
                    time.textContent = data.replays[replay].time;
                }
                replayDiv.appendChild(userOfCommentName);
                replayDiv.appendChild(content);
                if(data.replays[replay].hasOwnProperty('allow') && data.replays[replay].hasOwnProperty('id')){
                    allowButton.textContent = data.replays[replay].allow ? (lang[4]) : (lang[5]);
                    allowButton.setAttribute('id','allowButtonReplay' + data.replays[replay].id);
                    allowButton.onclick = new Function("RequestToggleAllowReplay(" + data.replays[replay].id + ",'" + generelErorrMsg +  "',['" + lang[4] + "','" + lang[5] + "']);");
                    replayDiv.appendChild(allowButton);
                }
                replayDiv.appendChild(time);

                div2.appendChild(replayDiv);
            }
        } else {
            div2.appendChild(emptyReplays);
        }
    } else {
        div2.appendChild(emptyReplays);
    }

    div2.setAttribute('style','display:none;');
    section1.onclick = function() {
        section1.setAttribute('class','template-section header-of-admin-home-selected-section no-select');
        section2.setAttribute('class','template-section no-select');
        div1.setAttribute('style','');
        div2.setAttribute('style','display:none;');
    }
    section2.onclick = function() {
        section1.setAttribute('class','template-section no-select');
        section2.setAttribute('class','template-section header-of-admin-home-selected-section no-select');
        div1.setAttribute('style','display:none;');
        div2.setAttribute('style','');
    }

    section1.appendChild(section1span);
    section2.appendChild(section2span);

    nav.appendChild(section1);
    nav.appendChild(section2);

    temp.appendChild(nav);
    temp.appendChild(div1);
    temp.appendChild(div2);

    if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].appendChild(temp);
}

function RequestToggleAllowCoachOpinion(coachOpinionId,generelErorrMsg,lang) {
    ajaxRequest('get','../../admin/home/' + coachOpinionId + '/toggleAllowCoachOpinion',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                    result = jsonResponse.data.allow;
                    var allowButton = document.getElementById('allowButtonCoachOpinion' + coachOpinionId);
                    if(result != null) {
                        if(allowButton != null) {
                            if(result) {
                                allowButton.textContent = lang[0];
                            } else {
                                allowButton.textContent = lang[1];
                            }
                        }
                        return;
                    }
                }
            }
        }
        showPopUpMassage(generelErorrMsg);
    });
}
function RequestToggleAllowPlaylistOpinion(playlistOpinionId,generelErorrMsg,lang) {
    ajaxRequest('get','../../admin/home/' + playlistOpinionId + '/toggleAllowPlaylistOpinion',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                    result = jsonResponse.data.allow;
                    var allowButton = document.getElementById('allowButtonplaylistOpinionId' + playlistOpinionId);
                    if(result != null) {
                        if(result) {
                            allowButton.textContent = lang[0];
                        } else {
                            allowButton.textContent = lang[1];
                        }
                        return;
                    }
                }
            }
        }
        showPopUpMassage(generelErorrMsg);
    });
}
function RequestToggleAllowComment(commentId,generelErorrMsg,lang) {
    ajaxRequest('get','../../admin/home/' + commentId + '/toggleAllowComment',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                    var allowButton = document.getElementById('allowButtonComment' + commentId);
                    result = jsonResponse.data.allow;
                    if(result != null) {
                        if(allowButton != null) {
                            if(result) {
                                allowButton.textContent = lang[0];
                            } else {
                                allowButton.textContent = lang[1];
                            }
                        }
                        return;
                    }
                }
            }
        }
        showPopUpMassage(generelErorrMsg);
    });
}
function RequestToggleAllowReplay(replayId,generelErorrMsg,lang) {
    ajaxRequest('get','../../admin/home/' + replayId + '/toggleAllowReplay',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                    var allowButton = document.getElementById('allowButtonReplay' + replayId);
                    result = jsonResponse.data.allow;
                    if(result != null) {
                        if(allowButton != null) {
                            if(result) {
                                allowButton.textContent = lang[0];
                            } else {
                                allowButton.textContent = lang[1];
                            }
                        }
                        return;
                    }
                }
            }
        }
        showPopUpMassage(generelErorrMsg);
    });
}
function ShowUsersOfThisPlaylist(path,generelErorrMsg,lang) {
    ajaxRequest('get',path,null,function(jsonResponse){
        if(jsonResponse == null) {
            showPopUpMassage(generelErorrMsg);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
            if(jsonResponse.status) {
                if(DivOfThisUser != null && jsonResponse.data != null) {
                    if(jsonResponse.data.hasOwnProperty('data')) {
                        if(jsonResponse.data.data.length > 0) {
                            renderUsersOfThisPlaylist(jsonResponse.data,lang,generelErorrMsg);
                            DivOfThisUser.setAttribute('style','display:block;');
                        } else {
                            showPopUpMassage(generelErorrMsg);
                        }
                    } else {
                        showPopUpMassage(generelErorrMsg);
                    }
                } else {
                    showPopUpMassage(generelErorrMsg);
                }
                return;
            }
            if(jsonResponse.hasOwnProperty('msg')) {
                showPopUpMassage(jsonResponse.msg);
                return;
            }
        }
        showPopUpMassage(generelErorrMsg);
        return;
    });
}
function renderUsersOfThisPlaylist(data,lang,generelErorrMsg) {
    var temp = document.getElementById('OfThisUser');
    if(temp != null) {
        if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].removeChild(temp);
    }
    temp = document.createElement('div');
    temp.setAttribute('id','OfThisUser');

    for(userSub in data.data) {
        var userSubDiv = document.createElement('div'),
            userSubDiv1 = document.createElement('div'),
            userSubDiv1Span1 = document.createElement('span'),
            userSubDiv1Span2 = document.createElement('span'),
            userSubDiv2 = document.createElement('div'),
            userSubDiv2Span1 = document.createElement('span'),
            userSubDiv2Span2 = document.createElement('span'),
            userSubDiv3 = document.createElement('div'),
            pullPlaylistOfThisUser = document.createElement('a');
        userSubDiv.setAttribute('class','user-div');

        userSubDiv1Span1.textContent = lang[0];
        if(data.data[userSub].hasOwnProperty('user')) {
            if(data.data[userSub].user.hasOwnProperty('first_name'))userSubDiv1Span2.textContent = data.data[userSub].user.first_name;
            else userSubDiv1Span2.textContent = '';
            if(data.data[userSub].user.hasOwnProperty('second_name'))userSubDiv1Span2.textContent += ' ' + data.data[userSub].user.second_name;;
            if(data.data[userSub].user.hasOwnProperty('last_name'));userSubDiv1Span2.textContent += ' ' + data.data[userSub].user.last_name;
            
            userSubDiv2Span1.textContent = lang[1];
            if(data.data[userSub].user.hasOwnProperty('email'))userSubDiv2Span2.textContent = data.data[userSub].user.email;
        }
        if(data.data[userSub].hasOwnProperty('access')) {
            pullPlaylistOfThisUser.textContent = data.data[userSub].access ? lang[2] : lang[3];
            if(data.data[userSub].hasOwnProperty('id')) {
                pullPlaylistOfThisUser.setAttribute('id','subscriptionAccess' + data.data[userSub].id);
                pullPlaylistOfThisUser.onclick = new Function("RequestToggleUserPlaylistAccess(" + data.data[userSub].id + ",'" + generelErorrMsg + "',['" + lang[2] + "','" + lang[3] + "']);");
            }
        }
        userSubDiv1.appendChild(userSubDiv1Span1);
        userSubDiv1.appendChild(userSubDiv1Span2);
        userSubDiv2.appendChild(userSubDiv2Span1);
        userSubDiv2.appendChild(userSubDiv2Span2);

        userSubDiv3.appendChild(pullPlaylistOfThisUser);

        userSubDiv.appendChild(userSubDiv1);
        userSubDiv.appendChild(userSubDiv2);
        userSubDiv.appendChild(userSubDiv3);
        temp.appendChild(userSubDiv);
    }

    if(data.hasOwnProperty('total') && data.hasOwnProperty('path') && data.hasOwnProperty('current_page')) {
        if(data.total > 1) {
            var userSubLinks = document.getElementById('userSubLinks');
            if(userSubLinks != null)temp.removeChild(userSubLinks);
            userSubLinks = makePaginationLinks(data,'us');
            if(userSubLinks != null)temp.appendChild(userSubLinks);
        }
    }
    if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].appendChild(temp);
}
function renderVisiters(data) {
    var temp = document.getElementById('OfThisUser');
    if(temp != null) {
        if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].removeChild(temp);
    }
    temp = document.createElement('div');
    temp.setAttribute('id','OfThisUser');
    var table = document.createElement('table'),
        thead = document.createElement('thead'),
        tr = document.createElement('tr'),
        th1 = document.createElement('th'),
        th2 = document.createElement('th'),
        th3 = document.createElement('th'),
        tbody = document.createElement('tbody');
    table.setAttribute('class','table table-striped');
    th1.textContent = 'ip_address';
    th2.textContent = 'mac_address';
    th3.textContent = 'device_data';
    tr.appendChild(th1);
    tr.appendChild(th2);
    tr.appendChild(th3);
    thead.appendChild(tr);
    table.appendChild(thead);
    for(visiter in data.data) {
        var tr = document.createElement('tr'),
            td1 = document.createElement('td'),
            td2 = document.createElement('td'),
            td3 = document.createElement('td');
        if(data.data[visiter].hasOwnProperty('ip_address'))td1.textContent = data.data[visiter].ip_address;
        if(data.data[visiter].hasOwnProperty('mac_address'))td2.textContent = data.data[visiter].mac_address;
        if(data.data[visiter].hasOwnProperty('device_data'))td3.textContent = data.data[visiter].device_data;
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tbody.appendChild(tr);
    }
    table.appendChild(tbody);
    temp.appendChild(table);
    if(data.hasOwnProperty('total') && data.hasOwnProperty('path') && data.hasOwnProperty('current_page')) {
        if(data.total > 1) {
            var visiterLinks = document.getElementById('visiterLinks');
            if(visiterLinks != null)temp.removeChild(visiterLinks);
            visiterLinks = makePaginationLinks(data,'v');
            if(visiterLinks != null)temp.appendChild(visiterLinks);
        }
    }
    if(DivOfThisUser.children.length > 1)DivOfThisUser.children[1].appendChild(temp);
}