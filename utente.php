<?php

include "config.php";

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

$paginaHTML = file_get_contents("template/templateHomeUtente.html");

/**/

$liste = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            /**/
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