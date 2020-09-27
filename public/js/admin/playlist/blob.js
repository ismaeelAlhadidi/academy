class Blob {
    static maxLengthAlert;
    static addAndEditBlobTemplate;
    static deleteBlobUrl;
    static parentOfWatingElements;
    static imageOfBlobUploading;
    static titleOneInputOfNewBlob;
    static titleTwoInputOfNewBlob;
    static dateInputOfNewBlob;
    static selectTypeOfThisBlob;
    static descInputOfNewBlob;
    static posterInputOfNewBlob;
    static srcInputOfNewBlob;
    static addNewBlobButton;
    static editBlobButton;
    static srcInputOfNewBlobOpenButton;
    static langOfBlobElement;
    static langOfWatingBlobElement;
    static currentUpdate;
    static removeAlert;
    static uploadBlobUrl;

    static validDataForUpdating() {
        if(Blob.titleOneInputOfNewBlob == null || Blob.titleTwoInputOfNewBlob == null || Blob.srcInputOfNewBlobOpenButton == null) return false;
        Blob.titleOneInputOfNewBlob.setAttribute('class','');
        Blob.titleTwoInputOfNewBlob.setAttribute('class','');
        Blob.srcInputOfNewBlobOpenButton.setAttribute('class','');
        if(Blob.titleOneInputOfNewBlob.value.trim() == '') {
            Blob.titleOneInputOfNewBlob.setAttribute('class','input-invalid');
            return false;
        }
        if(Blob.titleOneInputOfNewBlob.value.length > 255) {
            showPopUpMassage(Blob.maxLengthAlert);
            Blob.titleOneInputOfNewBlob.setAttribute('class','input-invalid');
            return false;
        }
        if(Blob.titleTwoInputOfNewBlob.value.trim() == '') {
            Blob.titleTwoInputOfNewBlob.setAttribute('class','input-invalid');
            return false;
        }
        if(Blob.titleTwoInputOfNewBlob.value.length > 255) {
            showPopUpMassage(Blob.maxLengthAlert);
            Blob.titleTwoInputOfNewBlob.setAttribute('class','input-invalid');
            return false;
        }
        return true;
    }
    static validDataForAdding() {
        if(Blob.titleOneInputOfNewBlob == null || Blob.titleTwoInputOfNewBlob == null || Blob.srcInputOfNewBlobOpenButton == null) return false;
        Blob.titleOneInputOfNewBlob.setAttribute('class','');
        Blob.titleTwoInputOfNewBlob.setAttribute('class','');
        Blob.srcInputOfNewBlobOpenButton.setAttribute('class','');
        if(Blob.titleOneInputOfNewBlob.value.trim() == '') {
            Blob.titleOneInputOfNewBlob.setAttribute('class','input-invalid');
            return false;
        }
        if(Blob.titleOneInputOfNewBlob.value.length > 255) {
            showPopUpMassage(Blob.maxLengthAlert);
            Blob.titleOneInputOfNewBlob.setAttribute('class','input-invalid');
            return false;
        }
        if(Blob.titleTwoInputOfNewBlob.value.trim() == '') {
            Blob.titleTwoInputOfNewBlob.setAttribute('class','input-invalid');
            return false;
        }
        if(Blob.titleTwoInputOfNewBlob.value.length > 255) {
            showPopUpMassage(Blob.maxLengthAlert);
            Blob.titleTwoInputOfNewBlob.setAttribute('class','input-invalid');
            return false;
        }
        if(Blob.srcInputOfNewBlob.files.length != 1) {
            if(Blob.srcInputOfNewBlobOpenButton != null) Blob.srcInputOfNewBlobOpenButton.setAttribute('class','input-invalid');
            return false;
        }
        return true;
    }
    static refreshTypes() {
        if(Blob.selectTypeOfThisBlob != null) {
            while(Blob.selectTypeOfThisBlob.children.length > 1)Blob.selectTypeOfThisBlob.removeChild(Blob.selectTypeOfThisBlob.children[Blob.selectTypeOfThisBlob.children.length-1]);
            var tempSelectedTypes = document.getElementsByClassName('type');
            if(tempSelectedTypes != null && tempSelectedTypes != undefined){
                if(tempSelectedTypes.length > 0) {
                    for(var i = 0; i < tempSelectedTypes.length;i++) {
                        var tempOptionTypeOfThisVideo = document.createElement('option');
                        if(tempSelectedTypes[i].children.length > 0) {
                            tempOptionTypeOfThisVideo.textContent = tempSelectedTypes[i].children[0].textContent;
                            if(tempSelectedTypes[i].children[0].children.length > 0) {
                                tempOptionTypeOfThisVideo.setAttribute('value',tempSelectedTypes[i].children[0].children[0].value); 
                            }
                        }
                        Blob.selectTypeOfThisBlob.appendChild(tempOptionTypeOfThisVideo);
                    }
                }
            }
        }
    }
    static refreshAddTemplate() {
        if(Blob.titleOneInputOfNewBlob != null) {
            Blob.titleOneInputOfNewBlob.value = '';
            Blob.titleOneInputOfNewBlob.setAttribute('class','');
        }
        if(Blob.titleTwoInputOfNewBlob != null) {
            Blob.titleTwoInputOfNewBlob.value = '';
            Blob.titleTwoInputOfNewBlob.setAttribute('class','');
        }
        if(Blob.srcInputOfNewBlob != null) Blob.srcInputOfNewBlob.value = '';
        if(Blob.srcInputOfNewBlobOpenButton != null) Blob.srcInputOfNewBlobOpenButton.setAttribute('class','');
        if(Blob.posterInputOfNewBlob != null) Blob.posterInputOfNewBlob.value = '';
        if(Blob.dateInputOfNewBlob != null)  Blob.dateInputOfNewBlob.value = '';
        if(Blob.descInputOfNewBlob != null)  Blob.descInputOfNewBlob.value = '';
        if(Blob.imageOfBlobUploading != null) Blob.imageOfBlobUploading.src = this.blobType == "book" ? Book.defaultPoster : Audio.defaultPoster;
    }
    constructor(title1,title2,date,type,desc,poster,file,jsonObject = null) {
        if(jsonObject == null) {
            this._title1 = title1;
            this._title2 = title2;
            this._date = date;
            this._type = type;
            this._desc = desc;
            this._poster = poster;
            this._file = file;
            if(typeof(file) == "object" && file != null) {
                this._size = file.size;
            } else this._size = 0;
            this._uploaded = false;
            this._playlistId = -1;
            this._tryUpload = 0;
            this._id = -1;
            this._posterUploaded = false;
            this._updateFormData = null;
            this._updated = false;
            this.addToCollection();
            this.createBlobElement();
            this.createBlobWatingElement();
        } else this.uploadedBlobConstuctor(jsonObject);
    }
    uploadedBlobConstuctor(jsonObject) {
        if(jsonObject == null || jsonObject == undefined) return;
        if(jsonObject.hasOwnProperty('id')) {
            this._id = jsonObject.id;
            this._uploaded = true;
            this._tryUpload = 1;
        }
        if(jsonObject.hasOwnProperty('pre_title')) this._title1 = jsonObject.pre_title;
        if(jsonObject.hasOwnProperty('title')) this._title2 = jsonObject.title;
        if(jsonObject.hasOwnProperty('availability_time')) this._date = jsonObject.availability_time;
        if(jsonObject.hasOwnProperty('type_id')) this._type = jsonObject.type_id;
        if(jsonObject.hasOwnProperty('playlist_id')) this._playlistId = jsonObject.playlist_id;
        if(jsonObject.hasOwnProperty('poster_src')) {
            this._poster = jsonObject.poster_src;
            this._posterUploaded = true;
        }
        if(jsonObject.hasOwnProperty('size')) this._size = jsonObject.size;
        else this._size = 0;
        if(jsonObject.hasOwnProperty('description')) this._desc = jsonObject.description;
        this._updated = false;
        this._updateFormData = new FormData();
        this.addToCollection();
        this.createBlobElement();
    }
    get size() { return this._size; }
    set size(value) { this._size = value; }
    get id() { return this._id; }
    set id(value) { this._id = value; }
    get playlistId() { return this._playlistId; }
    set playlistId(value) { this._playlistId = value; }
    get tryUpload() { return this._tryUpload; }
    set tryUpload(value) { this._tryUpload = value; }
    get uploaded() { return this._uploaded; }
    set uploaded(value) { this._uploaded = value;
        if(value) {
            if(this.updateFormData == null) this.updateFormData = new FormData();
        }
    }
    get title1() {
        return this._title1;
    }
    set title1(value) {
        this._title1 = value;
        if(this.id != -1 && this.updateFormData != null) this.updateFormData.append('pre_title',value);
    }
    get title2() {
        return this._title2;
    }
    set title2(value) {
        this._title2 = value;
        if(this.id != -1 && this.updateFormData != null) this.updateFormData.append('title',value);
    }
    get date() {
        return this._date;
    }
    set date(value) {
        this._date = value;
        if(this.id != -1 && this.updateFormData != null) this.updateFormData.append('availability_time',value);
    }
    get type() {
        return this._type;
    }
    set type(value) {
        this._type = value;
        if(this.id != -1 && this.updateFormData != null && value != null) this.updateFormData.append('type_id',value);
    }
    get desc() {
        return this._desc;
    }
    set desc(value) {
        this._desc = value;
        if(this.id != -1 && this.updateFormData != null) this.updateFormData.append('description',value);
    }
    get poster() {
        return this._poster;
    }
    set poster(value) {
        this._poster = value;
        if(this.id != -1 && this.updateFormData != null && typeof(value) == "object") {
            this.updateFormData.append('poster_src',value);
            this.posterUploaded = false;
        }
    }
    get file() {
        return this._file;
    }
    set file(value) {
        this._file = value;
        if(this.id != -1 && this.updateFormData != null && typeof(value) == "object") {
            this.updateFormData.append('blob',value);
            this.uploaded = false;
            this.tryUpload = 0;
        }
        if(typeof(value) == "object") {
            this._size = value.size;
        }
    }
    get updateFormData() {
        if(! this.updated) this.updated = true;
        return this._updateFormData;
    }
    set updateFormData(value) {
        this._updateFormData = value;
    }
    get updated() {
        return this._updated;
    }
    set updated(value) {
        this._updated = value;
    }
    get index() { return this._index; }
    set index(value) { this._index = value; }
    get blobType(){ return this._blobType; }
    set blobType(value){ this._blobType = value; }
    openEditTemplate() {
        Blob.currentUpdate = this;
        Blob.refreshTypes();
        Blob.refreshAddTemplate();
        if(Blob.editBlobButton != null && Blob.editBlobButton != undefined) {
            Blob.editBlobButton.setAttribute('style', '');
            Blob.editBlobButton.onclick = function () {
                Blob.currentUpdate.update();
            };
        }
        if(Blob.addNewBlobButton != null && Blob.addNewBlobButton != undefined) {
            Blob.addNewBlobButton.setAttribute('style', 'display:none !important');
        }
        if(addAndEditBlobTemplateTitle != null && addAndEditBlobTemplateTitle != undefined) {
            if(Blob.currentUpdate.blobType == "book") addAndEditBlobTemplateTitle.textContent = Book.titleOfEditTemplate;
            else addAndEditBlobTemplateTitle.textContent = Audio.titleOfEditTemplate;
        }
        if(Blob.srcInputOfNewBlob != null) {
            Blob.srcInputOfNewBlob.setAttribute('accept', (Blob.currentUpdate.blobType == "book") ? 'application/pdf' : 'audio/*');
        }
        Blob.currentUpdate.renderDataForEditing();
        Blob.addAndEditBlobTemplate.setAttribute('style', '');
    }
    renderDataForEditing() {
        if(Blob.titleOneInputOfNewBlob != null) Blob.titleOneInputOfNewBlob.value = this.title1;
        if(Blob.titleTwoInputOfNewBlob != null) Blob.titleTwoInputOfNewBlob.value = this.title2;
        if(Blob.descInputOfNewBlob != null) Blob.descInputOfNewBlob.value = this.desc;
        if(Blob.imageOfBlobUploading != null) {
            if(this.poster != null) {
                if(this.poster != null) {
                    if(typeof(this.poster) == "object") {
                        if(window.File && window.FileList && window.FileReader) {
                            if(this.poster.type.match('image')) {
                                var fileReader = new FileReader();
                                fileReader.addEventListener("load",function(event){
                                    var picFile = event.target;
                                    Blob.imageOfBlobUploading.setAttribute('src',picFile.result);
                                });
                                fileReader.readAsDataURL(this.poster);
                            }
                        } else {
                            Blob.imageOfBlobUploading.setAttribute('src', Blob.currentUpdate.constructor.defaultPoster);
                        }
                    } else {
                        Blob.imageOfBlobUploading.setAttribute('src',window.location.origin + this.poster);
                    }
                } else {
                    Blob.imageOfBlobUploading.setAttribute('src', Blob.currentUpdate.constructor.defaultPoster);
                }
            } else {
                Blob.imageOfBlobUploading.setAttribute('src', Blob.currentUpdate.constructor.defaultPoster);
            }
        }
        if(Blob.dateInputOfNewBlob != null)if(this.date != null) Blob.dateInputOfNewBlob.value = this.date;
        if(Blob.selectTypeOfThisBlob != null) {
            while(Blob.selectTypeOfThisBlob.children.length > 1) Blob.selectTypeOfThisBlob.removeChild(Blob.selectTypeOfThisBlob.children[Blob.selectTypeOfThisBlob.children.length-1]);
            var tempSelectedTypes = document.getElementsByClassName('type');
            if(tempSelectedTypes != null && tempSelectedTypes != undefined) {
                if(tempSelectedTypes.length > 0) {
                    for(var i = 0; i < tempSelectedTypes.length;i++) {
                        var tempOptionTypeOfThisBlob = document.createElement('option');
                        if(tempSelectedTypes[i].children.length > 0) {
                            tempOptionTypeOfThisBlob.textContent = tempSelectedTypes[i].children[0].textContent;
                            if(tempSelectedTypes[i].children[0].children.length > 0) {
                                tempOptionTypeOfThisBlob.setAttribute('value',tempSelectedTypes[i].children[0].children[0].value); 
                            }
                        }
                        if(this.type != null) {
                            if(tempOptionTypeOfThisBlob.value == this.type) {
                                var selected = true;
                                tempOptionTypeOfThisBlob.setAttribute('selected','selected');
                            }
                        }
                        Blob.selectTypeOfThisBlob.appendChild(tempOptionTypeOfThisBlob);
                    }
                }
                if(this.type != null) {
                    if(! selected) {
                        var tempOptionTypeOfThisBlob = document.createElement('option');
                        tempOptionTypeOfThisBlob.setAttribute('value', this.type);
                        ajaxRequest('get', window.location.origin + '/admin/playlist/getTypeNameFromId/' + this.type, null, function(jsonObject) {
                            if(jsonObject == null) return;
                            if(jsonObject.hasOwnProperty('name')) {
                                tempOptionTypeOfThisBlob.textContent = jsonObject.name;
                            }
                        });
                        Blob.selectTypeOfThisBlob.appendChild(tempOptionTypeOfThisBlob);
                    }
                }
            }
        }
        if(this.type != null) Blob.selectTypeOfThisBlob.value = this.type;
    }
    getFormData () {
        if(this.id != -1 && this.updated) {
            return this.getUpdateFormData();
        }
        var tempFormData = new FormData();
        tempFormData.append('blob',this.file);
        if(this.poster != null) tempFormData.append('poster_src',this.poster);
        tempFormData.append('pre_title',this.title1);
        tempFormData.append('title',this.title2);
        if(this.date != null) tempFormData.append('availability_time',this.date);
        if(this.type != null) tempFormData.append('type_id',this.type);
        if(this.desc != null) tempFormData.append('description',this.desc);
        if(this.playlistId != -1) tempFormData.append('playlist_id',this.playlistId);
        return tempFormData;
    }
    getUpdateFormData() {
        if(this.id != -1) {
            this.updateFormData.append('id',this.id);
            return this.updateFormData;
        }
        return null;
    }
    removeBlobRequest () {
        var tempThis = this;
        showPopUpMassage(Blob.removeAlert, null, function (exitThis,popUpMassageDiv) {
            if(tempThis.id != -1) {
                ajaxRequest('get', Blob.deleteBlobUrl + '/' +  tempThis.blobType + '/' + tempThis.id, null, function(jsonResponse) {
                    tempThis.removeBlobElement();
                    tempThis.removeBlobWatingElement();
                    tempThis.removeFromCollection();
                });
            } else {
                tempThis.removeBlobElement();
                tempThis.removeBlobWatingElement();
                tempThis.removeFromCollection();
            }
            exitThis(popUpMassageDiv);
        }, Blob.langOfBlobElement[3]);
    }
    update() {
        if(! Blob.validDataForUpdating()) return;
        var title1,title2,date = null,type = null,desc = null,poster = null,file;
        title1 = Blob.titleOneInputOfNewBlob.value.trim();
        title2 = Blob.titleTwoInputOfNewBlob.value.trim();
        if(Blob.descInputOfNewBlob != null) desc = Blob.descInputOfNewBlob.value;
        if(Blob.dateInputOfNewBlob != null) if(Blob.dateInputOfNewBlob.value.trim() != '') date = Blob.dateInputOfNewBlob.value;
        if(Blob.selectTypeOfThisBlob != null) {
            if(Blob.selectTypeOfThisBlob.value != -1) type = Blob.selectTypeOfThisBlob.value;
        }
        if(Blob.posterInputOfNewBlob != null) {
            if(Blob.posterInputOfNewBlob.files.length  == 1) poster = Blob.posterInputOfNewBlob.files[0];
        }
        if(Blob.srcInputOfNewBlob.files.length == 1) {
            file = Blob.srcInputOfNewBlob.files[0];
            this.file = file;
        }

        this.title1 = title1;
        this.title2 = title2;
        this.date = date;
        this.type = type;
        this.desc = desc;
        if(poster != null) this.poster = poster;
        this.editBlobElement();
        this.editBlobWatingElement();
        Blob.refreshAddTemplate();
        Blob.addAndEditBlobTemplate.setAttribute('style', 'display: none !important;');
    }
}
class Audio extends Blob {
    static parentOfElements;
    static titleOfAddTemplate;
    static titleOfEditTemplate;
    static deleteAlert;
    static defaultPoster;

