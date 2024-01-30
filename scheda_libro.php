<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

if($_SESSION['admin'] == 1) {
    header("Location: modifica_libro.php"); 
}
$paginaHTML = file_get_contents("template/templateSchedaLibro.html");
$menu ="";
//utenti
$userMenu ='<dt><a href="utente.php"><span lang="en">Home</span></a></dt>
    <dt><a href="stai_leggendo.php">Libri che stai leggendo</a></dt>
    <dt><a href="terminati.php">Libri terminati</a></dt>
    <dt><a href="da_leggere.php">Libri da leggere</a></dt>
    <dt><a href="recensione.php">Aggiungi Recensione</a></dt>
    <dt>Lista Generi:</dt>
    {listaGeneri}
    <dt><a href="statistiche.php">Statistiche</a></dt>
    <dt>Area Personale</dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

/*admin
L'Admin non entra qua quando entra in una scheda libro la può modificare quindi entra direttamente in modifica libro 
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="modifica_libro.php">Modifica Libro</a></dt>
    <dt>Categorie</dt>
    {listaGeneri}
    <dt>Area Personale</dt>
    <dt><a href="cerca.php">Cerca</a></dt>';
*/

$NonRegistrato='<dt><a href="index.php"><span lang="en">Home</span></a></dt>
                <dt>Categorie</dt>
                {listaGeneri}
                <dt><a href="accedi.php">Accedi</a></dt>
                <dt><a href="registrati.php">Registrati</a></dt>
                <dt><a href="cerca.php">Cerca</a></dt>';

if(isset($_SESSION['username']))
    $menu = $userMenu;
