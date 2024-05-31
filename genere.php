<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateGenere.html");
$menu ="";
$genereSelezionato="";
$torna_su="";
//utenti
$userMenu ='<dt><a href="utente.php"><span lang="en">Home</span></a></dt>
    <dt><a href="stai_leggendo.php">Libri che stai leggendo</a></dt>
    <dt><a href="terminati.php">Libri terminati</a></dt>
    <dt><a href="da_leggere.php">Libri da leggere</a></dt>
    <dt><a href="generi.php">Generi:</a></dt>
    {listaGeneri}
    <dt><a href="statistiche.php">Statistiche</a></dt>
    <dt><a href="area_personale.php">Area Personale</a></dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

//admin
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="generi.php">Generi:</a></dt>
    {listaGeneri}
    <dt><a href="area_personale.php">Area Personale</a></dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

$NonRegistrato='<dt><a href="index.php"><span lang="en">Home</span></a></dt>
                <dt><a href="generi.php">Generi:</a></dt>
                {listaGeneri}
                <dt><a href="accedi.php">Accedi</a></dt>
                <dt><a href="registrati.php">Registrati</a></dt>
                <dt><a href="cerca.php">Cerca</a></dt>';

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        $menu = $adminMenu;
    } 
    else
        $menu =$userMenu;
}
else {
    $menu =$NonRegistrato;
}

$listaGeneri = "";
$listaLibri = "";
$resultKeyword ="";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $ok=false;
    if(isset($_GET['genere'])) {
        $genereSelezionato = $_GET['genere'];
        $ok = $connection -> controllagenere($genereSelezionato);
    }
    if($ok) {
        $resultGeneri = $connection -> getListaGeneri();
        $risultatiLibri = $connection ->getListaLibriGenere($genereSelezionato);
        //$resultKeyword = $connection->getKeywordByGenere($genereSelezionato);
        $connection -> closeConnection();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            if($_GET["genere"]==$genere["nome"])
                $listaGeneri .='<dd>'.$genere["nome"]. '</dd>';
            else
                $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
        if(empty($risultatiLibri)) {
            $listaLibri.='<p>Ci scusiamo, al momento non abbiamo libri di questo genere</p>';
        }
        else {
            $listaLibri.='<ul id="listagenere">';
            foreach($risultatiLibri as $libro) {
                $listaLibri.='<li><a id="'.$libro["titolo_ir"].'" href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></li>';
            }
            $listaLibri.='</ul>';
        }
        if(count($risultatiLibri)>=15) {
            $torna_su=' <nav aria-label="Torna al form di ricerca">
                            <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
        }
        /*if(!empty($resultKeyword)) {
            foreach($resultKeyword as $keyword) {
                $listaKeyword .= '<li>'.$keyword['keyword'].'</li>';
            }
        } else {
            $listaKeyword = "Miglior genere";
        }*/
    }
    else {
        header("Location: 404.html");
        exit();
    }
} 
else {
    echo "Connessione fallita";
}

//$paginaHTML = str_replace("{keyword}", $listaKeyword , $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{LibriGenere}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{NomeGenere}", $genereSelezionato, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su , $paginaHTML);
echo $paginaHTML;

?>