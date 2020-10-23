var notifcation = document.getElementById('notifcation'),
	notifcationButton = document.getElementById('notifcationButton'),
	notifcationTemplate = document.getElementById('notifcationTemplate'),
	notifcationTemplateDiv = document.getElementById('notifcationTemplateDiv'),
	exitButtonCanvasOfNotifcationTemplate = document.getElementById('exitButtonCanvasOfNotifcationTemplate'),
	countOfNewNotifcations = document.getElementById('countOfNewNotifcations'),
	userLang = {};

if(exitButtonCanvasOfNotifcationTemplate != null && notifcationTemplate != null) {
	if(typeof(drawRemoveIconCanvas) == "function" && typeof(closeBobUpTemplate) == "function") {
		exitButtonCanvasOfNotifcationTemplate.width = 25;
        exitButtonCanvasOfNotifcationTemplate.height = 25;
        drawRemoveIconCanvas(exitButtonCanvasOfNotifcationTemplate,'#ffffff');
        exitButtonCanvasOfNotifcationTemplate.onclick = function () {
            closeBobUpTemplate(notifcationTemplate);
        }
	}
}
if(notifcationButton != null) {
	notifcationButton.onclick = openNotifcation;
}

function openNotifcation() {
	if(notifcation == null) return;
	notifcation.setAttribute('style','display: block;');
	notifcationButton.onclick = closeNotifcation;
}
function closeNotifcation() {
	if(notifcation == null) return;
	notifcation.setAttribute('style','display: none;');
	notifcationButton.onclick = openNotifcation;
}
function showNotifcation(id, type, lang) {
	var clickedNotifcation = document.getElementById('notifcation' + type + id);
	if(clickedNotifcation.getAttribute('class') == 'transition not-readed') decreaseCountOfNewNotifcation();
	else if(clickedNotifcation.class == 'transition not-readed') decreaseCountOfNewNotifcation();
	if(clickedNotifcation != null) clickedNotifcation.setAttribute('class','transition');
	if(notifcationTemplate == null) return;
	if(notifcationTemplateDiv != null) notifcationTemplate.removeChild(notifcationTemplateDiv);
	notifcationTemplateDiv = document.createElement('div');
	notifcationTemplateDiv.setAttribute('id', 'notifcationTemplateDiv');
	notifcationTemplate.appendChild(notifcationTemplateDiv);
	if(typeof(ajaxRequest) != "function") {
		if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
		return;
	}
	switch(type) {
		case 'CoachOpinion':
			openCoachOpinionNotifcation(id, lang);
			break;
		case 'PlaylistOpinion':
			openPlaylistOpinionNotifcation(id, lang);
			break;
		case 'Comment':
			openCommentNotifcation(id, lang);
			break;
		case 'Replay':
			openReplayNotifcation(id, lang);
			break;
		case 'SessionsOnline':
			openSessionsOnlineNotifcation(id, lang);
			break;
		default:
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
			return;
	}
	ajaxRequest('get', location.origin + '/admin/notification/setReaded/' + type + '/' + id, null, null);
}

