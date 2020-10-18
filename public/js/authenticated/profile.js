var firstNameInput = document.getElementById('firstNameInput');
var secondNameInput = document.getElementById('secondNameInput');
var lastNameInput = document.getElementById('lastNameInput');
var emailInput = document.getElementById('emailInput');

function editUserData(button) {
    if(firstNameInput != null){
        firstNameInput.removeAttribute('disabled');
        firstNameInput.focus();
    }
    if(secondNameInput != null) secondNameInput.removeAttribute('disabled');
    if(lastNameInput != null) lastNameInput.removeAttribute('disabled');
    if(emailInput != null) emailInput.removeAttribute('disabled');
    button.value = lang.save;
    button.onclick = function () {
        saveUserData(button);
    };
}
function saveUserData(button) {
    if(firstNameInput != null) firstNameInput.setAttribute('disabled', 'disabled');
    if(secondNameInput != null) secondNameInput.setAttribute('disabled', 'disabled');
    if(lastNameInput != null) lastNameInput.setAttribute('disabled', 'disabled');
    if(emailInput != null) emailInput.setAttribute('disabled', 'disabled');
    requestToSaveNewDataOfThisUser();
    button.value = lang.edit;
    button.onclick = function () {
        editUserData(button);
    };
}
function requestToSaveNewDataOfThisUser() {
    var userDataForm = new FormData();
    if(firstNameInput != null) userDataForm.append('first_name', firstNameInput.value);
    if(secondNameInput != null) userDataForm.append('second_name', secondNameInput.value);
    if(lastNameInput != null) userDataForm.append('last_name', lastNameInput.value);
    if(emailInput != null) userDataForm.append('email', emailInput.value);
    userDataForm.append('_token', TOKEN);
    ajaxRequest('post', window.location.origin + '/ajax/profile/save-changes', userDataForm, function(jsonResponse) {
        if(jsonResponse == null) {
            showPopUpMassage(lang.MassegeOfErrorInSave,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('msg')) {
            if(jsonResponse.status) {
                showPopUpMassage(jsonResponse.msg,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
                return;
            }
        }
        showPopUpMassage(lang.MassegeOfErrorInSave,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
    });
}
function editUserImage(inputFileElement) {
    inputFileElement.click();
}
function saveNewUserImage(inputFileElement, imageElement) {
    if(inputFileElement.files.length != 1) return;
    if(inputFileElement.files[0].size > ImageSize) {
        showPopUpMassage(lang.AlertOfBigSize,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
        return;
    }
    var userDataForm = new FormData();
    userDataForm.append('image', inputFileElement.files[0]);
    userDataForm.append('_token', TOKEN);
    ajaxRequest('post', window.location.origin + '/ajax/profile/change-image', userDataForm, function(jsonResponse) {
        if(jsonResponse == null) {
            showPopUpMassage(lang.MassegeOfErrorInChangeImage,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
            return;
        }
        if(jsonResponse.hasOwnProperty('status') && jsonResponse.hasOwnProperty('data')) {
            if(jsonResponse.status) {
                imageElement.src = jsonResponse.data;
                return;
            }
        }
        showPopUpMassage(lang.MassegeOfErrorInChangeImage,null,null,'ok',defaultStyleOfPopUpMassegeInWeb);
    });
}