<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

/*if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: modifica_libro.php");
    }
}*/
$paginaHTML = file_get_contents("template/templateSchedaLibro.html");
$menu ="";
//utenti
$userMenu ='<dt><a href="utente.php"><span lang="en">Home</span></a></dt>
    <dt><a href="stai_leggendo.php">Libri che stai leggendo</a></dt>
    <dt><a href="terminati.php">Libri terminati</a></dt>
    <dt><a href="da_leggere.php">Libri da leggere</a></dt>
    <dt><a href="recensione.php">Aggiungi Recensione</a></dt>
    <dt><a href="generi.php">Generi:</a></dt>
    {listaGeneri}
    <dt><a href="statistiche.php">Statistiche</a></dt>
    <dt><a href="area_personale.php">Area Personale</a></dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="generi.php">Generi:</a></dt>
    {listaGeneri}
    <dt><a href="area_personale.php">Area Personale</a></dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

$NonRegistrato='<dt><a href="index.php"><span lang="en">Home</span></a></dt>
                <dt><a href="generi.php">Generi:</a></dt>
                {listaGeneri}
                <dt><a href="accedi.php">Accedi</a></dt>
                <dt><a href="registrati.php">Registrati</a></dt>
                <dt><a href="cerca.php">Cerca</a></dt>';

$menupersonale = "";


if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
    $menu = $adminMenu;
}
else if(isset($_SESSION['username'])){
    $menu = $userMenu;
    $menupersonale = '<dd><a class="menulibro" href="#recensionetua">Vai alla tua recensione e voto</a></dd>';
}
else {
    $menu = $NonRegistrato;
    $menupersonale = '<dd><a class="menulibro" href="#recensionetua">Vai alla tua recensione e voto</a></dd>';
}


$messaggiForm = "";
$listaGeneri = "";
$listaKeyword = "";
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
$listaRecensioni = '<div class="recensioni"><ul>';
//chiama se stessa come pagina nel form
$arearecensionevoto="";
$copertina="";
$alt="";
$bottoni_admin="";

