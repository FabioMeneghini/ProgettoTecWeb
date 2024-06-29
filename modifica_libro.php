<?php
//dovrebbe eliminarlo e modificare anche la copertina 
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

function pulisciInput($input) {
    $input = trim($input);
    $input = strip_tags($input);
    $input = htmlentities($input);
    return $input;
}

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    header("Location: scheda_libro.php");
    exit();
}

$paginaHTML = file_get_contents("template/templateModificaLibro.html");
$listaGeneri = "";
$listaKeyword = "";
$immagine ="";
$titolo = "";
$autore = "";
$genere = "";
$lingua = "";
$trama = "";
$n_capitoli = "";
$lista_lingue="";
$data_list_generi="";
$messaggiForm = "";
$messaggiErrore = "";

if(isset($_GET['modifica']) && $_GET['modifica'] == 0) {
    $messaggiErrore = '<p class="messaggiErrore">Ci scusiamo ma non è stato possibile effettuare la modifica.</p>';
}

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    //da controllare
    if(isset($_POST['modifica'])) {
        $titolo = pulisciInput($_POST['titolo']);
        $autore = pulisciInput($_POST['autore']);
        $lingua = pulisciInput($_POST['lingua']);
        $capitoli = pulisciInput($_POST['capitoli']);
        $trama = pulisciInput($_POST['trama']);
        $genere = pulisciInput($_POST['genere']);
        $input_ok = controllaInput($titolo, $autore, $lingua, $capitoli, $trama, $genere);
        if($input_ok["ok"]) {
            if(isset($_POST['id'])) {
                $LibroSelezionato = $_POST['id'];
                $ok = $connection -> controllareIdLibro($LibroSelezionato);
                if($ok) {
                    $modificato = $connection -> modificaLibro($LibroSelezionato, $_POST['titolo'], $_POST['autore'], $_POST['lingua'], $_POST['capitoli'], $_POST['trama'], $_POST['genere']);
                    if($modificato) {
                        $connection -> closeConnection();
                        header("Location: scheda_libro.php?id=".$LibroSelezionato."&modificato=1");
                        exit();
                    }
                    else {
                        $messaggiErrore = '<p class="messaggiErrore">Ci scusiamo ma non è stato possibile effettuare la modifica.</p>';
                    }
                }
            }
            else {
                $messaggiErrore = '<p class="messaggiErrore">Ci scusiamo ma non è stato possibile effettuare la modifica.</p>';
            }
        }
        else {
            $messaggiForm = $input_ok["messaggi"];
        }
    }

    //controllo se l'id del libro è presente
    $tmp=false;
    if(isset($_POST['id'])) {
        $LibroSelezionato = $_POST['id'];
        $tmp = $connection -> controllareIdLibro($LibroSelezionato);
    }
    else if(isset($_GET['id'])) {
        $LibroSelezionato = $_GET['id'];
        $tmp = $connection ->  controllareIdLibro($LibroSelezionato);
    }
    if(!$tmp) {
        header("Location: 404.php"); //libro non trovato o non impostato in $_GET o $_POST
        exit();
    }
    $libro = $connection -> getLibro($LibroSelezionato);
    $resultGeneri = $connection -> getListaGeneri();
    $resultLingue= $connection -> getLingueLibri();
    $connection -> closeConnection();
    
    $titolo = $libro["titolo"];
    $autore = $libro["autore"];
    $genereold = $libro["genere"];
    $linguaold = $libro["lingua"];
    $trama = $libro["trama"];
    $n_capitoli = $libro["n_capitoli"];
    
    foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
        if($genere["nome"] == $genereold)
            $data_list_generi .= '<option value="'.$genere["nome"].'" selected>'.$genere["nome"].'</option>';
        else
            $data_list_generi .= '<option value="'.$genere["nome"].'">'.$genere["nome"].'</option>';
    }
    foreach($resultLingue as $lingue) {
        $lista_lingue .= '<option value="'.$lingue["lingua"].'">'.$lingue["lingua"].'</option>';
    }
}
else {
    header("Location: 500.php");
    exit();
}

$paginaHTML = str_replace("{id_libro}", $LibroSelezionato, $paginaHTML);
$paginaHTML = str_replace("{TitoloLibro}", $titolo, $paginaHTML);
$paginaHTML = str_replace("{messaggiForm}", $messaggiForm=="" ? "" : "<ul class=\"messaggiErrore\"'>".$messaggiForm."</ul>", $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{selectGeneri}", $data_list_generi, $paginaHTML);
$paginaHTML = str_replace("{listaLingue}", $lista_lingue, $paginaHTML);
$paginaHTML = str_replace("{ImmagineLibro}", $immagine , $paginaHTML);
$paginaHTML = str_replace("{titoloold}", $titolo , $paginaHTML);
$paginaHTML = str_replace("{autoreold}", $autore, $paginaHTML);
$paginaHTML = str_replace("{genereold}", $genereold , $paginaHTML);
$paginaHTML = str_replace("{linguaold}", $linguaold , $paginaHTML);
$paginaHTML = str_replace("{capitoliold}", $n_capitoli , $paginaHTML);
$paginaHTML = str_replace("{tramaold}", $trama , $paginaHTML);
$paginaHTML = str_replace("{idlibro}", $LibroSelezionato , $paginaHTML);
$paginaHTML = str_replace("{messaggiErrore}", $messaggiErrore, $paginaHTML);
echo $paginaHTML;

?>