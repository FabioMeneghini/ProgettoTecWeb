<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

function controllaInput($titolo, $autore, $lingua, $capitoli, $trama, $genere) { //da inserire eventualmente altri controlli su username e password
    $messaggi = "";
    if($titolo == "") {
        $messaggi .= "<li>Il titolo non può essere vuoto</li>";
    }
    if($autore == "") {
        $messaggi .= "<li>L'autore non può essere vuoto</li>";
    }
    if($genere == "") {
        $messaggi .= "<li>Il genere non può essere vuoto</li>";
    }
    if($lingua == "") {
        $messaggi .= "<li>La lingua non può essere vuota</li>";
    }
    if($capitoli == "") {
        $messaggi .= "<li>I capitoli non possono eseere vuoti</li>";
    }
    if($trama == "") {
        $messaggi .= "<li>La trama non può essere vuota</li>";
    }
    //aggiungere altri controlli
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}


/*if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] != 1) {
        header("Location: utente.php");
    }
}
else {
    header("Location: index.php");
}*/

$paginaHTML = file_get_contents("template/templateAggiungiLibro.html");

$listaGeneri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }

        if(isset($_POST['inserisci'])) {
            $titolo = $_POST['titolo'];
            $autore = $_POST['autore'];
            $lingua = $_POST['lingua'];
            $capitoli = $_POST['capitoli'];
            $trama = $_POST['trama'];
            $genere = $_POST['genere'];
            //$immagine = $_POST['immagine'];
            //$alt = $_POST['alt'];
            $ok = controllaInput($titolo, $autore, $lingua, $capitoli, $trama, $genere);
            if($ok["ok"]) {
                $result = $connection -> aggiungiLibro($titolo, $autore, $lingua, $capitoli, $trama, $genere);
                $connection -> closeConnection();
            }
            else {
                $paginaHTML = str_replace("{messaggi}", $ok["messaggi"], $paginaHTML);
            }
        }
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>