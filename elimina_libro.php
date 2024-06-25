<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    header("Location: scheda_libro.php");
    exit();
}

$eliminato = false;

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    if(isset($_GET['id'])) {
        $LibroSelezionato = $_GET['id'];
        $ok = $connection -> controllareIdLibro($LibroSelezionato);
        if($ok) {
            $eliminato = $connection -> eliminaLibro($LibroSelezionato);
            $connection -> closeConnection();
            if($eliminato) {
                header("Location: tutti_libri.php?eliminato=1.php");
                exit();
            }
            else {
                header("Location: scheda_libro.php?id=".$LibroSelezionato."&eliminato=0");
                exit();
            }
        }
    }
    header("Location: index.php");
    exit();
}
else {
    header("Location: 500.php");
    exit();
}

?>