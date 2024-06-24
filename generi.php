<?php

include "config.php";
include "menu.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateGeneri.html");
$menu ="";

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