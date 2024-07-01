<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateSchedaLibro.html");
$menu ="";

//utenti
$userMenu ='<li><a href="utente.php"><span lang="en">Home</span></a></li>
    <li><a href="stai_leggendo.php">Libri che stai leggendo</a></li>
    <li><a href="terminati.php">Libri terminati</a></li>
    <li><a href="da_leggere.php">Libri da leggere</a></li>
    <li>
        <a href="generi.php">Generi:</a>
        <ul>
            {listaGeneri}
        </ul>
    </li>
    <li><a href="statistiche.php">Statistiche</a></li>
    <li><a href="area_personale.php">Area personale</a></li>
    <li><a href="cerca.php">Cerca</a></li>';

//admin
$adminMenu = '<li><a href="admin.php"><span lang="en">Home</span></a></li>
    <li><a href="aggiungi_libro.php">Aggiungi un libro</a></li>
    <li><a href="tutti_libri.php">Catalogo libri</a></li>
    <li><a href="tutti_utenti.php">Archivio utenti</a></li>
    <li>
        <a href="generi.php">Generi:</a>
        <ul>
            {listaGeneri}
        </ul>
    </li>
    <li><a href="area_personale.php">Area personale</a></li>
    <li><a href="cerca.php">Cerca</a></li>';

$NonRegistrato='<li><a href="index.php"><span lang="en">Home</span></a></li>
    <li>
        <a href="generi.php">Generi:</a>
        <ul>
            {listaGeneri}
        </ul>
    </li>
    <li><a href="accedi.php">Accedi</a></li>
    <li><a href="registrati.php">Registrati</a></li>
    <li><a href="cerca.php">Cerca</a></li>';

$menupersonale = "";

if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
    $menu = $adminMenu;
}
else if(isset($_SESSION['username'])){
    $menu = $userMenu;
    $menupersonale = '<li><a class="menulibro" href="#tua_recensione">Vai alla tua recensione e voto</a></li>';
}
else {
    $menu = $NonRegistrato;
    $menupersonale = '<li><a class="menulibro" href="#tua_recensione">Vai alla tua recensione e voto</a></li>';
}

$messaggiSuccesso = "";
$messaggiErrore = "";
$messaggiForm = "";
$listaGeneri = "";
$listaKeyword = "";
$titolo = "";
$autore = "";
$genere = "";
$lingua = "";
$trama = "";
$n_capitoli = "";
$tuo_commento = "";
$voto="";
$media_voti = "";
$listaRecensioni = "";
$arearecensionevoto="";
$copertina="";
$alt="";
$bottoni_admin="";
$keywords="";

