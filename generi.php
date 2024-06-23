<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateGeneri.html");
$menu ="";
//utenti
$userMenu ='<dt><a href="utente.php"><span lang="en">Home</span></a></dt>
    <dt><a href="stai_leggendo.php">Libri che stai leggendo</a></dt>
    <dt><a href="terminati.php">Libri terminati</a></dt>
    <dt><a href="da_leggere.php">Libri da leggere</a></dt>
    <dt>Generi:</dt>
    {listaGeneri}
    <dt><a href="statistiche.php">Statistiche</a></dt>
    <dt><a href="area_personale.php">Area personale</a></dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

//admin
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt>Generi:</dt>
    {listaGeneri}
    <dt><a href="area_personale.php">Area personale</a></dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

$NonRegistrato='<dt><a href="index.php"><span lang="en">Home</span></a></dt>
                <dt>Generi:</dt>
                {listaGeneri}
                <dt><a href="accedi.php">Accedi</a></dt>
                <dt><a href="registrati.php">Registrati</a></dt>
                <dt><a href="cerca.php">Cerca</a></dt>';

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        $menu = $adminMenu;
    } 
    else
        $menu =$userMenu;
}
else {
    $menu =$NonRegistrato;
}

$menuGeneri = "";
$listaGeneri = "";
$torna_su="";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $resultGeneri = $connection -> getListaGeneri();
    $connection -> closeConnection();
    foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
        $menuGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        $listaGeneri .= '<li><a id="'.$genere["nome"].'" href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }

    if(count($resultGeneri)>=20) {
        $torna_su='<nav aria-label="Torna all\' inizio della lista dei generi">
                        <a class="torna_su" href="#content">Torna su</a>
                   </nav>';
    }
}
else {
    echo "Connessione fallita";
}

$keywords = "Generi, Libri";
foreach($resultGeneri as $genere) {
    $keywords .= ", ".$genere["nome"];
}

$paginaHTML = str_replace("{keyword}", $keywords , $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{Generi}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $menuGeneri, $paginaHTML);

echo $paginaHTML;

?>