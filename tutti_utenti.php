<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

/*if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] != 1) {
        header("Location: utente.php");
    }
}
else {
    header("Location: index.php");
}*/

$paginaHTML = file_get_contents("template/templateTuttiUtenti.html");

$listaGeneri = "";
$utenti="";
$alfabetico_nome="";
$alfabetico_cognome="";
$alfabetico_username="";
$data_iscrizione_piu_recente="";
$data_iscrizione_meno_recente="";
$attivi="";

try {
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
             $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
        
        if(!empty($resultUtenti)){
            $utenti.= '<p id="descr">
                               La tabella contiene l&apos; elenco di tutti gli utenti registrati al sito .
                               Ogni riga descrive un utente con 4 colonne nell&apos; ordine: nome, cognome, username, email.
                           </p>
                           <table aria-describedby="descr">
                           <caption>Tutti gli utenti del sito</caption>
                           <tr>
                               <th scope="col">Nome</th>
                               <th scope="col">Cognome</th>
                               <th scope="col">Username</th>
                               <th scope="col">Email</th>

                           </tr>';
           foreach($resultUtenti as $utente) {
            $utenti .= '<tr>
                        <td scope="row">'.$utente["nome"].'</td>
                        <td>'.$utente["cognome"].'</td>
                        <td>'.$utente["username"].'</td>
                        <td>'.$utente["email"].'</td>
                        </tr>';
           }
           $utenti .= "</table>";
       }
       else{
           $utenti= '<p>Al momento non ci sono utenti registrati al tuo servizio.</p>';
       }
        
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{selected_alfabetico_nome}", $alfabetico_nome, $paginaHTML);
$paginaHTML = str_replace("{selected_alfabetico_cognome}", $alfabetico_cognome, $paginaHTML);
$paginaHTML = str_replace("{selected_alfabetico_username}", $alfabetico_username, $paginaHTML);
$paginaHTML = str_replace("{selected_data_iscrizione_piu_recente}", $data_iscrizione_piu_recente, $paginaHTML);
$paginaHTML = str_replace("{selected_data_iscrizione_meno_recente}", $data_iscrizione_meno_recente, $paginaHTML);
$paginaHTML = str_replace("{selected_attivi}", $attivi, $paginaHTML);

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{ListaUtenti}", $utenti, $paginaHTML);
echo $paginaHTML;

?>