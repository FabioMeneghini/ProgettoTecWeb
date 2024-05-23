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

$paginaHTML = file_get_contents("template/templateHomeAdmin.html");
$listaGeneri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $n_registrati= $connection -> getUtentiRegistratiCount();
        $n_recensioni= $connection -> getRecensioniCount();
        $n_utenti= $connection -> getUtentiCheStannoLeggendoCount();
        $resultListaGeneri = $connection -> getListaGeneri();
        $n_libri = $connection-> getNumeroLibri();
        $n_eta = $connection-> getEtaMediaUtenti();
        $n_terminati = $connection-> getNumeroLibriTerminatiOggi();
        $n_registrati_oggi = $connection-> getNumeroUtentiRegistratiOggi();
        foreach($resultListaGeneri as $genere) {
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
        
        $connection -> closeConnection();
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{numeroUtentiRegistrati}", $n_registrati, $paginaHTML);
$paginaHTML = str_replace("{numeroRecensioni}", $n_recensioni, $paginaHTML);
$paginaHTML = str_replace("{numeroUtentiCheStannoLeggendo}", $n_utenti, $paginaHTML);
$paginaHTML = str_replace("{numeroLibriTotali}", $n_libri, $paginaHTML);
$paginaHTML = str_replace("{EtaMedia}", $n_eta, $paginaHTML);
$paginaHTML = str_replace("{numeroLibriTerminati}", $n_terminati, $paginaHTML);
$paginaHTML = str_replace("{numeroRegistratiOggi}", $n_registrati_oggi, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>