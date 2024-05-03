/*function caricamento(){
    let inputTitle = document.getElementById("titolo");
    inputTitle.onblur = function () {validateTitle(this)};
   
    let inputDate = document.getElementById("dataRadio");
    inputDate.onblur = function () {validateDate(this)};

    let inputURL = document.getElementById("urlVideo");
    inputURL.onblur = function () {validateURL(this)};
}

function validateDate(inputDate){
    removeChildInput(inputDate);
    if(inputDate.value.search(/^\d{2}\/\d{2}\/\d{4}$/)!=0){
        showError(inputDate,"Inserire la data nel formato corretto (DD/MM/AAAA)");
        inputDate.focus();
        inputDate.select(); // cancella il contenuto della casella data
        return false;
    }
    return true;
}


function validateURL(inputURL){
    removeChildInput(inputURL);
    try{
        if(inputURL.value.length>0){
            new URL(inputURL.value);
        }
        return true;
    } catch(err){
        showError(inputURL, "URL non valido");
        inputURL.focus();
        inputURL.select(); // cancella il contenuto della casella data
        return false;
    }

}

function validateExplicit(rYes, rNo){
    removeChildInput(rNo);
    if(!(rYes.checked || rNo.checked)){
        showError(rNo, "Selezionare una delle due opzioni");
        return false;
    }
    return true;
    
}

function removeChildInput(input){
    padre = input.parentNode;
    if (padre.children.length==2){
        padre.removeChild(padre.children[1]);
    }
}*/

function showError(tag, stringa, element){
    const padre=tag.parentNode;
    const errore = document.createElement(element);
    errore.className = "errorSuggestion";
    errore.appendChild(document.createTextNode(stringa));
    padre.appendChild(errore);
}
/*a posto qui sotto*/ 
function validazioneCerca() {
    let stringa = document.getElementById("stringa").value;
    let autore = document.getElementById("autore").value;
    //let genereSelezionato = document.getElementById("genereSelezionato").value;
    let lingua = document.getElementById("lingua");
    const lingua_val=lingua.value;

    console.log(stringa);
    console.log(autore);
    console.log(lingua);

    if (stringa === "" && autore === "" /*&& genereSelezionato === ""*/ && lingua_val === "") {
        showError(lingua.parentNode, "Inserisci almeno un parametro di ricerca", "p");
        return false;
    }
    return true;
}
function controllaAccedi() { //da inserire eventualmente altri controlli su username e password
    let messaggi = "";
    let ok=true;
    let username = document.getElementById("username");
    const username_val=username.value;
    let password = document.getElementById("password").value;

    if(username_val == "") {
        messaggi +="<li>Lo username non può essere vuoto</li>";
        ok=false;
    }
    if(password == "") {
        messaggi += "<li>La password non può essere vuota</li>";
        ok=false;
    }
    if(username_val.length <= 2) {
        messaggi += "<li>Lo username non può essere più corto di 3 caratteri</li>";
        ok=false;
    }
    console.log(username_val.length);
    alert(username_val.length);
    if(!ok) {
        showError(username.parentNode, messaggi, "ul");
    }
    return ok;
}

/*function validazioneForm(){
    let inputTitle = document.getElementById("titolo");

    let inputDate = document.getElementById("dataRadio");
    let inputURL = document.getElementById("urlVideo");
    let rYes = document.getElementById("rYes");
    let rNo = document.getElementById("rNo");
    return validateTitle(inputTitle)
        
        && validateDate(inputDate)
        && validateURL(inputURL)
        && validateExplicit(rYes,rNo);
    
}*/