    static openAddTemplate() {
        Blob.refreshTypes();
        Blob.refreshAddTemplate();
        if(addAndEditBlobTemplateTitle != null && addAndEditBlobTemplateTitle != undefined) 
            addAndEditBlobTemplateTitle.textContent = Audio.titleOfAddTemplate;

        if(Blob.imageOfBlobUploading != null && Blob.imageOfBlobUploading != undefined)
            Blob.imageOfBlobUploading.setAttribute('src', Audio.defaultPoster);

        if(Blob.addAndEditBlobTemplate != null && Blob.addAndEditBlobTemplate != undefined) 
            Blob.addAndEditBlobTemplate.setAttribute('style', '');

        if(Blob.srcInputOfNewBlob != null) {
            Blob.srcInputOfNewBlob.setAttribute('accept', 'audio/*');
        }
        if(Blob.editBlobButton != null && Blob.editBlobButton != undefined) {
            Blob.editBlobButton.setAttribute('style', 'display:none !important');
        }
        if(Blob.addNewBlobButton != null && Blob.addNewBlobButton != undefined) {
            Blob.addNewBlobButton.setAttribute('style', '');
            Blob.addNewBlobButton.onclick = function () {
                Audio.addNewBlob();
            };
        }
    }
    static addNewBlob() {
        if(! Blob.validDataForAdding()) return;
        var title1 = Blob.titleOneInputOfNewBlob.value != "" ? Blob.titleOneInputOfNewBlob.value : null,
            title2 = Blob.titleTwoInputOfNewBlob.value != "" ? Blob.titleTwoInputOfNewBlob.value : null,
            date = Blob.dateInputOfNewBlob.value != "" ? Blob.dateInputOfNewBlob.value : null,
            type = Blob.selectTypeOfThisBlob.value != "-1" ? Blob.selectTypeOfThisBlob.value : null,
            desc = Blob.descInputOfNewBlob.value != "" ? Blob.descInputOfNewBlob.value : null,
            file = Blob.srcInputOfNewBlob.files[0];
        
        if(Blob.posterInputOfNewBlob != null) {
            if(Blob.posterInputOfNewBlob.value != "") {
                if(Blob.posterInputOfNewBlob.files.length > 0) poster = Blob.posterInputOfNewBlob.files[0];
                else var poster = null;
            } else var poster = null;
        } else var poster = null;

        var temp = new Audio(title1,title2,date,type,desc,poster,file,null);
        temp.blobType = 'audio';
        Blob.addAndEditBlobTemplate.style = "display:none !important";
    }

