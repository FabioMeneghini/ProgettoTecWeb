<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

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

$paginaHTML = file_get_contents("template/templateTuttiLibri.html");

$listaGeneri = "";
$catalogo="";
$opzione="";
$selezionato_alfabetico="";
$selezionato_popolarita="";
$selezionato_piu_recente="";
$selezionato_meno_recente="";
$messaggiSuccesso = "";
$torna_su="";

if(isset($_GET['inserito']) && $_GET['inserito'] == 1) {
    $messaggiSuccesso = '<p class="messaggiSuccesso">Libro inserito con successo!</p>';
}
else if(isset($_GET['eliminato']) && $_GET['eliminato'] == 1) {
    $messaggiSuccesso = '<p class="messaggiSuccesso">Libro eliminato con successo!</p>';
}

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $resultGeneri = $connection -> getListaGeneri();
    if(isset($_POST['ordina'])) {
        if(isset($_POST['opzione'])) {
            $opzione=$_POST['opzione'];
            $resultLibri= $connection ->getTuttiLibriOrdinati($_POST['opzione']);
        }
        else {
            $resultLibri= $connection ->getTuttiLibriOrdinati("alfabetico");
        }
    }
    else {
        $resultLibri= $connection ->getTuttiLibriOrdinati("alfabetico");
    }
    $connection -> closeConnection();
    foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
        $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
    }
    if(!empty($resultLibri)){
        $catalogo.= '<p id="descr">
                        La tabella contiene l\'elenco di tutti i libri presenti nel sito.
                        Ogni riga descrive un libro con 5 colonne nominate: "titolo", "copertina", "autore", "lingua", "data inserimento".
                    </p>
                    <table aria-describedby="descr" class="righealternate">
                        <caption>Catalogo di tutti i libri</caption>
                        <tr>
                            <th scope="col">Titolo</th>
                            <th scope="col" abbr="Cop">Copertina</th>
                            <th scope="col" abbr="Aut">Autore</th>
                            <th class="rimuovi" scope="col" abbr="Lin">Lingua</th>
                            <th class="rimuovi" scope="col" abbr="Data ins">Data inserimento</th>
                        </tr>';
        foreach($resultLibri as $libro) {
        $catalogo .= '<tr>
                        <th scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></th>
                        <td><img src="copertine_libri/'.$libro['titolo_ir'].'.jpg" alt="'.$libro["descrizione"].'" width="50" height="70"></td>
                        <td>'.$libro["autore"].'</td>
                        <td class="rimuovi">'.$libro["lingua"].'</td>
                        <td class="rimuovi"><time datetime="'.$libro["data_inserimento"].'">'.$libro["data_inserimento"].'</time></td>
                    </tr>';
        }
        $catalogo .= "</table>";
        if(count($resultLibri)>=10) {
            $torna_su='<nav aria-label="Torna all\'inizio della lista dei libri">
                            <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
        }
    }
    else{
        $catalogo= '<p>Il catalogo è vuoto al momento</p>';
    }
}
else {
    echo "Connessione fallita";
}

if($opzione=="alfabetico"){
    $selezionato_alfabetico="selected";
}
else if($opzione=="popolarita"){
    $selezionato_popolarita="selected";
}
else if($opzione=="piu_recente"){
    $selezionato_piu_recente="selected";
}
else if($opzione=="meno_recente"){
    $selezionato_meno_recente="selected";
}
$paginaHTML = str_replace("{selected_alfabetico}", $selezionato_alfabetico, $paginaHTML);
$paginaHTML = str_replace("{selected_popolarita}", $selezionato_popolarita, $paginaHTML);
$paginaHTML = str_replace("{selected_piu_recente}", $selezionato_piu_recente, $paginaHTML);
$paginaHTML = str_replace("{selected_meno_recente}", $selezionato_meno_recente, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{CatalogoLibri}", $catalogo, $paginaHTML);
$paginaHTML = str_replace("{messaggiSuccesso}", $messaggiSuccesso, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su , $paginaHTML);
echo $paginaHTML;

?>