<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templatecerca.html");
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

//admin
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="modifica_libro.php">Modifica Libro</a></dt>
    <dt>Categorie</dt>
    {listaGeneri}
    <dt>Area Personale</dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

$NonRegistrato='<dt><a href="index.php"><span lang="en">Home</span></a></dt>
                <dt>Categorie</dt>
                {listaGeneri}
                <dt><a href="accedi.php">Accedi</a></dt>
                <dt><a href="registrati.php">Registrati</a></dt>
                <dt><a href="cerca.php">Cerca</a></dt>';

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        $menu = $adminMenu;
    } else {
        if(isset($_SESSION['username']))
            $menu =$userMenu;
        else
            $menu =$NonRegistrato;

    }
}
$listaGeneri = "";
$risultatoRicerca="";
$opzioneGeneri="";
$opzioneLingue="";
$rislutati_ricerca="";
try {
    //get della ricerca $stringa, $autore, $genere, $lingua
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        $libri_ricercati = $connection -> cercaLibro( $stringa, $autore, $genere, $lingua);
        $lingue_opz= connection-> getLingueLibri();
        $generi_opz= connection-> getListaGeneri();

        $connection -> closeConnection();
        foreach( $lingue_opz as $lingue) {
            $opzioneLingue .= '<option value="'.$lingue["lingue"].'">';
        }
        //dovrebbe essere una tabella !! 
        if($libri_ricercati!=""){
             $rislutati_ricerca.= '<p id="descr">
                                La tabella contiene l\'elenco dei libri che assomigliano alla tua ricerca.
                                Ogni riga descrive un libro con sette colonne nominate:"titolo","copertina", "autore", "genere", "numero capitoli".
                                È anche presente una quinta e una sesta colonna che contengono rispettivamente un bottone per salvare il libro nella lista dei libri da leggere e uno per iniziarne la lettura.
                            </p>
                            <table aria-describedby="descr">
                            <caption>Risultati della tua ricerca</caption>
                            <th>
                                <copertinaaaaaaaaaaa
                                <th scope="col">Titolo</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Genere</th>
                                <th scope="col">lingua </th>
                                <th scope="col">Bottone salva</th>
                                <th scope="col">Bottone inizia</th>

                            </th>';
            foreach($libri_ricercati as $libro) {
                $rislutati_ricerca .= '<tr>
                                    <td scope="row"><a href="scheda_libro.php?id='.$libri["id"].'">'.$libri["id"].'</a></td>
                                    <td> <img src="copertine_libri/'.$libri["titolo_ir"].'.jpg" alt="'.$libri["descrizione"].'">
                                    <td>'.$libro["autore"].'</td>
                                    <td>'.$libro["genere"].'</td>
                                    <td>'.$libro["lingua"].'</td>
                                    <td><button type="input" id="salva" name="inizia" value="salva"></td>
                                    <td><button type="input" id="inizia" name="inizia" value="inizia"></td>
                                </tr>';
            }
            $rislutati_ricerca .= "</table>";
        }
       
        foreach($generi_opz as $genere) {
            $listaGeneri .= '<option value="'.$genere["genere"].'">';
        }
        foreach($resultGeneri as $genere) {
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></dd>';
        }
        foreach($libri_ricercati as $libri) {
            $$risultatoRicerca .= '<dd><a href="scheda_libro.php?id='.$libri["id"].'">'.$libri["id"].'</a></dd>';
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
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{opzioneGeneri}", $opzioneGeneri, $paginaHTML);
$paginaHTML = str_replace("{opzioneLingue}", $opzioneLingue, $paginaHTML);
$paginaHTML = str_replace("{rislutati}", $rislutati_ricerca, $paginaHTML);

echo $paginaHTML;

?>