    createBlobElement() {
        if(Blob.langOfBlobElement == undefined || Blob.langOfBlobElement == null) return;
        if(Audio.parentOfElements == undefined || Audio.parentOfElements == null) return;

        var tempBlobDiv = document.createElement('div'),
            titleOfBlobDiv = document.createElement('span'),
            sizeOfBlobDiv = document.createElement('span'),
            posterOfBlobDiv = document.createElement('img'),
            removeBlobDiv = document.createElement('canvas'),
            editButtonOfBlobDiv = document.createElement('a');
        
        var index = this.index;
        tempBlobDiv.setAttribute('id','audio' + index);
        tempBlobDiv.setAttribute('class','video');

        titleOfBlobDiv.textContent = this.title1;
        sizeOfBlobDiv.textContent = Blob.langOfBlobElement[0] + ': '+ Math.ceil(this.size/1000000) + ' MB';
        if(this.poster != null) {
            if(typeof(this.poster) == "string") {
                posterOfBlobDiv.setAttribute('src', window.location.origin + this.poster);
            } else {
                if(window.File && window.FileList && window.FileReader) {
                    if(this.poster.type.match('image')) {
                        var fileReader = new FileReader();
                        fileReader.addEventListener("load",function(event) {
                            var picFile = event.target;
                            posterOfBlobDiv.setAttribute('src', picFile.result);
                        });
                        fileReader.readAsDataURL(this.poster);
                    } else {
                        posterOfBlobDiv.setAttribute('src', Audio.defaultPoster);
                    }
                } else {
                    posterOfBlobDiv.setAttribute('src', Audio.defaultPoster);
                }
            }
        } else {
            posterOfBlobDiv.setAttribute('src', Audio.defaultPoster);
        }
        removeBlobDiv.width = 25;
        removeBlobDiv.height = 25;
        if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(removeBlobDiv,'red');
        removeBlobDiv.onclick = new Function("removeAudioFromPlaylist(" + index + ",'" + Blob.langOfBlobElement[2] + "');");
        editButtonOfBlobDiv.textContent = Blob.langOfBlobElement[1];
        editButtonOfBlobDiv.onclick = new Function("editAudio(" + index + ",'" + Blob.langOfBlobElement[2] + "');");
        tempBlobDiv.appendChild(titleOfBlobDiv);
        tempBlobDiv.appendChild(sizeOfBlobDiv);
        tempBlobDiv.appendChild(posterOfBlobDiv);
        tempBlobDiv.appendChild(removeBlobDiv);
        tempBlobDiv.appendChild(editButtonOfBlobDiv);

        Audio.parentOfElements.appendChild(tempBlobDiv);
    }
    createBlobWatingElement() {
        if(Blob.langOfWatingBlobElement == undefined || Blob.langOfWatingBlobElement == null) return;
        if(Blob.parentOfWatingElements == undefined || Blob.parentOfWatingElements == null) return;
        
        var tempWatingBlobDiv = document.createElement('div'),
            titleOfWatingBlobDiv = document.createElement('span'),
            posterOfWatingBlobDiv = document.createElement('img'),
            sectionOfWatingBlobDiv = document.createElement('section'),
            sectionDiv = document.createElement('div'),
            sectionDivDiv = document.createElement('div'),
            sectionDivSpan = document.createElement('span'),
            sectionFooter = document.createElement('footer');
            
        var index = this.index;

        tempWatingBlobDiv.setAttribute('id','watingAudio' + index);
        tempWatingBlobDiv.setAttribute('class','video');
        sectionOfWatingBlobDiv.setAttribute('class','default-progress');
        titleOfWatingBlobDiv.textContent = this.title1;
        if(this.poster != null) {
            if(this.posterUploaded) {
                posterOfWatingBlobDiv.setAttribute('src',window.location.origin + this.poster);
            } else {
                if(typeof(this.poster) == "object") {
                    if(window.File && window.FileList && window.FileReader) {
                        if(this.poster.hasOwnProperty('type')) {
                            if(this.poster.type.match('image')) {
                                var fileReader = new FileReader();
                                fileReader.addEventListener("load",function(event){
                                    var picFile = event.target;
                                    posterOfWatingBlobDiv.setAttribute('src',picFile.result);
                                });
                                fileReader.readAsDataURL(this.poster);
                            } else {
                                posterOfWatingBlobDiv.setAttribute('src', Audio.defaultPoster);
                            }
                        } else {
                            posterOfWatingBlobDiv.setAttribute('src', Audio.defaultPoster);
                        }
                    } else {
                        posterOfWatingBlobDiv.setAttribute('src', Audio.defaultPoster);
                    }
                } else {
                    posterOfWatingBlobDiv.setAttribute('src',this.poster);
                }
            }
        } else {
            posterOfWatingBlobDiv.setAttribute('src', Audio.defaultPoster);
        }
        

        sectionDivSpan.textContent = '0%';
        sectionFooter.textContent = (index == 0) ? Blob.langOfWatingBlobElement[1] : Blob.langOfWatingBlobElement[2];
        sectionDiv.appendChild(sectionDivDiv);
        sectionDiv.appendChild(sectionDivSpan);
        sectionOfWatingBlobDiv.appendChild(sectionDiv);
        sectionOfWatingBlobDiv.appendChild(sectionFooter);
        tempWatingBlobDiv.appendChild(titleOfWatingBlobDiv);
        tempWatingBlobDiv.appendChild(posterOfWatingBlobDiv);
        tempWatingBlobDiv.appendChild(sectionOfWatingBlobDiv);

        Blob.parentOfWatingElements.appendChild(tempWatingBlobDiv);
    }
    addToCollection() {
        if(Array.isArray(audios)) {
            this.index = audios.length;
            audios.push(this);
        }
    }
    removeBlobElement() {
        if(Audio.parentOfElements == null || Audio.parentOfElements == undefined) return;
        var temp = document.getElementById('audio' + this.index);
        if(temp != null && temp != undefined) Audio.parentOfElements.removeChild(temp);
    }
    removeBlobWatingElement () {
        if(Blob.parentOfWatingElements == null || Blob.parentOfWatingElements == undefined) return;
        var temp = document.getElementById('watingAudio' + this.index);
        if(temp != null && temp != undefined) Blob.parentOfWatingElements.removeChild(temp);
    }
    removeFromCollection() {
        if(Array.isArray(audios)) if(this.index < audios.length && this.index > -1) delete audios[this.index];
    }
    editBlobElement() {
        var tempBlobElement = document.getElementById('audio' + this.index);
        if(tempBlobElement != null) {
            if(tempBlobElement.children.length > 3) {
                if(tempBlobElement.children[0] != null) tempBlobElement.children[0].textContent = this.title1;
                if(tempBlobElement.children[1] != null) {
                    if(! this.uploaded)var size = this.file.size;
                    else var size = this.size;
                    if(isNaN(size)) size = 0;
                    tempBlobElement.children[1].textContent = Blob.langOfBlobElement[0] + ': ' + Math.ceil(size/1000000) + ' MB';
                }
                if(! this.posterUploaded  && this.poster != null) {
                    if(typeof(this.poster) == "object") {
                        if(tempBlobElement.children[2] != null) {
                            if(window.File && window.FileList && window.FileReader) {
                                if(this.poster.type.match('image')) {
                                    var fileReader = new FileReader();
                                    fileReader.addEventListener("load",function(event){
                                        var picFile = event.target;
                                        tempBlobElement.children[2].setAttribute('src',picFile.result);
                                    });
                                    fileReader.readAsDataURL(this.poster);
                                }
                            }
                        }
                    } else tempBlobElement.children[2].setAttribute('src', this.poster);
                } else if(this.poster != null) {
                    tempBlobElement.children[2].setAttribute('src', this.poster);
                } else tempBlobElement.children[1].setAttribute('src', Audio.defaultPoster);
            }
        }
    }
    editBlobWatingElement() {
        var tempBlobWatingElement = document.getElementById('watingAudio' + this.index);
        if(tempBlobWatingElement != null) {
            if(tempBlobWatingElement.children.length > 2) {
                if(tempBlobWatingElement.children[0] != null) tempBlobWatingElement.children[0].textContent = this.title1;
                if(tempBlobWatingElement.children[1] != null) {
                    if(! this.posterUploaded  && this.poster != null) {
                        if(typeof(this.poster) == "object") {
                            if(window.File && window.FileList && window.FileReader && this.poster != null) {
                                if(this.poster.type.match('image')) {
                                    var fileReader = new FileReader();
                                    fileReader.addEventListener("load",function(event){
                                        var picFile = event.target;
                                        tempBlobWatingElement.children[1].setAttribute('src',picFile.result);
                                    });
                                    fileReader.readAsDataURL(this.poster);
                                }
                            }
                        } else tempBlobWatingElement.children[1].setAttribute('src', this.poster);
                    } else {
                        if(this.poster != null) tempBlobWatingElement.children[1].setAttribute('src', this.poster);
                        else tempBlobWatingElement.children[1].setAttribute('src', Audio.defaultPoster);
                    }
                }
            }
        }
    }
}

