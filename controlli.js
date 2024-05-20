function removeChildInput(input){
    padre = input.parentNode;
    if (padre.children.length==2){
        padre.removeChild(padre.children[1]);
    }
}

function showError(tag, stringa){
    const padre=tag.parentNode;
    const errore = document.createElement("strong");
    errore.className = "errorSuggestion";
    errore.appendChild(document.createTextNode(stringa));
    padre.appendChild(errore);
}

/* REGISTRAZIONE */

function validaNomeRegistrazione(nome){
    removeChildInput(nome);
    if(nome.value.length==0 || nome.value.length>25){
        showError(nome, "Il nome non può essere vuoto o più lungo di 25 caratteri");
        nome.focus();
        nome.select();
        return false;
    }
    return true;
}

function validaCognomeRegistrazione(cognome){
    removeChildInput(cognome);
    if(cognome.value.length==0 || cognome.value.length>25){
        showError(cognome, "Il cognome non può essere vuoto o più lungo di 25 caratteri");
        cognome.focus();
        cognome.select();
        return false;
    }
    return true;
}

function validaUsernameRegistrazione(username){
    removeChildInput(username);
    if(username.value.length==0 || username.value.length<3 || username.value.length>25){
        showError(username, "Lo username non può essere vuoto e deve essere compreso tra 3 e 25 caratteri");
        username.focus();
        username.select();
        return false;
    }
    return true;
}

function validaEmailRegistrazione(email){
    removeChildInput(email);
    if(email.value.length==0 || email.value.length>60){
        showError(email, "La mail non può essere vuota o più lunga di 60 caratteri");
        email.focus();
        email.select();
        return false;
    }
    else if(email.value.search(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)!=0){
        showError(email, "Inserire una mail valida");
        email.focus();
        email.select();
        return false;
    }
    return true;
}

function validaDataRegistrazione(data) {
    removeChildInput(data);
    if(data.value.length==0) {
        showError(data, "La data di nascita non può essere vuota");
        data.focus();
        data.select();
        return false;
    }
    else if(data.value > new Date().toISOString().split('T')[0]) {
        showError(data, "La data di nascita non può essere futura");
        data.focus();
        data.select();
        return false;
    }
    return true;
}

function validaPasswordRegistrazione(password1, password2) {
    removeChildInput(password1);
    removeChildInput(password2);
    if(password1.value.length==0 || password1.value.length<8){
        showError(password1, "La password non può essere vuota o più corta di 8 caratteri");
        password1.focus();
        password1.select();
        return false;
    }
    else if(password1.value.search(/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/)!=0){
        showError(password1, "La password deve contenere almeno un numero, una lettera minuscola e una lettera maiuscola");
        password1.focus();
        password1.select();
        return false;
    }
    else if(password1.value !== password2.value){
        showError(password2, "Le due password non coincidono");
        password2.focus();
        password2.select();
        return false;
    }
    return true;
}

function validaRegistrazione() {
    let nome = document.getElementById('name');
    let cognome = document.getElementById('cognome');
    let data = document.getElementById('data');
    let email = document.getElementById('email');
    let username = document.getElementById('username');
    let password1 = document.getElementById('password1');
    let password2 = document.getElementById('password2');
    
    return validaNomeRegistrazione(nome) && validaCognomeRegistrazione(cognome) && validaDataRegistrazione(data)
                                         && validaEmailRegistrazione(email) && validaUsernameRegistrazione(username)
                                         && validaPasswordRegistrazione(password1, password2);
}

/* LOGIN */

function validaUsernameLogin(username){
    removeChildInput(username);
    if(username.value.length==0){
        showError(username, "Inserisci lo username");
        username.focus();
        username.select();
        return false;
    }
    return true;
}

function validaPasswordLogin(password){
    removeChildInput(password);
    if(password.value.length==0){
        showError(password, "Inserisci la password");
        password.focus();
        password.select();
        return false;
    }
    return true;
}

function validaLogin() {
    let username = document.getElementById('username');
    let password = document.getElementById('password');
    
    return validaUsernameLogin(username) && validaPasswordLogin(password);
}