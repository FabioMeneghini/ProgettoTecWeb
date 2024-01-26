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
    <dt>Area Personale</dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

//admin
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="modifica_libro.php">Modifica Libro</a></dt>
    <dt>Categorie</dt>
    {listaGeneri}
    <dt>Area Personale</dt>
    <dt><a href="tcerca.php">Cerca</a></dt>';

$NonRegistrato='<dt><a href="index.php"><span lang="en">Home</span></a></dt>
                <dt>Categorie</dt>
                {listaGeneri}
                <dt><a href="accedi.php">Accedi</a></dt>
                <dt><a href="registrati.php">Registrati</a></dt>
                <dt><a href="cerca.php">Cerca</a></dt>';


if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        $menu = $adminMenu;
    } else {
        if(isset($_SESSION['username']))
            $menu =$userMenu;
        else
            $menu =$NonRegistrato;

    }
}

$listaGeneri = "";
if(isset($_GET['genere'])) {
    $genereSelezionato = $_GET['genere'];
    try {
        $connection = new DBAccess();
        $connectionOk = $connection -> openDBConnection();
        if($connectionOk) {
            $resultGeneri = $connection -> getListaGeneri();
            $risultatiLibri = $connection ->getListaLibriGenere($genere);
            $resultKeyword = $connection->getKeywordByGenere($genereSelezionato);
            $connection -> closeConnection();
            foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
                if($_GET["genere"]==$genere["genere"])
                $listaGeneri .=$genere["genere"];
                else
                    $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></dd>';
            }
            //DONE
            if(!empty($resultKeyword)) {
                foreach($resultKeyword as $keyword) {
                    $listaKeyword .= '<li>'.$keyword['keyword'].'</li>';
                }
            } else {
                $listaKeyword = "Miglior genere";
            }
        } 

        else {
            echo "Connessione fallita";
        }
    }
    catch(Throwable $e) {
        echo "Errore: ".$e -> getMessage();
    }
}
$paginaHTML = str_replace("{keyword}", $listaKeyword , $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{libriGenere}", $risultatiLibri, $paginaHTML);
$paginaHTML = str_replace("{NomeGenere}", $genereSelezionato, $paginaHTML);
echo $paginaHTML;

?>