<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] != 1) {
        header("Location: utente.php");
    }
}
else {
    header("Location: index.php");
}

$paginaHTML = file_get_contents("template/templateHomeAdmin.html");

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $n_registrati= $connection -> getUtentiRegistratiCount();
        $n_recensioni= $connection -> getRecensioniCount();
        $n_utenti= $connection -> getUtentiCheStannoLeggendoCount();
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
echo $paginaHTML;

?>