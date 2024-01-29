<?php

include "config.php";

require_once "DBAccess.php";
use DB\DBAccess;

/*if(isset($_SESSION['admin'])) {
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
    }
}
else {
    header("Location: index.php");
}*/

$paginaHTML = file_get_contents("template/templateHomeUtente.html");


$listaGeneri = "";
$listaLibri = '<hr class="linea"><div class=libri_genere >';
try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getGeneriPiuLetti($_SESSION['username']);
        foreach($resultGeneri as $genere) {
            $listaLibri.='<hr><h3><a href="genere.php?genere="'.$genere["genere"].'">'.$genere["genere"].'</a></h3>
            <ul class="listagenere">';

            $risultatiLibri = $connection ->getListaLibriGenere($genere["genere"],10);
            foreach($risultatiLibri as $libro) {
                //$listaLibri.='<li><a href="scehda_libro.php?id='.$libro["id"].'" id="'.$libro["titolo_IR"].'">'.$libro["titolo"].'</a></li>';
                $listaLibri.='<li><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></li>';
                //torna il titolo che deve fare img replace 
            }
            $listaLibri.='</ul>';
        }
        $listaLibri.="</div>";
        //$risultatiLibri = $connection ->getListaLibriGenere($genere);
        
        $resultGeneri = $connection -> getListaGeneri();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
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

$paginaHTML = str_replace("{LibriGenere}", $listaLibri, $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;

?>