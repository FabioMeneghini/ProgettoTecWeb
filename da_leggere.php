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
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
    }
    if(empty($lista)) {
        $listaLibri = "Non hai nessun libro da leggere."; //aggiungere link alla pagina di ricerca?
    }
    else {
        $listaLibri .= '<form method="post" action="da_leggere.php" onsubmit="return validaLibriCheckbox()">
                            <p id="descr">
                                La tabella contiene l\'elenco dei tuoi libri da leggere.
                                Ogni riga descrive un libro con tre colonne: "titolo", "autore" e una <span lang="en">checkbox</span> per iniziare o eliminare il libro.
                            </p>
                            <fieldset>
                                <table aria-describedby="descr">
                                    <caption>Lista dei libri salvati</caption>
                                    <tr>
                                        <th scope="col">Titolo</th>
                                        <th scope="col">Autore</th>
                                        <th>Seleziona</th>
                                    </tr>';
        foreach($lista as $libro) {
            $listaLibri .= '<tr>
                                <td scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></td>
                                <td>'.$libro["autore"].'</td>
                                <td><input type="checkbox" name="checkbox[]" value="'.$libro["id"].'"></td>
                            </tr>';
        }
        $listaLibri .= '    </table>
                        </fieldset>
                        <fieldset>
                            <input type="submit" id="inizia" name="inizia" value="Inizia">
                            <input type="submit" id="elimina" name="elimina" value="Elimina" onclick="return conferma(\'Sei sicuro/sicura di voler eliminare i libri selezionati dalla lista dei libri da leggere?\')">
                        </fieldset>
                        </form>';
    }
}
else {
    echo "Connessione fallita";
}

$paginaHTML = str_replace("{listaLibri}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>