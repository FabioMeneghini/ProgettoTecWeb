<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}

$paginaHTML = file_get_contents("template/templateStaiLeggendo.html");

$listaLibri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $lista = $connection -> getListaStaLeggendo($_SESSION['username']);
        $connection -> closeConnection();
        if(empty($lista)) {
            $listaLibri = "Non stai leggendo nessun libro. Aggiungine uno ora dalla lista dei tuoi libri salvati."; //aggiungere link alla pagina dei libri salvati
        }
        else {
            $listaLibri .= "<ul>";
            foreach($lista as $libro) {
                $listaLibri .= "<li>".$libro["autore"].' - <a href="templateSchedaLibro.html">'.$libro["titolo"]."</a> - ".$libro["genere"]."</li>";
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