<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
    exit();
}

$paginaHTML = file_get_contents("template/templateDaLeggere.html");

$listaLibri = "";
$listaGeneri = "";
$torna_su="";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    if(isset($_POST['elimina']) && !empty($_POST['checkbox'])) {
        $id_libri=$_POST["checkbox"];
        $connection -> eliminaLibriDaLeggere($_SESSION['username'], $id_libri);
    }
    if(isset($_POST['inizia']) && !empty($_POST['checkbox'])) {
        $id_libri=$_POST["checkbox"];
        $connection -> iniziaALeggere($_SESSION['username'], $id_libri);
        $connection -> closeConnection();
        header("Location: stai_leggendo.php?iniziato=1");
        exit();
    }
    $lista = $connection -> getListaSalvati($_SESSION['username']);
    $resultListaGeneri = $connection -> getListaGeneri();
    $connection -> closeConnection();
    foreach($resultListaGeneri as $genere) {
            $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }
    if(empty($lista)) {
        $listaLibri = "Non hai nessun libro da leggere.";
    }
    else {
        $listaLibri .= '<form method="post" action="da_leggere.php" onsubmit="return validaLibriCheckbox()">
                            <p id="descr">
                                La tabella contiene l\'elenco dei tuoi libri da leggere.
                                Ogni riga descrive un libro con tre colonne: "titolo", "autore" e "seleziona", ovvero una <span lang="en">checkbox</span> con cui è possibile selezionare i libri per poi iniziarli o eliminarli.
                            </p>
                            <fieldset class="righealternate">
                                <legend class="rimuovi_print">Elenco dei libri salvati</legend>
                                <table aria-describedby="descr">
                                    <caption>Lista dei libri salvati</caption>
                                    <tr>
                                        <th scope="col">Titolo</th>
                                        <th scope="col" abbr="Aut">Autore</th>
                                        <th class="rimuovi_print" scope="col" abbr="Sel">Seleziona</th>
                                    </tr>';
        foreach($lista as $libro) {
            $listaLibri .= '<tr>
                                <th scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></th>
                                <td>'.$libro["autore"].'</td>
                                <td class="rimuovi_print"><input aria-label="Seleziona '.$libro["titolo"].' per poi eliminarlo o iniziarlo" type="checkbox" name="checkbox[]" value="'.$libro["id"].'"></td>
                            </tr>';
        }
        $listaLibri .= '    </table>
                            <input type="submit" id="inizia" name="inizia" value="Inizia">
                            <input type="submit" id="elimina" name="elimina" value="Elimina" onclick="return conferma(\'Sei sicuro/sicura di voler eliminare i libri selezionati dalla lista dei libri da leggere?\')">
                        </fieldset>
                        </form>';
        if(count($lista)>=10) {
            $torna_su=' <nav aria-label="Torna all\' inizio della lista dei libri salvati">
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