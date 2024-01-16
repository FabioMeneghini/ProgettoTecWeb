<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

/*if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
    }
}
else {
    header("Location: index.php");
}*/

$paginaHTML = file_get_contents("template/templateHomeUtente.html");

$liste = "";
$listaGeneri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= "<dd>".$genere["genere"]."</dd>";
            $liste .= '<h2 class="titologenere"><a href="templateGenere.html">'.$genere['genere'].'</a></h2>
                       <ul class="listageneri">';
            $listaLibri = $connection -> getListaLibriGenere($genere['genere']);
            foreach($listaLibri as $libro) {
                $liste .= "<li>".$libro["titolo"]."</li>"; //$libro["autore"] lo si visualizza solo al momento del passaggio del mouse sopra al libro
            }
            $liste .= "</ul>";
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

$paginaHTML = str_replace("{listeLibri}", $liste, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>