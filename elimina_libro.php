<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    header("Location: scheda_libro.php");
}

$eliminato = false;

try {
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
        //header("Location: index.php?success=0"); //MOSTRARE MESSAGE DI ERRORE
        exit();
    }
} catch(Throwable $t) {
    echo "Connessione fallita";
}

?>