function controlla($recensione, $voto) {
    $messaggi = "";
    if(strlen($recensione) > 1000) {
        $messaggi .= "<li>La recensione può essere lunga al massimo 1000 caratteri</li>";
    }
    if(!is_numeric($voto)) {
        $messaggi .= "<li>Il voto deve essere un numero intero</li>";
    }
    else
        if($voto < 1 || $voto > 10) {
            $messaggi .= "<li>Il voto deve essere compreso tra 1 e 10</li>";
        }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

if(isset($_GET['eliminato']) && $_GET['eliminato'] == 0) {
    $messaggiErrore = '<p class="messsaggiErrore">Ci scusiamo per il disagio ma non è stato possibile eliminare il libro.</p>';
}
if(isset($_GET['modificato']) && $_GET['modificato'] == 1) {
    $messaggiSuccesso = '<p class="messaggiSuccesso">Libro modificato con successo!</p>';
}
if(isset($_GET['salvato']) && $_GET['salvato'] == 1) {
    $messaggiSuccesso = '<p class="messaggiSuccesso">Libro salvato con successo!</p>';
}
if(isset($_GET['valutato']) && $_GET['valutato'] == 1) {
    $messaggiSuccesso = '<p class="messaggiSuccesso">Valutazione aggiunta/modificata con successo!</p>';
}

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    //controllo se l'utente vuole salvare, iniziare o valutare un libro
    if(isset($_POST["salva"])) {
        $salvato = $connection -> aggiungiDaLeggere($_SESSION['username'], $_POST['id_libro']);
        //mi sposto con header(Location: ...) perché altrimenti da problemi con il libro che visualizza (mostra sempre il primo in quanto perde il parametro id nel get)
        header("Location: scheda_libro.php?salvato=1&id=".$_POST['id_libro']);
        exit();
    }
    if(isset($_POST["inizia"])) {
        $eliminato = $connection -> rimuoviDaLeggere($_SESSION['username'], $_POST['id_libro']);
        $iniziato = $connection -> aggiungiStaLeggendo($_SESSION['username'], $_POST['id_libro']);
        header("Location: stai_leggendo.php?iniziato=1");
        exit();
    }
    if(isset($_POST["valuta"])) {
        $tmp = controlla($_POST["recensione"], $_POST["voto"]);
        if($tmp["ok"]) {
            $salvato = $connection -> aggiungiValutazione($_SESSION['username'], $_POST['id_libro'], $_POST['voto'], $_POST['recensione']);
            //mi sposto con header(Location: ...) perché altrimenti da problemi con il libro che visualizza (mostra sempre il primo in quanto perde il parametro id nel get)
            header("Location: scheda_libro.php?valutato=1&id=".$_POST['id_libro']);
            exit();
        }
        else {
            $messaggiForm = $tmp["messaggi"];
        }
    }

    //controllo se l'id del libro è presente
    $tmp=false;
    if(isset($_POST['id'])) {
        $LibroSelezionato = $_POST['id'];
        $tmp = $connection -> controllareIdLibro($LibroSelezionato);
    }
    else if(isset($_GET['id'])) {
        $LibroSelezionato = $_GET['id'];
        $tmp = $connection ->  controllareIdLibro($LibroSelezionato);
    }
    if(!$tmp) {
        header("Location: 404.php"); //libro non trovato o non impostato in $_GET o $_POST
        exit();
    }
    $resultGeneri = $connection -> getListaGeneri();
    $libro = $connection -> getLibro($LibroSelezionato);
    $media_voti = $connection -> getmediavoti($LibroSelezionato);
    $altre_recensioni = $connection -> getaltrerecensioni($LibroSelezionato, isset($_SESSION['username']) ? $_SESSION['username'] : "" );
    $titolo = $libro["titolo"];
    $autore = $libro["autore"];
    $genereLibro = $libro["genere"];
    $lingua = $libro["lingua"];
    $trama = $libro["trama"];
    $n_capitoli = $libro["n_capitoli"];
    $copertina = $libro["titolo_ir"];
    $alt = $libro["descrizione"];
    $keywords = $libro["keywords"];
    $description = $libro["descrizione_pagina"];

    foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    } 

    if(!isset($_SESSION['username'])) {
        $arearecensionevoto='<div>
                                <p>Per lasciare una recensione e un voto devi prima accedere al tuo <span lang="en">account</span></p>
                                <p><a href="accedi.php">Accedi</a></p>
                                <p>Non hai ancora un <span lang="en">account</span>? Entra a far parte della nostra <span lang="en">community</span>!</p>
                                <p><a href="registrati.php">Registrati</a></p>
                            </div>';
    }
    else if(isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
        $terminato = $connection -> is_terminato($LibroSelezionato, $_SESSION['username']);
        $salvato= $connection -> is_salvato($LibroSelezionato, $_SESSION['username']);
        $iniziato= $connection -> is_iniziato($LibroSelezionato, $_SESSION['username']);
        if($terminato) {
            $recensione = $connection -> getTuaRecensione($LibroSelezionato, $_SESSION['username']);
            if ($recensione !== null) {
                $tuo_commento = $recensione["commento"] ?? "";
                $voto = $recensione["voto"] ?? "";
            } else {
                $tuo_commento = "";
                $voto = "";
            }

            $arearecensionevoto='
                <form method="post" action="scheda_libro.php" onsubmit="return validaSchedaLibro()" onreset="conferma(\'Sei sicuro di voler annullare le modifiche alla tua recensione e al tuo voto?\')"> 
                    {messaggiForm}
                    <fieldset>
                        <legend>La tua Recensione e il tuo voto</legend>
                        <label for="recensione">Recensione:</label>
                        <span><textarea id="recensione" name="recensione" rows="10" cols="70" maxlength="1000" placeholder="Inserisci la tua recensione...">'.$tuo_commento.'</textarea></span>
                        <label for="voto">Voto:</label>
                        <span><input type="number" name="voto" id="voto" max="10" min="1" required placeholder="Il tuo voto..." value="{voto}"></span>
                        <input type="hidden" id="id_libro" name="id_libro" value="'.$LibroSelezionato.'">';
            if($tuo_commento=='' && $voto=='')
                $arearecensionevoto.='<input type="submit" id="valuta" name="valuta" value="Pubblica valutazione">';
            else
                $arearecensionevoto.='<input type="submit" id="valuta" name="valuta" value="Modifica valutazione">';
            $arearecensionevoto.='
                        <input type="reset" value="Annulla">
                    </fieldset>
                </form>';
        }
        else if($salvato) {
            $arearecensionevoto='
                <form action="scheda_libro.php" method="post"> 
                    <fieldset>
                        <legend>Inizia a leggere</legend>
                        <input type="hidden" id="id_libro" name="id_libro" value="'.$LibroSelezionato.'">
                        <label for="username">Questo libro &egrave; nella lista dei tuoi libri da leggere. &Egrave; il momento di iniziare a leggerlo?</label>
                        <input type="submit" id="inizia" name="inizia" value="inizia">
                    </fieldset>
                </form>';
        }
        else if($iniziato) {
            $arearecensionevoto='<p>Prima di poter recensire questo libro devi averlo terminato. Questo libro si trova nella lista di libri che stai leggendo, per vedere il tuo avanzamento vai al link: <a href="stai_leggendo.php">Libri che stai leggendo</a></p>';
        }
        else  {
            $arearecensionevoto='
            <form action="scheda_libro.php" method="post" class="form-bottoni">
                <fieldset>
                    <legend>Prima di poter recensire questo libro devi averlo terminato</legend>
                    <input type="hidden" id="id_libro" name="id_libro" value="'.$LibroSelezionato.'">
                    <label for="username">Salva per leggerlo più tardi:</label> 
                    <input type="submit" id="salva" name="salva" value="Salva">
                    <label for="username">Inizia a leggere:</label> 
                    <input type="submit" id="inizia" name="inizia" value="Inizia a leggere">
                </fieldset>
            </form>';
        }
    }
    else {
        $bottoni_admin='
            <div class="container_form">
                <form action="modifica_libro.php" method="get">
                    <input type="hidden" id="libroId" name="id" value='.$LibroSelezionato.'>
                    <input type="submit" id="modifica_libro" name="modifica_libro" value="Modifica">
                </form>
                <form action="elimina_libro.php" method="get" onsubmit="return conferma(\'Sei sicuro/sicura di voler eliminare questo libro?\')">
                    <input type="hidden" id="libroId" name="id" value='.$LibroSelezionato.'>
                    <input type="submit" id="elimina" name="elimina" value="Elimina">
                </form>
            </div>';
    }
    $connection -> closeConnection();
    
    if(empty($altre_recensioni)) {
        $listaRecensioni.='<p>Non ci sono ancora recensioni per questo libro da parte degli altri utenti</p>';
    }
    else {
        $listaRecensioni.='<ul class="lista_recensioni_scheda_libro">';
        foreach($altre_recensioni as $recensione) {
            $listaRecensioni .= '<li><p><span class="username">'.$recensione["username_autore"].':</span> <span class="commento-testuale">'.$recensione["commento"].'</span></p></li>';
        }
        $listaRecensioni.="</ul>";
    }
}
else {
    header("Location: 500.php");
    exit();
}

