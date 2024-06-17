<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
    } else {
        header("Location: utente.php");
    }
}

$paginaHTML = file_get_contents("template/templateHomeNonRegistrato.html");

$listaBestSeller = "";
$listaGeneri = "";
$listaLibri = '<div class="libri_genere">';
$torna_su="";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $resultListaBestSeller = $connection -> getListaBestSeller();
    $resultListaGeneri = $connection -> getListaGeneri();
    $resultGeneri = $connection -> getGeneriPiuPopolari();
    
    foreach($resultGeneri as $genere) {
        $listaLibri.='<div class="genere_singolo"><h3><a  href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></h3></div>';
        $risultatiLibri = $connection ->getListaLibriGenere($genere["genere"], 10);
        if(empty($risultatiLibri)) {
            $listaLibri.='<p>Ci scusiamo, al momento non abbiamo libri di questo genere</p>';
        }
        else {
            $listaLibri.='<ul class="librigeneri">';
            foreach($risultatiLibri as $libro) {
                $listaLibri.='<li><a id="'.$libro["titolo_ir"].'" href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></li>';
            }
            $listaLibri.='</ul>';
        }
        if($resultGeneri>=15) {
            $torna_su=' <nav aria-label="Torna all\' inizio della pagina">
                             <a class="torna_su" href="#content">Torna su</a>
                        </nav>';
         }    
    }
    $listaLibri.="</div>";  
    //$risultatiLibri = $connection ->getListaLibriGenere($genere);
    $connection -> closeConnection();
    foreach($resultListaBestSeller as $libro) {
        $listaBestSeller .=  '<div class="item">
                                <a href="scheda_libro.php?id='.$libro["id"].'"><img src="copertine_libri/'.$libro["titolo_ir"].'.jpg" alt="'.$libro["descrizione"].'" ></a>
                                <ul>
                                    <li><strong>Titolo:</strong> '.$libro["autore"].'</li>
                                    <li><strong>Autore:</strong> '.$libro["titolo"].'</li>
                                    <li><strong>Genere:</strong> '.$libro["genere"].'</li>
                                    <li class="commento"><strong>Commento:</strong> '.$libro["migliore_recensione"].'</li>
                                    <li><strong>Voto medio:</strong></li><li><span class="voto_medio">'.$libro["voto_medio"].'</span></li>
                                    </ul>
                                </div>';
    }
    foreach($resultListaGeneri as $genere){
        $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
    }
}
else {
    echo "Connessione fallita";
}

$paginaHTML = str_replace("{listaBestSeller}", $listaBestSeller, $paginaHTML);
$paginaHTML = str_replace("{LibriGenere}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{torna_su}", $torna_su, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>