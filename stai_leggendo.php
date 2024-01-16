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
            $listaLibri .= '<p id="descr">La tabella contiene l\'elenco dei libri che stai leggendo. Ogni riga descrive un libro con quattro colonne: titolo, autore, genere, numero capitoli letti.</p>
                            <table aria-describedby="descr">
                            <caption>Lista dei libri che stai leggendo</caption>
                            <tr>
                                <th scope="col">Titolo</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Genere</th>
                                <th scope="col">Numero capitoli letti</th>
                            </tr>';
            foreach($lista as $libro) {
                $listaLibri .= '<tr>
                                    <td scope="row"><a href="templateSchedaLibro.html">'.$libro["titolo"].'</a></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td>'.$libro["genere"].'</td>
                                    <td>'.$libro["n_capitoli_letti"].'/'.$libro["n_capitoli"].'</td>
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