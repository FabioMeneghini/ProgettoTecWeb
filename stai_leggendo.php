<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
    exit();
}

$paginaHTML = file_get_contents("template/templateStaiLeggendo.html");

$listaLibri = "";
$listaGeneri = "";
$messaggiSuccesso = "";
$messaggiErrore = "";
$torna_su="";

if(isset($_GET['iniziato']) && $_GET['iniziato'] == 1) {
    $messaggiSuccesso = '<p class="messaggiSuccesso">Libro iniziato con successo!</p>';
}
$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    if(isset($_POST['aggiorna'])){
        $capitoli=$_POST["capitoli"];
        $id_libri=$_POST["id_libri"];
        $username=$_SESSION["username"];
        $aggiornamento = $connection -> aggiornaStaLeggendo($username, $id_libri, $capitoli);
        if($aggiornamento)
            $messaggiSuccesso = '<p class="messaggiSuccesso">Capitoli aggiornati con successo!</p>';
        else
            $messaggiErrore = '<p class="messaggiErrore">Errore nell\'aggiornamento dei capitoli. Riprova.</p>';
    }

    $lista = $connection -> getListaStaLeggendo($_SESSION['username']);
    $resultListaGeneri = $connection -> getListaGeneri();
    $connection -> closeConnection();
    foreach($resultListaGeneri as $genere) {
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }
    if(empty($lista)) {
        $listaLibri = "Non stai leggendo nessun libro. Aggiungine uno ora dalla lista dei tuoi libri salvati."; //aggiungere link alla pagina dei libri salvati
    }
    else {
        $listaLibri .= '<form method="post" action="stai_leggendo.php">
                            <p id="descr">
                                La tabella contiene l\'elenco dei libri che stai leggendo.
                                Ogni riga descrive un libro con tre colonne: "titolo", "autore", "numero capitoli letti".
                                La terza colonna è un campo per modificare il numero del capitolo a cui sei arrivato/a nella lettura, con la possibilità di aumentarli fino a terminare il libro o diminuirli.
                            </p>
                            <fieldset class="righealternate">
                                <legend>Elenco dei libri che stai leggendo</legend>
                                <table aria-describedby="descr">
                                    <caption>Lista dei libri che stai leggendo</caption>
                                    <tr>
                                        <th scope="col">Titolo</th>
                                        <th class="rimuovi" scope="col" abbr="Aut">Autore</th>
                                        <th scope="col" abbr="Cap. letti">Numero capitoli letti</th>
                                    </tr>';
        foreach($lista as $libro) {
            $listaLibri .= '<tr>
                                <th scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></th>
                                <td class="rimuovi">'.$libro["autore"].'</td>
                                <td>
                                    <input aria-label="Aumenta o diminuisci i capitoli letti di '.$libro["titolo"].'" type="number" name="capitoli[]" class="non_rimuovi_print" min="0" max="'.$libro["n_capitoli"].'" required placeholder="'.$libro["n_capitoli_letti"].'" value="'.$libro["n_capitoli_letti"].'">
                                    <input type="hidden" name="id_libri[]" value="'.$libro['id'].'">
                                </td>
                            </tr>';
        }
        $listaLibri .= '    </table>
                            <input type="submit" id="aggiorna" name="aggiorna" value="Aggiorna capitoli">
                        </fieldset>
                        </form>';
        if(count($lista)>=8) {
            $torna_su=' <nav aria-label="Torna al\'inizio della lista dei libri che stai leggendo">
                            <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
        }
    }
}
else {
    header("Location: 500.php");
    exit();
}

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{listaLibri}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{messaggiSuccesso}", $messaggiSuccesso, $paginaHTML);
$paginaHTML = str_replace("{messaggiErrore}", $messaggiErrore, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su, $paginaHTML);
echo $paginaHTML;

?>