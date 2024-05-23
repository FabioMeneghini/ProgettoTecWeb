<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

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
    $menupersonale = '<li><a class="menulibro" href="#recensionetua">Vai alla tua recensione e voto</a></li>';
}
else {
    $menu = $NonRegistrato;
    $menupersonale = '<li><a class="menulibro" href="#recensionetua">Vai alla tua recensione e voto</a></li>';
}

$messaggiSuccesso = "";
$messaggiErrore = "";
$messaggiForm = "";
$listaGeneri = "";
$listaKeyword = "";
$titolo = "";
$autore = "";
$genere = "";
$lingua = "";
$trama = "";
$n_capitoli = "";
$tua_recensione = "";
$voto="";
$media_voti = "";
$listaRecensioni = '<div class="recensioni"><ul>';
$arearecensionevoto="";
$copertina="";
$alt="";
$bottoni_admin="";

function controlla($recensione, $voto) {
    $messaggi = "";
    if(strlen($recensione) > 1000) {
        $messaggi .= "<li>La recensione può essere lunga al massimo 1000 caratteri</li>";
    }
    if(!is_numeric($voto) || !is_int($voto) || !preg_match("/^[0-9]+$/", $voto)) {
        $messaggi .= "<li>Il voto deve essere un numero intero</li>";
    }
    else
    if($voto < 1 || $voto > 10) {
        $messaggi .= "<li>Il voto deve essere compreso tra 1 e 10</li>";
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

if(isset($_GET['eliminato']) && $_GET['eliminato'] == 0) {
    $messaggiErrore = '<p class="errore">Ci scusiamo per il disagio ma non è stato possibile eliminare il libro.</p>';
}
if(isset($_GET['modificato']) && $_GET['modificato'] == 1) {
    $messaggiSuccesso = '<p class="successo">Libro modificato con successo!</p>';
}
if(isset($_GET['salvato']) && $_GET['salvato'] == 1) {
    $messaggiSuccesso = '<p class="successo">Libro salvato con successo!</p>';
}
if(isset($_GET['valutato']) && $_GET['valutato'] == 1) {
    $messaggiSuccesso = '<p class="successo">Valutazione aggiunta/modificata con successo!</p>';
}

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        //controllo se l'utente vuole salvare, iniziare o valutare un libro
        if(isset($_POST["salva"])) {
            $salvato = $connection -> aggiungiDaLeggere($_SESSION['username'], $_POST['id_libro']);
            //mi sposto con header(Location: ...) perché altrimenti da problemi con il libro che visualizza (mostra sempre il primo in quanto perde il parametro id nel get)
            header("Location: scheda_libro.php?salvato=1&id=".$_POST['id_libro']);
            exit();
        }
        if(isset($_POST["inizia"])) {
            $eliminato = $connection -> rimuoviDaLeggere($_SESSION['username'], $_POST['id_libro']);
            $iniziato = $connection -> aggiungiStaLeggendo($_SESSION['username'], $_POST['id_libro']);
            header("Location: stai_leggendo.php?iniziato=1");
            exit();
        }
        if(isset($_POST["valuta"])) {
            $tmp = controlla($_POST["recensione"], $_POST["voto"]);
            if($tmp["ok"]) {
                $salvato = $connection -> aggiungiValutazione($_SESSION['username'], $_POST['id_libro'], $_POST['voto'], $_POST['recensione']);
                //mi sposto con header(Location: ...) perché altrimenti da problemi con il libro che visualizza (mostra sempre il primo in quanto perde il parametro id nel get)
                header("Location: scheda_libro.php?valutato=1&id=".$_POST['id_libro']);
                exit();
            }
            else {
                $messaggiForm = $tmp["messaggi"];
            }
        }

        if(isset($_POST['id_libro']))
            $LibroSelezionato = $_POST['id_libro'];
        else if(isset($_GET['id']))
            $LibroSelezionato = $_GET['id'];
        else
            $LibroSelezionato = 1; //libro di default
        $ok = $connection -> controllareIdLibro($LibroSelezionato);
        if($ok) {
            $resultGeneri = $connection -> getListaGeneri();
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
                $arearecensionevoto='<div>
                                        <p>Per lasciare una recensione e un voto devi prima accedere al tuo <span lang="en">account</span><p>
                                        <a href="accedi.php">Accedi</a>
                                        <p>Non hai ancora un <span lang="en">account</span>?</p>
                                        <p>Entra a far parte della nostra <span lang="en">community</span>!</p>
                                        <a href="registrati.php">Registrati</a>
                                    <div>';
            }
            else if(isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
                $terminato = $connection -> is_terminato($LibroSelezionato,$_SESSION['username']);
                $salvato= $connection -> is_salvato($LibroSelezionato,$_SESSION['username']);
                $iniziato= $connection -> is_iniziato($LibroSelezionato,$_SESSION['username']);
                if($terminato) {  
                    $tua_recensione = $connection -> getrecensionetua($LibroSelezionato,$_SESSION['username']);
                    $voto = $connection -> getvototuo($LibroSelezionato,$_SESSION['username']);

                    if($tua_recensione=='') { //se la recensione è vuota
                        $tua_recensione='Scrivi qui una recensione';
                    }
                    $arearecensionevoto='
                        <form method="post" action="scheda_libro.php" onsubmit="return validaSchedaLibro()" onreset="conferma(\'Sei sicuro di voler annullare le modifiche alla tua recensione e al tuo voto?\')"> 
                            {messaggiForm}
                            <fieldset>
                                <legend>La tua Recensione e il tuo voto:</legend>
                                <label for="recensione">Recensione: </label><br>
                                <span><textarea id="recensione" name="recensione" rows="20" cols="70" maxlength="1000">'.$tua_recensione.'</textarea></span><br>
                                <label for="voto">Voto: </label>
                                <!--<span><input type="number" name="voto" id="voto" max="10" min="1" required placeholder="{voto}" value="{voto}"></span><br>-->
                                <span><input type="text" name="voto" id="voto"required placeholder="{voto}" value="{voto}"></span><br>
                                <input type="hidden" id="id_libro" name="id_libro" value="'.$LibroSelezionato.'">
                                <input type="submit" id="valuta" name="valuta" value="Pubblica valutazione">
                                <input type="reset" value="Annulla">
                            </fieldset>
                        </form>';
                        //Modifica e annulla attivi su js solo se ha modificato qualcosa
                }
                else if($salvato) {
                    $arearecensionevoto='
                        <form action="scheda_libro.php" method="post"> 
                        <fieldset>
                            <legend>Inizia a leggere:</legend>
                            <input type="hidden" id="id_libro" name="id_libro" value="'.$LibroSelezionato.'">
                            <label for="username">Questo libro è nella lista dei tuoi libri da leggere. &Egrave; il momento di iniziare a leggerlo?</label><br>
                            <input type="submit" id="inizia" name="inizia" value="inizia">
                        </fieldset>
                    </form>';
                }
                else if($iniziato) {
                    $arearecensionevoto='<p>Prima di poter recensire questo libro devi averlo terminato. Questo libro si trova nella lista di libri che stai leggendo, per vedere il tuo avanzamento vai al link: <a href="stai_leggendo.php">Libri che stai leggendo</a></p>';
                }
                else  {
                    $arearecensionevoto='
                        <form action="scheda_libro.php" method="post">
                            <p>Prima di poter recensire questo libro devi averlo terminato. Vuoi iniziare questo libro o salvarlo per iniziarlo più tardi?</p>
                            <fieldset>
                                <!-- <legend>Prima di poter recensire questo libro devi averlo terminato</legend> -->
                                <input type="hidden" id="id_libro" name="id_libro" value="'.$LibroSelezionato.'">
                                <!-- <label for="username">Salva per leggerlo più tardi:</label> -->
                                <input type="submit" id="salva" name="salva" value="Salva">
                                <!-- <label for="username">Inizia a leggere:</label> -->
                                <input type="submit" id="inizia" name="inizia" value="Inizia a leggere">
                            </fieldset>
                        </form>';
                }
            }
            else {
                $bottoni_admin='<section id="bottoni_admin">
                    <!-- <a href="modifica_libro.php?id='.$LibroSelezionato.'">Modifica</a> -->
                    <form action="modifica_libro.php" method="get">
                        <fieldset>
                            <input type="hidden" id="libroId" name="id" value='.$LibroSelezionato.'>
                            <input type="submit" id="modifica_libro" name="modifica_libro" value="Modifica">
                        </fieldset>
                    </form>
                    <form action="elimina_libro.php" method="get" onsubmit="return conferma(\'Sei sicuro/sicura di voler eliminare questo libro?\')">
                        <fieldset>
                            <input type="hidden" id="libroId" name="id" value='.$LibroSelezionato.'>
                            <input type="submit" id="elimina" name="elimina" value="Elimina">
                        </fieldset>
                    </form>';
            }
            $media_voti = $connection -> getmediavoti($LibroSelezionato);
            $altre_recensioni = $connection -> getaltrerecensioni($LibroSelezionato);
            $connection -> closeConnection();
            //torna un array che deve essere messo in una lista se sono vuote scritta non ci sono recensioni
            if(empty($altre_recensioni)) {
                $listaRecensioni.='<p>Non ci sono ancora recensioni per questo libro</p>';
            }
            else {
                $listaRecensioni.='<ul>';
                foreach($altre_recensioni as $recensione) {
                    $listaRecensioni.='<li><p class="commento">'.$recensione["commento"].'</p></li>';
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
$paginaHTML = str_replace("{voto}", $voto, $paginaHTML);
$paginaHTML = str_replace("{recensioniComunity}", $listaRecensioni, $paginaHTML);
$paginaHTML = str_replace("{messaggiForm}", $messaggiForm=="" ? "" : "<ul class=\"messaggiErrore\">".$messaggiForm."</ul>", $paginaHTML);
$paginaHTML = str_replace("{messaggiErrore}", $messaggiErrore ,$paginaHTML);
$paginaHTML = str_replace("{messaggiSuccesso}", $messaggiSuccesso ,$paginaHTML);
echo $paginaHTML;

?>