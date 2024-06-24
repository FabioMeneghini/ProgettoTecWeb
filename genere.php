<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateGenere.html");
$menu ="";
$genereSelezionato="";
$torna_su="";

//utenti
$userMenu ='<li><a href="utente.php"><span lang="en">Home</span></a></li>
    <li><a href="stai_leggendo.php">Libri che stai leggendo</a></li>
    <li><a href="terminati.php">Libri terminati</a></li>
    <li><a href="da_leggere.php">Libri da leggere</a></li>
    <li>
        <a href="generi.php">Generi:</a>
        <ul>
            {listaGeneri}
        </ul>
    </li>
    <li><a href="statistiche.php">Statistiche</a></li>
    <li><a href="area_personale.php">Area personale</a></li>
    <li><a href="area_personale.php">Cerca</a></li>';

//admin
$adminMenu = '<li><a href="admin.php"><span lang="en">Home</span></a></li>
    <li><a href="aggiungi_libro.php">Aggiungi un libro</a></li>
    <li><a href="tutti_libri.php">Catalogo libri</a></li>
    <li><a href="tutti_utenti.php">Archivio utenti</a></li>
    <li>
        <a href="generi.php">Generi:</a>
        <ul>
            {listaGeneri}
        </ul>
    </li>
    <li><a href="area_personale.php">Area personale</a></li>
    <li><a href="area_personale.php">Cerca</a></li>';

$NonRegistrato='<li><a href="index.php"><span lang="en">Home</span></a></li>
    <li>
        <a href="generi.php">Generi:</a>
        <ul>
            {listaGeneri}
        </ul>
    </li>
    <li><a href="accedi.php">Accedi</a></li>
    <li><a href="registrati.php">Registrati</a></li>
    <li><a href="area_personale.php">Cerca</a></li>';

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        $menu = $adminMenu;
    } 
    else
        $menu = $userMenu;
}
else {
    $menu = $NonRegistrato;
}

$listaGeneri = "";
$listaLibri = "";
$keywords ="";

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
        $keywords = $connection->getkeywordsGenere($genereSelezionato);
        $connection -> closeConnection();

        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            if($_GET["genere"]==$genere["nome"])
                $listaGeneri .='<li>'.$genere["nome"].'</li>';
            else
                $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
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
    }
    else {
        header("Location: 404.html");
    }
} 
else {
    echo "Connessione fallita";
}

if($keywords=="")
    $keywords="narrazione, personaggi, trama, emozioni, conflitto, ambientazione, temi, climax, svolgimento, conclusione";

$paginaHTML = str_replace("{keyword}", $keywords , $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{LibriGenere}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{NomeGenere}", $genereSelezionato, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su , $paginaHTML);
echo $paginaHTML;

?>