<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

/*if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] != 1) {
        header("Location: utente.php");
    }
}
else {
    header("Location: index.php");
}<dl class="Statistiche">
                    <dt>Numero capitoli letti Oggi:</dt>
                    <dd>{NumeroCapitoliLettiUtente}</dd>
                    <dt>Numero Recensioni totali:</dt>
                    <dd>{NumeroRecensioniUtente}</dd>
                    <dt>Libri Letti quest'anno:</dt>
                    <dd>{LibriLettiAnnoUtente}</dd>
                    <!--si può mettere link alle recensioni? -->
                    <!--si possono mettere delle canvas per fare i grafici-->
                </dl>*/

$paginaHTML = file_get_contents("template/templateStatisticheUtente.html");
$listaGeneri = "";

try {
    $connection = new DBAccess();
    $utente = $_GET['utente'];//prende l'utente
    $connectionOk = $connection -> openDBConnection();
    
    if($connectionOk) {
        $n_capitoli= $connection -> getCapitoliUtenteOggi($utente);
        $n_recensioni= $connection -> getRecensioniUtenteOggi($utente);
        $n_librianno= $connection ->  getLibriUtenteAnno($utente);
        $n_librimese= $connection -> getLibriUtenteMese($utente);
        $resultListaGeneri = $connection -> getListaGeneri();

        foreach($resultListaGeneri as $genere) {
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></dd>';
        }
        $connection -> closeConnection();
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}
$paginaHTML = str_replace("{listaLibri}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{NumeroCapitoliLettiOggi}", $n_registrati, $paginaHTML);
$paginaHTML = str_replace("{NumeroRecensioni}", $n_recensioni, $paginaHTML);
$paginaHTML = str_replace("{LibriLettiAnno}", $n_utenti, $paginaHTML);
$paginaHTML = str_replace("{libriLettiMese}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>