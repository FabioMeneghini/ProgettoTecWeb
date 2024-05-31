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
        if(isset($_POST['elimina']) && !empty($_POST['checkbox'])) {
            $id_libri=$_POST["checkbox"];
            $connection -> eliminaLibriTerminati($_SESSION['username'], $id_libri);
            $connection -> eliminaValutazioni($_SESSION['username'], $id_libri);
        }
        $lista = $connection -> getListaTerminati($_SESSION['username']);
        $resultListaGeneri = $connection -> getListaGeneri();
        $connection -> closeConnection();
        foreach($resultListaGeneri as $genere) {
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
        if(empty($lista)) {
            $listaLibri = "<p>Non hai terminato nessun libro.</p>";
        }
        else {
            $listaLibri .= '<form method="post" action="terminati.php" onsubmit="return conferma(\'Sei sicuro di voler eliminare i libri selezionati dalla lista dei tuoi libri terminati? Eventuali valutazioni assegnate ad essi verranno perse definitivamente.\')">
                                <p id="descr">La tabella contiene l\'elenco dei libri che hai terminato. Ogni riga descrive un libro con cinque colonne: titolo, autore, data di fine lettura, voto assegnato e una <span lang="en">checkbox</span> per eliminare il libro.</p>
                                <fieldset class="righealternate">
                                    <table aria-describedby="descr">
                                        <caption>Lista dei libri che hai terminato</caption>
                                        <tr>
                                            <th scope="col">Titolo</th>
                                            <th scope="col">Autore</th>
                                            <th scope="col">Data di fine lettura</th>
                                            <th scope="col">Voto assegnato</th>
                                            <th scope="col">Elimina</th>
                                        </tr>';
            foreach($lista as $libro) {
                $listaLibri .= '<tr>
                                    <td scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td>'.$libro["data_fine_lettura"].'</td>
                                    <td>'.$libro["voto"].'</td>
                                    <td><input type="checkbox" name="checkbox[]" value="'.$libro["id"].'"></td>
                                </tr>';
            }
            $listaLibri .= '    </table>
                                <input type="submit" id="elimina" name="elimina" value="Elimina" onclick="return validaLibriCheckbox()">
                            </fieldset>
                            </form>';
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