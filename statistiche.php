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
        $n_recensioni= $connection -> getRecensioniUtente($utente);
        $n_libri= $connection ->  getLibriUtente($utente);
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
$paginaHTML = str_replace("{listaLibri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{NumeroRecensioni}", $n_recensioni, $paginaHTML);
$paginaHTML = str_replace("{LibriLetti}", $n_libri, $paginaHTML);
echo $paginaHTML;

?>