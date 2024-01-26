<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

/*if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
    } else {
        header("Location: utente.php");
    }
}*/

$paginaHTML = file_get_contents("template/templateHomeNonRegistrato.html");

$listaBestSeller = "";
$listaGeneri = "";
$listaLibri = "<div class=ok >";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultListaBestSeller = $connection -> getListaBestSeller();
        $resultListaGeneri = $connection -> getListaGeneri();
        $resultGeneri = $connection -> getGeneriPiuPopolari();
        $connection -> closeConnection();

        foreach($resultGeneri as $genere) {
            $listaLibri.='
            <ul class="lista genere">';
            $risultatiLibri = $connection ->getListaLibriGenere($genere["genere"], 10);
            foreach($risultatiLibri as $libro) {
                $listaLibri.="<li></li>";
            }
            $listaLibri.='</ul>';
        }
        $listaLibri.="</div>"
        //$risultatiLibri = $connection ->getListaLibriGenere($genere);
        
        foreach($resultListaBestSeller as $libro) {
            $titolo=$libro["titolo"];
            $titolo=strtolower($titolo);
            $titolo=str_replace(' ', '_',$titolo);
            $titolo=str_replace('\'', '',$titolo);
            if (ctype_digit($titolo)) {
                $titolo = '_'.$titolo;
            }
           // $listaBestSeller .= "<li>".$libro["titolo"]."</li>";  
           //$libro["autore"], $libro["genere"] lo si visualizza solo al momento del passaggio del mouse sopra al libro
           $listaBestSeller .='<li>
                                <figure>
                                    <img src="copertine_libri/'.$titolo.'.jpg">
                                    <div class="dati">
                                    <div>'.$libro["autore"].' - '.$libro["titolo"].'</div>
                                    <div>'.$libro["trama"].'</div>
                                    </div>
                                </figure>
                                </li>';
        }
        
        foreach($resultListaGeneri as $genere) {
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

$paginaHTML = str_replace("{listaBestSeller}", $listaBestSeller, $paginaHTML);
$paginaHTML = str_replace("{libriGenere}", $risultatiLibri, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>