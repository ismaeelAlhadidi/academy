class OfferCollection {
    constructor() {
        this._length = 0;
    }
    append(value) {
        if(typeof(value) != "object") return;
        if(value.constructor != Offer) return;
        this[value.id] = value;
        this._length = this._length + 1;
    }
    remove(key) {
        if(this[key] == undefined) return;
        delete this[key];
        this._length = this._length - 1;
    }
    get length() { return this._length; }
}

class Offer {

    static defaultPoster;
    static defaultUserImage;
    static deleteUrl;
    static getDataUrl;
    static updateUrl;
    static addUrl;
    static getSessionUrl;
    static lang;
    static token;
    static collection = new OfferCollection();
    static currentOfferUpdating;

    static addOffer(formData) {
        if(typeof(ajaxRequest) != "function") return;
        formData.append('_token',Offer.token);
        ajaxRequest('post',Offer.addUrl,formData,function(jsonResponse) {
            if(jsonResponse == null) {
                showPopUpMassage(Offer.lang[7]);
                return;
            }
            if(jsonResponse.hasOwnProperty('status')) {
                if(jsonResponse.status) {
                    if(jsonResponse.hasOwnProperty('data')) {
                        var temp = new Offer(null, null, null, null, null, null, jsonResponse.data);
                        temp.hasSessions = false;
                        if(addAndEditTemplate != null) {
                            addAndEditTemplate.setAttribute ("style","display: none !important;");
                            addAndEditTemplate.style = 'display: none !important;';
                        }
                        window.scrollTo(0,0);
                        if(jsonResponse.hasOwnProperty('msg')) showPopUpMassage(jsonResponse.msg);
                        else showPopUpMassage(Offer.lang[8]);
                        return;
                    }
                }
                if(jsonResponse.hasOwnProperty('msg')) {
                    showPopUpMassage(jsonResponse.msg);
                    return;
                }
            }
            showPopUpMassage(Offer.lang[7]);
            
        });

    }

