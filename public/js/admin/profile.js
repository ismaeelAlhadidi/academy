var currentAdmin = null;
( function setEvents() {
    if(exitButtonCanvasOfTemplateAddOrUpdateAdminData != null) {
        exitButtonCanvasOfTemplateAddOrUpdateAdminData.width = 25;
        exitButtonCanvasOfTemplateAddOrUpdateAdminData.height = 25;
        drawRemoveIconCanvas(exitButtonCanvasOfTemplateAddOrUpdateAdminData,'#fff');
        exitButtonCanvasOfTemplateAddOrUpdateAdminData.onclick = function () {
            closeBobUpTemplate(templateAddOrUpdateAdminData);
        };
    }
    if(addAdminButton != null) addAdminButton.onclick = addAdmin;
    if(updateAdminButton != null) updateAdminButton.onclick = updateAdmin;
    if(openAddTemplateButton != null) openAddTemplateButton.onclick = openAddTemplate;
})();

function validateAdminData() {
    if(inputAdminEmail == null || inputAdminUserName == null 
        || inputAdminPassword == null  || repeatAdminPassword == null ) return false;

    inputAdminEmail.setAttribute('class' , 'default-input');
    inputAdminUserName.setAttribute('class' , 'default-input');
    inputAdminPassword.setAttribute('class' , 'default-input');
    repeatAdminPassword.setAttribute('class' , 'default-input');
    if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = "";
    if(inputAdminEmail.value.trim().length < 1) {
        inputAdminEmail.setAttribute('class' , 'default-input input-invalid');
        return false;
    }
    if(inputAdminUserName.value.trim().length < 1) {
        inputAdminUserName.setAttribute('class' , 'default-input input-invalid');
        return false;
    }
    if(inputAdminPassword.value.length < 8) {
        inputAdminPassword.setAttribute('class' , 'default-input input-invalid');
        if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = lang.passwordLessThanMin;
        return false;
    }
    if(inputAdminPassword.value != repeatAdminPassword.value) {
        inputAdminPassword.setAttribute('class' , 'default-input input-invalid');
        repeatAdminPassword.setAttribute('class' , 'default-input input-invalid');
        if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = lang.passwordDeferent;
        return false;
    }
    return true;
}
function renderAdminDataInUpdateTemplate(email, username) {
    if(inputAdminEmail != null) inputAdminEmail.value = email;
    if(inputAdminUserName != null) inputAdminUserName.value = username;
}
function resetTemplateAddOrUpdateAdmin() {
    if(inputAdminEmail != null) {
        inputAdminEmail.value = "";
        inputAdminEmail.setAttribute('class' , 'default-input');
    }
    if(inputAdminUserName != null) {
        inputAdminUserName.value = "";
        inputAdminUserName.setAttribute('class' , 'default-input');
    }
    if(inputAdminPassword != null) {
        inputAdminPassword.value = "";
        inputAdminPassword.setAttribute('class' , 'default-input');
    }
    if(repeatAdminPassword != null) {
        repeatAdminPassword.value = "";
        repeatAdminPassword.setAttribute('class' , 'default-input');
    }
}
function openAddTemplate() {
    resetTemplateAddOrUpdateAdmin();
    if(addAdminButton != null) {
        addAdminButton.style = '';
        addAdminButton.setAttribute('style', '');
    }
    if(updateAdminButton != null) {
        updateAdminButton.style = 'display: none;';
        updateAdminButton.setAttribute('style', 'display: none;');
    }
    if(templateAddOrUpdateAdminData != null) {
        templateAddOrUpdateAdminData.style = '';
        templateAddOrUpdateAdminData.setAttribute('style', ''); 
    }
    if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = "";
    currentAdmin = null;
}
function openUpdateTemplate(email, username) {
    resetTemplateAddOrUpdateAdmin();
    renderAdminDataInUpdateTemplate(email, username);
    if(addAdminButton != null) {
        addAdminButton.style = 'display: none;';
        addAdminButton.setAttribute('style', 'display: none;');
    }
    if(updateAdminButton != null) {
        updateAdminButton.style = '';
        updateAdminButton.setAttribute('style', '');
    }
    if(templateAddOrUpdateAdminData != null) {
        templateAddOrUpdateAdminData.style = '';
        templateAddOrUpdateAdminData.setAttribute('style', ''); 
    }
    if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = "";
    currentAdmin = email;
}
function getFormDataFromAddTemplate() {
    var formData = new FormData();
    formData.append('_token', TOKEN);
    formData.append('email', inputAdminEmail.value);
    formData.append('username', inputAdminUserName.value);
    formData.append('password', inputAdminPassword.value);
    if(currentAdmin != null) formData.append('old_email', currentAdmin);
    return formData;
}
function addAdmin() {
    if(! validateAdminData()) return;
    ajaxRequest('post', AddAdminUrl, getFormDataFromAddTemplate(), function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status')) {
                if(jsonResponse.status) {
                    if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = lang.addOk;
                    resetTemplateAddOrUpdateAdmin();
                    var record = document.createElement('tr');
                    record.setAttribute('id', 'record' + jsonResponse.data.id);
                    record.innerHTML = `<tr id="record${ jsonResponse.data.id }">
                    <td>${ jsonResponse.data.username }</td>
                    <td>${ jsonResponse.data.email }</td>
                    <td><button class="default-button" onclick="openUpdateTemplate('${ jsonResponse.data.email }','${ jsonResponse.data.username }')">${ lang.update }</button>
                    <button class="default-button" onclick="deleteAdmin('${ jsonResponse.data.id }')">${ lang.delete }</button></td>
                    </tr>`;
                    if(tableBody != null) tableBody.appendChild(record);
                    return;
                }
            }
        }
        if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = lang.generalError;
    });
}
function updateAdmin() {
    if(! validateAdminData()) return;
    ajaxRequest('post', UpdateAdminUrl, getFormDataFromAddTemplate(), function(jsonResponse) {
        if(jsonResponse != null) {
            if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
                if(jsonResponse.status) {
                    if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = lang.updateOk;
                    resetTemplateAddOrUpdateAdmin();
                    var record = document.getElementById('record' + jsonResponse.data.id);
                    if(record != null) {
                        if(tableBody != null) tableBody.removeChild(record);
                        record = document.createElement('tr');
                        record.setAttribute('id', 'record' + jsonResponse.data.id);
                        record.innerHTML = `<tr id="record${ jsonResponse.data.id }">
                        <td>${ jsonResponse.data.username }</td>
                        <td>${ jsonResponse.data.email }</td>
                        <td><button class="default-button" onclick="openUpdateTemplate('${ jsonResponse.data.email }','${ jsonResponse.data.username }')">${ lang.update }</button>
                        <button class="default-button" onclick="deleteAdmin('${ jsonResponse.data.id }')">${ lang.delete }</button></td>
                    </tr>`;
                        if(tableBody != null) tableBody.appendChild(record);
                    }
                    return;
                }
            }
        }
        if(alertInTemplateAddOrUpdateAdminData != null) alertInTemplateAddOrUpdateAdminData.textContent = lang.generalError;
    });
}
function deleteAdmin(id) {
    showPopUpMassage(lang.askToDeleteMassege, null, function (exitThis,popUpMassageDiv) {
        ajaxRequest('get', DeleteAdminUrl + '/' + id, null, function(jsonResponse) {
            if(jsonResponse != null) {
                if(jsonResponse.hasOwnProperty('status')) {
                    if(jsonResponse.status) {
                        var record = document.getElementById('record' + jsonResponse.data.id);
                        if(record != null && tableBody != null) {
                            tableBody.removeChild(record);
                        }
                        return;
                    }
                }
            }
        });
        exitThis(popUpMassageDiv);
    }, lang.ok);
}
