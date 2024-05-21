<?php
//dovrebbe eliminarlo e modificare anche la copertina 
include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) {
    header("Location: scheda_libro.php");
    exit();
}

$paginaHTML = file_get_contents("template/templateModificaLibro.html");
$listaGeneri = "";
$listaKeyword = "";
$immagine ="";
$titolo = "";
$autore = "";
$genere = "";
$lingua = "";
$trama = "";
$n_capitoli = "";
$lista_lingue="";
$data_list_generi="";
$messaggiForm = "";
$messaggiErrore = "";

if(isset($_GET['modifica']) && $_GET['modifica'] == 0) {
    $messaggiErrore = '<p class="errore">Ci scusiamo ma non è stato possibile effettuare la modifica.</p>';
}

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        //controllo se l'id del libro è presente
        $tmp=false;
        if(isset($_GET['id'])) {
            $LibroSelezionato = $_GET['id'];
            $tmp = $connection ->  controllareIdLibro($LibroSelezionato);
        }
        if(!$tmp) {
            header("Location: template/404.html");
            exit();
        }

        $ok=false;
        if(isset($_GET['id'])) {
            $LibroSelezionato = $_GET['id'];
            $ok = $connection -> controllareIdLibro($LibroSelezionato);
        }
        if($ok) {
            $titolo = $connection ->  gettitololibro($LibroSelezionato);
            $autore = $connection ->  getautoreLibro($LibroSelezionato);
            $genereold = $connection ->  getgenereLibro($LibroSelezionato);
            $linguaold = $connection ->  getlinguaLibro($LibroSelezionato);
            $trama = $connection ->  gettramaLibro($LibroSelezionato);
            $n_capitoli = $connection ->  getncapitoliLibro($LibroSelezionato);

            $resultGeneri = $connection -> getListaGeneri();
            $resultLingue= $connection -> getLingueLibri();
            
            foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
                $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
                if($genere["nome"] == $genereold) {
                    $data_list_generi .= '<option value="'.$genere["nome"].'" selected>'.$genere["nome"].'</option>';
                }
                else
                    $data_list_generi .= '<option value="'.$genere["nome"].'">'.$genere["nome"].'</option>';
            }
            foreach($resultLingue as $lingue) {
                $lista_lingue .= '<option value="'.$lingue["lingua"].'">'.$lingue["lingua"].'</option>';
            }
            $connection -> closeConnection();


            /*if(!empty($resultKeyword)) {
                for ($i=0; $i<count($resultKeyword)-1; $i++) {
                    $listaKeyword .= $resultKeyword[$i]['keyword'].', ';
                }
                $listaKeyword .= $resultKeyword[count($resultKeyword)-1]['keyword'];
            } else {
                $listaKeyword = "Miglior libro";
            }*/
        }
        /*else {
            $messaggigenereselezionato.="Libro non trovato";
        }*/
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{id_libro}", $LibroSelezionato, $paginaHTML);
$paginaHTML = str_replace("{TitoloLibro}", $titolo, $paginaHTML);
$paginaHTML = str_replace("{messaggiForm}", $messaggiForm, $paginaHTML);
$paginaHTML = str_replace("{keywords}", $listaKeyword, $paginaHTML);

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{selectGeneri}", $data_list_generi, $paginaHTML);
$paginaHTML = str_replace("{listaLingue}", $lista_lingue, $paginaHTML);

$paginaHTML = str_replace("{ImmagineLibro}", $immagine , $paginaHTML);
$paginaHTML = str_replace("{titoloold}", $titolo , $paginaHTML);
$paginaHTML = str_replace("{autoreold}", $autore, $paginaHTML);
$paginaHTML = str_replace("{genereold}", $genereold , $paginaHTML);
$paginaHTML = str_replace("{linguaold}", $linguaold , $paginaHTML);
$paginaHTML = str_replace("{capitoliold}", $n_capitoli , $paginaHTML);
$paginaHTML = str_replace("{tramaold}", $trama , $paginaHTML);
$paginaHTML = str_replace("{messaggiErrore}", $messaggiErrore, $paginaHTML);
echo $paginaHTML;

?>