    constructor(id, name, price, duration, poster, hasSessions, json = null) {
        if(json == null) {
            this._id = id;
            this._name = name;
            this._price = price;
            this._duration = duration;
            this._poster = poster;
            this._hasSessions = hasSessions;
        } else this.jsonConstructor(json);
        this.addToCollection();
        this.createElement();
    }
    jsonConstructor(jsonObject) {
        if(jsonObject == null) return;
        if(typeof(jsonObject) != "object") return;
        if(jsonObject.hasOwnProperty('id')) this._id = jsonObject.id;
        else this._id = null;
        if(jsonObject.hasOwnProperty('name')) this._name = jsonObject.name;
        else this._name = null;
        if(jsonObject.hasOwnProperty('price')) this._price = jsonObject.price;
        else this._price = null;
        if(jsonObject.hasOwnProperty('duration')) this._duration = jsonObject.duration;
        else this._duration = null;
        if(jsonObject.hasOwnProperty('poster')) this._poster = jsonObject.poster;
        else this._poster = null;
        if(jsonObject.hasOwnProperty('hasSessions')) this._hasSessions = jsonObject.hasSessions;
        else this._hasSessions = null;
        if(jsonObject.hasOwnProperty('for_who')) this._forWho = jsonObject.for_who;
        else this._forWho = '';
        if(jsonObject.hasOwnProperty('for_who_not')) this._forWhoNot = jsonObject.for_who_not;
        else this._forWhoNot = '';
        if(jsonObject.hasOwnProperty('benefits')) this._benefits = jsonObject.benefits;
        else this._benefits = '';
        if(jsonObject.hasOwnProperty('notes')) this._notes = jsonObject.notes;
        else this._notes = '';
    }
    addToCollection() {
        if(Offer.collection == null || Offer.collection == undefined) return;
        if(typeof(Offer.collection) != "object") return;
        if(Offer.collection.constructor != OfferCollection) return;
        Offer.collection.append(this);
    }
    createElement() {
        var offers = document.getElementById('offers');
        if(offers == null) return;

        var tempOffer = document.getElementById('offerDiv' + this.id);
        if(tempOffer != null) return;

        tempOffer = document.createElement('div');
        tempOffer.setAttribute('id','offerDiv' + this.id);
        tempOffer.setAttribute('class','playlist-div');

        var sectionOfTempOffer = document.createElement('section'),
            imgOfSection = document.createElement('img'),
            divOfTempOffer = document.createElement('div'),
            div1OfDiv = document.createElement('div'),
            span1Div1 = document.createElement('span'),
            span2Div1 = document.createElement('span'),
            div2OfDiv = document.createElement('div'),
            span1Div2 = document.createElement('span'),
            span2Div2 = document.createElement('span'),
            div3OfDiv = document.createElement('div'),
            span1Div3 = document.createElement('span'),
            span2Div3 = document.createElement('span'),
            div4OfDiv = document.createElement('div'),
            a1Div4 = document.createElement('a'),
            a2Div4 = document.createElement('a'),
            a3Div4 = document.createElement('a');

        imgOfSection.setAttribute('src',this.poster);
        sectionOfTempOffer.appendChild(imgOfSection);

        span1Div1.setAttribute('class','no-select');
        span1Div1.textContent = Offer.lang[0];
        span2Div1.textContent = this.name;
        div1OfDiv.appendChild(span1Div1);
        div1OfDiv.appendChild(span2Div1);

        span1Div2.setAttribute('class','no-select');
        span1Div2.textContent = Offer.lang[1];
        span2Div2.textContent = this.price;
        div2OfDiv.appendChild(span1Div2);
        div2OfDiv.appendChild(span2Div2);

        span1Div3.setAttribute('class','no-select');
        span1Div3.textContent = Offer.lang[2];
        span2Div3.textContent = this.duration;
        div3OfDiv.appendChild(span1Div3);
        div3OfDiv.appendChild(span2Div3);

        div4OfDiv.setAttribute('class','no-select');
        a1Div4.textContent = Offer.lang[3];
        div4OfDiv.appendChild(a1Div4);
        a2Div4.textContent = Offer.lang[4];
        div4OfDiv.appendChild(a2Div4);
        a3Div4.textContent = Offer.lang[5];
        div4OfDiv.appendChild(a3Div4);

        a1Div4.onclick = new Function("deleteOffer('" + this.id + "');");
        a2Div4.onclick = new Function("updateOffer('" + this.id + "');");
        a3Div4.onclick = new Function("showSession('" + this.id + "');");

        divOfTempOffer.appendChild(div1OfDiv);
        divOfTempOffer.appendChild(div2OfDiv);
        divOfTempOffer.appendChild(div3OfDiv);
        divOfTempOffer.appendChild(div4OfDiv);

        tempOffer.appendChild(sectionOfTempOffer);
        tempOffer.appendChild(divOfTempOffer);


        var empty = document.getElementById('offersEmpty');
        if(empty != null) {
            offers.removeChild(empty);
            offers.appendChild(tempOffer);
            return;
        }
        if(offers.children.length > 0) {
            offers.insertBefore(tempOffer,offers.children[0]);
            window.scrollTo(0,0);
            return;
        }
        else offers.appendChild(tempOffer);
    }
    delete() {
        var tempAlertContent = Offer.lang[11],
            tempThis = this;
        if(tempThis.hasSessions) tempAlertContent = Offer.lang[9];
        showPopUpMassage(tempAlertContent, null, function(exitThis,popUpMassageDiv) {
            exitThis(popUpMassageDiv);
            ajaxRequest('get', Offer.deleteUrl + '/' + tempThis.id, null, function(jsonResponse) {
                if(jsonResponse == null) {
                    showPopUpMassage(Offer.lang[7]);
                    return;
                }
                if(jsonResponse.hasOwnProperty('status')) {
                    if(jsonResponse.status) {
                        var tempDeletedOffer = document.getElementById('offerDiv' + tempThis.id),
                            offers = document.getElementById('offers');
                        if(tempDeletedOffer != null && offers != null){
                            offers.removeChild(tempDeletedOffer);
                            if(offers.children.length == 0) {
                                var empty = document.createElement('div');
                                empty.setAttribute('id', 'offersEmpty');
                                empty.setAttribute('class', 'empty');
                                empty.textContent = Offer.lang[12];
                                offers.appendChild(empty);
                            }
                        }
                        if(jsonResponse.hasOwnProperty('msg')) {
                            showPopUpMassage(jsonResponse.msg);
                            return;
                        }
                    }
                }
                showPopUpMassage(Offer.lang[7]);
            });
        }, Offer.lang[10]);
    }
    openEditTemplate() {
        if(addNewOfferButton != null) addNewOfferButton.style = "display:none !important;";
        if(editOfferButton != null) editOfferButton.style = "";
        if(addAndEditTemplate == null) return;

        if(sessionNameInput != null) sessionNameInput.value = this.name;
        if(sessionPriceInput != null) sessionPriceInput.value = this.price;
        if(sessionDurationInput != null) sessionDurationInput.value = this.duration;

        if(typeof(resetValidOfAddTemplate) == "function") resetValidOfAddTemplate();

        if(forWhoInput != null) forWhoInput.value = this.forWho;
        if(forWhoNotInput != null) forWhoNotInput.value = this.forWhoNot;
        if(benefitsInput != null) benefitsInput.value = this.benefits;
        if(notesInput != null) notesInput.value = this.notes;
        if(imageOfOfferUploading != null) imageOfOfferUploading.setAttribute('src',this.poster);
        if(posterInputOfNewOffer != null) posterInputOfNewOffer.value = '';
        addAndEditTemplate.style = "";
        Offer.currentOfferUpdating = this;
    }
    update() {
        if(typeof(ajaxRequest) != "function") return;
        var updateFormData = new FormData();
        updateFormData.append('id',this.id);
        if(sessionNameInput.value != this.name) {
            updateFormData.append('name',sessionNameInput.value);
        }
        if(sessionPriceInput.value != this.poster) {
            updateFormData.append('price',sessionPriceInput.value);
        }
        if(sessionDurationInput.value != this.duration){
            updateFormData.append('duration',sessionDurationInput.value);
        }
        if(forWhoInput != null) if(forWhoInput.value.trim() != '') {
            if(forWhoInput.value != this.forWho) updateFormData.append('for_who',forWhoInput.value);
        }
        if(forWhoNotInput != null) if(forWhoNotInput.value.trim() != '') {
            if(forWhoNotInput.value != this.forWhoNot) updateFormData.append('for_who_not',forWhoNotInput.value);
        }
        if(benefitsInput != null) if(benefitsInput.value.trim() != '') {
            if(benefitsInput.value != this.benefits) updateFormData.append('benefits',benefitsInput.value);
        } 
        if(notesInput != null) if(notesInput.value.trim() != '') {
            if(notesInput.value != this.notes) updateFormData.append('notes',notesInput.value);
        }
        if(posterInputOfNewOffer != null) if(posterInputOfNewOffer.files.length == 1) {
            updateFormData.append('poster',posterInputOfNewOffer.files[0]);
        }
        updateFormData.append('_token',Offer.token);
        var tempThis = this;
        ajaxRequest('post', Offer.updateUrl, updateFormData, function(jsonResponse) {
            if(jsonResponse == null) {
                showPopUpMassage(Offer.lang[7]);
                return;
            }
            if(jsonResponse.hasOwnProperty('status')) {
                if(jsonResponse.status && jsonResponse.hasOwnProperty('data')) {
                    tempThis.edit(jsonResponse.data);
                    Offer.currentOfferUpdating = null;
                    addAndEditTemplate.style = "display: none !important;";
                    showPopUpMassage(Offer.lang[13]);
                    return;
                } else {
                    if(jsonResponse.hasOwnProperty('msg')) {
                        showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
            }
            showPopUpMassage(Offer.lang[7]);
        });
    }
    edit(jsonObject) {
        if(jsonObject == null) return;
        if(typeof(jsonObject) != "object") return;
        var tempElement = document.getElementById('offerDiv' + this.id);
        if(jsonObject.hasOwnProperty('name')) {
            this.name = jsonObject.name;
            if(tempElement != null) {
                if(tempElement.children.length >= 2) if(tempElement.children[1].children.length >= 1) {
                    if(tempElement.children[1].children[0].children.length >= 2) {
                        tempElement.children[1].children[0].children[1].textContent = jsonObject.name;
                    }
                }
            }
        }
        if(jsonObject.hasOwnProperty('price')) {
            this.price = jsonObject.price;
            if(tempElement != null) {
                if(tempElement.children.length >= 2) if(tempElement.children[1].children.length >= 2) {
                    if(tempElement.children[1].children[1].children.length >= 2) {
                        tempElement.children[1].children[1].children[1].textContent = jsonObject.price;
                    }
                }
            }
        }
        if(jsonObject.hasOwnProperty('duration')) {
            this.duration = jsonObject.duration;
            if(tempElement != null) {
                if(tempElement.children.length >= 2) if(tempElement.children[1].children.length >= 3) {
                    if(tempElement.children[1].children[2].children.length >= 2) {
                        tempElement.children[1].children[2].children[1].textContent = jsonObject.duration;
                    }
                }
            }
        }
        if(jsonObject.hasOwnProperty('poster')) {
            this.poster = jsonObject.poster;
            if(tempElement != null) {
                if(tempElement.children.length >= 1) if(tempElement.children[0].children.length >= 1) {
                    tempElement.children[0].children[0].setAttribute('src', jsonObject.poster);
                    tempElement.children[0].children[0].src = jsonObject.poster;
                }
            }
        }
        if(jsonObject.hasOwnProperty('hasSessions')) this.hasSessions = jsonObject.hasSessions;
        if(jsonObject.hasOwnProperty('for_who')) this.forWho = jsonObject.for_who;
        if(jsonObject.hasOwnProperty('for_who_not')) this.forWhoNot = jsonObject.for_who_not;
        if(jsonObject.hasOwnProperty('benefits')) this.benefits = jsonObject.benefits;
        if(jsonObject.hasOwnProperty('notes')) this.notes = jsonObject.notes;
    }
    getSessions(path = null) {
        if(path == null) if(this.hasSessions != true) {
            showPopUpMassage(Offer.lang[6]);
            return;
        }
        if(path == null) if(divOfThisSession != null) divOfThisSession.style = '';
        var tempThis = this;
        if(path == null) path = Offer.getSessionUrl + '/' + tempThis.id;
        ajaxRequest('get', path, null,function (jsonResponse) {
            tempThis.renderSession();
            if(jsonResponse == null) {
                showPopUpMassage(Offer.lang[7]);
                return;
            }
            if(jsonResponse.hasOwnProperty('status')) {
                if(jsonResponse.status && jsonResponse.hasOwnProperty('data')) {
                    tempThis.sessions = jsonResponse.data;
                    tempThis.renderSession();
                    return;
                } else {
                    if(jsonResponse.hasOwnProperty('msg')) {
                        showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
            }
            showPopUpMassage(Offer.lang[7]);
        });
    }
    renderSession() {
        if(divOfThisSession == null || tbodyOfSessions == null || tableOfSessions == null) return;
        if(sessionsDivHeader != null) {
            if(sessionsDivHeader.children.length >= 1) {
                sessionsDivHeader.children[0].textContent = this.name;
            }
        }
        tableOfSessions.removeChild(tbodyOfSessions);
        tbodyOfSessions = document.createElement('tbody');
        tbodyOfSessions.setAttribute('id', 'tbodyOfSessions');
        tableOfSessions.appendChild(tbodyOfSessions);
        if(typeof(this.sessions) == "object") {
            if(this.sessions.hasOwnProperty('data')) {
                if(typeof(this.sessions.data) == "object") {
                    var data = this.sessions.data;
                    for(var i = 0; i < data.length; i++) {
                        var tr = document.createElement('tr'),
                            imageTd = document.createElement('td'),
                            img = document.createElement('img'),
                            userNameTd = document.createElement('td'),
                            userNameSpan = document.createElement('span'),
                            sessionTimeTd = document.createElement('td'),
                            sessionTimeSpan = document.createElement('span'),
                            lastTd = document.createElement('td'),
                            toggleAccept = document.createElement('button');
                        
                        if(data[i].hasOwnProperty('userName')) userNameSpan.textContent = data[i].userName;
                        if(data[i].hasOwnProperty('time')) sessionTimeSpan.textContent = data[i].time;
                        if(data[i].hasOwnProperty('userImage')) img.setAttribute('src', location.origin + data[i].userImage);
                        else img.setAttribute('src', Offer.defaultUserImage);

                        img.setAttribute('class','user-image-in-sessions-table');
                        toggleAccept.setAttribute('class','toggleAcceptButton-in-sessions-table');
                        userNameSpan.setAttribute('class','text-feild-in-sessions-table');
                        sessionTimeSpan.setAttribute('class','text-feild-in-sessions-table');

                        if(data[i].hasOwnProperty('admission')) {
                            if(data[i].admission) toggleAccept.textContent = Offer.lang[14];
                            else toggleAccept.textContent = Offer.lang[15];
                            if(data[i].hasOwnProperty('id')) {
                                toggleAccept.setAttribute('id', 'SessionOnlineAdmissionButton' + data[i].id);
                                toggleAccept.onclick = new Function("RequestSetAdmission(" + data[i].id + "," + data[i].admission+ ");");
                            }
                        }

                        imageTd.appendChild(img);
                        if(data[i].hasOwnProperty('taken')) {
                            if(data[i].taken) {
                                var takenCanvas = document.createElement('canvas');
                                if(typeof(drawCorrectSign) == "function") {
                                    takenCanvas.width = '40';
                                    takenCanvas.height = '40';
                                    drawCorrectSign(takenCanvas,'red', true);
                                    lastTd.appendChild(takenCanvas);
                                }
                            }
                            else lastTd.appendChild(toggleAccept);
                        }
                        else lastTd.appendChild(toggleAccept);
                        userNameTd.appendChild(userNameSpan);
                        sessionTimeTd.appendChild(sessionTimeSpan);

                        tr.appendChild(imageTd);
                        tr.appendChild(userNameTd);
                        tr.appendChild(sessionTimeTd);
                        tr.appendChild(lastTd);
                        
                        tbodyOfSessions.appendChild(tr);
                    }
                }
            }
            if(typeof(makeGeneralPaginationLinks) == "function") {
                if(sessionsOfOffer == null) return;
                var tempThis = this;
                var linksClickHandler = function (path) {
                    tempThis.getSessions(path);
                };
                var sessionsLinks = document.getElementById('sessionsLinks' + tempThis.id);
                if(sessionsLinks != null) sessionsOfOffer.removeChild(sessionsLinks);
                sessionsLinks = makeGeneralPaginationLinks(this.sessions, linksClickHandler);
                if(sessionsLinks != null && sessionsLinks != undefined) {
                    sessionsLinks.setAttribute('id', 'sessionsLinks' + tempThis.id);
                    sessionsOfOffer.appendChild(sessionsLinks);
                }
            }
        }
    }

    get id() { return this._id; }
    set id(value) { this._id = value; }

    get name() { return this._name; }
    set name(value) { this._name = value; }

    get price() { return this._price; }
    set price(value) { this._price = value; }

    get duration() { return this._duration; }
    set duration(value) { this._duration = value; }

    get hasSessions() { return this._hasSessions; }
    set hasSessions(value) { this._hasSessions = value; }

    get forWho() { if( this._forWho != null) return this._forWho; else return ''; }
    set forWho(value) { this._forWho = value; }

    get forWhoNot() { if( this._forWhoNot != null) return this._forWhoNot; else return ''; }
    set forWhoNot(value) { this._forWhoNot = value; }

    get benefits() { if(this._benefits != null) return this._benefits; else return ''; }
    set benefits(value) { this._benefits = value; }

    get notes() { if( this._notes != null) return this._notes; else return ''; }
    set notes(value) { this._notes = value; }

    get sessions() { return this._sessions; }
    set sessions(value) { this._sessions = value; }

    get poster() {
        if(this._poster != null) return this._poster;
        return Offer.defaultPoster;
    }
    set poster(value) { this._poster = value; }
    
}

function deleteOffer(id) {
    if(Offer.collection[id] != undefined) Offer.collection[id].delete();
}
function updateOffer(id) {
    if(Offer.collection[id] != undefined) Offer.collection[id].openEditTemplate();
}
function showSession(id) {
    if(Offer.collection[id] != undefined) Offer.collection[id].getSessions();
}
function resetValidOfAddTemplate() {
    if(sessionNameInput != null) sessionNameInput.setAttribute('class', '');
    if(sessionPriceInput != null) sessionPriceInput.setAttribute('class', '');
    if(sessionDurationInput != null) sessionDurationInput.setAttribute('class', '');
}
function openAddTemplate() {
    if(addNewOfferButton != null) addNewOfferButton.style = "";
    if(editOfferButton != null) editOfferButton.style = "display:none !important";
    if(addAndEditTemplate == null) return;

    if(sessionNameInput != null) sessionNameInput.value = '';
    if(sessionPriceInput != null) sessionPriceInput.value = '';
    if(sessionDurationInput != null) sessionDurationInput.value = '';

    resetValidOfAddTemplate();

    if(forWhoInput != null) forWhoInput.value = '';
    if(forWhoNotInput != null) forWhoNotInput.value = '';
    if(benefitsInput != null) benefitsInput.value = '';
    if(notesInput != null) notesInput.value = '';
    if(imageOfOfferUploading != null) imageOfOfferUploading.setAttribute('src',Offer.defaultPoster);
    if(posterInputOfNewOffer != null) posterInputOfNewOffer.value = '';
    addAndEditTemplate.style = "";
}
function addOfferButton() {
    var formData = new FormData();
    formData.append('name',sessionNameInput.value);
    formData.append('price',sessionPriceInput.value);
    formData.append('duration',sessionDurationInput.value);
    if(forWhoInput != null) if(forWhoInput.value.trim() != '') formData.append('for_who',forWhoInput.value);
    if(forWhoNotInput != null) if(forWhoNotInput.value.trim() != '') formData.append('for_who_not',forWhoNotInput.value);
    if(benefitsInput != null) if(benefitsInput.value.trim() != '') formData.append('benefits',benefitsInput.value);
    if(notesInput != null) if(notesInput.value.trim() != '') formData.append('notes',notesInput.value);
    if(posterInputOfNewOffer != null) if(posterInputOfNewOffer.files.length == 1) formData.append('poster',posterInputOfNewOffer.files[0]);
    if(formData != null) Offer.addOffer(formData);
}
function addNewOfferButtonClickHandler() {
    resetValidOfAddTemplate();
    if(sessionNameInput == null) return;
    if(sessionNameInput.value.trim() == '') {
        sessionNameInput.setAttribute('class', 'input-invalid');
        if(addAndEditTemplate != null) addAndEditTemplate.scrollTo(0,0);
        return;
    }
    if(sessionNameInput.value.length > 255) {
        sessionNameInput.setAttribute('class', 'input-invalid');
        if(addAndEditTemplate != null) addAndEditTemplate.scrollTo(0,0);
        if(maxLengthAlert != null && maxLengthAlert != undefined) showPopUpMassage(maxLengthAlert);
        return;
    }
    if(sessionPriceInput == null) return;
    if(sessionPriceInput.value.trim() == '' || isNaN(sessionPriceInput.value)) {
        sessionPriceInput.setAttribute('class', 'input-invalid');
        if(addAndEditTemplate != null) addAndEditTemplate.scrollTo(0,0);
        return;
    }
    if(sessionDurationInput == null) return;
    if(sessionDurationInput.value.trim() == '' || isNaN(sessionDurationInput.value)) {
        sessionDurationInput.setAttribute('class', 'input-invalid');
        if(addAndEditTemplate != null) addAndEditTemplate.scrollTo(0,0);
        return;
    }
    addOfferButton();
}
function editOfferButtonClickHandler() {
    if(sessionNameInput == null) return;
    if(sessionNameInput.value.trim() == '') {
        sessionNameInput.setAttribute('class', 'input-invalid');
        if(addAndEditTemplate != null) addAndEditTemplate.scrollTo(0,0);
        return;
    }
    if(sessionNameInput.value.length > 255) {
        sessionNameInput.setAttribute('class', 'input-invalid');
        if(addAndEditTemplate != null) addAndEditTemplate.scrollTo(0,0);
        if(maxLengthAlert != null && maxLengthAlert != undefined) showPopUpMassage(maxLengthAlert);
        return;
    }
    if(sessionPriceInput == null) return;
    if(sessionPriceInput.value.trim() == '' || isNaN(sessionPriceInput.value)) {
        sessionPriceInput.setAttribute('class', 'input-invalid');
        if(addAndEditTemplate != null) addAndEditTemplate.scrollTo(0,0);
        return;
    }
    if(sessionDurationInput == null) return;
    if(sessionDurationInput.value.trim() == '' || isNaN(sessionDurationInput.value)) {
        sessionDurationInput.setAttribute('class', 'input-invalid');
        if(addAndEditTemplate != null) addAndEditTemplate.scrollTo(0,0);
        return;
    }
    if(Offer.currentOfferUpdating != undefined && Offer.currentOfferUpdating != null) {
        Offer.currentOfferUpdating.update();
    }
}
function RequestSetAdmission(SessionOnlineId,admission) {
    if(admission) msg = Offer.lang[16];
    else msg = Offer.lang[17];
    showPopUpMassage(msg,null,function (exitThis,popUpMassageDiv) {
        ajaxRequest('get',location.origin + '/admin/home/' + SessionOnlineId + '/setAdmission',null,function(jsonResponse) {
            if(jsonResponse != null) {
                if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
                    if(jsonResponse.status && jsonResponse.data.hasOwnProperty('admission')) {
                        var admissionButton = document.getElementById('SessionOnlineAdmissionButton' + SessionOnlineId);
                        result = jsonResponse.data.admission;
                        if(result != null) {
                            if(admissionButton != null) {
                                if(result) {
                                    admissionButton.textContent = Offer.lang[14];
                                } else {
                                    admissionButton.textContent = Offer.lang[15];
                                }
                                admissionButton.onclick = new Function("RequestSetAdmission(" + SessionOnlineId + "," + result + ");");
                            }
                            return;
                        }
                    } else if(jsonResponse.status == false && jsonResponse.hasOwnProperty('msg')) {
                        showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
            }
            showPopUpMassage(Offer.lang[7]);
        });
        exitThis(popUpMassageDiv);
    },Offer.lang[10]);
}