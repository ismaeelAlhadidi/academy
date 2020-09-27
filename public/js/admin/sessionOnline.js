function RequestSetAdmission(SessionOnlineId,admission) {
    if(admission) msg = lang[4];
    else msg = lang[5];
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
                                    admissionButton.textContent = lang[2];
                                } else {
                                    admissionButton.textContent = lang[3];
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
            showPopUpMassage(lang[0]);
        });
        exitThis(popUpMassageDiv);
    },lang[1]);
}