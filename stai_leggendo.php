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
             $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
        if(empty($lista)) {
            $listaLibri = "Non stai leggendo nessun libro. Aggiungine uno ora dalla lista dei tuoi libri salvati."; //aggiungere link alla pagina dei libri salvati
        }
        else {
            $listaLibri .= '<p id="descr">
                                La tabella contiene l\'elenco dei libri che stai leggendo.
                                Ogni riga descrive un libro con tre colonne: "titolo", "autore", "numero capitoli letti".
                                La terza colonna contiene un input per modificare il numero di capitoli che stai leggendo, con la possibilità di aumentare o diminuire.
                            </p>
                            <form method="post" action="stai_leggendo.php">
                                <table aria-describedby="descr">
                                <caption>Lista dei libri che stai leggendo</caption>
                                <tr>
                                    <th scope="col">Titolo</th>
                                    <th scope="col">Autore</th>
                                    <th scope="col">Numero capitoli letti</th>
                                </tr>';
            foreach($lista as $libro) {
                $listaLibri .= '<fieldset>
                                    <tr>
                                        <td scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></td>
                                        <td>'.$libro["autore"].'</td>
                                        <td><input type="number" name="capitoli" id="capitoli" min="0" max="'.$libro["n_capitoli"].'" required placeholder="'.$libro["n_capitoli_letti"].'" value="'.$libro["n_capitoli_letti"].'"></td>
                                    </tr>
                                </fieldset>';
            }
            $listaLibri .= "</table>
            <filedset>
                <input type='submit' id='aggiorna' name='aggiorna' value='Aggiorna capitoli'>
            </filedset>
            </form>";
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