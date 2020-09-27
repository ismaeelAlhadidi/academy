var usernameInput = document.getElementById('usernameInput'),
    passwordInput = document.getElementById('passwordInput');



if(usernameInput != null) {
    usernameInput.onfocus = function () {
        makeFocusEfectOfInput(usernameInput);
    }
    usernameInput.onblur = function () {
        removeFocusEfectOfInput(usernameInput);
    }
}
if(passwordInput != null) {
    passwordInput.onfocus = function () {
        makeFocusEfectOfInput(passwordInput);
    }
    passwordInput.onblur = function () {
        removeFocusEfectOfInput(passwordInput);
    }
}