<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateModificaLibro.html");
$listaGeneri = "";
$listaKeyword = "";
$immagine ="";//IMMAGINE CON ALT no ImgReplace qua
//TO DO NEL DB METTERE ALT 
$titolo = "";
$autore = "";
$genere = "";
$lingua = "";
$trama = "";
$n_capitoli = "";
$media_voti = "";
$listaRecensioni = "<div class=recensioni ><dl>";
//chiama se stessa come pagina nel form
//TO DO deve avere un bottone per eliminarle una ad una è una tabella ? 
$listaGeneri = "";

//Dentro ad un form non l'ho gestito 
//TO DO 
if(isset($_GET['id_libro'])) {
    $LibroSelezionato = $_GET['id_libro'];
    $tmp = controllareIdLibro($LibroSelezionato);
            if(! $tmp['ok']) {
                //DOVE VANNO VISUALIZZATI / Gestit QUESTI MESSAGGI? 
                //TO DO
                $messaggigenereselezionato.= $tmp['messaggi'];
            }
    //TO DO 
    try {
        $connection = new DBAccess();
        $connectionOk = $connection -> openDBConnection();
        if($connectionOk) {
            $resultGeneri = $connection -> getListaGeneri();
            //$resultKeyword = $connection->getKeywordLibro($LibroSelezionato);
            $immagine $connection ->  getimmagine($LibroSelezionato);
            $titolo = $connection ->  gettitololibro($LibroSelezionato);
            $autore = $connection ->  getLibriUtente($LibroSelezionato);
            $genere = $connection ->  getgenereLibro($LibroSelezionato);
            $lingua = $connection ->  getlinguaLibro($LibroSelezionato);
            $trama = $connection ->  gettramaLibro($LibroSelezionato);
            $n_capitoli = $connection ->  getncapitoliLibro($LibroSelezionato); 
            $media_voti = $connection -> getmediavoti($LibroSelezionato);
            $altre_recensioni = $connection -> getaltrerecensioni($LibroSelezionato);
            //torna un array che deve essere messo in una lista se sono vuote scritta non ci sono recensioni
            foreach ($altre_recensioni as $recensione) {
                $listaRecensioni .= '<dt>' . $recensione["recensione"] . '</dt>';
                $listaRecensioni .= '<dd><button aria-label="Elimina questa recensione">elimina</button></dd>';
                //controllo sei sicuro vi voler eliminare la recensione? 
                //TODO CSS VISUALIZZARE DD A DX
            }
            $listaRecensioni.="</dl></div>"

            $connection -> closeConnection();
            
            foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
                $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></dd>';
            }

            if(!empty($resultKeyword)) {
                for ($i=0; $i<count($resultKeyword)-1; $i++) {
                    $listaKeyword .= $resultKeyword[$i]['keyword'].', ';
                }
                $listaKeyword .= $resultKeyword[count($resultKeyword)-1]['keyword'];
            } else {
                $listaKeyword = "Miglior libro";
            }
        }
        else {
            echo "Connessione fallita";
        }
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}
$paginaHTML = str_replace("{keywords}", $menu , $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);

$paginaHTML = str_replace("{ImmagineLibro}", $immagine , $paginaHTML);
$paginaHTML = str_replace("{titoloold}", $titolo , $paginaHTML);
$paginaHTML = str_replace("{autoreold}", $autore, $paginaHTML);
$paginaHTML = str_replace("{genereold}", $genere , $paginaHTML);
$paginaHTML = str_replace("{linguaold}", $lingua , $paginaHTML);
$paginaHTML = str_replace("{mediavoti}", $media_voti , $paginaHTML);
$paginaHTML = str_replace("{capitoliold}", $n_capitoli , $paginaHTML);
$paginaHTML = str_replace("{tramaold}", $trama , $paginaHTML);
$paginaHTML = str_replace("{recensioniComunity}", $listaRecensioni, $paginaHTML);
echo $paginaHTML;

?>