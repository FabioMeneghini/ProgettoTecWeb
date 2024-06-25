<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
        exit();
    }
}
else {
    header("Location: index.php");
    exit();
}

$paginaHTML = file_get_contents("template/templateStatisticheUtente.html");
$listaGeneri = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if($connectionOk) {
    $n_recensioni = $connection -> getRecensioniUtente($_SESSION['username']);
    $n_libri_letti = $connection ->  getNumeroLibriLetti($_SESSION['username']);
    $n_libri_stai_leggendo = $connection -> getNumeroLibriStaLeggendo($_SESSION['username']);
    $n_libri_salvati = $connection -> getNumeroLibriSalvati($_SESSION['username']);
    $n_libri_letti_anno = $connection -> getNumeroLibriLettiUltimoAnno($_SESSION['username']);
    $resultListaGeneri = $connection -> getListaGeneri();
        
    foreach($resultListaGeneri as $genere) {
            $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }
    $connection -> closeConnection();
    if ($n_libri_letti_anno == 0 && $n_libri_stai_leggendo == 0) {
        $messaggio_motivazionale = '<p>Ogni impresa inizia con piccoli passi, quest\'anno puoi ancora leggere molti libri! Lasciati ispirare dalle recensioni della <span lang="en">community</span> per iniziare nuove letture</p>';
    } elseif ($n_libri_letti_anno == 0 && $n_libri_stai_leggendo > 0) {
        $messaggio_motivazionale = '</p>Dedica il giusto tempo alla lettura per crescere, rilassarti e imparare, e non dimenticarti di segnare i tuoi progressi e le recensioni per aiutare gli altri come te!</p>';
    } else {
        $messaggio_motivazionale = '</p>Complimenti! Continua a leggere i tuoi libri, grazie a te ed alle tue recensioni altre persone scoprono e continuano ad amare il mondo dela lettura</p>';
    }
    }
else {
    echo "Connessione fallita";
}

$paginaHTML = str_replace("{listaLibri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{NumeroRecensioni}", $n_recensioni, $paginaHTML);
$paginaHTML = str_replace("{LibriLetti}", $n_libri_letti, $paginaHTML);
$paginaHTML = str_replace("{LibriStaiLeggendo}", $n_libri_stai_leggendo, $paginaHTML);
$paginaHTML = str_replace("{LibriSalvati}", $n_libri_salvati, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{LibriLettiAnno}", $n_libri_letti_anno, $paginaHTML);
$paginaHTML = str_replace("{messaggio_motivazionale}", $messaggio_motivazionale, $paginaHTML);

echo $paginaHTML;

?>