if($keywords=="")
    $keywords="libro, recensioni, lettura, autore, genere, lingua, trama, capitoli, voto, recensione, storia, personaggi";
if($description=="")
    $description="Pagina dedicata al libro ".$titolo." di ".$autore." con trama, numero di capitoli, genere, lingua e media voti. Inoltre è possibile leggere le recensioni degli altri utenti e lasciare la propria valutazione.";
if($alt=="")
    $alt="Copertina del libro ".$titolo." di ".$autore;

$paginaHTML = str_replace("{keywords}", $keywords, $paginaHTML);
$paginaHTML = str_replace("{description}", $description, $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{menupersonale}", $menupersonale, $paginaHTML);

//prima sostituisce l'area della recensione con un form poi lo compila 
$paginaHTML = str_replace("{arearecensionevotoform}", $arearecensionevoto=="" ? "" : '<section><h2 id="tua_recensione">La tua recensione</h2>'.$arearecensionevoto.'</section>', $paginaHTML);

$paginaHTML = str_replace("{ImmagineLibro}", "copertine_libri/".$copertina.".jpg", $paginaHTML);
$paginaHTML = str_replace("{altlibro}", $alt , $paginaHTML);
$paginaHTML = str_replace("{TitoloLibro}", $titolo , $paginaHTML);
$paginaHTML = str_replace("{AutoreLibro}", $autore, $paginaHTML);
$paginaHTML = str_replace("{GenereLibro}", $genereLibro, $paginaHTML);
$paginaHTML = str_replace("{LinguaLibro}", $lingua , $paginaHTML);
$paginaHTML = str_replace("{mediavoti}", number_format(doubleval($media_voti), 1), $paginaHTML);
$paginaHTML = str_replace("{CapitoliLibro}", $n_capitoli , $paginaHTML);
$paginaHTML = str_replace("{tramaLibro}", $trama , $paginaHTML);
$paginaHTML = str_replace("{bottoniAdmin}", $bottoni_admin , $paginaHTML);
$paginaHTML = str_replace("{voto}", $voto, $paginaHTML);
$paginaHTML = str_replace("{recensioniComunity}", $listaRecensioni, $paginaHTML);
$paginaHTML = str_replace("{messaggiForm}", $messaggiForm=="" ? "" : "<ul class=\"messaggiErrore\">".$messaggiForm."</ul>", $paginaHTML);
$paginaHTML = str_replace("{messaggiErrore}", $messaggiErrore, $paginaHTML);
$paginaHTML = str_replace("{messaggiSuccesso}", $messaggiSuccesso, $paginaHTML);

echo $paginaHTML;

?>