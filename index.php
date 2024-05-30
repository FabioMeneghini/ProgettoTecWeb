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

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultListaBestSeller = $connection -> getListaBestSeller();
        $resultListaGeneri = $connection -> getListaGeneri();
        $resultGeneri = $connection -> getGeneriPiuPopolari();
        

        foreach($resultGeneri as $genere) {
            $listaLibri.='<h3><a class="genere_singolo" href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></h3>';
            $risultatiLibri = $connection ->getListaLibriGenere($genere["genere"],10);
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
        }
        $listaLibri.="</div>";  
        //$risultatiLibri = $connection ->getListaLibriGenere($genere);
        $connection -> closeConnection();
        foreach($resultListaBestSeller as $libro) {
           // $listaBestSeller .= "<li>".$libro["titolo"]."</li>";  
           //$libro["autore"], $libro["genere"] lo si visualizza solo al momento del passaggio del mouse sopra al libro
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
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{listaBestSeller}", $listaBestSeller, $paginaHTML);
$paginaHTML = str_replace("{LibriGenere}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>