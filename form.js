function caricamento(){
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
}

function showError(tag, stringa){
    const padre=tag.parentNode;
    const errore = document.createElement("strong");
    errore.className = "errorSuggestion";
    errore.appendChild(document.createTextNode(stringa));
    padre.appendChild(errore);

}
/*a posto qui sotto*/ 
function validazioneCerca(){
    let stringa = document.getElementById("stringa");
    let autore = document.getElementById("autore");
    let genereSelezionato = document.getElementById("genereSelezionato");
    let lingua = document.getElementById("lingua");

    if(stringa == "" && autore == "" && genereSelezionato == "" && lingua == "") {
        //$messaggi_form = "<p>Inserisci almeno un parametro di ricerca</p>";
        showError("Inserisci almeno un parametro di ricerca")
        return false
    }
    return true
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