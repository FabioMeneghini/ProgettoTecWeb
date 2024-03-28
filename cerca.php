<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templatecerca.html");
$menu ="";
$breadcrumbs = "";
//utenti
$userMenu ='<dt><a href="utente.php"><span lang="en">Home</span></a></dt>
    <dt><a href="stai_leggendo.php">Libri che stai leggendo</a></dt>
    <dt><a href="terminati.php">Libri terminati</a></dt>
    <dt><a href="da_leggere.php">Libri da leggere</a></dt>
    <dt><a href="recensione.php">Aggiungi Recensione</a></dt>
    <dt>Lista Generi:</dt>
    {listaGeneri}
    <dt><a href="statistiche.php">Statistiche</a></dt>
    <dt><a href="area_personale.php">Area Personale</a></dt>
    <dt>Cerca</dt>';

//admin
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="modifica_libro.php">Modifica Libro</a></dt>
    <dt>Categorie</dt>
    {listaGeneri}
    <dt><a href="area_personale.php">Area Personale</a></dt>
    <dt>Cerca</dt>';

$NonRegistrato='<dt><a href="index.php"><span lang="en">Home</span></a></dt>
                <dt>Categorie</dt>
                {listaGeneri}
                <dt><a href="accedi.php">Accedi</a></dt>
                <dt><a href="registrati.php">Registrati</a></dt>
                <dt>Cerca</dt>';


/*if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        $menu = $adminMenu;
        $breadcrumbs = "admin.php";
    } else {
        $breadcrumbs = "index.php";
        if(isset($_SESSION['username']))
            $menu =$userMenu;
        else
            $menu =$NonRegistrato;

    }
}*/
if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        $menu = $adminMenu;
        $breadcrumbs = "admin.php";
    }
}
else if(isset($_SESSION['username'])){
    $menu = $userMenu;
    $breadcrumbs = "index.php";
}
else {
    $menu = $NonRegistrato;
    $breadcrumbs = "index.php";
}

$listaGeneri = "";
$risultatoRicerca="";
$opzioneGeneri="";
$rislutati_ricerca="";
$messaggi_form="";
try {
    //get della ricerca $stringa, $autore, $genere, $lingua
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        $libri_ricercati = array();
        if(isset($_POST['cerca_generale'])) {
            $stringa = isset($_POST['stringa']) ? $_POST['stringa'] : "";
            $autore = isset($_POST['autore']) ? $_POST['autore'] : "";
            $genere = $_POST['genere'];
            $lingua = $_POST['lingua'];
            if($stringa=="" && $autore=="" && $genere=="" && $lingua=="")
                $messaggi_form = "<p>Inserisci almeno un parametro di ricerca</p>";
            else {
                if($stringa=="")
                    $stringa = "*****";
                if($autore=="")
                    $autore = "*****";
                if($genere=="")
                    $genere = "*****";
                if($lingua=="")
                    $lingua = "*****";
                $libri_ricercati = $connection -> cercaLibro($stringa, $autore, $genere, $lingua);
            }
        }
        $connection -> closeConnection();
        //dovrebbe essere una tabella !! 
        if(!empty($libri_ricercati) && $messaggi_form==""){
             $rislutati_ricerca.= '<p id="descr">
                                La tabella contiene l\'elenco dei libri che assomigliano alla tua ricerca.
                                Ogni riga descrive un libro con sette colonne nominate:"titolo","copertina", "autore", "genere", "numero capitoli".
                                È anche presente una quinta e una sesta colonna che contengono rispettivamente un bottone per salvare il libro nella lista dei libri da leggere e uno per iniziarne la lettura.
                            </p>
                            <table aria-describedby="descr">
                            <caption>Risultati della tua ricerca</caption>
                            <tr>
                                <th scope="col">Titolo</th>
                                <th scope="col">Copertina</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Genere</th>
                                <th scope="col">lingua </th>

                            </tr>';
                            //"copertine_libri/'..$libro["titolo_ir"].jpg"
            foreach($libri_ricercati as $libro) {
                $rislutati_ricerca .= '<tr>
                                    <td scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></td>
                                    <td><img src="copertine_libri/..$libro["titolo_ir"].jpg" alt="'.$libro["descrizione"].'"></td>
                                    <td>'.$libro["autore"].'</td>
                                    <td>'.$libro["genere"].'</td>
                                    <td>'.$libro["lingua"].'</td>
                                </tr>';
            }
            $rislutati_ricerca .= "</table>";
        }
        else {
            if($messaggi_form=="")
                $rislutati_ricerca= '<p>Ci scusiamo ma al momento non abbiamo libri che corrispondono alla tua ricerca</p>';
        }
        foreach($resultGeneri as $genere) {
            $opzioneGeneri .= '<option value="'.$genere["nome"].'">'.$genere["nome"].'</option>';
        }
        foreach($resultGeneri as $genere) {
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
       
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}
$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{breadcrumbs}", $breadcrumbs, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{opzioneGeneri}", $opzioneGeneri, $paginaHTML);
$paginaHTML = str_replace("{rislutati}", $rislutati_ricerca, $paginaHTML);
$paginaHTML = str_replace("{messaggiForm}", $messaggi_form, $paginaHTML);

echo $paginaHTML;

?>