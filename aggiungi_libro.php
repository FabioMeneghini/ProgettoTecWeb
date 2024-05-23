<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

function controllaInput($titolo, $autore, $lingua, $capitoli, $trama, $genere) {
    $messaggi = "";
    if($titolo == "") {
        $messaggi .= "<li>Il titolo non può essere vuoto</li>";
    }
    else if(strlen($titolo) > 100) {
        $messaggi .= "<li>Il titolo non può superare i 100 caratteri</li>";
    }
    if($autore == "") {
        $messaggi .= "<li>L'autore non può essere vuoto</li>";
    }
    else if(strlen($autore) > 100) {
        $messaggi .= "<li>L'autore non può superare i 100 caratteri</li>";
    }
    if($genere == "") {
        $messaggi .= "<li>Il genere non può essere vuoto</li>";
    }
    else if(strlen($genere) > 25) {
        $messaggi .= "<li>Il genere non può superare i 25 caratteri</li>";
    }
    if($lingua == "") {
        $messaggi .= "<li>La lingua non può essere vuota</li>";
    }
    else if(strlen($lingua) > 25) {
        $messaggi .= "<li>La lingua non può superare i 25 caratteri</li>";
    }
    if($capitoli == "") {
        $messaggi .= "<li>I capitoli non possono essere vuoti</li>";
    }
    else if(!is_numeric($capitoli)) {
        $messaggi .= "<li>I capitoli devono essere un numero</li>";
    }
    else if($capitoli < 1) {
        $messaggi .= "<li>I capitoli devono essere almeno 1</li>";
    }
    if($trama == "") {
        $messaggi .= "<li>La trama non può essere vuota</li>";
    }
    else if(strlen($trama) > 3000) {
        $messaggi .= "<li>La trama non può superare i 3000 caratteri</li>";
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}


if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] != 1) {
        header("Location: utente.php");
        exit();
    }
}
else {
    header("Location: index.php");
    exit();
}

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
        foreach($resultGeneri as $genere) {
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
            $data_list_generi.= '<option value="'.$genere["nome"].'">'.$genere["nome"].'</option>';
        }
        $resultLingue= $connection -> getLingueLibri();
        foreach($resultLingue as $lingue) {
            $lista_lingue .= '<option value="'.$lingue["lingua"].'">'.$lingue["lingua"].'</option>';
        }

        if(isset($_POST['inserisci'])) {
            $titolo = $_POST['titolo'];
            $autore = $_POST['autore'];
            $lingua = $_POST['lingua'];
            $capitoli = $_POST['capitoli'];
            $trama = $_POST['trama'];
            $genere = $_POST['genere'];
            $ok = controllaInput($titolo, $autore, $lingua, $capitoli, $trama, $genere);
            if($ok["ok"]) {
                $result = $connection -> aggiungiLibro($titolo, $autore, $lingua, $capitoli, $trama, $genere);
                $connection -> closeConnection();
                if($result) {
                    header("Location: tutti_libri.php?inserito=1");
                    exit();
                }
                else
                    $messaggi = '<li>Errore durante l\'inserimento del libro</li>';
            }
            else
                $messaggi = $ok["messaggi"];
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
$paginaHTML = str_replace("{messaggiForm}", $messaggi=="" ? "" : "<ul>".$messaggi."</ul>", $paginaHTML);
$paginaHTML = str_replace("{selectGeneri}", $data_list_generi, $paginaHTML);
$paginaHTML = str_replace("{listaLingue}", $lista_lingue, $paginaHTML);

echo $paginaHTML;

?>