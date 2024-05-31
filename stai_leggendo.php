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
$messaggiSuccesso = "";
$torna_su="";

if(isset($_GET['iniziato']) && $_GET['iniziato'] == 1) {
    $messaggiSuccesso = '<p class="successo">Libro iniziato con successo!</p>';
}

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        if(isset($_POST['aggiorna'])){
            $capitoli=$_POST["capitoli"];
            $id_libri=$_POST["id_libri"];
            $username=$_SESSION["username"];
            $connection -> aggiornaStaLeggendo($username, $id_libri, $capitoli); //AGGIORNARE LIBRI CHE STA LEGGENDO L'UTENTE CON QUELLI PRESI DA POST, SE HA MESSO MASSIMO DEVE ANDARE IN TERMINATI E TOGLIERLO DA DA LEGGERE
        }
        if(isset($_GET['id_add'])) { // !!!!! da giustificare nella relazione il perché ho usato il metodo GET invece del POST: !!!!!
                                     // in pratica se avessi usato il post avrei dovuto fare un form per ogni riga della tabella,
                                     // mentre così la tabella è più accessibile (credo)
            $connection -> rimuoviDaLeggere($_SESSION['username'], $_GET['id_add']);
            if(!$connection -> staLeggendo($_SESSION['username'], $_GET['id_add']))
                $connection -> aggiungiStaLeggendo($_SESSION['username'], $_GET['id_add']);
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
            $listaLibri .= '<form method="post" action="stai_leggendo.php">
                                <p id="descr">
                                    La tabella contiene l\'elenco dei libri che stai leggendo.
                                    Ogni riga descrive un libro con tre colonne: "titolo", "autore", "numero capitoli letti".
                                    La terza colonna è un campo per modificare il numero del capitolo a cui sei arrivato/a nella lettura, con la possibilità di aumentarli fino a terminare il libro o diminuirli.
                                </p>
                                <fieldset>
                                    <table aria-describedby="descr">
                                        <caption>Lista dei libri che stai leggendo</caption>
                                        <tr>
                                            <th scope="col">Titolo</th>
                                            <th scope="col">Autore</th>
                                            <th scope="col">Numero capitoli letti</th>
                                        </tr>';
            $i=0;
            foreach($lista as $libro) {
                $i++;
                $listaLibri .= '<tr>
                                    <td scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td>
                                        <input type="number" name="capitoli[]" id="capitoli'.$i.'" min="0" max="'.$libro["n_capitoli"].'" required placeholder="'.$libro["n_capitoli_letti"].'" value="'.$libro["n_capitoli_letti"].'">
                                        <input type="hidden" name="id_libri[]" value="'.$libro['id'].'">
                                    </td>
                                </tr>';
                $i+=1;
            }
            $listaLibri .= '    </table>
                            </fieldset>
                            <fieldset>
                                <input type="submit" id="aggiorna" name="aggiorna" value="Aggiorna capitoli">
                            </fieldset>
                            </form>';
        }
        if(count($lista)>=8) {

            $torna_su=' <nav aria-label="Torna al\'inizio della lista dei libri che stai leggendo">
                             <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
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
$paginaHTML = str_replace("{torna_su}", $torna_su, $paginaHTML);
if (empty($messaggiSuccesso)) {
    $paginaHTML = str_replace("{messaggiSuccesso}", "", $paginaHTML);
} else {
    $paginaHTML = str_replace("{messaggiSuccesso}", "<div class=\"messaggiSuccesso\">".$messaggiSuccesso."</div>", $paginaHTML);
}
echo $paginaHTML;

?>