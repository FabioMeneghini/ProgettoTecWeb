<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] != 1) {
        header("Location: utente.php");
        exit();
    }
}
else {
    header("Location: index.php");
    exit();
}

$paginaHTML = file_get_contents("template/templateTuttiUtenti.html");

$listaGeneri = "";
$utenti="";
$alfabetico_nome="";
$alfabetico_cognome="";
$alfabetico_username="";
$data_iscrizione_piu_recente="";
$data_iscrizione_meno_recente="";
$attivi="";
$torna_su="";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $resultGeneri = $connection -> getListaGeneri();
    if(isset($_POST['ordina'])) {
        if(isset($_POST['opzione'])) {
            $opzione=$_POST['opzione'];
            $resultUtenti= $connection ->getTuttiUtentiOrdinati($_POST['opzione']);
            
            if($opzione=="alfabetico_nome"){
                $alfabetico_nome="selected";
            }
            else if($opzione=="alfabetico_cognome"){
                $alfabetico_cognome="selected";
            }
            else if($opzione=="alfabetico_username"){
                $alfabetico_username="selected";
            }
            else if($opzione=="data_iscrizione_piu_recente"){
                $data_iscrizione_piu_recente="selected";
            }
            else if($opzione=="data_iscrizione_meno_recente"){
                $data_iscrizione_meno_recente="selected";
            }
            else if($opzione=="attivi"){
                $attivi="selected";
            }
        }
        else {
            $resultUtenti= $connection ->getTuttiUtentiOrdinati("alfabetico_nome");
        }
    }
    else {
        $resultUtenti= $connection ->getTuttiUtentiOrdinati("alfabetico_nome");
    }
    $connection -> closeConnection();
    foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }
    
    if(!empty($resultUtenti)){
        $utenti.= '<p id="descr">
                        La tabella contiene l&apos;elenco di tutti gli utenti registrati al sito.
                        Ogni riga descrive un utente con 5 colonne nominate: "username", "nome", "cognome", "email", "data di iscrizione al sito".
                    </p>
                    <table aria-describedby="descr" class="righealternate">
                    <caption>Tutti gli utenti del sito</caption>
                    <tr>
                        <th scope="col"><span lang="en">Username</span></th>
                        <th scope="col">Nome</th>
                        <th scope="col">Cognome</th>
                        <th class="rimuovi" scope="col"><span lang="en">Email</span></th>
                        <th class="rimuovi" scope="col" abbr="Data isc">Data di iscrizione</th>
                    </tr>';
        foreach($resultUtenti as $utente) {
            $utenti .= '<tr>
                            <th scope="row">'.$utente["username"].'</th>
                            <td>'.$utente["nome"].'</td>
                            <td>'.$utente["cognome"].'</td>
                            <td class="rimuovi">'.$utente["email"].'</td>
                            <td class="rimuovi"><time datetime="'.$utente["data_iscrizione"].'">'.$utente["data_iscrizione"].'</time></td>
                        </tr>';
        }
        $utenti .= "</table>";
        if(count($resultUtenti)>=20) {
            $torna_su='<nav aria-label="Torna all\'inizio della lista degli utenti">
                            <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
        }
    }
    else{
        $utenti= '<p>Al momento non ci sono utenti registrati al tuo servizio.</p>';
    }
}
else {
    header("Location: 500.php");
    exit();
}

$paginaHTML = str_replace("{selected_alfabetico_nome}", $alfabetico_nome, $paginaHTML);
$paginaHTML = str_replace("{selected_alfabetico_cognome}", $alfabetico_cognome, $paginaHTML);
$paginaHTML = str_replace("{selected_alfabetico_username}", $alfabetico_username, $paginaHTML);
$paginaHTML = str_replace("{selected_data_piu_recente}", $data_iscrizione_piu_recente, $paginaHTML);
$paginaHTML = str_replace("{selected_data_meno_recente}", $data_iscrizione_meno_recente, $paginaHTML);
$paginaHTML = str_replace("{selected_attivi}", $attivi, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{ListaUtenti}", $utenti, $paginaHTML);
echo $paginaHTML;

?>