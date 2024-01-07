<?php

include "config.php";
echo "Prova utente.php";
/*
require_once "DBAccess.php";
use DB\DBAccess;

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
    }
}
else {
    header("Location: index.php");
}

$listaBestSeller = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultListaBestSeller = $connection -> getListaBestSeller();
        foreach($resultListaBestSeller as $libro) {
            $listaBestSeller .= "<li>".$libro["autore"]." - ".$libro["titolo"]." - ".$libro["genere"]."</li>";
        }
    }
    $connection -> closeConnection();
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{listaBestSeller}", $listaBestSeller, $paginaHTML);
echo $paginaHTML;
*/

?>