function openCoachOpinionNotifcation(id, lang) {
	ajaxRequest('get', location.origin + '/admin/notification/CoachOpinion/' + id, null, function(jsonResponse) {
		if(jsonResponse == null) {
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
			return;
		}
		if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
			if(jsonResponse.status) {
				var div = document.createElement('div'),
					image = document.createElement('img'),
					name = document.createElement('span'),
					time = document.createElement('span'),
					content = document.createElement('p'),
					allowButton = document.createElement('a');

				if(jsonResponse.data.hasOwnProperty('image')) image.setAttribute('src', jsonResponse.data.image);
				if(jsonResponse.data.hasOwnProperty('name')) name.textContent = jsonResponse.data.name;
				if(jsonResponse.data.hasOwnProperty('time')) time.textContent = jsonResponse.data.time;
				if(jsonResponse.data.hasOwnProperty('content')) content.textContent = jsonResponse.data.content;

				div.appendChild(image);
				div.appendChild(name);
				div.appendChild(time);
				div.appendChild(content);

				if(jsonResponse.data.hasOwnProperty('allow')) {
					if(jsonResponse.data.allow) allowButton.textContent = lang[1];
					else allowButton.textContent = lang[2];
					allowButton.setAttribute('id','allowButtonNotificationCoachOpinion' + id);
					allowButton.onclick = new Function(
						"RequestToggleAllowCoachOpinionNotification(" + id + ",'" +
						lang[0] + "',['" +
						lang[1] + "','" +
						lang[2] + "']);");
					div.appendChild(allowButton);
				}

				div.setAttribute('class', 'opend-notification-div no-select');
				if(notifcationTemplateDiv != null) notifcationTemplateDiv.appendChild(div);
				notifcationTemplate.setAttribute('style', '');
				return;
			} else if(jsonResponse.hasOwnProperty('msg')) {
				if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
				return;
			}
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
		}
	});
}
function RequestToggleAllowCoachOpinionNotification(coachOpinionId,generelErorrMsg,lang) {
    ajaxRequest('get',location.origin + '/admin/home/' + coachOpinionId + '/toggleAllowCoachOpinion',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                    result = jsonResponse.data.allow;
                    var allowButton = document.getElementById('allowButtonNotificationCoachOpinion' + coachOpinionId);
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

function openPlaylistOpinionNotifcation(id, lang) {
	ajaxRequest('get', location.origin + '/admin/notification/PlaylistOpinion/' + id, null, function(jsonResponse) {
		if(jsonResponse == null) {
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
			return;
		}
		if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
			if(jsonResponse.status) {
				var div = document.createElement('div'),
					image = document.createElement('img'),
					name = document.createElement('span'),
					playlistTitle = document.createElement('span'),
					time = document.createElement('span'),
					content = document.createElement('p'),
					allowButton = document.createElement('a');

				if(jsonResponse.data.hasOwnProperty('image')) image.setAttribute('src', jsonResponse.data.image);
				if(jsonResponse.data.hasOwnProperty('name')) name.textContent = jsonResponse.data.name;
				if(jsonResponse.data.hasOwnProperty('playlist_title')) playlistTitle.textContent = jsonResponse.data.playlist_title + ' - ';
				if(jsonResponse.data.hasOwnProperty('time')) time.textContent = jsonResponse.data.time;
				if(jsonResponse.data.hasOwnProperty('content')) content.textContent = jsonResponse.data.content;

				div.appendChild(image);
				div.appendChild(name);
				div.appendChild(playlistTitle);
				div.appendChild(time);
				div.appendChild(content);

				if(jsonResponse.data.hasOwnProperty('allow')) {
					if(jsonResponse.data.allow) allowButton.textContent = lang[1];
					else allowButton.textContent = lang[2];
					allowButton.setAttribute('id','allowButtonNotificationPlaylistOpinion' + id);
					allowButton.onclick = new Function(
						"RequestToggleAllowPlaylistOpinionNotification(" + id + ",'" +
						lang[0] + "',['" +
						lang[1] + "','" +
						lang[2] + "']);");
					div.appendChild(allowButton);
				}

				div.setAttribute('class', 'opend-notification-div no-select');
				if(notifcationTemplateDiv != null) notifcationTemplateDiv.appendChild(div);
				notifcationTemplate.setAttribute('style', '');
				return;
			} else if(jsonResponse.hasOwnProperty('msg')) {
				if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
				return;
			}
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
		}
	});
}
function RequestToggleAllowPlaylistOpinionNotification(playlistOpinionId,generelErorrMsg,lang) {
    ajaxRequest('get',location.origin + '/admin/home/' + playlistOpinionId + '/toggleAllowPlaylistOpinion',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                    result = jsonResponse.data.allow;
                    var allowButton = document.getElementById('allowButtonNotificationPlaylistOpinion' + playlistOpinionId);
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

function openCommentNotifcation(id, lang) {
	ajaxRequest('get', location.origin + '/admin/notification/Comment/' + id, null, function(jsonResponse) {
		if(jsonResponse == null) {
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
			return;
		}
		if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
			if(jsonResponse.status) {
				var div = document.createElement('div'),
					image = document.createElement('img'),
					name = document.createElement('span'),
					playlistTitle = document.createElement('span'),
					time = document.createElement('span'),
					content = document.createElement('p'),
					allowButton = document.createElement('a');

				if(jsonResponse.data.hasOwnProperty('image')) image.setAttribute('src', jsonResponse.data.image);
				if(jsonResponse.data.hasOwnProperty('name')) name.textContent = jsonResponse.data.name;
				if(jsonResponse.data.hasOwnProperty('playlist_title')) playlistTitle.textContent = jsonResponse.data.playlist_title + ' - ';
				if(jsonResponse.data.hasOwnProperty('time')) time.textContent = jsonResponse.data.time;
				if(jsonResponse.data.hasOwnProperty('content')) content.textContent = jsonResponse.data.content;

				div.appendChild(image);
				div.appendChild(name);
				div.appendChild(playlistTitle);
				div.appendChild(time);
				div.appendChild(content);

				if(jsonResponse.data.hasOwnProperty('allow')) {
					if(jsonResponse.data.allow) allowButton.textContent = lang[1];
					else allowButton.textContent = lang[2];
					allowButton.setAttribute('id','allowButtonNotificationComment' + id);
					allowButton.onclick = new Function(
						"RequestToggleAllowCommentNotification(" + id + ",'" +
						lang[0] + "',['" +
						lang[1] + "','" +
						lang[2] + "']);");
					div.appendChild(allowButton);
				}

				div.setAttribute('class', 'opend-notification-div no-select');
				if(notifcationTemplateDiv != null) notifcationTemplateDiv.appendChild(div);
				notifcationTemplate.setAttribute('style', '');
				return;
			} else if(jsonResponse.hasOwnProperty('msg')) {
				if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
				return;
			}
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
		}
	});
}
function RequestToggleAllowCommentNotification(commentId,generelErorrMsg,lang) {
    ajaxRequest('get', location.origin + '/admin/home/' + commentId + '/toggleAllowComment',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                    var allowButton = document.getElementById('allowButtonNotificationComment' + commentId);
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

function openReplayNotifcation(id, lang) {
	ajaxRequest('get', location.origin + '/admin/notification/Replay/' + id, null, function(jsonResponse) {
		if(jsonResponse == null) {
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
			return;
		}
		if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
			if(jsonResponse.status) {
				var div = document.createElement('div'),
					image = document.createElement('img'),
					name = document.createElement('span'),
					playlistTitle = document.createElement('span'),
					time = document.createElement('span'),
					content = document.createElement('p'),
					allowButton = document.createElement('a');

				if(jsonResponse.data.hasOwnProperty('image')) image.setAttribute('src', jsonResponse.data.image);
				if(jsonResponse.data.hasOwnProperty('name')) name.textContent = jsonResponse.data.name;
				if(jsonResponse.data.hasOwnProperty('playlist_title')) playlistTitle.textContent = jsonResponse.data.playlist_title + ' - ';
				if(jsonResponse.data.hasOwnProperty('time')) time.textContent = jsonResponse.data.time;
				if(jsonResponse.data.hasOwnProperty('content')) content.textContent = jsonResponse.data.content;

				div.appendChild(image);
				div.appendChild(name);
				div.appendChild(playlistTitle);
				div.appendChild(time);
				div.appendChild(content);

				if(jsonResponse.data.hasOwnProperty('allow')) {
					if(jsonResponse.data.allow) allowButton.textContent = lang[1];
					else allowButton.textContent = lang[2];
					allowButton.setAttribute('id','allowButtonNotificationReplay' + id);
					allowButton.onclick = new Function(
						"RequestToggleAllowReplayNotification(" + id + ",'" +
						lang[0] + "',['" +
						lang[1] + "','" +
						lang[2] + "']);");
					div.appendChild(allowButton);
				}

				div.setAttribute('class', 'opend-notification-div no-select');
				if(notifcationTemplateDiv != null) notifcationTemplateDiv.appendChild(div);
				notifcationTemplate.setAttribute('style', '');
				return;
			} else if(jsonResponse.hasOwnProperty('msg')) {
				if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
				return;
			}
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
		}
	});
}
function RequestToggleAllowReplayNotification(replayId,generelErorrMsg,lang) {
    ajaxRequest('get', location.origin + '/admin/home/' + replayId + '/toggleAllowReplay',null,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('allow')) {
                    var allowButton = document.getElementById('allowButtonNotificationReplay' + replayId);
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

function openSessionsOnlineNotifcation(id, lang) {
	ajaxRequest('get', location.origin + '/admin/notification/SessionsOnline/' + id, null, function(jsonResponse) {
		if(jsonResponse == null) {
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
			return;
		}
		if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
			if(jsonResponse.status) {
				var div = document.createElement('div'),
					image = document.createElement('img'),
					name = document.createElement('span'),
					time = document.createElement('span'),
					content = document.createElement('p'),
					admissionButton = document.createElement('a');

				if(jsonResponse.data.hasOwnProperty('image')) image.setAttribute('src', jsonResponse.data.image);
				if(jsonResponse.data.hasOwnProperty('name')) name.textContent = jsonResponse.data.name;
				if(jsonResponse.data.hasOwnProperty('time')) time.textContent = jsonResponse.data.time;
				if(jsonResponse.data.hasOwnProperty('offer_name')) content.textContent = jsonResponse.data.offer_name;

				div.appendChild(image);
				div.appendChild(name);
				div.appendChild(time);
				div.appendChild(content);

				if(jsonResponse.data.hasOwnProperty('admission')) {
					if(jsonResponse.data.admission) admissionButton.textContent = lang[3];
					else admissionButton.textContent = lang[4];
					admissionButton.setAttribute('id','SessionOnlineAdmissionNotificationButton' + id);
					admissionButton.onclick = new Function(
						"RequestSetAdmissionNotification(" + id + "," + jsonResponse.data.admission + ",'" +
						lang[0] + "',['" +
						lang[3] + "','" +
						lang[4] + "','" +
						lang[6] + "','" +
						lang[5] + "','" +
						lang[7] + "']);");
					div.appendChild(admissionButton);
				}

				div.setAttribute('class', 'opend-notification-div no-select');
				if(notifcationTemplateDiv != null) notifcationTemplateDiv.appendChild(div);
				notifcationTemplate.setAttribute('style', '');
				return;
			} else if(jsonResponse.hasOwnProperty('msg')) {
				if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
				return;
			}
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang[0]);
		}
	});
}
function RequestSetAdmissionNotification(SessionOnlineId,admission,generelErorrMsg,lang) {
    if(admission) msg = lang[3];
    else msg = lang[2];
    showPopUpMassage(msg,null,function (exitThis,popUpMassageDiv) {
        ajaxRequest('get', location.origin + '/admin/home/' + SessionOnlineId + '/setAdmission',null,function(jsonResponse) {
            if(jsonResponse != null) {
                if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')){
                    if(jsonResponse.status && jsonResponse.data.hasOwnProperty('admission')) {
                        var admissionButton = document.getElementById('SessionOnlineAdmissionNotificationButton' + SessionOnlineId);
                        result = jsonResponse.data.admission;
                        if(result != null) {
                            if(admissionButton != null) {
                                if(result) {
                                    admissionButton.textContent = lang[0];
                                } else {
                                    admissionButton.textContent = lang[1];
                                }
                                admissionButton.onclick = new Function("RequestSetAdmissionNotification(" + SessionOnlineId + "," + result + ",'" + generelErorrMsg + "',['" + lang[0] + "','" + lang[1] + "','" + lang[2] + "','" + lang[3] + "','" + lang[4] + "']);");
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
function showUserNotifcation(id,type,lang) {
	var clickedNotifcation = document.getElementById('notifcation' + type + id);
	if(clickedNotifcation.getAttribute('class') == 'transition not-readed') decreaseCountOfNewNotifcation();
	else if(clickedNotifcation.class == 'transition not-readed') decreaseCountOfNewNotifcation();
	if(clickedNotifcation != null) clickedNotifcation.setAttribute('class','transition');
	if(notifcationTemplate == null) return;
	if(notifcationTemplateDiv != null) notifcationTemplate.removeChild(notifcationTemplateDiv);
	notifcationTemplateDiv = document.createElement('div');
	notifcationTemplateDiv.setAttribute('id', 'notifcationTemplateDiv');
	notifcationTemplate.appendChild(notifcationTemplateDiv);
	if(typeof(ajaxRequest) != "function") {
		if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang.generalError);
		return;
	}
	switch(type) {
		case 'Replay':
			openReplayUserNotifcation(id, lang);
			break;
		case 'SessionsOnline':
			openSessionsOnlineUserNotifcation(id, lang);
			break;
		default:
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang.generalError);
			return;
	}
	ajaxRequest('get', location.origin + '/ajax/notification/setReaded/' + type + '/' + id, null, null);
}
function openSessionsOnlineUserNotifcation(id, lang) {
	var template = findOrCreateTemplateRequestToSessionFromNotifcation(lang);
	var titleOfTemplate = document.getElementById('titleOfContentOfRequestToSessionFromNotifcation'),
		buttonSendRequestToSessionFromNotifcation = document.getElementById('buttonSendRequestToSessionFromNotifcation'),
		inputDateOfRequestToSessionFromNotifcation = document.getElementById('inputDateOfRequestToSessionFromNotifcation'),
		inputTimeOfRequestToSessionFromNotifcation = document.getElementById('inputTimeOfRequestToSessionFromNotifcation');
	ajaxRequest('get', window.location.origin + '/ajax/notification/session-online/' + id + '/offer', null, function (jsonResponse) {
		if(jsonResponse == null) {
			showPopUpMassage(lang.SessionNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
			return;
		}
		if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
			if(jsonResponse.status) {
				if(typeof(jsonResponse.data) == "object" && jsonResponse.data != null) {
					if(jsonResponse.data.hasOwnProperty('offerId') && jsonResponse.data.hasOwnProperty('offerName') && jsonResponse.data.hasOwnProperty('admission') && jsonResponse.data.hasOwnProperty('sessionsUrl')) {
						if(jsonResponse.data.admission) {
							window.location.href = jsonResponse.data.sessionsUrl;
							return;
						}
						offerId = jsonResponse.data.offerId;
						title = jsonResponse.data.offerName;
						if(titleOfTemplate != null) {
							titleOfTemplate.textContent = jsonResponse.data.offerName;
						}
						if(buttonSendRequestToSessionFromNotifcation != null) {
							buttonSendRequestToSessionFromNotifcation.onclick = function () {
								var formData = new FormData();
								if(inputDateOfRequestToSessionFromNotifcation != null) {
									formData.append('date', inputDateOfRequestToSessionFromNotifcation.value);
								}
								if(inputTimeOfRequestToSessionFromNotifcation != null) {
									formData.append('time', inputTimeOfRequestToSessionFromNotifcation.value);
								}
								formData.append('id', offerId);
								formData.append('_token', lang.TOKEN);
								ajaxRequest('post', window.location.origin + '/ajax/session-offer/request', formData, function(jsonResponse) {
									if(jsonResponse == null) {
										showPopUpMassage(lang.SessionNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
										return;
									}
									if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('msg')) {
										if(jsonResponse.status) {
											if(jsonResponse.hasOwnProperty('data')) {
												if(typeof(jsonResponse.data) == "object") {
													showPopUpMassage(jsonResponse.msg,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
													inputDateOfRequestToSessionFromNotifcation.value = '';
													inputTimeOfRequestToSessionFromNotifcation.value = '';
													template.style = 'display: none !important;';
													return;
												}
											}
										} else if(jsonResponse.hasOwnProperty('msg')) {
											showPopUpMassage(jsonResponse.msg,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
											if(jsonResponse.hasOwnProperty('data')) {
												if(typeof(jsonResponse.data) == "object") {
													if(jsonResponse.data.length == 1) {
														if(jsonResponse.data[0] == 'invalid') {
															inputDateOfRequestToSessionFromNotifcation.setAttribute('class', 'input-invalid');
															inputTimeOfRequestToSessionFromNotifcation.setAttribute('class', 'input-invalid');
														}
													}
												}
											}
											return;
										}
										showPopUpMassage(lang.SessionNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
									}
								});
							};
						}
						template.style = "";
						return;
					}
				}
			}
		}
		showPopUpMassage(lang.SessionNotForUseAlert,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
		return;
	});
}
function findOrCreateTemplateRequestToSessionFromNotifcation(lang) {
	var TemplateRequestToSessionFromNotifcation = document.getElementById('TemplateRequestToSessionFromNotifcation');

	if(TemplateRequestToSessionFromNotifcation != null) return TemplateRequestToSessionFromNotifcation;

	var TemplateRequestToSessionFromNotifcation = document.createElement('div'),
		header = document.createElement('header'),
		headerDiv = document.createElement('div'),
		headerCanvas = document.createElement('canvas'),
		contentOfRequestToSessionFromNotifcation = document.createElement('div');

	headerCanvas.setAttribute('width' ,'25');
	headerCanvas.setAttribute('height' ,'25');
	if(typeof(drawRemoveIconCanvas) == "function" && typeof(closeBobUpTemplate) == "function") {
		headerCanvas.width = 25;
		headerCanvas.height = 25;
		drawRemoveIconCanvas(headerCanvas,'#ffffff');
		headerCanvas.onclick = function () {
			closeBobUpTemplate(TemplateRequestToSessionFromNotifcation);
		}
	}
	headerDiv.appendChild(headerCanvas);
	header.appendChild(headerDiv);

	contentOfRequestToSessionFromNotifcation.innerHTML = `<div><h2 id="titleOfContentOfRequestToSessionFromNotifcation"></h2></div><section><section><span>${ lang.date }</span><input id="inputDateOfRequestToSessionFromNotifcation" type="date" name="date" autocomplete="off" class=""></section><section><span>${ lang.time }</span><input id="inputTimeOfRequestToSessionFromNotifcation" type="time" name="time" autocomplete="off" class=""></section></section>
	<footer><div><a id="buttonSendRequestToSessionFromNotifcation">${ lang.ok }</a></div></footer>`;
	contentOfRequestToSessionFromNotifcation.setAttribute('class', 'welcome-playlist-opend-template offer-template request-session notifcation-session');
	
	TemplateRequestToSessionFromNotifcation.appendChild(header);
	TemplateRequestToSessionFromNotifcation.appendChild(contentOfRequestToSessionFromNotifcation);
	TemplateRequestToSessionFromNotifcation.setAttribute('class', 'pop-up-template template-of-this-playlist no-select');
	TemplateRequestToSessionFromNotifcation.setAttribute('id', 'TemplateRequestToSessionFromNotifcation');
	TemplateRequestToSessionFromNotifcation.setAttribute('style', 'display: none;');
	document.body.appendChild(TemplateRequestToSessionFromNotifcation);

	return TemplateRequestToSessionFromNotifcation;
}
function openReplayUserNotifcation(id, lang) {
	notifcationTemplate.setAttribute('style', '');
	ajaxRequest('get', location.origin + '/ajax/notification/replay/' + id, null, function(jsonResponse) {
		if(jsonResponse == null) {
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang.generalError);
			return;
		}
		if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
			if(jsonResponse.status) {
				var div = document.createElement('div'),
					image = document.createElement('img'),
					name = document.createElement('span'),
					playlistTitle = document.createElement('span'),
					time = document.createElement('span'),
					content = document.createElement('p'),
					allowButton = document.createElement('a');

				if(jsonResponse.data.hasOwnProperty('image')) image.setAttribute('src', jsonResponse.data.image);
				if(jsonResponse.data.hasOwnProperty('name')) name.textContent = jsonResponse.data.name;
				if(jsonResponse.data.hasOwnProperty('playlist_title')) playlistTitle.textContent = jsonResponse.data.playlist_title + ' - ';
				if(jsonResponse.data.hasOwnProperty('time')) time.textContent = jsonResponse.data.time;
				if(jsonResponse.data.hasOwnProperty('content')) content.textContent = jsonResponse.data.content;

				div.appendChild(image);
				div.appendChild(name);
				div.appendChild(playlistTitle);
				div.appendChild(time);
				div.appendChild(content);

				if(jsonResponse.data.hasOwnProperty('replay_url')) {
					allowButton.textContent = lang.goToThisReplay;
					allowButton.href = jsonResponse.data.replay_url;
					div.appendChild(allowButton);
				}

				div.setAttribute('class', 'opend-notification-div no-select');
				if(notifcationTemplateDiv != null) notifcationTemplateDiv.appendChild(div);
				notifcationTemplate.setAttribute('style', '');
				return;
			} else if(jsonResponse.hasOwnProperty('msg')) {
				if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
				return;
			}
			if(typeof(showPopUpMassage) == "function") showPopUpMassage(lang.generalError);
		}
	});
}
function decreaseCountOfNewNotifcation() {
	if(countOfNewNotifcations == null) return;
	if(isNaN(countOfNewNotifcations.textContent)) {
		countOfNewNotifcations.style = "display: none;";
		return;
	}
	countOfNewNotifcations.textContent = countOfNewNotifcations.textContent -1;
	if(countOfNewNotifcations.textContent <= 0) countOfNewNotifcations.style = "display: none;";
}