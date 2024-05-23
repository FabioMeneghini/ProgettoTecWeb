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

/******************************************** REGISTRAZIONE */

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

/******************************************** LOGIN */

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

/******************************************** CAMBIA USERNAME */

function validaUsernameCambio() {
    let username = document.getElementById('username');
    return validaUsernameRegistrazione(username);
}

/******************************************** CAMBIA PASSWORD */

function validaPasswordCambio() {
    let passwordold = document.getElementById('passwordold');
    let passwordnew1 = document.getElementById('passwordnew1');
    let passwordnew2 = document.getElementById('passwordnew2');

    removeChildInput(passwordold);
    removeChildInput(passwordnew1);
    removeChildInput(passwordnew2);

    if(passwordold.value===passwordnew1.value){
        showError(passwordnew1, "La nuova password non può essere uguale a quella vecchia");
        passwordnew1.focus();
        passwordnew1.select();
        return false;
    }
    if(passwordnew1.value.length==0 || passwordnew1.value.length<8){
        showError(passwordnew1, "La nuova password non può essere vuota o più corta di 8 caratteri");
        passwordnew1.focus();
        passwordnew1.select();
        return false;
    }
    else if(passwordnew1.value.search(/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/)!=0){
        showError(passwordnew1, "La nuova password deve contenere almeno un numero, una lettera minuscola e una lettera maiuscola");
        passwordnew1.focus();
        passwordnew1.select();
        return false;
    }
    else if(passwordnew1.value !== passwordnew2.value){
        showError(password2, "Le due password non coincidono");
        passwordnew2.focus();
        passwordnew2.select();
        return false;
    }
    return true;
}

/******************************************** CAMBIA EMAIL */

function validaEmailCambio() {
    let email = document.getElementById('email');
    if(email.value.length==0){
        showError(email, "La nuova email non può essere vuota");
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

/******************************************** LIBRI TERMINATI E DA LEGGERE */

function validaLibriCheckbox() {
    let checkboxes = document.querySelectorAll('input[type="checkbox"][name="checkbox[]"]');
    let values = Array.from(checkboxes).map((checkbox) => {
        return {
            checked: checkbox.checked
        };
    });
    if (values.every((value) => value.checked === false)) {
        alert("Seleziona almeno un libro");
        return false;
    }
    return true;
}

/******************************************** MODIFICA LIBRO */

function validaAutore(autore){
    removeChildInput(autore);
    if(autore.value.length==0 || autore.value.length>100){
        showError(autore, "L'autore non può essere vuoto o più lungo di 100 caratteri");
        autore.focus();
        autore.select();
        return false;
    }
    return true;
}

function validaTitolo(titolo){
    removeChildInput(titolo);
    if(titolo.value.length==0 || titolo.value.length>100){
        showError(titolo, "Il titolo non può essere vuoto o più lungo di 100 caratteri");
        titolo.focus();
        titolo.select();
        return false;
    }
    return true;
}

function validaGenere(genere){
    removeChildInput(genere);
    if(genere.value.length==0){
        showError(genere, "Il genere non può essere vuoto");
        genere.focus();
        genere.select();
        return false;
    }
    return true;
}

function validaLingua(lingua){
    removeChildInput(lingua);
    if(lingua.value.length==0 || lingua.value.length>25){
        showError(lingua, "La lingua non può essere vuota o più lunga di 25 caratteri");
        lingua.focus();
        lingua.select();
        return false;
    }
    return true;
}

function validaCapitoli(capitoli){
    removeChildInput(capitoli);
    if(capitoli.value==0){
        showError(capitoli, "Il libro deve avere almeno un capitolo");
        capitoli.focus();
        capitoli.select();
        return false;
    }
    return true;
}

function validaTrama(trama){
    removeChildInput(trama);
    if(trama.value.length==0 || trama.value.length>3000){
        showError(trama, "La trama non può essere vuota o più lunga di 3000 caratteri");
        trama.focus();
        trama.select();
        return false;
    }
    return true;
}

function validaModificaLibro() {
    let autore = document.getElementById('autore');
    let titolo = document.getElementById('titolo');
    let genere = document.getElementById('genere');
    let lingua = document.getElementById('lingua');
    let capitoli = document.getElementById('capitoli');
    let trama = document.getElementById('trama');

    return validaAutore(autore) && validaTitolo(titolo) && validaGenere(genere) && validaLingua(lingua)
                                && validaCapitoli(capitoli) && validaTrama(trama);
}

/******************************************** SCHEDA LIBRO */

function validaRecensione(recensione){
    removeChildInput(recensione);
    if(recensione.value.length>1000){
        showError(recensione, "La recensione non può essere più lunga di 1000 caratteri");
        recensione.focus();
        recensione.select();
        return false;
    }
    return true;
}

function validaVoto(voto){
    removeChildInput(voto);
    if(voto.value<1 || voto.value>10){
        showError(voto, "Inserisci un voto da 1 a 10");
        voto.focus();
        voto.select();
        return false;
    }
    return true;
}

function validaSchedaLibro() {
    let recensione = document.getElementById('recensione');
    let voto = document.getElementById('voto');

    return validaRecensione(recensione) && validaVoto(voto);
}
