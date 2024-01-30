<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}

$paginaHTML = file_get_contents("template/templateDaLeggere.html");

$listaLibri = "";
$listaGeneri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $lista = $connection -> getListaSalvati($_SESSION['username']);
        $resultListaGeneri = $connection -> getListaGeneri();
        $connection -> closeConnection();
        foreach($resultListaGeneri as $genere) {
             $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
        if(empty($lista)) {
            $listaLibri = "Non hai nessun libro da leggere."; //aggiungere link alla pagina di ricerca?
        }
        else {
            $listaLibri .= '<p id="descr">
                                La tabella contiene l\'elenco dei tuoi libri da leggere.
                                Ogni riga descrive un libro con tre colonne: "titolo", "autore" e "genere".
                                È anche presente una quarta colonna che contiene un link che permette di iniziare a leggere il libro,
                                chiamata "Inizia a leggere".
                            </p>
                            <table aria-describedby="descr">
                            <caption>Lista dei libri salvati</caption>
                            <tr>
                                <th scope="col">Titolo</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Genere</th>
                                <th scope="col">Inizia a leggere</th>
                            </tr>';
            foreach($lista as $libro) {
                $listaLibri .= '<tr>
                                    <td scope="row"><a href="templateSchedaLibro.html">'.$libro["titolo"].'</a></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td>'.$libro["genere"].'</td>
                                    <td><a href="stai_leggendo.php?id_add='.$libro["id"].'">Inizia</a></td>
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
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>