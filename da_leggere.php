<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}

$paginaHTML = file_get_contents("template/templateDaLeggere.html");

$listaLibri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $lista = $connection -> getListaSalvati($_SESSION['username']);
        $connection -> closeConnection();
        if(empty($lista)) {
            $listaLibri = "Non hai nessun libro da leggere."; //aggiungere link alla pagina di ricerca?
        }
        else {
            $listaLibri .= '<p id="descr">La tabella contiene l\'elenco dei tuoi libri da leggere. Ogni riga descrive un libro con tre colonne: titolo, autore e genere.</p>
                            <table aria-describedby="descr">
                            <caption>Lista dei libri salvati</caption>
                            <tr>
                                <th scope="col">Titolo</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Genere</th>
                            </tr>';
            foreach($lista as $libro) {
                $listaLibri .= '<tr>
                                    <td scope="row"><a href="templateSchedaLibro.html">'.$libro["titolo"].'</a></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td>'.$libro["genere"].'</td>
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