class Book extends Blob {
    static parentOfElements;
    static titleOfAddTemplate;
    static titleOfEditTemplate;
    static deleteAlert;
    static defaultPoster;
    
    static openAddTemplate() {
        Blob.refreshTypes();
        Blob.refreshAddTemplate();
        if(addAndEditBlobTemplateTitle != null && addAndEditBlobTemplateTitle != undefined) 
            addAndEditBlobTemplateTitle.textContent = Book.titleOfAddTemplate;
        if(Blob.imageOfBlobUploading != null && Blob.imageOfBlobUploading != undefined)
            Blob.imageOfBlobUploading.setAttribute('src', Book.defaultPoster);
        
        if(Blob.addAndEditBlobTemplate != null && Blob.addAndEditBlobTemplate != undefined) 
            Blob.addAndEditBlobTemplate.setAttribute('style', '');
        
        if(Blob.srcInputOfNewBlob != null) {
            Blob.srcInputOfNewBlob.setAttribute('accept', 'application/pdf');
        }
        if(Blob.editBlobButton != null && Blob.editBlobButton != undefined) {
            Blob.editBlobButton.setAttribute('style', 'display:none !important');
        }
        if(Blob.addNewBlobButton != null && Blob.addNewBlobButton != undefined) {
            Blob.addNewBlobButton.setAttribute('style', '');
            Blob.addNewBlobButton.onclick = function () {
                Book.addNewBlob();
            };
        }
    }
    static addNewBlob() {
        if(! Blob.validDataForAdding()) return;
        var title1 = Blob.titleOneInputOfNewBlob.value != "" ? Blob.titleOneInputOfNewBlob.value : null,
            title2 = Blob.titleTwoInputOfNewBlob.value != "" ? Blob.titleTwoInputOfNewBlob.value : null,
            date = Blob.dateInputOfNewBlob.value != "" ? Blob.dateInputOfNewBlob.value : null,
            type = Blob.selectTypeOfThisBlob.value != "-1" ? Blob.selectTypeOfThisBlob.value : null,
            desc = Blob.descInputOfNewBlob.value != "" ? Blob.descInputOfNewBlob.value : null,
            file = Blob.srcInputOfNewBlob.files[0];
        
        if(Blob.posterInputOfNewBlob != null) {
            if(Blob.posterInputOfNewBlob.value != "") {
                if(Blob.posterInputOfNewBlob.files.length > 0) poster = Blob.posterInputOfNewBlob.files[0];
                else var poster = null;
            } else var poster = null;
        } else var poster = null;

        var temp = new Book(title1,title2,date,type,desc,poster,file,null);
        temp.blobType = 'book';
        Blob.addAndEditBlobTemplate.style = "display:none !important";
    }
    
