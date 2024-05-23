<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    header("Location: scheda_libro.php");
}

$modificato = false;

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        if(isset($_POST['id'])) {
            $LibroSelezionato = $_POST['id'];
            $ok = $connection -> controllareIdLibro($LibroSelezionato);
            if($ok) {
                $modificato = $connection -> modificaLibro($LibroSelezionato, $_POST['titolo'], $_POST['autore'], $_POST['lingua'], $_POST['capitoli'], $_POST['trama'], $_POST['genere']);
                $connection -> closeConnection();
                if($modificato) {
                    header("Location: scheda_libro.php?id=".$LibroSelezionato."&modificato=1");
                    exit();
                }
                else {
                    header("Location: modifica_libro.php?id=".$LibroSelezionato."&modificato=0");
                    exit();
                }
            }
        }
        header("Location: modifica_libro.php?id=".$LibroSelezionato."&modificato=0");
        exit();
    }
} catch(Throwable $t) {
    echo "Connessione fallita";
}

?>