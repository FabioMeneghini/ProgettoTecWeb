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
                    header("Location: index.php");
                    //header("Location: index.php?success=1"); //MOSTRARE MESSAGE DI SUCCESSO
                    exit();
                }
                else { //da sistemare
                    header("Location: scheda_libro.php");
                    //header("Location: index.php?success=0"); //MOSTRARE MESSAGE DI ERRORE
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