    createBlobElement() {
        if(Blob.langOfBlobElement == undefined || Blob.langOfBlobElement == null) return;
        if(Book.parentOfElements == undefined || Book.parentOfElements == null) return;

        var tempBlobDiv = document.createElement('div'),
            titleOfBlobDiv = document.createElement('span'),
            sizeOfBlobDiv = document.createElement('span'),
            posterOfBlobDiv = document.createElement('img'),
            removeBlobDiv = document.createElement('canvas'),
            editButtonOfBlobDiv = document.createElement('a');
        
        var index = this.index;
        tempBlobDiv.setAttribute('id','book' + index);
        tempBlobDiv.setAttribute('class','video');

        titleOfBlobDiv.textContent = this.title1;
        sizeOfBlobDiv.textContent = Blob.langOfBlobElement[0] + ': '+ Math.ceil(this.size/1000000) + ' MB';
        if(this.poster != null) {
            if(typeof(this.poster) == "string") {
                posterOfBlobDiv.setAttribute('src', window.location.origin + this.poster);
            } else {
                if(window.File && window.FileList && window.FileReader) {
                    if(this.poster.type.match('image')) {
                        var fileReader = new FileReader();
                        fileReader.addEventListener("load",function(event) {
                            var picFile = event.target;
                            posterOfBlobDiv.setAttribute('src', picFile.result);
                        });
                        fileReader.readAsDataURL(this.poster);
                    } else {
                        posterOfBlobDiv.setAttribute('src', Book.defaultPoster);
                    }
                } else {
                    posterOfBlobDiv.setAttribute('src', Book.defaultPoster);
                }
            }
        } else {
            posterOfBlobDiv.setAttribute('src', Book.defaultPoster);
        }
        removeBlobDiv.width = 25;
        removeBlobDiv.height = 25;
        if(typeof(drawRemoveIconCanvas) == "function") drawRemoveIconCanvas(removeBlobDiv,'red');
        removeBlobDiv.onclick = new Function("removeBookFromPlaylist(" + index + ",'" + Blob.langOfBlobElement[2] + "');");
        editButtonOfBlobDiv.textContent = Blob.langOfBlobElement[1];
        editButtonOfBlobDiv.onclick = new Function("editBook(" + index + ",'" + Blob.langOfBlobElement[2] + "');");
        tempBlobDiv.appendChild(titleOfBlobDiv);
        tempBlobDiv.appendChild(sizeOfBlobDiv);
        tempBlobDiv.appendChild(posterOfBlobDiv);
        tempBlobDiv.appendChild(removeBlobDiv);
        tempBlobDiv.appendChild(editButtonOfBlobDiv);

        Book.parentOfElements.appendChild(tempBlobDiv);
    }
    createBlobWatingElement() {
        if(Blob.langOfWatingBlobElement == undefined || Blob.langOfWatingBlobElement == null) return;
        if(Blob.parentOfWatingElements == undefined || Blob.parentOfWatingElements == null) return;
        
        var tempWatingBlobDiv = document.createElement('div'),
            titleOfWatingBlobDiv = document.createElement('span'),
            posterOfWatingBlobDiv = document.createElement('img'),
            sectionOfWatingBlobDiv = document.createElement('section'),
            sectionDiv = document.createElement('div'),
            sectionDivDiv = document.createElement('div'),
            sectionDivSpan = document.createElement('span'),
            sectionFooter = document.createElement('footer');
            
        var index = this.index;

        tempWatingBlobDiv.setAttribute('id','watingBook' + index);
        tempWatingBlobDiv.setAttribute('class','video');
        sectionOfWatingBlobDiv.setAttribute('class','default-progress');
        titleOfWatingBlobDiv.textContent = this.title1;
        if(this.poster != null) {
            if(this.posterUploaded) {
                posterOfWatingBlobDiv.setAttribute('src',window.location.origin + this.poster);
            } else {
                if(typeof(this.poster) == "object") {
                    if(window.File && window.FileList && window.FileReader) {
                        if(this.poster.hasOwnProperty('type')) {
                            if(this.poster.type.match('image')) {
                                var fileReader = new FileReader();
                                fileReader.addEventListener("load",function(event){
                                    var picFile = event.target;
                                    posterOfWatingBlobDiv.setAttribute('src',picFile.result);
                                });
                                fileReader.readAsDataURL(this.poster);
                            } else {
                                posterOfWatingBlobDiv.setAttribute('src', Book.defaultPoster);
                            }
                        } else {
                            posterOfWatingBlobDiv.setAttribute('src', Book.defaultPoster);
                        }
                    } else {
                        posterOfWatingBlobDiv.setAttribute('src', Book.defaultPoster);
                    }
                } else {
                    posterOfWatingBlobDiv.setAttribute('src',this.poster);
                }
            }
        } else {
            posterOfWatingBlobDiv.setAttribute('src', Book.defaultPoster);
        }
        

        sectionDivSpan.textContent = '0%';
        sectionFooter.textContent = (index == 0) ? Blob.langOfWatingBlobElement[1] : Blob.langOfWatingBlobElement[2];
        sectionDiv.appendChild(sectionDivDiv);
        sectionDiv.appendChild(sectionDivSpan);
        sectionOfWatingBlobDiv.appendChild(sectionDiv);
        sectionOfWatingBlobDiv.appendChild(sectionFooter);
        tempWatingBlobDiv.appendChild(titleOfWatingBlobDiv);
        tempWatingBlobDiv.appendChild(posterOfWatingBlobDiv);
        tempWatingBlobDiv.appendChild(sectionOfWatingBlobDiv);

        Blob.parentOfWatingElements.appendChild(tempWatingBlobDiv);
    }
    addToCollection() {
        if(Array.isArray(books)) {
            this.index = books.length;
            books.push(this);
        }
    }
    removeBlobElement() {
        if(Book.parentOfElements == null || Book.parentOfElements == undefined) return;
        var temp = document.getElementById('book' + this.index);
        if(temp != null && temp != undefined) Book.parentOfElements.removeChild(temp);
    }
    removeBlobWatingElement () {
        if(Blob.parentOfWatingElements == null || Blob.parentOfWatingElements == undefined) return;
        var temp = document.getElementById('watingBook' + this.index);
        if(temp != null && temp != undefined) Blob.parentOfWatingElements.removeChild(temp);
    }
    removeFromCollection() {
        if(Array.isArray(books)) if(this.index < books.length && this.index > -1) delete books[this.index];
    }
    editBlobElement() {
        var tempBlobElement = document.getElementById('book' + this.index);
        if(tempBlobElement != null) {
            if(tempBlobElement.children.length > 3) {
                if(tempBlobElement.children[0] != null) tempBlobElement.children[0].textContent = this.title1;
                if(tempBlobElement.children[1] != null) {
                    if(! this.uploaded)var size = this.file.size;
                    else var size = this.size;
                    if(isNaN(size)) size = 0;
                    tempBlobElement.children[1].textContent = Blob.langOfBlobElement[0] + ': ' + Math.ceil(size/1000000) + ' MB';
                }
                if(! this.posterUploaded  && this.poster != null) {
                    if(typeof(this.poster) == "object") {
                        if(tempBlobElement.children[2] != null) {
                            if(window.File && window.FileList && window.FileReader) {
                                if(this.poster.type.match('image')) {
                                    var fileReader = new FileReader();
                                    fileReader.addEventListener("load",function(event){
                                        var picFile = event.target;
                                        tempBlobElement.children[2].setAttribute('src',picFile.result);
                                    });
                                    fileReader.readAsDataURL(this.poster);
                                }
                            }
                        }
                    } else tempBlobElement.children[2].setAttribute('src', this.poster);
                } else if(this.poster != null) {
                    tempBlobElement.children[2].setAttribute('src', this.poster);
                } else tempBlobElement.children[1].setAttribute('src', Book.defaultPoster);
            }
        }
    }
    editBlobWatingElement() {
        var tempBlobWatingElement = document.getElementById('watingBook' + this.index);
        if(tempBlobWatingElement != null) {
            if(tempBlobWatingElement.children.length > 2) {
                if(tempBlobWatingElement.children[0] != null) tempBlobWatingElement.children[0].textContent = this.title1;
                if(tempBlobWatingElement.children[1] != null) {
                    if(! this.posterUploaded  && this.poster != null) {
                        if(typeof(this.poster) == "object") {
                            if(window.File && window.FileList && window.FileReader && this.poster != null) {
                                if(this.poster.type.match('image')) {
                                    var fileReader = new FileReader();
                                    fileReader.addEventListener("load",function(event){
                                        var picFile = event.target;
                                        tempBlobWatingElement.children[1].setAttribute('src',picFile.result);
                                    });
                                    fileReader.readAsDataURL(this.poster);
                                }
                            }
                        } else tempBlobWatingElement.children[1].setAttribute('src', this.poster);
                    } else {
                        if(this.poster != null) tempBlobWatingElement.children[1].setAttribute('src', this.poster);
                        else tempBlobWatingElement.children[1].setAttribute('src', Book.defaultPoster);
                    }
                }
            }
        }
    }
}

