<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/chisiamo.html");

$menu="";

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

if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1)
    $menu = $adminMenu;
else if(isset($_SESSION['username']))
    $menu = $userMenu;
else
    $menu = $NonRegistrato;

$listaGeneri ="";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $resultGeneri = $connection -> getListaGeneri();
    foreach($resultGeneri as $genere)  //per ogni genere, creo una lista di libri di quel genere
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    $connection -> closeConnection();
}
else {
    header("Location: 500.php");
    exit();
}

$paginaHTML = str_replace("{menu}", $menu, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>