else
    $menu = $NonRegistrato;


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
$tua_recensione = "";
$voto="";
$media_voti = "";
//in THML TO DO E DB
$listaRecensioni = "<div class=recensioni ><ul>";
//chiama se stessa come pagina nel form
$arearecensionevoto="";
if(isset($_SESSION['username'])) {
    $arearecensionevoto='<section id="tramavoto">
    <form method="post" action="scheda_libro.php"> 
        {messaggiForm}
    <fieldset>
    <legend>La tua Recensione e il tuo voto:</legend>
    <label for="durata">Recensione: </label>
    <textarea id="recensione" name="recensione" rows="20" cols="70" placeholder="{recensione}">"{recensione}"</textarea>
    <label for="durata">Voto: </label>
    <input type="number" name="voto" id="voto" max="10" required placeholder="{voto}" value="{voto}"
    <button type="submit">Modifica</button>
    <button type="reset">Annulla</button> />
    </fieldset>
    </form>
    </section>';
    //Modifica e annulla attivi su js solo se ha modificato qualcosa
}
else {
    $arearecensionevoto='<h3 d="recensionetua">La tua Recensione e il tuo Voto:</h3>{recensione}{voto}';
}
//TO DO 
try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $ok=false;
        if(isset($_GET['id'])) {
            //E' ID LIBRO NEL DB VERO ? 
            // TO CHECK
            $LibroSelezionato = $_GET['id'];
            $ok = $connessione -> controllareIdLibro($LibroSelezionato);
        }
        if($ok) {
            $resultGeneri = $connection -> getListaGeneri();
            //$resultKeyword = $connection->getKeywordLibro($LibroSelezionato);
            //$immagine=$connection ->  getimmagine($LibroSelezionato);
            //IMMAGINE CON ALT no ImgReplace qua
            //TO DO NEL DB METTERE ALT 
            $titolo = $connection ->  gettitololibro($LibroSelezionato);
            $autore = $connection ->  getLibriUtente($LibroSelezionato);
            $genere = $connection ->  getgenereLibro($LibroSelezionato);
            $lingua = $connection ->  getlinguaLibro($LibroSelezionato);
            $trama = $connection ->  gettramaLibro($LibroSelezionato);
            $n_capitoli = $connection ->  getncapitoliLibro($LibroSelezionato);
            //admin reindirizzato non ha la , non registrato ha link accedi, se registrato con rec la ha in textarea
            //se non è un libro che ha terminato ha aggiungi ai salvati 
            // se è un libro terminato ha una text area con lascia una recensione 
            $terminato = $connection -> is_terminato($LibroSelezionato,$_SESSION['username']);
            if(isset($_SESSION['username'])&& $terminato) {
                $tua_recensione = $connection -> getrecensionetua($LibroSelezionato,$_SESSION['username']);
                $voto = $connection -> getvototuo($LibroSelezionato,$_SESSION['username']);
                //se la recensione è vuota
                if($tua_recensione==''){
                    $tua_recensione='Scrivi qui una recensione';
                    //il voto rimane vuoto ok place holder vuoto
                }
                else if(isset($_SESSION['username'])&& !$terminato) {
                    $tua_recensione ='<section id="accediform"><h2>Prima di recensire questo libro devi averlo terminato :</h2>
                    <form action=".php" method="post"> 
                        <ul class="messaggiErrore">
                            {messaggi}
                            <!--con js non lasciare submit se i campi dati non sono compilati correttamente-->
                        </ul>
                        <fieldset>
                            <legend></legend>
                            <label for="username">Salva per leggerlo più tardi:</label><br>
                            <input type="button" id="aggiungi" name="aggiungi" value="Aggiungi">
                            <label for="username">Inizia a leggere:</label><br>
                            <input type="button" id="inizia" name="inizia" value="inizia">
                        </fieldset>
                    </form>
                    </section>';
                }
                else {
                    $tua_recensione ='<div><p>Per lasciare una recensione e un voto devi prima accedere al tuo <span lang="en">account</span><p>
                    <a href="accedi.php">Accedi</a>
                    <p>Non hai ancora un <span lang="en">account</span>?</p>
                    <p>Entra a far parte della nostra <span lang="en">community</span>!</p>
                    <a href="registrati.php">Registrati</a><div>';
                    //voto vuoto ok non serve ACCESSIBILE O LO LEGGE ?
                }
        
                $media_voti = $connection -> getmediavoti($LibroSelezionato);
                //è un paramtro salvato che va aggiornato ogni volta alle form delle recensioni 
                // è una query che viene calcolata al momento in base al join con tutti gli utenti e il libro ? 
                $altre_recensioni = $connection -> getaltrerecensioni($LibroSelezionato);
                //torna un array che deve essere messo in una lista se sono vuote scritta non ci sono recensioni
                foreach($altre_recensioni as $recensione) {
                    $listaRecensioni.='<dd>'.$recensione["commento"].'</dd>';
                }
                $listaRecensioni.="</ul></div>";

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
        }
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{keywords}", $menu , $paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);

//prima sostituisce l'area della recensione con un form poi lo compila 
$paginaHTML = str_replace("{arecensionevotoform}", $arearecensionevoto , $paginaHTML);

$paginaHTML = str_replace("{ImmagineLibro}", $immagine , $paginaHTML);
$paginaHTML = str_replace("{TitoloLibro}", $titolo , $paginaHTML);
$paginaHTML = str_replace("{AutoreLibro}", $autore, $paginaHTML);
$paginaHTML = str_replace("{GenereLibro}", $genere , $paginaHTML);
$paginaHTML = str_replace("{LinguaLibro}", $lingua , $paginaHTML);
$paginaHTML = str_replace("{mediavoti}", $media_voti , $paginaHTML);
$paginaHTML = str_replace("{CapitoliLibro}", $n_capitoli , $paginaHTML);
$paginaHTML = str_replace("{tramaLibro}", $trama , $paginaHTML);
$paginaHTML = str_replace("{recensione}", $tua_recensione , $paginaHTML);
$paginaHTML = str_replace("{voto}", $tuovoto, $paginaHTML);
$paginaHTML = str_replace("{recensioniComunity}", $altre_recensioni, $paginaHTML);
echo $paginaHTML;

?>