<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

/*if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}*/

function controllaUsername($username) { //da inserire eventualmente altri controlli su username e password
    $messaggi = "";
    if($username == $_SESSION["username"]) {
        $messaggi .= "<li>Lo username è uguale a quello precedente</li>";
    }
    if($username == "") {
        $messaggi .= "<li>Lo username non può essere vuoto</li>";
    }
    if(strlen($username) < 3) {
        $messaggi .= "<li>Lo username non può essere più corto di 3 caratteri</li>";
    }
    else if(strlen($username) > 10) {
        $messaggi .= "<li>Lo username non può essere più lungo di 10 caratteri</li>";
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

function controllaPassword($password_old, $password1, $password2) { //da inserire eventualmente altri controlli su username e password
    $messaggi = "";
    if($password_old == $password1) {
        $messaggi .= "<li>La nuova password non può essere uguale a quella vecchia</li>";
    }
    else if($password1 == "") {
        $messaggi .= "<li>La nuova password non può essere vuota</li>";
    }
    else if(strlen($password1) < 8) {
        $messaggi .= "<li>La password deve contenere almeno 8 caratteri</li>";
    }
    else {
        $regexOk=preg_match('/[0-9]/', $password1) && preg_match('/[A-Z]/', $password1) && preg_match('/[a-z]/', $password1);
        if(!$regexOk) {
            $messaggi .= "<li>La password deve contenere almeno un numero, una lettera maiuscola e una minuscola</li>";
        }
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
        //username
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

        //password
        if(isset($_POST['cambia_password'])) { //se è stato premuto il pulsante per cambiare la password
            $password_old = trim($_POST['passwordold']);
            $password_new1 = trim($_POST['passwordnew1']);
            $password_new2 = trim($_POST['passwordnew2']);

            if($connection -> verificaPassword($_SESSION['username'], $password_old)) { //se la password vecchia è corretta
                $tmp = controllaPassword($password_old, $password_new1, $password_new2);
                if($tmp['ok']) {
                    $connection -> modificaPassword($_SESSION['username'], $password);
                }
                else {
                    $messaggiPassword .= $tmp['messaggi'];
                }
            }
            else {
                $messaggiPassword .= "<li>La password vecchia non è corretta</li>";
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