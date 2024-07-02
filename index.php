<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: utente.php");
        exit();
    }
}

$paginaHTML = file_get_contents("template/templateHomeNonRegistrato.html");

$listaBestSeller = "";
$listaGeneri = "";
$listaLibri = "";
$torna_su="";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $resultListaBestSeller = $connection -> getListaBestSeller();
    $resultListaGeneri = $connection -> getListaGeneri();
    $resultGeneri = $connection -> getGeneriPiuPopolari();
    
    foreach($resultGeneri as $genere) {
        $listaLibri.='<section class="genere_singolo"><h3><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></h3>';
        $risultatiLibri = $connection ->getListaLibriGenere($genere["genere"], 10);
        if(empty($risultatiLibri)) {
            $listaLibri.='<p>Ci scusiamo, al momento non abbiamo libri di questo genere</p>';
        }
        else {
            $listaLibri.='<ul class="librigeneri">';
            foreach($risultatiLibri as $libro) {
                $listaLibri.='<li><a id="'.$libro["titolo_ir"].'" href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></li>';
            }
            $listaLibri.='</ul></section>';
        }
        if($resultGeneri>=15) {
            $torna_su=' <nav aria-label="Torna all\' inizio della pagina">
                             <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
        }    
    }
    $connection -> closeConnection();
    foreach($resultListaBestSeller as $libro) {
        $listaBestSeller .= '<li class="item">
                                <a href="scheda_libro.php?id='.$libro["id"].'"><img src="copertine_libri/'.$libro["titolo_ir"].'.jpg" alt="Copertina del libro '.$libro["titolo"].'"></a>
                                <ul>
                                    <li><strong class="rimuovi_print">Titolo:</strong> '.$libro["titolo"].'</li>
                                    <li class="rimuovi_print"><strong>Autore:</strong> '.$libro["autore"].'</li>
                                    <li class="rimuovi_print"><strong>Genere:</strong> '.$libro["genere"].'</li>
                                    <li class="rimuovi_print commento"><strong>Commento:</strong> '.$libro["migliore_recensione"].'</li>
                                    <li class="rimuovi_print"><strong>Voto medio:</strong></li><li class="rimuovi_print"><span class="voto_medio">'.$libro["voto_medio"].'</span></li>
                                </ul>
                            </li>';
    }
    foreach($resultListaGeneri as $genere){
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }
}
else {
    header("Location: 500.php");
    exit();
}

$paginaHTML = str_replace("{listaBestSeller}", $listaBestSeller, $paginaHTML);
$paginaHTML = str_replace("{LibriGenere}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>