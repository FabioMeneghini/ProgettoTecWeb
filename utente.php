<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
        exit();
    }
}
else {
    header("Location: index.php");
    exit();
}

$paginaHTML = file_get_contents("template/templateHomeUtente.html");

$messaggiSuccesso = "";
$listaGeneri = "";
$listaLibri = '<div id="libri_genere_home">';
$torna_su="";

if(isset($_GET['registrato']) && $_GET['registrato'] == 1) {
    $messaggiSuccesso = '<p class="messaggiSuccesso">Registrazione avvenuta con successo. Benvenuto, '.$_SESSION['username'].'!</p>';
}
if(isset($_GET['accesso']) && $_GET['accesso'] == 1) {
    $messaggiSuccesso = '<p class="messaggiSuccesso">Accesso avvenuto con successo. Bentornato, '.$_SESSION['username'].'!</p>';
}

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $resultNEW = $connection -> getGeneriPiuLetti($_SESSION['username']);
    if($resultNEW == NULL || empty($resultNEW)) {
        $resultNEW = $connection -> getGeneriPiuPopolari();
    }
    if(is_array($resultNEW)) {
        foreach ($resultNEW as $genere) {
            $listaLibri.='<div class="genere_singolo"><h3><a  href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></h3></div>';
            $risultatiLibri = $connection ->getListaLibriGenere($genere["genere"], 10);
            if(empty($risultatiLibri)) {
                $listaLibri.='<p>Ci scusiamo, al momento non abbiamo libri di questo genere</p>';
            }
            else {
                $listaLibri.='<ul class="librigeneri">';
                foreach($risultatiLibri as $libro) {
                    //$listaLibri.='<li><a href="scehda_libro.php?id='.$libro["id"].'" id="'.$libro["titolo_IR"].'">'.$libro["titolo"].'</a></li>';
                    $listaLibri.='<li><a id="'.$libro["titolo_ir"].'" href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></li>';
                    //torna il titolo che deve fare img replace 
                }
                $listaLibri.='</ul>';
            }
        }
        $listaLibri.="</div>";
    }
    //$risultatiLibri = $connection ->getListaLibriGenere($genere);
    
    $resultGeneri = $connection -> getListaGeneri();
    foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
        $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
    }
    $connection -> closeConnection();
    if(count($resultGeneri)>=3) {

        $torna_su=' <nav aria-label="Torna all\'inizio della home">
                         <a class="torna_su" href="#content">Torna su</a>
                    </nav>';
     }
}
else {
    echo "Connessione fallita";
}

$paginaHTML = str_replace("{LibriGenere}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su, $paginaHTML);
$paginaHTML = str_replace("{messaggiSuccesso}", $messaggiSuccesso, $paginaHTML);
echo $paginaHTML;

?>