function removeBookFromPlaylist(index, generalErrorAlert) {
    if(! Array.isArray(books)) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    if(index > books.length-1 || index < 0) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    if(typeof(books[index]) != "object" || books[index] == null) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    books[index].removeBlobRequest();
}
function removeAudioFromPlaylist(index, generalErrorAlert) {
    if(! Array.isArray(audios)) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    if(index > audios.length-1 || index < 0) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    if(typeof(audios[index]) != "object" || audios[index] == null) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    audios[index].removeBlobRequest();
}
function editBook(index, generalErrorAlert) {
    if(! Array.isArray(books)) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    if(index > books.length-1 || index < 0) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    if(typeof(books[index]) != "object" || books[index] == null) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    books[index].openEditTemplate();
}
function editAudio(index, generalErrorAlert) {
    if(! Array.isArray(audios)) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    if(index > audios.length-1 || index < 0) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    if(typeof(audios[index]) != "object" || audios[index] == null) {
        showPopUpMassage(generalErrorAlert);
        return;
    }
    audios[index].openEditTemplate();
}
function getNextFile(index) {
    if(audios.length - 1 < index) {
        return convertToBooks(0);
    }
    while(audios[index] == null || typeof(audios[index]) != "object") {
        index++;
        if(audios.length - 1 < index) {
            return convertToBooks(0);
        }
    }
    if(audios[index].uploaded) return getNextFile(index + 1);
    if(audios[index].tryUpload > 0) return getNextFile(index + 1);
    return audios[index];
}
function convertToBooks(index) {
    if(books.length - 1 < index) {
        return null;
    }
    while(books[index] == null || typeof(books[index]) != "object") {
        index++;
        if(books.length - 1 < index) {
            return null;
        }
    }
    if(books[index].uploaded) return convertToBooks(index + 1);
    if(books[index].tryUpload > 0) return convertToBooks(index + 1);
    return books[index];
}

