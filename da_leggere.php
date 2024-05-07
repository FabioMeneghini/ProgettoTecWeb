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
            $listaLibri .= '<form method="post" action="da_leggere.php">
                                <p id="descr">
                                    La tabella contiene l\'elenco dei tuoi libri da leggere.
                                    Ogni riga descrive un libro con tre colonne: "titolo", "autore" e un checkbox per iniziare o eliminare il libro.
                                </p>
                                <fieldset>
                                    <table aria-describedby="descr">
                                        <caption>Lista dei libri salvati</caption>
                                        <tr>
                                            <th scope="col">Titolo</th>
                                            <th scope="col">Autore</th>
                                            <th>Inizia</th>
                                            <th>Elimina</th>
                                        </tr>';
            foreach($lista as $libro) {
                $listaLibri .= '<tr>
                                    <td scope="row"><a href="templateSchedaLibro.html">'.$libro["titolo"].'</a></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td><input type="checkbox" id="myCheckbox" name="myCheckbox"></td>
                                    <td><input type="checkbox" id="myCheckbox" name="myCheckbox"></td>
                                </tr>';
            }
            $listaLibri .= '    </table>
                            </fieldset>
                            <fieldset>
                                <input type="submit" id="inizia" name="inizia" value="Inizia">
                                <input type="submit" id="elimina" name="elimina" value="Elimina">
                            </fieldset>
                            </form>';
            /*<td><a href="stai_leggendo.php?id_add='.$libro["id"].'">Inizia</a></td>*/
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