//TO DO 
try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $_SESSION['id_libro'] = 1; //libro di default
        $ok=false;
        if(isset($_GET['id'])) {
            $LibroSelezionato = $_GET['id'];
            $_SESSION['id_libro'] = $LibroSelezionato;
            $ok = $connection -> controllareIdLibro($LibroSelezionato);
        }
        else {
            $LibroSelezionato = $_SESSION['id_libro'];
            $ok = $connection -> controllareIdLibro($LibroSelezionato);
        }
        if(isset($_POST['modifica'])) {
            $modificata = $connection -> modificaRecensione($_SESSION['id_libro'], $_SESSION['username'], $_POST["recensione"], $_POST["voto"]);
            if($modificata)
                $messaggiForm='<p class="messaggiSuccesso">Recensione modificata con successo</p>';
        }
        if($ok) {
            $resultGeneri = $connection -> getListaGeneri();
            $listaKeyword = $connection->getkeywords($LibroSelezionato);
            //TO DO NEL DB METTERE ALT 
            $titolo = $connection ->  gettitololibro($LibroSelezionato);
            $autore = $connection ->  getautoreLibro($LibroSelezionato);
            $genereLibro = $connection ->  getgenereLibro($LibroSelezionato);
            $lingua = $connection ->  getlinguaLibro($LibroSelezionato);
            $trama = $connection ->  gettramaLibro($LibroSelezionato);
            $n_capitoli = $connection ->  getncapitoliLibro($LibroSelezionato);
            $copertina = $connection -> getcopertina($LibroSelezionato);
            $alt = $connection -> getalt($LibroSelezionato);

            foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
                $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
            }

            //admin reindirizzato non ha la , non registrato ha link accedi, se registrato con rec la ha in textarea
            //se non è un libro che ha terminato ha aggiungi ai salvati 
            // se è un libro terminato ha una text area con lascia una recensione 

            if(!isset($_SESSION['username'])) {
                $arearecensionevoto='<h3 id="recensionetua">La tua Recensione e il tuo Voto:</h3>{recensione}';
                $tua_recensione ='<div><p>Per lasciare una recensione e un voto devi prima accedere al tuo <span lang="en">account</span><p>
                                <a href="accedi.php">Accedi</a>
                                <p>Non hai ancora un <span lang="en">account</span>?</p>
                                <p>Entra a far parte della nostra <span lang="en">community</span>!</p>
                                <a href="registrati.php">Registrati</a><div>';
            }
            else if(!isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
                $terminato = $connection -> is_terminato($LibroSelezionato,$_SESSION['username']);
                $salvato= $connection -> is_salvato($LibroSelezionato,$_SESSION['username']);
                $iniziato= $connection -> is_iniziato($LibroSelezionato,$_SESSION['username']);
                if($terminato) {  
                    $tua_recensione = $connection -> getrecensionetua($LibroSelezionato,$_SESSION['username']);
                    $voto = $connection -> getvototuo($LibroSelezionato,$_SESSION['username']);
                    //se la recensione è vuota
                    if($tua_recensione==''){
                        $tua_recensione='Scrivi qui una recensione';
                        //il voto rimane vuoto ok place holder vuoto
                    }
                    $arearecensionevoto='<section id="tramavoto">
                        <form method="post" action="scheda_libro.php"> 
                            {messaggiForm}
                        <fieldset>
                        <legend>La tua Recensione e il tuo voto:</legend>
                        <label for="durata">Recensione: </label><br>
                        <textarea id="recensione" name="recensione" rows="20" cols="70" >'.$tua_recensione.'</textarea><br>
                        <label for="durata">Voto: </label>
                        <input type="number" name="voto" id="voto" max="10" min="0" required placeholder="{voto}" value="{voto}"><br>
                        <button type="submit" id="modifica" name="modifica">Modifica</button><br>
                        <button type="reset">Annulla</button>
                        </fieldset>
                        </form>
                        </section>';
                        //Modifica e annulla attivi su js solo se ha modificato qualcosa
                }
                else if($salvato) {
                    //se inizia a leggerlo deve settare il numero di capitoli letti a 0
                    $arearecensionevoto='<section id="accediform">
                        <form action="scheda_libro.php" method="post"> 
                        <fieldset>
                            <legend>Inizia a leggere:</legend>
                            <label for="username">Questo libro è nei tuoi libri salvati .&Egrave; il momento di iniziare a leggerlo?</label><br>
                            <input type="button" id="inizia" name="inizia" value="inizia">
                        </fieldset>
                    </form>
                    </section>';
                }
                else if($iniziato) {
                    $arearecensionevoto='<p> Questo libro si trova nella lista di libri che stai leggendo. Per vedere il suo avanzamento vai al link: <a href="stai_leggendo.php">Libri che stai leggendo</a> </p>';
                    //manca id del utente che sta leggendo il libro
                    
                }
                else  {
                    $arearecensionevoto='<section id="accediform">
                        <form action="scheda_libro.php" method="post"> 
                        <fieldset>
                            <legend>Prima di recensire questo libro devi averlo terminato</legend>
                            <label for="username">Salva per leggerlo più tardi:</label>
                            <input type="button" id="salva" name="salva" value="Salva"><br>
                            <label for="username">Inizia a leggere:</label>
                            <input type="button" id="inizia" name="inizia" value="Inizia">
                        </fieldset>
                    </form>
                    </section>';
                }
            }
            else {
                $bottoni_admin='<section id="bottoni_admin">
                    <a href="modifica_libro.php?id='.$LibroSelezionato.'">Modifica</a>
                    <form action="elimina_libro.php" method="get">
                        <fieldset>
                            <input type="hidden" id="libroId" name="id" value='.$LibroSelezionato.'>
                            <input type="submit" id="elimina" name="elimina" value="Elimina">
                        </fieldset>
                    </form>';
            }
            $media_voti = $connection -> getmediavoti($LibroSelezionato);
            //è un paramtro salvato che va aggiornato ogni volta alle form delle recensioni 
            // è una query che viene calcolata al momento in base al join con tutti gli utenti e il libro ? 
            $altre_recensioni = $connection -> getaltrerecensioni($LibroSelezionato);
            $connection -> closeConnection();
            //torna un array che deve essere messo in una lista se sono vuote scritta non ci sono recensioni
            if(empty($altre_recensioni)) {
                $listaRecensioni.='<p>Non ci sono ancora recensioni per questo libro</p>';
            }
            else {
                $listaRecensioni.='<ul>';
                foreach($altre_recensioni as $recensione) {
                    $listaRecensioni.='<li class="recensione_singola"><p>'.$recensione["commento"].'</p></li>';
                }
                $listaRecensioni.="</ul></div>";
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
            header("Location: index.php");
            //header("Location: index.php?success=0??????"); //MOSTRARE MESSAGE DI ERRORE (libro non trovato)
            exit();
        }
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{keywords}", $listaKeyword ,$paginaHTML);
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{menupersonale}", $menupersonale, $paginaHTML);

//prima sostituisce l'area della recensione con un form poi lo compila 
$paginaHTML = str_replace("{arearecensionevotoform}", $arearecensionevoto , $paginaHTML);

$paginaHTML = str_replace("{ImmagineLibro}", "copertine_libri/".$copertina.".jpg", $paginaHTML);
$paginaHTML = str_replace("{altlibro}", $alt , $paginaHTML);
$paginaHTML = str_replace("{TitoloLibro}", $titolo , $paginaHTML);
$paginaHTML = str_replace("{AutoreLibro}", $autore, $paginaHTML);
$paginaHTML = str_replace("{GenereLibro}", $genereLibro, $paginaHTML);
$paginaHTML = str_replace("{LinguaLibro}", $lingua , $paginaHTML);
$paginaHTML = str_replace("{mediavoti}", number_format(doubleval($media_voti), 1), $paginaHTML);
$paginaHTML = str_replace("{CapitoliLibro}", $n_capitoli , $paginaHTML);
$paginaHTML = str_replace("{tramaLibro}", $trama , $paginaHTML);
$paginaHTML = str_replace("{bottoniAdmin}", $bottoni_admin , $paginaHTML);
$paginaHTML = str_replace("{recensione}", $tua_recensione , $paginaHTML);
$paginaHTML = str_replace("{voto}", $voto, $paginaHTML);
$paginaHTML = str_replace("{recensioniComunity}", $listaRecensioni, $paginaHTML);
$paginaHTML = str_replace("{messaggiForm}", $messaggiForm ,$paginaHTML);
echo $paginaHTML;

?>