function allFilesIsUpload() {
    for(var i = 0; i < audios.length; i++) {
        if(audios[i] != null && audios[i] != undefined) {
            if(audios[i].constructor == Audio) {
                if(audios[i].uploaded == false) {
                    return false;
                }
            }
        }
    }
    for(var i = 0; i < books.length; i++) {
        if(books[i] != null && books[i] != undefined) {
            if(books[i].constructor == Book) {
                if(books[i].uploaded == false) {
                    return false;
                }
            }
        }
    }
    return true;
}
function StartUploadingFiles(playlist,lang,secsses) {
    var currentUpload = getNextFile(0);
    if(currentUpload == null) {
        if(allFilesIsUpload()) {
            showPopUpMassage(secsses,function () {
                window.location.href = window.location.origin + '/admin/playlist';
            }, function() {
                window.location.href = window.location.origin + '/admin/playlist';
            });
        } else {
            showPopUpMassage(lang[10] ,function () {
                window.location.href = window.location.origin + '/admin/playlist';
            }, function(exitThis,popUpMassageDiv) {
                for(var i = 0; i < audios.length; i++) if(typeof(audios[i]) == "object") if(audios[i].uploaded == false) audios[i].tryUpload = 0;
                for(var i = 0; i < books.length; i++) if(typeof(books[i]) == "object") if(books[i].uploaded == false) books[i].tryUpload = 0;
                StartUploadingFiles(playlist,lang,secsses);
                exitThis(popUpMassageDiv);
            });
        }
        return;
    }
    if(playlist.hasOwnProperty('id')) {
        currentUpload.playlistId = playlist.id;
    } else {
        showPopUpMassage(lang[0]);
        return;
    }
    var tempForm = currentUpload.getFormData();
    tempForm.append('_token', lang[9]);

    if(currentUpload.blobType == "book") var watingElementIdName = 'watingBook';
    else var watingElementIdName = 'watingAudio';

    var tempWatingVideo = document.getElementById(watingElementIdName + currentUpload.index);
    if(tempWatingVideo.children.length > 2) {
        if(tempWatingVideo.children[2].children.length > 1) {
            tempWatingVideo.children[2].children[1].textContent = lang[6];
            if(tempWatingVideo.children[2].children[0].children.length > 1) {
                tempWatingVideo.children[2].children[0].children[0].class = "transition";
            }
        }
    }
    currentUpload.tryUpload += 1;

    ajaxUploadVideo('post',Blob.uploadBlobUrl + '/' + currentUpload.blobType,tempForm,function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
                if(jsonResponse.status && jsonResponse.data.hasOwnProperty('id')) {
                    currentUpload.uploaded = true;
                    currentUpload.id = jsonResponse.data.id;
                    currentUpload.posterUploaded = true;
                    if(tempWatingVideo != null) {
                        if(tempWatingVideo.children.length > 2) {
                            if(tempWatingVideo.children[2].children.length > 1) {
                                if(tempWatingVideo.children[2].children[0].children.length > 1) {
                                    tempWatingVideo.children[2].children[0].children[0].style = 'width: 100%;';
                                    tempWatingVideo.children[2].children[0].children[1].textContent = '100%';
                                }
                                tempWatingVideo.children[2].children[1].textContent = lang[5];
                                StartUploadingFiles(playlist,lang,secsses);
                                return;
                            }
                        }
                    }
                }
            }
        }
        showPopUpMassage(lang[7]);
        StartUploadingFiles(playlist,lang,secsses);
    },function (e) {
        if(tempWatingVideo.children.length > 2) {
            if(tempWatingVideo.children[2].children.length > 1) {
                if(tempWatingVideo.children[2].children[0].children.length > 1) {
                    var uploaded_percent = Math.round((e.loaded / e.total)*100);
                    tempWatingVideo.children[2].children[0].children[0].style = 'width: ' + uploaded_percent + '%;';
                    tempWatingVideo.children[2].children[0].children[1].textContent = uploaded_percent + '%';
                }
            }
        }
    },function () {
        showPopUpMassage(lang[7]);
        StartUploadingFiles(playlist,lang,secsses);
    });
}
function updateFiles() {
    var lang = getPlaylistLang();
    for(var i = 0; i < audios.length; i++) {
        if(audios[i] != undefined && audios[i] != null) {
            if(audios[i].uploaded && audios[i].updated) {
                var updatedFormData = audios[i].getUpdateFormData();
                if(updatedFormData != null) {
                    updatedFormData.append('_token',lang[9]);
                    ajaxRequest('post',window.location.origin + '/admin/playlist/update/blob/' + audios[i].blobType, updatedFormData);
                }
            } else if(audios[i].updated) {
                audios[i].createBlobWatingElement();
            }
        }
    }
    for(var i = 0; i < books.length; i++) {
        if(books[i] != undefined && books[i] != null) {
            if(books[i].uploaded && books[i].updated) {
                var updatedFormData = books[i].getUpdateFormData();
                if(updatedFormData != null) {
                    updatedFormData.append('_token',lang[9]);
                    ajaxRequest('post',window.location.origin + '/admin/playlist/update/blob/' + books[i].blobType, updatedFormData);
                }
            } else if(books[i].updated) {
                books[i].createBlobWatingElement();
            }
        }
    }
}