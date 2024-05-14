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

$paginaHTML = file_get_contents("template/templateStatisticheUtente.html");
$listaGeneri = "";


try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    
    if($connectionOk) {
        $n_recensioni = $connection -> getRecensioniUtente($_SESSION['username']);
        $n_libri_letti = $connection ->  getLibriUtente($_SESSION['username']);
        $n_libri_stai_leggendo = $connection -> getNumeroLibriStaLeggendo($_SESSION['username']);
        $n_libri_salvati = $connection -> getNumeroLibriSalvati($_SESSION['username']);
        $resultListaGeneri = $connection -> getListaGeneri();
           
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
$paginaHTML = str_replace("{listaLibri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{NumeroRecensioni}", $n_recensioni, $paginaHTML);
$paginaHTML = str_replace("{LibriLetti}", $n_libri_letti, $paginaHTML);
$paginaHTML = str_replace("{LibriStaiLeggendo}", $n_libri_stai_leggendo, $paginaHTML);
$paginaHTML = str_replace("{LibriSalvati}", $n_libri_salvati, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>