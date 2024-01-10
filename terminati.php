<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}

$paginaHTML = file_get_contents("template/templateTerminati.html");

$listaLibri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $lista = $connection -> getListaTerminati($_SESSION['username']);
        $connection -> closeConnection();
        if(empty($lista)) {
            $listaLibri = "Non hai letto nessun libro."; //aggiungere link alla pagina di ricerca?
        }
        else {
            $listaLibri .= "<ul>";
            foreach($lista as $libro) {
                $listaLibri .= "<article><li>".$libro["autore"].' - <a href="templateSchedaLibro.html">'.$libro["titolo"]."</a> - ".$libro["genere"]."</li></article>";
            }
            $listaLibri .= "</ul>";
        }
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{listaLibri}", $listaLibri, $paginaHTML);
echo $paginaHTML;

?>