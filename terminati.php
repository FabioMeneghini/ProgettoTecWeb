<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
    exit();
}

$paginaHTML = file_get_contents("template/templateTerminati.html");

$listaLibri = "";
$listaGeneri = "";
$torna_su="";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    if(isset($_POST['elimina']) && !empty($_POST['checkbox'])) {
        $id_libri=$_POST["checkbox"];
        $connection -> eliminaLibriTerminati($_SESSION['username'], $id_libri);
        $connection -> eliminaValutazioni($_SESSION['username'], $id_libri);
    }
    $lista = $connection -> getListaTerminati($_SESSION['username']);
    $resultListaGeneri = $connection -> getListaGeneri();
    $connection -> closeConnection();
    foreach($resultListaGeneri as $genere) {
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }
    if(empty($lista)) {
        $listaLibri = "<p>Non hai terminato nessun libro.</p>";
    }
    else {
        $listaLibri .= '<form method="post" action="terminati.php" onsubmit="return conferma(\'Sei sicuro di voler eliminare i libri selezionati dalla lista dei tuoi libri terminati? Eventuali valutazioni assegnate ad essi verranno perse definitivamente.\')">
                        <p id="descr">La tabella contiene l\'elenco dei libri che hai terminato. Ogni riga descrive un libro con cinque colonne: "titolo", "autore", "data di fine lettura", "voto assegnato" e "seleziona", ovvero una colonna utile per selezionare tramite <span lang="en">checkbox</span> i libri che si vogliono eliminare.</p>
                        <fieldset class="righealternate">
                            <legend>Elenco dei libri che hai terminato</legend>
                            <table aria-describedby="descr">
                                <caption>Lista dei libri che hai terminato</caption>
                                <tr>
                                    <th scope="col">Titolo</th>
                                    <th scope="col" abbr="Aut">Autore</th>
                                    <th class="rimuovi" scope="col" abbr="Fine lett.">Data di fine lettura</th>
                                    <th class="rimuovi" scope="col" abbr="Voto">Voto assegnato</th>
                                    <th class="rimuovi_print" scope="col" abbr="Sel">Seleziona</th>
                                </tr>';
        foreach($lista as $libro) {
            $listaLibri .= '<tr>
                                <th scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></th>
                                <td>'.$libro["autore"].'</td>
                                <td class="rimuovi"><time datetime="'.$libro["data_fine_lettura"].'">'.$libro["data_fine_lettura"].'</time></td>
                                <td class="rimuovi">'.$libro["voto"].'</td>
                                <td class="rimuovi_print"><input aria-label="Seleziona '.$libro["titolo"].' per eliminazione" type="checkbox" name="checkbox[]" value="'.$libro["id"].'"></td>
                            </tr>';
        }
        $listaLibri .= '</table>
                        <input type="submit" id="elimina" name="elimina" value="Elimina" onclick="return validaLibriCheckbox()">
                        </fieldset>
                        </form>';
        if(count($lista)>=8) {
            $torna_su=' <nav aria-label="Torna all\'inizio della lista dei libri che stai leggendo">
                            <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
        }
    }
}
else {
    header("Location: 500.php");
    exit();
}

$paginaHTML = str_replace("{listaLibri}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>