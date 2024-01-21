<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}

$paginaHTML = file_get_contents("template/templateStaiLeggendo.html");

$listaLibri = "";
$listaGeneri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        if(isset($_GET['id_add'])) { // !!!!! da giustificare nella relazione il perché ho usato il metodo GET invece del POST: !!!!!
                                     // in pratica se avessi usato il post avrei dovuto fare un form per ogni riga della tabella,
                                     // mentre così la tabella è più accessibile (credo)
            $connection -> rimuoviDaLeggere($_SESSION['username'], $_GET['id']);
            if(!$connection -> staLeggendo($_SESSION['username'], $_GET['id']))
                $connection -> aggiungiStaLeggendo($_SESSION['username'], $_GET['id']);
        }
        $lista = $connection -> getListaStaLeggendo($_SESSION['username']);
        $resultListaGeneri = $connection -> getListaGeneri();
        $connection -> closeConnection();
        foreach($resultListaGeneri as $genere) {
            $listaGeneri .= "<dd>".$genere["genere"]."</dd>";
        }
        if(empty($lista)) {
            $listaLibri = "Non stai leggendo nessun libro. Aggiungine uno ora dalla lista dei tuoi libri salvati."; //aggiungere link alla pagina dei libri salvati
        }
        else {
            $listaLibri .= '<p id="descr">
                                La tabella contiene l\'elenco dei libri che stai leggendo.
                                Ogni riga descrive un libro con quattro colonne: "titolo", "autore", "genere", "numero capitoli letti".
                                È anche presente una quinta colonna che contiene un pulsante che permette di avanzare la lettura del
                                libro di un capitolo, chiamata "Leggi capitolo".
                            </p>
                            <table aria-describedby="descr">
                            <caption>Lista dei libri che stai leggendo</caption>
                            <tr>
                                <th scope="col">Titolo</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Genere</th>
                                <th scope="col">Numero capitoli letti</th>
                                <th scope="col">Leggi capitolo</th>
                            </tr>';
            foreach($lista as $libro) {
                $listaLibri .= '<tr>
                                    <td scope="row"><a href="templateSchedaLibro.html">'.$libro["titolo"].'</a></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td>'.$libro["genere"].'</td>
                                    <td>'.$libro["n_capitoli_letti"].'/'.$libro["n_capitoli"].'</td>
                                    <td>?????</td>
                                </tr>';
            }
            $listaLibri .= "</table>";
        }
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{listaLibri}", $listaLibri, $paginaHTML);
echo $paginaHTML;

?>