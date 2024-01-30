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
}*/

$paginaHTML = file_get_contents("template/templateTuttiLibri.html");

$listaGeneri = "";
$catalogo="";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        $resultGeneri = $connection -> getListaGeneri();
        $risultUtenti= $connection->getTuttiLibri();
        $connection -> closeConnection();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
        }
        if(!empty($risultUtenti)){
            $catalogo.= '<p id="descr">
                               La tabella contiene l\'elenco dei libri che assomigliano alla tua ricerca.
                               Ogni riga descrive un libro con sette colonne nominate:"titolo","copertina", "autore", "genere", "numero capitoli".
                               È anche presente una quinta e una sesta colonna che contengono rispettivamente un bottone per salvare il libro nella lista dei libri da leggere e uno per iniziarne la lettura.
                           </p>
                           <table aria-describedby="descr">
                           <caption>Risultati della tua ricerca</caption>
                           <th>
                               <th scope="col">Titolo</th>
                               <th scope="col">Copertina</th>
                               <th scope="col">Autore</th>
                               <th scope="col">lingua </th>
                           </th>';
                           //"copertine_libri/'..$libro["titolo_ir"].jpg"
           foreach($risultUtenti as $libro) {
            $catalogo .= '<tr>
                                   <td scope="row"><a href="scheda_libro.php?id='.$libro["id"].'">'.$libro["titolo"].'</a></td>
                                   <td><img src="copertine_libri/_1984.jpg" alt="'.$libro["descrizione"].'"></td>
                                   <td>'.$libro["autore"].'</td>
                                   <td>'.$libro["lingua"].'</td>
                               </tr>';
           }
           $catalogo .= "</table>";
       }
       else{
           $catalogo= '<p>Il catalogo è vuoto al momento</p>';
       }
    }
    else {
        echo "Connessione fallita";
    }
}
catch(Throwable $e) {
    echo "Errore: ".$e -> getMessage();
}

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{CatalogoLibri}", $catalogo, $paginaHTML);
echo $paginaHTML;

?>