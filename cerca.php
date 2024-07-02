<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateCerca.html");
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
    <li>Cerca</li>';

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
    <li>Cerca</li>';

$NonRegistrato='<li><a href="index.php"><span lang="en">Home</span></a></li>
    <li>
        <a href="generi.php">Generi:</a>
        <ul>
            {listaGeneri}
        </ul>
    </li>
    <li><a href="accedi.php">Accedi</a></li>
    <li><a href="registrati.php">Registrati</a></li>
    <li>Cerca</li>';

function pulisciInput($input) {
    $input = trim($input);
    $input = strip_tags($input);
    $input = htmlentities($input);
    return $input;
}

if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1)
    $menu = $adminMenu;
else if(isset($_SESSION['username']))
    $menu = $userMenu;
else
    $menu = $NonRegistrato;

$listaGeneri = "";
$lista_lingue="";
$risultatoRicerca="";
$opzioneGeneri="";
$rislutati_ricerca="";
$messaggi_form="";
$torna_su="";

$stringa = "";
$autore = "";
$lingua = "";
$genereSelezionato = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $resultGeneri = $connection -> getListaGeneri();
    $libri_ricercati = array();
    $resultLingue= $connection -> getLingueLibri();
    foreach($resultLingue as $lingue) { //lista di tutte le lingue
        $lista_lingue .= '<option value="'.$lingue["lingua"].'">'.$lingue["lingua"].'</option>'; 
    }
    if(isset($_POST['cerca_generale'])) {
        $stringa = isset($_POST['stringa']) ? pulisciInput($_POST['stringa']) : "";
        $autore = isset($_POST['autore']) ? pulisciInput($_POST['autore']) : "";
        $genereSelezionato = isset($_POST['genere']) ? pulisciInput($_POST['genere']) : "";
        //$genereSelezionato = $genereSelezionato=="x" ? "" : $genereSelezionato;
        $lingua = isset($_POST['lingua']) ? pulisciInput($_POST['lingua']) : "";
        if($stringa=="" && $autore=="" && $genereSelezionato=="" && $lingua=="")
            $messaggi_form = '<p class="messaggiErrore">Inserisci almeno un parametro di ricerca</p>';
        else
            $libri_ricercati = $connection -> cercaLibro($stringa, $autore, $genereSelezionato, $lingua);
    }
    $connection -> closeConnection();
    if(!empty($libri_ricercati) && $messaggi_form==""){
            $rislutati_ricerca.= '<p id="descr">
                            La tabella contiene l\'elenco dei libri che corrispondono alla tua ricerca.
                            Ogni riga descrive un libro con cinque colonne nominate: "titolo", "copertina", "autore", "genere", "lingua".
                            </p>
                            <table id="tabella_risultati_ricerca_libri" aria-describedby="descr">
                                <caption>Risultati della tua ricerca</caption>
                                <tr>
                                    <th scope="col">Titolo</th>
                                    <th class="rimuovi_print" scope="col" abbr="Cop">Copertina</th>
                                    <th scope="col" abbr="Aut">Autore</th>
                                    <th class="rimuovi" scope="col" abbr="Gen">Genere</th>
                                    <th class="rimuovi" scope="col" abbr="Lin">Lingua </th>
                                </tr>';
        foreach($libri_ricercati as $libro) {
            $rislutati_ricerca .= '<tr>
                                    <th scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></th>
                                    <td class="rimuovi_print"><img src="copertine_libri/'.$libro["titolo_ir"].'.jpg" alt="'.$libro["descrizione"].'" width="50" height="70"></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td class="rimuovi">'.$libro["genere"].'</td>
                                    <td class="rimuovi">'.$libro["lingua"].'</td>
                                </tr>';
        }
        $rislutati_ricerca .= "</table>";
        if(count($libri_ricercati)>=3) {
            $torna_su='<nav aria-label="Torna al form di ricerca">
                            <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
        }
    }
    else {
        if(empty($libri_ricercati) && isset($_POST['cerca_generale'])) {
            $rislutati_ricerca= '<p>Ci scusiamo ma al momento non abbiamo libri che corrispondono alla tua ricerca</p>';
        }
    }
    $opzioneGeneri .= '<option value="%">Qualsiasi</option>';
    foreach($resultGeneri as $genere) {
        if($genere["nome"] != $genereSelezionato)
            $opzioneGeneri .= '<option value="'.$genere["nome"].'">'.$genere["nome"].'</option>';
        else
            $opzioneGeneri .= '<option value="'.$genere["nome"].'" selected>'.$genere["nome"].'</option>';
    }
    foreach($resultGeneri as $genere) {
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }
    
}
else {
    header("Location: 500.php");
    exit();
}

$paginaHTML = str_replace("{stringa}", $stringa, $paginaHTML);
$paginaHTML = str_replace("{autore}", $autore, $paginaHTML);
$paginaHTML = str_replace("{lingua}", $lingua, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su , $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{opzioneGeneri}", $opzioneGeneri, $paginaHTML);
$paginaHTML = str_replace("{listaLingue}", $lista_lingue, $paginaHTML);
$paginaHTML = str_replace("{rislutati}", $rislutati_ricerca, $paginaHTML);
$paginaHTML = str_replace("{messaggiForm}", $messaggi_form, $paginaHTML);

echo $paginaHTML;

?>