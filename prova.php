<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

/*if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}  
*/
$isAdmin = true; // Sostituisci con la tua logica di verifica

//utenti
$userMenu = array(
    "<dt><a href='utente.php'><span lang='en'>Home</span></a></dt>",
    "<dt><a href='stai_leggendo.php'>Libri che stai leggendo</a></dt>",
    "<dt><a href='terminati.php'>Libri terminati</a></dt>",
    "<dt><a href='da_leggere.php'>Libri da leggere</a></dt>",
    "<dt><a href='recensione.php'>Aggiungi Recensione</a></dt>",
    "<dt>Lista Generi:</dt>",
    "{listaGeneri}",  //richiama php??
    "<dt><a href='statistiche.php'>Statistiche</a></dt>",
    "<dt>Area Personale</dt>",
    "<dt><a href='cerca.php'>Cerca</a></dt>",
);

//admin
$adminMenu = array(
    "<dt><a href='admin.php'><span lang='en'>Home</span></a></dt>",
    "<dt><a href='aggiungi_libro.php'>Aggiungi un libro</a></dt>",
    "<dt><a href='tutti_libri.php'>Catalogo libri</a></dt>",
    "<dt><a href='tutti_utenti.php'>Archivio utenti</a></dt>",
    "<dt><a href='modifica_libro.php'>Modifica Libro</a></dt>",
    "<dt>Categorie</dt>",
    "{listaGeneri}",
    "<dt>Area Personale</dt>",
    "<dt><a href='tcerca.php'>Cerca</a></dt>",
);

$menu = $isAdmin ? $adminMenu : $userMenu;



$paginaHTML = file_get_contents("template/templateAreaPersonale.html");

$listaGeneri = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        $connection -> closeConnection();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></dd>';
        }
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}
//prima il menu poi il resto 
echo "<nav id='menu'><dl>" . implode("", $menu) . "</dl></nav>";


$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;
// Stampa il menu


?>