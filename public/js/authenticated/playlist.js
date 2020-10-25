var PlaylistOpinionTemplate = document.getElementById('PlaylistOpinionTemplate'),
    CoachOpinionTemplate = document.getElementById('CoachOpinionTemplate'),
    inputOpinionOfPlaylist = document.getElementById('inputOpinionOfPlaylist'),
    inputOpinionOfCoach = document.getElementById('inputOpinionOfCoach'),
    inputContentOfComment = document.getElementById('inputContentOfComment'),
    commentsContianer = document.getElementById('commentsContianer'),
    spanOfCountOfComment = document.getElementById('spanOfCountOfComment'),
    maxLengthOfVideoTitle = 55,
    maxNumberOfErrorInRequestToGetMoreComments = 3,
    errorInRequestToGetMoreComments = 0,
    getMoreCommentsURL = null,
    loadingComment = document.getElementById('loadingComment');

( function addEvents() {
    var tempAddPlaylistOpinionButton = document.getElementById('addPlaylistOpinionButton'),
        tempAddCoachOpinionButton = document.getElementById('addCoachOpinionButton'),
        tempSendOpinionOfPlaylistButton = document.getElementById('sendOpinionOfPlaylistButton');
        tempSendOpinionOfCoachButton = document.getElementById('sendOpinionOfCoachButton'),
        tempPostCommentButton = document.getElementById('postCommentButton'),
        tempOpenVideosButton = document.getElementById('openVideosButton'),
        tempOpenAudiosButton = document.getElementById('openAudiosButton'),
        tempOpenBooksButton = document.getElementById('openBooksButton'),
        tempVideos = document.getElementById('videos'),
        tempAudios = document.getElementById('audios'),
        tempBooks = document.getElementById('books');

    if(tempAddPlaylistOpinionButton != null) tempAddPlaylistOpinionButton.onclick = openPlaylistOpinionTemplate;
    if(tempAddCoachOpinionButton != null) tempAddCoachOpinionButton.onclick = openCoachOpinionTemplate;
    if(tempSendOpinionOfPlaylistButton != null) tempSendOpinionOfPlaylistButton.onclick = sendOpinionOfPlaylist;
    if(tempSendOpinionOfCoachButton != null) tempSendOpinionOfCoachButton.onclick = sendOpinionOfCoach;
    if(tempPostCommentButton != null) tempPostCommentButton.onclick = postComment;
    window.onscroll = handelScroll;
    window.ontouchmove = handelScroll;
    window.document.body.oncontextmenu = function (e) {
        e.preventDefault();
    };
    var openVideos = function openVideos() {
        if(tempOpenVideosButton != null) tempOpenVideosButton.setAttribute('class', 'selected-blob');
        if(tempOpenAudiosButton != null) tempOpenAudiosButton.setAttribute('class', '');
        if(tempOpenBooksButton != null) tempOpenBooksButton.setAttribute('class', '');
        if(tempVideos != null) tempVideos.style = '';
        if(tempAudios != null) tempAudios.style = 'display: none !important;';
        if(tempBooks != null) tempBooks.style = 'display: none !important;';
    };
    var openAudios = function openAudios() {
        if(tempOpenVideosButton != null) tempOpenVideosButton.setAttribute('class', '');
        if(tempOpenAudiosButton != null) tempOpenAudiosButton.setAttribute('class', 'selected-blob');
        if(tempOpenBooksButton != null) tempOpenBooksButton.setAttribute('class', '');
        if(tempVideos != null) tempVideos.style = 'display: none !important;';
        if(tempAudios != null) tempAudios.style = '';
        if(tempBooks != null) tempBooks.style = 'display: none !important;';
    };
    var openBooks = function openBooks() {
        if(tempOpenVideosButton != null) tempOpenVideosButton.setAttribute('class', '');
        if(tempOpenAudiosButton != null) tempOpenAudiosButton.setAttribute('class', '');
        if(tempOpenBooksButton != null) tempOpenBooksButton.setAttribute('class', 'selected-blob');
        if(tempVideos != null) tempVideos.style = 'display: none !important;';
        if(tempAudios != null) tempAudios.style = 'display: none !important;';
        if(tempBooks != null) tempBooks.style = '';
    };
    if(tempOpenVideosButton != null) tempOpenVideosButton.onclick = openVideos;
    if(tempOpenAudiosButton != null) tempOpenAudiosButton.onclick = openAudios;
    if(tempOpenBooksButton != null) tempOpenBooksButton.onclick = openBooks;
} )();
( function drawCanvas() {
    var tempExitButtonCanvasOfPlaylistOpinionTemplate = document.getElementById('exitButtonCanvasOfPlaylistOpinionTemplate'),
        tempExitButtonCanvasOfCoachOpinionTemplate = document.getElementById('exitButtonCanvasOfCoachOpinionTemplate');

    if(tempExitButtonCanvasOfPlaylistOpinionTemplate != null && PlaylistOpinionTemplate != null) {
        tempExitButtonCanvasOfPlaylistOpinionTemplate.width = 25;
        tempExitButtonCanvasOfPlaylistOpinionTemplate.height = 25;
        if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(tempExitButtonCanvasOfPlaylistOpinionTemplate,'#ffffff');
        tempExitButtonCanvasOfPlaylistOpinionTemplate.onclick = function () {
            if(typeof(closeBobUpTemplate) == "function") {
                if(inputOpinionOfPlaylist != null) inputOpinionOfPlaylist.setAttribute('class' , 'add-opinion-textarea');
                closeBobUpTemplate(PlaylistOpinionTemplate);
            }
        };
    }

    if(tempExitButtonCanvasOfCoachOpinionTemplate != null && CoachOpinionTemplate != null) {
        tempExitButtonCanvasOfCoachOpinionTemplate.width = 25;
        tempExitButtonCanvasOfCoachOpinionTemplate.height = 25;
        if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(tempExitButtonCanvasOfCoachOpinionTemplate,'#ffffff');
        tempExitButtonCanvasOfCoachOpinionTemplate.onclick = function () {
            if(typeof(closeBobUpTemplate) == "function") {
                if(inputOpinionOfCoach != null) inputOpinionOfCoach.setAttribute('class' , 'add-opinion-textarea');
                closeBobUpTemplate(CoachOpinionTemplate);
            }
        };
    }
} )();
( function editOverflowVideosTitle() {
    if(typeof(maxLengthOfVideoTitle) != "number") return;
    var temp = document.getElementsByClassName('edit-overflow-text');
    if(temp.length == 0) return;
    for(var i = 0; i < temp.length; i++) {
        if(temp[i].textContent.trim().length > maxLengthOfVideoTitle) {
            temp[i].textContent = temp[i].textContent.trim().slice(0, maxLengthOfVideoTitle-3) + '...';
        } else {
            temp[i].textContent = temp[i].textContent.trim();
        }
    }
})();
function openPlaylistOpinionTemplate() {
    if(PlaylistOpinionTemplate != null) PlaylistOpinionTemplate.style = "";
}
function openCoachOpinionTemplate() {
    if(CoachOpinionTemplate != null) CoachOpinionTemplate.style = "";
}
function sendOpinionOfPlaylist () {
    if(inputOpinionOfPlaylist == null) return;
    var formData = new FormData();
    if(inputOpinionOfPlaylist.value.trim() == '') {
        inputOpinionOfPlaylist.setAttribute('class', 'add-opinion-textarea input-invalid');
        return;
    }
    formData.append('content', inputOpinionOfPlaylist.value.trim());
    formData.append('_token', TOKEN);
    ajaxRequest('post', window.location.origin + '/ajax/post-opinion/playlist/' + PLAYLIST_ID, formData, function (jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status')) {
                if(jsonResponse.status) {
                    if(PlaylistOpinionTemplate != null) PlaylistOpinionTemplate.style = "display: none !important;";
                    inputOpinionOfPlaylist.setAttribute('class' , 'add-opinion-textarea');
                    inputOpinionOfPlaylist.value = '';
                    showPopUpMassage(lang.thanksForShareYourOpinion, null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
                    return;
                }
            }
        }
        showPopUpMassage(lang.generalError , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
    });
}
function sendOpinionOfCoach () {
    if(inputOpinionOfCoach == null) return;
    var formData = new FormData();
    if(inputOpinionOfCoach.value.trim() == '') {
        inputOpinionOfCoach.setAttribute('class', 'add-opinion-textarea input-invalid');
        return;
    }
    formData.append('content', inputOpinionOfCoach.value.trim());
    formData.append('_token', TOKEN);
    ajaxRequest('post', window.location.origin + '/ajax/post-opinion/coach/', formData, function (jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status')) {
                if(jsonResponse.status) {
                    if(CoachOpinionTemplate != null) CoachOpinionTemplate.style = "display: none !important;";
                    inputOpinionOfCoach.setAttribute('class' , 'add-opinion-textarea');
                    inputOpinionOfCoach.value = '';
                    showPopUpMassage(lang.thanksForShareYourOpinion, null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
                    return;
                }
            }
        }
        showPopUpMassage(lang.generalError , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
    });
}
function postComment() {
    if(inputContentOfComment == null) return;
    if(inputContentOfComment.value.trim().length < 1) {
        inputContentOfComment.setAttribute('class', 'add-opinion-textarea input-invalid');
        return;
    }
    if(inputContentOfComment.value.trim().length > 5000) {
        inputContentOfComment.setAttribute('class', 'add-opinion-textarea input-invalid');
        showPopUpMassage(lang.alertOfMaxSizeOfCharacter , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    var formData = new FormData();
    formData.append('_token', TOKEN);
    formData.append('content', inputContentOfComment.value.trim());
    ajaxRequest('post', window.location.origin + '/ajax/playlist/' + PLAYLIST_ID + '/post-comment/', formData, function(jsonResponse) {
        if(jsonResponse == null) {
            showPopUpMassage(lang.generalError , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
            inputContentOfComment.setAttribute('class', 'add-opinion-textarea');
            return;
        }
        if(jsonResponse.hasOwnProperty('status')) {
            if(jsonResponse.status && jsonResponse.hasOwnProperty('data')) {
                renderNewComment(jsonResponse.data);
                setNewCountOfComment();
                inputContentOfComment.value = '';
                inputContentOfComment.setAttribute('class', 'add-opinion-textarea');
                return;
            } else if(jsonResponse.hasOwnProperty('msg')) {
                if(jsonResponse.msg.trim() != '') {
                    inputContentOfComment.setAttribute('class', 'add-opinion-textarea');
                    showPopUpMassage(jsonResponse.msg , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
                    return;
                }
            }
        }
        showPopUpMassage(lang.generalError , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
    });
}
function renderNewComment(data) {
    if(commentsContianer == null) return;
    var comment = createCommentDiv(data);
    if(comment != null) {
        if(commentsContianer.children.length < 1) commentsContianer.appendChild(comment);
        else commentsContianer.insertBefore(comment, commentsContianer.children[0]);
    }
}
function createCommentDiv(data, notNew = false, isReplay = false) {
    if(typeof(data) != "object") return null;
    if(! data.hasOwnProperty('content') || ! data.hasOwnProperty('id')) return null;
    var commentId = data.id;
    var replays = '';
    if(notNew) {
        var image = data.image,
            name = data.name,
            time = data.time,
            userId = data.userId;
    }
    var comment = document.createElement('div');
    if(! isReplay) {
        if(data.hasOwnProperty('replays')) {
            if(typeof(data.replays) == "object" && data.replays != null) {
                for(var i = 0; i < data.replays.length; i++) {
                    var replay = createCommentDiv(data.replays[i], true, true);
                    if(replay != null) {
                        replays += replay.outerHTML;
                    }
                }
            }
        }
    }
    comment.innerHTML = `
                    <div class="no-select"><a href="${ profilePageURL + '/' + userId }"><img loading="lazy" src="${ (notNew) ? image : thisUser.image }"/></a></div>
                    <div>
                        <header class="no-select">${ (notNew) ? name : thisUser.name }</header>
                        <div><p>${ data.content }</p></div>
                        ${ (isReplay) ? '' 
                            : `<footer class="footer-with-replays no-select"><button id="replaysButtonOfComment${ commentId }" class="open-replays-button" onclick="openReplayOf(${ commentId })">${ lang.replays }</button><span>${ (notNew) ? time : lang.now }</span></footer>
                                <div id="replaysOfComment${ commentId }" class="replays-contianer" style="display: none !important;">
                                    ${ replays }
                                    <div id="postReplayElement${ commentId }" class="post-comment"><div class="comment replay post-replay">
                                    <div class="no-select"><a><img loading="lazy" src="${ thisUser.image }"/></a></div>
                                    <div>
                                        <div><textarea id="inputContentOfReplay${ commentId }" class="add-opinion-textarea" placeholder="${ lang.postReplay }"></textarea></div>
                                        <footer class="no-select"><button onclick="postReplay(${ commentId })" class="post-comment-button">${ lang.addReplay + ' '}<li class="material-icons">add</li></button></footer>
                                    </div>
                                    </div></div>
                            </div>`
                        }
                        </div><span class="no-select">${ (notNew) ? '' : ((isReplay) ? lang.newReplay : lang.newComment) }</span>
                    </div>
                `;
    if(notNew) {
        if(isReplay) comment.setAttribute('class', 'comment replay');
        else comment.setAttribute('class', 'comment');
    }
    else {
        if(isReplay) comment.setAttribute('class', 'comment replay new-comment');
        else comment.setAttribute('class', 'comment new-comment');
    }
    return comment;
}
function setNewCountOfComment() {
    countOfComments++;
    if(spanOfCountOfComment == null) return; 
    if(countOfComments == 1) {
        spanOfCountOfComment.textContent = lang.comment;
    } else if(countOfComments == 2) {
        spanOfCountOfComment.textContent = lang.twoComment;
    } else if(countOfComments <= 10) {
        spanOfCountOfComment.textContent = countOfComments + ' ' + lang.comments;
    } else if(countOfComments > 10) {
        spanOfCountOfComment.textContent = countOfComments + ' ' + lang.comment;
    }
}
function handelScroll() {
    if( (window.scrollY + window.outerHeight) < document.documentElement.scrollHeight - 50 ) return;
    if(commentsContianer == null) return;
    if(typeof(PLAYLIST_ID) != "number") var PLAYLIST_ID = window.location.pathname.split('/')[window.location.pathname.split('/').length -1];
    if(getMoreCommentsURL == null) getMoreCommentsURL = window.location.origin + '/ajax/playlist/' + PLAYLIST_ID + '/more-comment?page=2';
    getMoreComments(getMoreCommentsURL);
}
function getMoreComments() {
    window.onscroll = null;
    window.ontouchmove = null;
    var tempUrl = getMoreCommentsURL + ( getMoreCommentsURL.lastIndexOf('?') != -1 ? window.location.search.replace('?', '&') : window.location.search );
    ajaxRequest('get', tempUrl, null, function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
                if(jsonResponse.status) {
                    if(typeof(jsonResponse.data) == "object") {
                        if(jsonResponse.data.hasOwnProperty('data')) {
                            renderCommentsOneByOne(jsonResponse.data.data);
                            if(jsonResponse.data.data.length < commentsCountOnOneScroll) {
                                window.onscroll = null;
                                window.ontouchmove = null;
                                if(loadingComment != null) {
                                    commentsContianer.removeChild(loadingComment);
                                }
                                return;
                            }
                            if(! jsonResponse.data.hasOwnProperty('next_page_url')) {
                                window.onscroll = null;
                                window.ontouchmove = null;
                                if(loadingComment != null) {
                                    commentsContianer.removeChild(loadingComment);
                                }
                                return;
                            }
                            getMoreCommentsURL = jsonResponse.data.next_page_url;
                            if(jsonResponse.data.next_page_url == null)  {
                                window.onscroll = null;
                                window.ontouchmove = null;
                                if(loadingComment != null) {
                                    commentsContianer.removeChild(loadingComment);
                                }
                                return;
                            }
                            window.onscroll = handelScroll;
                            window.ontouchmove = handelScroll;
                            return;
                        }
                    }
                }
            }
        }
        errorInRequestToGetMoreComments++;
        if(errorInRequestToGetMoreComments >= maxNumberOfErrorInRequestToGetMoreComments) {
            window.onscroll = null;
            window.ontouchmove = null;
            if(loadingComment != null) {
                commentsContianer.removeChild(loadingComment);
            }
        }
        else {
            window.onscroll = handelScroll;
            window.ontouchmove = handelScroll;

        }
    }, false);
}
function renderCommentsOneByOne(comments) {
    for(var i = 0; i < comments.length; i++) {
        var tempComment = createCommentDiv(comments[i], true);
        if(tempComment != null) {
            if(loadingComment != null) {
                commentsContianer.insertBefore(tempComment,loadingComment);
            } else commentsContianer.appendChild(tempComment);
        }
    }
}
function openReplayOf(commentId) {
    var button = document.getElementById('replaysButtonOfComment' + commentId),
        contianer = document.getElementById('replaysOfComment' + commentId);
    if(button == null || contianer == null) return;
    contianer.setAttribute('style', '');
    button.setAttribute('style', 'display:none !important;');
}
function postReplay(commentId) {
    var inputContentOfReplay = document.getElementById('inputContentOfReplay' + commentId);
    if(inputContentOfReplay == null) return;
    if(inputContentOfReplay.value.trim().length < 1) {
        inputContentOfReplay.setAttribute('class', 'add-opinion-textarea input-invalid');
        return;
    }
    if(inputContentOfReplay.value.trim().length > 5000) {
        inputContentOfReplay.setAttribute('class', 'add-opinion-textarea input-invalid');
        showPopUpMassage(lang.alertOfMaxSizeOfCharacter , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    var formData = new FormData();
    formData.append('_token', TOKEN);
    formData.append('content', inputContentOfReplay.value.trim());
    ajaxRequest('post', window.location.origin + '/ajax/playlist/' + commentId + '/post-replay/', formData, function(jsonResponse) {
        if(jsonResponse == null) {
            showPopUpMassage(lang.generalError , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
            inputContentOfReplay.setAttribute('class', 'add-opinion-textarea');
            return;
        }
        if(jsonResponse.hasOwnProperty('status')) {
            if(jsonResponse.status && jsonResponse.hasOwnProperty('data')) {
                renderNewReplay(jsonResponse.data, commentId);
                inputContentOfReplay.value = '';
                inputContentOfReplay.setAttribute('class', 'add-opinion-textarea');
                return;
            } else if(jsonResponse.hasOwnProperty('msg')) {
                if(jsonResponse.msg.trim() != '') {
                    inputContentOfReplay.setAttribute('class', 'add-opinion-textarea');
                    showPopUpMassage(jsonResponse.msg , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
                    return;
                }
            }
        }
        showPopUpMassage(lang.generalError , null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
    });
}
function renderNewReplay(data, commentId) {
    var replay = createCommentDiv(data, false, true);
    var replaysOfComment = document.getElementById('replaysOfComment' + commentId);
    var postReplayElement = document.getElementById('postReplayElement' + commentId);
    if(replay != null && replaysOfComment != null && postReplayElement != null) {
        replaysOfComment.insertBefore(replay, postReplayElement);
    }
}
function openThisVideo(publicKey, posterSrc, blobId, videoId) {
    if(typeof(player) == "undefined") return;
    if(player == null) return;
    if(typeof(changeStorage) == "function") {
        changeStorage(publicKey, posterSrc, blobId, videoId, 'video');
    }
    player.setVideo(publicKey, posterSrc, blobId);
    if(! needToGoToFirstComment) window.scrollTo(0, 0);
}
function openThisAudio(publicKey, posterSrc, blobId, audioId) {
    if(typeof(player) == "undefined") return;
    if(player == null) return;
    if(typeof(changeStorage) == "function") {
        changeStorage(publicKey, posterSrc, blobId, audioId, 'audio');
    }
    player.setVideo(publicKey, posterSrc, blobId, 'audio');
    if(! needToGoToFirstComment) window.scrollTo(0, 0);
}
function openThisBook(publicKey, permision) {
    if(! permision) {
        showPopUpMassage(lang.needSubscriptionMassege, null, null, 'ok', defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    var url = window.location.origin + '/blob/book/' + publicKey;
    var link = document.createElement('a');
    link.href = url;
    link.target = "_blank";
    link.click();
}
function goToFirstComment(commentId) {
    var firstComment = document.getElementsByClassName('first-comment');
    firstComment = firstComment[0];
    openReplayOf(commentId);
    firstComment.scrollIntoView();
}