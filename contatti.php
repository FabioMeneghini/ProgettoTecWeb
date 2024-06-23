<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/contatti.html");

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
    echo "Connessione fallita";
}

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>