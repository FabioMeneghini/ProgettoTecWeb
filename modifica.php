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
                    header("Location: modifica_libro.php?id=$LibroSelezionato");
                    //header("Location: index.php?success=1"); //MOSTRARE MESSAGE DI SUCCESSO
                    exit();
                }
                else { //da sistemare
                    header("Location: modifica_libro.php?id=$LibroSelezionato");
                    //header("Location: index.php?success=0"); //MOSTRARE MESSAGE DI ERRORE
                    exit();
                }
            }
        }
        header("Location: modifica_libro.php?id=$LibroSelezionato");
        //header("Location: index.php?success=0"); //MOSTRARE MESSAGE DI ERRORE
        exit();
    }
} catch(Throwable $t) {
    echo "Connessione fallita";
}

?>