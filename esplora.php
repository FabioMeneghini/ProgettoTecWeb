<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateEsplora.html");

$listaGeneri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        foreach($resultGeneri as $genere) {
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></dd>';
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

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>