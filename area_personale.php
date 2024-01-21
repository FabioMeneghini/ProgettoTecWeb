<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

/*if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}*/

function controllaUsername($username) { //da inserire eventualmente altri controlli su username e password
    $messaggi = "";
    if($username == "") {
        $messaggi .= "<li>Lo username non può essere vuoto</li>";
    }
    if(strlen($username) <= 2) {
        $messaggi .= "<li>Lo username non può essere più corto di 3 caratteri</li>";
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

$paginaHTML = file_get_contents("template/templateAreaPersonale.html");

$messaggi = "";
$listaGeneri = "";
$messaggiUsername = "";
$messaggiEmail = "";
$messaggiPassword = "";

try {
    $connection = new DBAccess();
    $connectionOk = $connection -> openDBConnection();
    if($connectionOk) {
        if(isset($_POST['cambia_username'])) { //se è stato premuto il pulsante per cambiare lo username
            $username = trim($_POST['username']);
            $tmp = controllaUsername($username);
            if($tmp['ok']) {
                $connection -> modificaUsername($_SESSION['username'], $username);
                $_SESSION['username'] = $username;
            }
            else {
                $messaggiUsername .= $tmp['messaggi'];
            }
        }

        $resultGeneri = $connection -> getListaGeneri();
        $connection -> closeConnection();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= "<dd>".$genere["genere"]."</dd>";
        }
    }
    else {
        $messaggi.="<li>Connessione fallita</li>";
    }
}
catch(Throwable $e) {
    $messaggi.="<li>Errore: ".$e -> getMessage()."</li>";
}

$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{nome}", $_SESSION["nome"], $paginaHTML);
$paginaHTML = str_replace("{usernameattuale}", $_SESSION["username"], $paginaHTML);
$paginaHTML = str_replace("{emailattuale}", $_SESSION["email"], $paginaHTML);
$paginaHTML = str_replace("{messaggi}", $messaggi, $paginaHTML);
$paginaHTML = str_replace("{messaggiUsername}", $messaggiUsername, $paginaHTML);
$paginaHTML = str_replace("{messaggiEmail}", $messaggiEmail, $paginaHTML);
$paginaHTML = str_replace("{messaggiPassword}", $messaggiPassword, $paginaHTML);

echo $paginaHTML;

?>