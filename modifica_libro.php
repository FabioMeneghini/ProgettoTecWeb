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
$listaRecensioni = "<div class=recensioni ><ul>";
//chiama se stessa come pagina nel form
//TO DO deve avere un bottone per eliminarle una ad una è una tabella ? 
$listaGeneri = "";

//Dentro ad un form non l'ho gestito 
//TO DO 
/*if(isset($_GET['id'])) {
    $LibroSelezionato = $_GET['id'];
    $tmp =$connection ->  controllareIdLibro($LibroSelezionato);
    if(!$tmp) {
        //DOVE VANNO VISUALIZZATI / Gestit QUESTI MESSAGGI? 
        //TO DO
        //$messaggigenereselezionato.= $tmp['messaggi'];
    }*/
    //TO DO 
try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        //$resultKeyword = $connection->getKeywordLibro($LibroSelezionato);
        //$immagine= $connection ->  getimmagine($LibroSelezionato);

        //TO DO METTERE UN IMG E RESTITUIRE ALT CON UN ALTRA QUARY 


        //IMMAGINE CON ALT no ImgReplace qua
        //TO DO NEL DB METTERE ALT

        $ok=false;
        if(isset($_GET['id'])) {
            $LibroSelezionato = $_GET['id'];
            $ok = $connection -> controllareIdLibro($LibroSelezionato);
        }
        if($ok) {
            $titolo = $connection ->  gettitololibro($LibroSelezionato);
            $autore = $connection ->  getLibriUtente($LibroSelezionato);
            $genere = $connection ->  getgenereLibro($LibroSelezionato);
            $lingua = $connection ->  getlinguaLibro($LibroSelezionato);
            $trama = $connection ->  gettramaLibro($LibroSelezionato);
            $n_capitoli = $connection ->  getncapitoliLibro($LibroSelezionato);
            //admin reindirizzato non ha la , non registrato ha link accedi, se registrato con rec la ha in textarea
            //se non è un libro che ha terminato ha aggiungi ai salvati 
            // se è un libro terminato ha una text area con lascia una recensione 
            $media_voti = $connection -> getmediavoti($LibroSelezionato);
            //è un paramtro salvato che va aggiornato ogni volta alle form delle recensioni 
            // è una query che viene calcolata al momento in base al join con tutti gli utenti e il libro ? 
            $altre_recensioni = $connection -> getaltrerecensioni($LibroSelezionato);
            //torna un array che deve essere messo in una lista se sono vuote scritta non ci sono recensioni
            /*foreach($altre_recensioni as $recensione) {
                $listaRecensioni.='<dd>'.$recensione["commento"].'</dd>';
            }//TODOTABELLA
            $listaRecensioni.="</ul></div>";*/

            $connection -> closeConnection();
            
            foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
                 $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
            }

            /*if(!empty($resultKeyword)) {
                for ($i=0; $i<count($resultKeyword)-1; $i++) {
                    $listaKeyword .= $resultKeyword[$i]['keyword'].', ';
                }
                $listaKeyword .= $resultKeyword[count($resultKeyword)-1]['keyword'];
            } else {
                $listaKeyword = "Miglior libro";
            }*/
        }
        else {
            $messaggigenereselezionato.="Libro non trovato";
        }
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{keywords}", $listaKeyword, $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri."blabla", $paginaHTML);

//prima sostituisce l'area della recensione con un form poi lo precompila 
//<paginaHTML = str_replace("{arecensionevotoform}", $arearecensionevoto , $paginaHTML);

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