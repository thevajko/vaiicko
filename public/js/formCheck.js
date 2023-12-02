'use strict'

document.getElementById('runnerForm').onsubmit = checkForm;

function checkForm() {
    const password = document.getElementById("password");
    const passwordRepeat = document.getElementById("passwordRepeat");
    const error = document.getElementById('error');

    if (password.value !== passwordRepeat.value) {
        error.hidden = false;
        error.innerText = "Heslá sa nezhodujú!";
        return false;
    } else  {
        error.hidden = true;
        error.innerText = "";
    }
}
