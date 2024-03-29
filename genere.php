<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateGenere.html");
$menu ="";
$genereSelezionato="";
//utenti
$userMenu ='<dt><a href="utente.php"><span lang="en">Home</span></a></dt>
    <dt><a href="stai_leggendo.php">Libri che stai leggendo</a></dt>
    <dt><a href="terminati.php">Libri terminati</a></dt>
    <dt><a href="da_leggere.php">Libri da leggere</a></dt>
    <dt><a href="recensione.php">Aggiungi Recensione</a></dt>
    <dt>Lista Generi:</dt>
    {listaGeneri}
    <dt><a href="statistiche.php">Statistiche</a></dt>
    <dt><a href="area_personale.php">Area Personale</a></dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

//admin
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="modifica_libro.php">Modifica Libro</a></dt>
    <dt>Categorie</dt>
    {listaGeneri}
    <dt><a href="area_personale.php">Area Personale</a></dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

$NonRegistrato='<dt><a href="index.php"><span lang="en">Home</span></a></dt>
                <dt>Categorie</dt>
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

try {
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
            //TO DO DB
            $connection -> closeConnection();
            foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
                if($_GET["genere"]==$genere["nome"])
                    $listaGeneri .=$genere["nome"];
                else
                    $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
            }
            if(empty($risultatiLibri)) {
                $listaLibri.='<p>Ci scusiamo, al momento non abbiamo libri di questo genere</p>';
            }
            else {
                $listaLibri.='<ul class="listagenere">';
                foreach($risultatiLibri as $libro) {
                    //$listaLibri.='<li><a href="scehda_libro.php?id='.$libro["id"].'" id="'.$libro["titolo_IR"].'">'.$libro["titolo"].'</a></li>';
                    $listaLibri.='<li><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></li>';
                    //torna il titolo che deve fare img replace 
                }
                $listaLibri.='</ul></div>';
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

        }
    } 
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

//$paginaHTML = str_replace("{keyword}", $listaKeyword , $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{LibriGenere}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{NomeGenere}", $genereSelezionato, $paginaHTML);
echo $paginaHTML;

?>