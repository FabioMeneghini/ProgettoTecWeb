<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}

$paginaHTML = file_get_contents("template/templateTerminati.html");

$listaLibri = "";
$listaGeneri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $lista = $connection -> getListaTerminati($_SESSION['username']);
        $resultListaGeneri = $connection -> getListaGeneri();
        $connection -> closeConnection();
        foreach($resultListaGeneri as $genere) {
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></dd>';
        }
        if(empty($lista)) {
            $listaLibri = "Non hai terminato nessun libro."; //aggiungere link alla pagina di ricerca?
        }
        else {
            $listaLibri .= '<p id="descr">La tabella contiene l\'elenco dei libri che hai terminato. Ogni riga descrive un libro con cinque colonne: titolo, autore, genere, data di fine lettura, voto assegnato.</p>
                            <table aria-describedby="descr">
                            <caption>Lista dei libri che hai terminato</caption>
                            <tr>
                                <th scope="col">Titolo</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Genere</th>
                                <th scope="col">Data di fine lettura</th>
                                <th scope="col">Voto assegnato</th>
                            </tr>';
            foreach($lista as $libro) {
                $listaLibri .= '<tr>
                                    <td scope="row"><a href="templateSchedaLibro.html">'.$libro["titolo"].'</a></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td>'.$libro["genere"].'</td>
                                    <td>'.$libro["data_fine_lettura"].'</td>
                                    <td>'.$libro["voto"].'</td>
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

$paginaHTML = str_replace("{listaLibri}", $listaLibri, $paginaHTML);
echo $paginaHTML;

?>