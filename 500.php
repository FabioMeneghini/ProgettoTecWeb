<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/500.html");
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

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        $menu = $adminMenu;
    } 
    else
        $menu = $userMenu;
}
else {
    $menu = $NonRegistrato;
}

$listaGeneri = "";

// la lista dei generi è statica perché questa pagina viene raggiunta quando non è possibile connettersi al database,
// quindi non è possibile recuperare i generi dal database.
// Pertanto, ogni volta che si aggiunge un nuovo genere al database, è necessario aggiornare questa lista manualmente.
$listaGeneri .= '<li><a href="genere.php?genere=Fantasy">Fantasy</a></li>
                <li><a href="genere.php?genere=Romanzo">Romanzo</a></li>
                <li><a href="genere.php?genere=Fantascienza">Fantascienza</a></li>
                <li><a href="genere.php?genere=Storico">Storico</a></li>
                <li><a href="genere.php?genere=Avventura">Avventura</a></li>
                <li><a href="genere.php?genere=Horror">Horror</a></li>
                <li><a href="genere.php?genere=Thriller">Thriller</a></li>';

$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri , $paginaHTML);
echo $paginaHTML;

?>