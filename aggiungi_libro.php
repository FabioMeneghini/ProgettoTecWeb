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
$messaggi = "";
$lista_lingue="";
$data_list_generi="";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
        $resultLingue= $connection -> getLingueLibri();
        
        foreach($resultGeneri as $genere1) { //per ogni genere, creo una lista di libri di quel genere
            $data_list_generi.= '<option value="'.$genere1["nome"].'">'.$genere1["nome"].'</option>';

        }
        foreach($resultLingue as $lingue) { //lista di tutte le lingue
            $lista_lingue .= '<option value="'.$lingue["lingua"].'">'.$lingue["lingua"].'</option>'; ////////////////////////////////
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
                if($result) {
                    header("Location: tutti_libri.php?inserito=1");
                }
                else {
                    $messaggi = '<li>Errore durante l\'inserimento del libro</li>';
                }
            }
            else {
                //$paginaHTML = str_replace("{messaggiForm}", $ok["messaggi"], $paginaHTML);
                $messaggi = $ok["messaggi"];
                if($messaggi != "") {
                    $messaggi = '<ul>'.$messaggi.'</ul>';
                }
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
$paginaHTML = str_replace("{messaggiForm}", $messaggi, $paginaHTML);
$paginaHTML = str_replace("{selectGeneri}", $data_list_generi, $paginaHTML);
$paginaHTML = str_replace("{listaLingue}", $lista_lingue, $paginaHTML);

echo $paginaHTML;

?>