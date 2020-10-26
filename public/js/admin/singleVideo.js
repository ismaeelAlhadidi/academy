class SingleVideo extends HTMLElement {
    static parentElement;
    static addAndUpdateTemplate;
    static usersDataTemplate;
    static currentUpdateingVideo = null;
    static defaultPoster;
    static massegeOfLengthMoreThanMaxSize;
    static massegeOfGeneralError;
    static urlOfAddNewVideo;
    static urlOfUpdateVideo;
    static addAndUpdateFormElement;
    static formRoute;
    static langArrayFromServer;
    static tempBlobUrl;
    static massegeOfErrorInShowVideoAlert;
    static userFormColumn;
    static userFormColumnLang;

    static resetDataOfAddAndUpdateTemplate() {
        if(titleOneInputOfNewVideo != null && titleOneInputOfNewVideo != undefined) {
            titleOneInputOfNewVideo.value = '';
            titleOneInputOfNewVideo.setAttribute("class", '');
        }
        if(titleTwoInputOfNewVideo != null && titleTwoInputOfNewVideo != undefined) {
            titleTwoInputOfNewVideo.value = '';
            titleTwoInputOfNewVideo.setAttribute("class", '');
        }
        if(srcInputOfNewVideo != null && srcInputOfNewVideo != undefined) {
            srcInputOfNewVideo.value = '';
        }
        if(srcInputOfNewVideoOpenButton != null && srcInputOfNewVideoOpenButton != undefined) {
            srcInputOfNewVideoOpenButton.setAttribute("class", '');
        }
        if(dateInputOfNewVideo != null && dateInputOfNewVideo != undefined) {
            dateInputOfNewVideo.value = '';
        }
        if(selectTypeOfThisVideo != null && selectTypeOfThisVideo != undefined) {
            selectTypeOfThisVideo.value = '-1';
        }
        if(imageOfVideoUploading != null && imageOfVideoUploading != undefined) {
            imageOfVideoUploading.setAttribute("src", SingleVideo.defaultPoster);
        }
        if(posterInputOfNewVideo != null && posterInputOfNewVideo != undefined) {
            posterInputOfNewVideo.value = '';
        }
    }
    static openAddTemplate() {
        SingleVideo.currentUpdateingVideo = null;
        SingleVideo.resetDataOfAddAndUpdateTemplate();
        if(addNewVideoButton != null && addNewVideoButton != undefined) {
            addNewVideoButton.setAttribute("style", '');
        }
        if(editVideoButton != null && editVideoButton != undefined) {
            editVideoButton.setAttribute("style", 'display: none !important');
        }
        if(SingleVideo.addAndUpdateTemplate != null && SingleVideo.addAndUpdateTemplate != undefined) SingleVideo.addAndUpdateTemplate.setAttribute("style", '');
    }
    static validateAddAndUpdateTemplateData() {
        if(titleOneInputOfNewVideo == null || titleOneInputOfNewVideo == undefined) return;
        if(titleTwoInputOfNewVideo == null || titleTwoInputOfNewVideo == undefined) return;
        if(srcInputOfNewVideo == null || srcInputOfNewVideo == undefined) return;

        titleOneInputOfNewVideo.setAttribute("class", '');
        titleTwoInputOfNewVideo.setAttribute("class", '');
        if(srcInputOfNewVideoOpenButton != null && srcInputOfNewVideoOpenButton != undefined) srcInputOfNewVideoOpenButton.setAttribute("class", '');
        
        if(titleOneInputOfNewVideo.value.trim() == '') {
            titleOneInputOfNewVideo.setAttribute('class','input-invalid');
            return false;
        }
        if(titleOneInputOfNewVideo.value.length > 255) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfLengthMoreThanMaxSize);
            titleOneInputOfNewVideo.setAttribute('class','input-invalid');
            return false;
        }

        if(titleTwoInputOfNewVideo.value.trim() == '') {
            titleTwoInputOfNewVideo.setAttribute('class','input-invalid');
            return false;
        }
        if(titleTwoInputOfNewVideo.value.length > 255) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfLengthMoreThanMaxSize);
            titleTwoInputOfNewVideo.setAttribute('class','input-invalid');
            return false;
        }

        if(SingleVideo.currentUpdateingVideo == null && srcInputOfNewVideo.value == '') {
            if(srcInputOfNewVideoOpenButton != null && srcInputOfNewVideoOpenButton != undefined) srcInputOfNewVideoOpenButton.setAttribute("class", 'input-invalid');
            return false;
        }
        if(SingleVideo.currentUpdateingVideo == null && srcInputOfNewVideo.files.length != 1) {
            if(srcInputOfNewVideoOpenButton != null && srcInputOfNewVideoOpenButton != undefined) srcInputOfNewVideoOpenButton.setAttribute("class", 'input-invalid');
            return false;
        }

        return true;
    }
    static addNewSingleVideo() {
        if(SingleVideo.parentElement == null || SingleVideo.parentElement == undefined) SingleVideo.parentElement = window.document.getElementById("main");
        if(SingleVideo.parentElement == null) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        if(typeof(SingleVideo.parentElement) != "object") {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        if(! SingleVideo.validateAddAndUpdateTemplateData()) return;
        console.log("validate ok");
        let newSingleVideo = new SingleVideo();
        newSingleVideo.addThisSingleVideoInServer();
    }
    static clearUsersTemplateAndReturnContentDiv() {
        var temp = document.getElementById("contentOfUsersDataTemplate");
        if(temp != null) SingleVideo.usersDataTemplate.removeChild(temp);
        temp = document.createElement('div');
        temp.setAttribute('id', 'contentOfUsersDataTemplate');
        return temp;
    }
    static createAndReturnTableOfFormUsers() {
        var table = document.getElementById('tableOfFormUsers');
        if(table == null) {
            table = document.createElement('table');
            table.setAttribute('class' ,'table table-striped form-users-tabel');
            table.setAttribute('id' ,'tableOfFormUsers');
        }
        return table;
    }
    static createAndReturnTableHeader() {
        var thead = document.getElementById('tableHeaderOfFormUsers');
        if(thead == null) {
            thead = document.createElement('thead');
            thead.setAttribute('class' ,'no-select');
            thead.setAttribute('id' ,'tableHeaderOfFormUsers');
            var tr = document.createElement('tr');
            if(typeof(SingleVideo.userFormColumn) != "object") return null;
            if(SingleVideo.userFormColumn == null) return null;
            if(typeof(SingleVideo.userFormColumnLang) != "object") return null;
            if(SingleVideo.userFormColumnLang == null) return null;
            for(var i = 0; i < SingleVideo.userFormColumn.length; i++) {
                var th = document.createElement('td');
                th.textContent = SingleVideo.userFormColumnLang[SingleVideo.userFormColumn[i]];
                tr.appendChild(th);
            }
            thead.appendChild(tr);
        }
        return thead;
    }
    static createAndReturnTableBody() {
        var tbody = document.getElementById('tableBodyOfFormUsers');
        if(tbody == null) {
            tbody = document.createElement('tbody');
            tbody.setAttribute('id' ,'tableBodyOfFormUsers');
        }
        return tbody;
    }
    static createAndReturnFormUserRecord(data) {
        if(typeof(data) != "object") return null;
        if(data == null) return null;
        if(typeof(SingleVideo.userFormColumn) != "object") return null;
        if(SingleVideo.userFormColumn == null) return null;
        if(data.length < SingleVideo.userFormColumn.length) return null;
        var tr = document.createElement('tr');
        for(var i = 0; i < SingleVideo.userFormColumn.length; i++) {
            var key = SingleVideo.userFormColumn[i];
            if(data.hasOwnProperty(key)) {
                var td = document.createElement('td');
                td.textContent = data[key];
                tr.appendChild(td);
            }
        }
        return tr;
    }
    static createAndReturnPaginateLinksOfFormUser(data) {
        if(typeof(makeGeneralPaginationLinks) != "function") return null;
        return makeGeneralPaginationLinks(data, function (path) {
            var contentDiv = document.getElementById("contentOfUsersDataTemplate");
            if(contentDiv != null) {
                if(contentDiv.children.length >= 2) {
                    contentDiv.removeChild(contentDiv.children[1]);
                }
            }
            var table = document.getElementById('tableOfFormUsers');
            var tbody = document.getElementById('tableBodyOfFormUsers');
            if(table == null) return;
            if(tbody != null) table.removeChild(tbody);
            tbody = SingleVideo.createAndReturnTableBody();
            if(tbody != null) table.appendChild(tbody);
            SingleVideo.requestDataFromServerAndFullTableBody(path, tbody, contentDiv)
        });
    }
    static requestDataFromServerAndFullTableBody(path, tableBody, contentDiv) {
        ajaxRequest('get', path,null ,function (jsonResponse) {
            if(jsonResponse == null) {
                if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
                if(typeof(loadingDotElement) == "undefined") var loadingDotElement = document.getElementById('loadingDotElement');
                if(loadingDotElement != null) {
                    loadingDotElement.style = 'display: none;';
                    loadingDotElement.setAttribute('style', 'display: none;');
                }
                return;
            }
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data') && jsonResponse.hasOwnProperty('msg')) {
                if(jsonResponse.status) {
                    if(jsonResponse.data.hasOwnProperty('data')) {
                        if(jsonResponse.data.data.length > 0) {
                            for(var key in jsonResponse.data.data) {
                                var formUserRecord = SingleVideo.createAndReturnFormUserRecord(jsonResponse.data.data[key]);
                                if(formUserRecord != null) {
                                    tableBody.appendChild(formUserRecord);
                                }
                            }
                            SingleVideo.usersDataTemplate.appendChild(contentDiv);
                            var PaginateLinks = SingleVideo.createAndReturnPaginateLinksOfFormUser(jsonResponse.data);
                            if(PaginateLinks != null) contentDiv.appendChild(PaginateLinks);
                            SingleVideo.usersDataTemplate.style = "";
                            if(typeof(loadingDotElement) == "undefined") var loadingDotElement = document.getElementById('loadingDotElement');
                            if(loadingDotElement != null) {
                                loadingDotElement.style = 'display: none;';
                                loadingDotElement.setAttribute('style', 'display: none;');
                            }
                            return;
                        }
                    }
                    if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
                    if(typeof(loadingDotElement) == "undefined") var loadingDotElement = document.getElementById('loadingDotElement');
                    if(loadingDotElement != null) {
                        loadingDotElement.style = 'display: none;';
                        loadingDotElement.setAttribute('style', 'display: none;');
                    }
                    return;
                }
            }
            if(typeof(loadingDotElement) == "undefined") var loadingDotElement = document.getElementById('loadingDotElement');
            if(loadingDotElement != null) {
                loadingDotElement.style = 'display: none;';
                loadingDotElement.setAttribute('style', 'display: none;');
            }
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
        }, true, false);
    }
    constructor() {
        super();
        this._added = false;
        this._deleted = false;
        this._singleVideoId = null;
        this._singleVideoTitleOne = "";
        this._singleVideoTitleTwo = "";
        this._singleVideoPosterSrc = SingleVideo.defaultPoster;
        this._singleVideoTime = "";
        this._singleVideoType = "-1";
        this._singleVideoFileSrc = null;
        this._singleVideoFormUrl = "";
    }

    get data() {
        return this.getAttribute('data');
    }
    set data(value) {
        this.setAttribute('data', value);
    }

    static get observedAttributes() {
        return ['data'];
    }

    /* override from HTMLElement */
    attributeChangedCallback(name, oldValue, newValue) {
        if(name === "data") {
            this.setDataToProperties();
        }
    }

    /* override from HTMLElement */
    connectedCallback() {
        this.deleted = false;
        this.innerHTML = `<div class="playlist-div">
        <section><img src="${this.singleVideoPosterSrc}"/></section>
        <div>
            <div><span class="no-select">${SingleVideo.langArrayFromServer[0]}</span><span>${this.singleVideoTitleOne}</span></div>
            <div><span class="no-select">${SingleVideo.langArrayFromServer[1]}</span><span>${this.singleVideoTime == null ? SingleVideo.langArrayFromServer[4] : this.singleVideoTime}</span></div>
            <div><span class="no-select">${SingleVideo.langArrayFromServer[2]}</span><span style="direction: ltr; font-size: 12px;overflow-wrap: break-word;">${this.singleVideoFormUrl}</span></div>
            <div class="no-select">
                <a>${SingleVideo.langArrayFromServer[5]}</a>
                <a>${SingleVideo.langArrayFromServer[6]}</a>
                <a>${SingleVideo.langArrayFromServer[7]}</a>
                <a>${SingleVideo.langArrayFromServer[8]}</a>
            </div>
        </div></div>`;

        var tempThis = this;
        if(this.children.length > 0) {
            if(this.children[0].children.length > 1) {
                if(this.children[0].children[1].children.length > 3) {
                    /* set click handler to delete button */
                    if(this.children[0].children[1].children[3].children.length > 0) {
                        this.children[0].children[1].children[3].children[0].onclick = function () {
                            tempThis.delete();
                        };
                    }
                    /* set click handler to update button */
                    if(this.children[0].children[1].children[3].children.length > 1) {
                        this.children[0].children[1].children[3].children[1].onclick = function () {
                            tempThis.openUpdateTemplate();
                        };
                    }
                    /* set click handler to show video button */
                    if(this.children[0].children[1].children[3].children.length > 2) {
                        this.children[0].children[1].children[3].children[2].onclick = function () {
                            tempThis.playingThisVideo();
                        };
                    }
                    /* set click handler to show users button */
                    if(this.children[0].children[1].children[3].children.length > 3) {
                        this.children[0].children[1].children[3].children[3].onclick = function () {
                            tempThis.showUsersOfThisVideo();
                        };
                    }
                }
            }
        }
        this.added = true;
    }

    /* override from HTMLElement */
    disconnectedCallback() {
        // check if exisists
        if(! this.deleted) this.removeFromServer();
    }

    playingThisVideo() {
        this.openWatchingTemplateAndshowThisVideo();
    }
    showUsersOfThisVideo() {
        if(SingleVideo.usersDataTemplate != null && SingleVideo.usersDataTemplate != undefined) {
            var temp = SingleVideo.clearUsersTemplateAndReturnContentDiv();
            this.renderUsersOfThisVideo(temp);
            return;
        }
        if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
    }
    renderUsersOfThisVideo(contentDiv) {
        var tableOfFormUsers = SingleVideo.createAndReturnTableOfFormUsers();
        if(tableOfFormUsers == null) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        contentDiv.appendChild(tableOfFormUsers);
        var tableHeader = SingleVideo.createAndReturnTableHeader();
        if(tableHeader == null) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        tableOfFormUsers.appendChild(tableHeader);
        var tableBody = SingleVideo.createAndReturnTableBody();
        if(tableBody == null) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        tableOfFormUsers.appendChild(tableBody);
        var path = window.location.origin + '/admin/single-videos/get-users/' + this.singleVideoId;
        SingleVideo.requestDataFromServerAndFullTableBody(path, tableBody, contentDiv);
    }
    removeFromDocument() {
        if(SingleVideo.parentElement == null || SingleVideo.parentElement == undefined) SingleVideo.parentElement = window.document.getElementById("main");
        if(SingleVideo.parentElement == null) return;
        if(typeof(SingleVideo.parentElement) != "object") return;
        SingleVideo.parentElement.removeChild(this);
    }
    removeFromServer() {
        if(this.deleted) return;
        if(this.singleVideoId != null) ajaxRequest('get', window.location.origin + '/admin/playlist/delete/video/' + this.singleVideoId, null);
        this.deleted = true;
    }
    delete() {
        var tempThis = this;
        showPopUpMassage(SingleVideo.langArrayFromServer[9], null, function(exitThis,popUpMassageDiv) {
            tempThis.removeFromServer();
            tempThis.removeFromDocument();
            exitThis(popUpMassageDiv);
        }, SingleVideo.langArrayFromServer[10], null);
    }
    openUpdateTemplate() {
        SingleVideo.resetDataOfAddAndUpdateTemplate();
        SingleVideo.currentUpdateingVideo = this;
        if(addNewVideoButton != null && addNewVideoButton != undefined) {
            addNewVideoButton.setAttribute("style", 'display: none !important');
        }
        if(editVideoButton != null && editVideoButton != undefined) {
            editVideoButton.setAttribute("style", '');
            editVideoButton.onclick = function() {
                if(SingleVideo.currentUpdateingVideo != null)SingleVideo.currentUpdateingVideo.updateThisSingleVideoInServer();
            };
        }
        if(titleOneInputOfNewVideo != null && titleOneInputOfNewVideo != undefined) {
            titleOneInputOfNewVideo.value = SingleVideo.currentUpdateingVideo.singleVideoTitleOne;
        }
        if(titleTwoInputOfNewVideo != null && titleTwoInputOfNewVideo != undefined) {
            titleTwoInputOfNewVideo.value = SingleVideo.currentUpdateingVideo.singleVideoTitleTwo;
        }
        if(srcInputOfNewVideo != null && srcInputOfNewVideo != undefined) {
            srcInputOfNewVideo.value = '';
        }
        if(dateInputOfNewVideo != null && dateInputOfNewVideo != undefined) {
            if(SingleVideo.currentUpdateingVideo.singleVideoTime != null) dateInputOfNewVideo.value = SingleVideo.currentUpdateingVideo.singleVideoTime;
        }
        if(selectTypeOfThisVideo != null && selectTypeOfThisVideo != undefined) {
            selectTypeOfThisVideo.value = SingleVideo.currentUpdateingVideo.singleVideoType;
        }
        if(imageOfVideoUploading != null && imageOfVideoUploading != undefined) {
            imageOfVideoUploading.src = SingleVideo.currentUpdateingVideo.singleVideoPosterSrc;
        }
        if(posterInputOfNewVideo != null && posterInputOfNewVideo != undefined) {
            posterInputOfNewVideo.value = '';
        }
        if(SingleVideo.addAndUpdateTemplate != null && SingleVideo.addAndUpdateTemplate != undefined) SingleVideo.addAndUpdateTemplate.setAttribute("style", '');
    }
    updateThisSingleVideoInServer() {
        if(SingleVideo.parentElement == null || SingleVideo.parentElement == undefined) SingleVideo.parentElement = window.document.getElementById("main");
        if(SingleVideo.parentElement == null) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        if(typeof(SingleVideo.parentElement) != "object") {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        if(! SingleVideo.validateAddAndUpdateTemplateData()) return;
        if(SingleVideo.addAndUpdateFormElement != null && SingleVideo.addAndUpdateFormElement != undefined) {
            var idInput = document.createElement('input');
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', this.singleVideoId);
            SingleVideo.addAndUpdateFormElement.appendChild(idInput);
            if(srcInputOfNewVideo != null && srcInputOfNewVideo != undefined) {
                if(srcInputOfNewVideo.files.length == 1) {
                    this.addThisSingleVideoInServer();
                    return;
                }
            }
            this.editDataInServer();
        }
    }
    editDataInServer() {
        if(SingleVideo.addAndUpdateFormElement == null || SingleVideo.addAndUpdateFormElement == undefined) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        if(posterInputOfNewVideo != null && posterInputOfNewVideo != undefined) {
            if(posterInputOfNewVideo.files.length == 1) posterInputOfNewVideo.setAttribute("name", "poster_src");
            else posterInputOfNewVideo.removeAttribute("name");
        }
        if(dateInputOfNewVideo != null && dateInputOfNewVideo != undefined) {
            if(dateInputOfNewVideo.value != "") dateInputOfNewVideo.setAttribute("name", "availability_time");
            else dateInputOfNewVideo.removeAttribute("name");
        }
        if(selectTypeOfThisVideo != null && selectTypeOfThisVideo != undefined) {
            if(selectTypeOfThisVideo.value != "" && selectTypeOfThisVideo.value != "-1") selectTypeOfThisVideo.setAttribute("name", "type_id");
            else selectTypeOfThisVideo.removeAttribute("name");
        }
        if(srcInputOfNewVideo != null && srcInputOfNewVideo != undefined) srcInputOfNewVideo.removeAttribute("name");
        var tempThis = this;
        var tempFormData = new FormData(SingleVideo.addAndUpdateFormElement);
        ajaxRequest("post",SingleVideo.urlOfUpdateVideo, tempFormData,function (jsonResponse) {
            if(jsonResponse == null || jsonResponse == undefined) {
                if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
                return;
            }
            if(jsonResponse.hasOwnProperty("status")) {
                if(jsonResponse.status) {
                    if(jsonResponse.hasOwnProperty("data")) {
                        tempThis.data = JSON.stringify(jsonResponse.data);
                        tempThis.connectedCallback();
                        if(SingleVideo.addAndUpdateTemplate != null && SingleVideo.addAndUpdateTemplate != undefined) SingleVideo.addAndUpdateTemplate.setAttribute("style", 'display:none !important;');
                        if(jsonResponse.hasOwnProperty("msg")) {
                            if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
                        }
                        return;
                    }
                } else {
                    if(jsonResponse.hasOwnProperty("msg")) {
                        if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
            }
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
        });
    }
    setDataToProperties() {
        if(typeof(IsJsonString) == "function") {
            if(IsJsonString(this.data)) {
                var tempData = JSON.parse(this.data);
            } else return;
        } else return;
        if(tempData.hasOwnProperty("id")) this.singleVideoId = tempData.id;
        if(tempData.hasOwnProperty("pre_title")) this.singleVideoTitleOne = tempData.pre_title;
        if(tempData.hasOwnProperty("title")) this.singleVideoTitleTwo = tempData.title;
        if(tempData.hasOwnProperty("poster_src")) if(tempData.poster_src != "null" && tempData.poster_src != null) this.singleVideoPosterSrc = window.origin + tempData.poster_src;
        if(tempData.hasOwnProperty("availability_time")) if(tempData.availability_time != "null") this.singleVideoTime = tempData.availability_time;
        if(tempData.hasOwnProperty("type_id")) if(tempData.type_id != "null") this.singleVideoType = tempData.type_id;
        if(tempData.hasOwnProperty("src")) this.singleVideoFileSrc = tempData.src;
        if(tempData.hasOwnProperty("form_key")) this.singleVideoFormUrl = SingleVideo.formRoute + '/' + tempData.form_key;
    }
    addThisSingleVideoInServer() {
        if(SingleVideo.addAndUpdateFormElement == null || SingleVideo.addAndUpdateFormElement == undefined) {
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
            return;
        }
        if(posterInputOfNewVideo != null && posterInputOfNewVideo != undefined) {
            if(posterInputOfNewVideo.files.length == 1) posterInputOfNewVideo.setAttribute("name", "poster_src");
            else posterInputOfNewVideo.removeAttribute("name");
        }
        if(dateInputOfNewVideo != null && dateInputOfNewVideo != undefined) {
            if(dateInputOfNewVideo.value != "") dateInputOfNewVideo.setAttribute("name", "availability_time");
            else dateInputOfNewVideo.removeAttribute("name");
        }
        if(selectTypeOfThisVideo != null && selectTypeOfThisVideo != undefined) {
            if(selectTypeOfThisVideo.value != "" && selectTypeOfThisVideo.value != "-1") selectTypeOfThisVideo.setAttribute("name", "type_id");
            else selectTypeOfThisVideo.removeAttribute("name");
        }
        var tempThis = this;
        var tempFormData = new FormData(SingleVideo.addAndUpdateFormElement);
        var tempProgress = showGlobalProgressPopUpTemplate(SingleVideo.langArrayFromServer[3]);
        ajaxUploadVideo("post", SingleVideo.urlOfAddNewVideo, tempFormData,function (jsonResponse) {
            if(jsonResponse == null || jsonResponse == undefined) {
                if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
                return;
            }
            if(jsonResponse.hasOwnProperty("status")) {
                if(jsonResponse.status) {
                    if(jsonResponse.hasOwnProperty("data")) {
                        if(jsonResponse.data.hasOwnProperty("video")) {                        
                            tempThis.data = JSON.stringify(jsonResponse.data.video);
                            if(typeof(SingleVideo.currentUpdateingVideo) != "object" || SingleVideo.currentUpdateingVideo == null) {
                                var emptyDivOfSingleVideos = document.getElementById("emptyDivOfSingleVideos");
                                if(emptyDivOfSingleVideos != null) SingleVideo.parentElement.removeChild(emptyDivOfSingleVideos);
                                if(SingleVideo.parentElement.children.length > 1) SingleVideo.parentElement.insertBefore(tempThis, SingleVideo.parentElement.children[1]);
                                window.scrollTo(0,0);
                            } else {
                                tempThis.connectedCallback();
                            }
                            if(SingleVideo.addAndUpdateTemplate != null && SingleVideo.addAndUpdateTemplate != undefined) SingleVideo.addAndUpdateTemplate.setAttribute("style", 'display:none !important;');
                            closeGlobalProgressPopUpTemplate();
                            return;
                        }
                    }
                } else {
                    if(jsonResponse.hasOwnProperty("msg")) {
                        closeGlobalProgressPopUpTemplate();
                        if(typeof(showPopUpMassage) == "function") showPopUpMassage(jsonResponse.msg);
                        return;
                    }
                }
            }
            closeGlobalProgressPopUpTemplate();
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
        }, function (e) {
            if(tempProgress.children.length > 0) {
                if(tempProgress.children[0].children.length > 1) {
                    var uploaded_percent = Math.round((e.loaded / e.total)*100);
                    tempProgress.children[0].children[0].style = 'width: ' + uploaded_percent + '%;';
                    tempProgress.children[0].children[1].textContent = uploaded_percent + '%';
                }
            }
        }, function () {
            closeGlobalProgressPopUpTemplate();
            if(typeof(showPopUpMassage) == "function") showPopUpMassage(SingleVideo.massegeOfGeneralError);
        });
    }
    openWatchingTemplateAndshowThisVideo() {
        if(watchingVideoElement == null || watchingDiv == null) return;
        if(this.singleVideoFileSrc != null) {
            if(typeof(this.singleVideoFileSrc) == "object" && this.singleVideoFileSrc != null) {
                SingleVideo.tempBlobUrl = URL.createObjectURL(this.singleVideoFileSrc);
                watchingVideoElement.setAttribute('src', SingleVideo.tempBlobUrl);
            } else watchingVideoElement.setAttribute('src', this.singleVideoFileSrc);
            watchingDiv.setAttribute('style', '');
            return;
        }
        showPopUpMassage(SingleVideo.massegeOfErrorInShowVideoAlert);
    }

    get deleted() { return this._deleted; }
    set deleted(value) { this._deleted = value; }

    get added() { return this._added; }
    set added(value) { this._added = value; }

    get singleVideoId() { return this._singleVideoId; }
    set singleVideoId(value) { this._singleVideoId = value; }

    get singleVideoTitleOne() { return this._singleVideoTitleOne; }
    set singleVideoTitleOne(value) { this._singleVideoTitleOne = value; }

    get singleVideoTitleTwo() { return this._singleVideoTitleTwo; }
    set singleVideoTitleTwo(value) { this._singleVideoTitleTwo = value; }

    get singleVideoPosterSrc() { 
        if(this._singleVideoPosterSrc != null) return this._singleVideoPosterSrc;
        else return SingleVideo.defaultPoster;
    }
    set singleVideoPosterSrc(value) { this._singleVideoPosterSrc = value; }

    get singleVideoTime() { return this._singleVideoTime; }
    set singleVideoTime(value) { this._singleVideoTime = value; }

    get singleVideoType() { return this._singleVideoType; }
    set singleVideoType(value) { this._singleVideoType = value; }

    get added() { return this._singleVideoFileSrc; }
    set added(value) { this._singleVideoFileSrc = value; }

    get singleVideoFormUrl() { return this._singleVideoFormUrl; }
    set singleVideoFormUrl(value) { this._singleVideoFormUrl = value; }
}
