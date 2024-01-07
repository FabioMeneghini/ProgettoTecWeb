<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateAccedi.html");

function controllaInput($username, $password) { //da inserire eventualmente altri controlli su username e password
    $messaggi = "";
    if($username == "") {
        $messaggi .= "<li>Lo username non può essere vuoto</li>";
    }
    if($password == "") {
        $messaggi .= "<li>La password non può essere vuota</li>";
    }
    if(strlen($username) <= 2) {
        $messaggi .= "<li>Lo username non può essere più corto di 3 caratteri</li>";
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

$messaggiPerForm = "";

$ok = true;
if(isset($_POST['accedi'])) {
    $username = trim($_POST['username']);
    //$password = md5($_POST['password']); //calcola l'hash md5 della password
    $password = $_POST['password'];

    $tmp = controllaInput($username, $password);
    $ok = $tmp['ok'];
    $messaggiPerForm .= $tmp['messaggi'];

    if($ok) { //si connette al database solamente se i dati inseriti sono validi
        try {
            $connection = new DBAccess();
            $connectionOk = $connection -> openDBConnection();

            if($connectionOk) {
                $user = $connection -> login($username, $password);
                $connection -> closeConnection();
            } else {
                $messaggiPerForm .= "<li>Errore di connessione al database</li>";
            }
            
            if($user!=null) {
                $_SESSION['username'] = $user['username']; //salva lo username in una variabile di sessione
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['cognome'] = $user['cognome'];
                $_SESSION['email'] = $user['email'];
                if($user['admin']==1) {
                    $_SESSION['admin'] = true;
                    header("Location: admin.php");
                }
                else {
                    $_SESSION['admin'] = false;
                    header("Location: utente.php");
                }
            } else {
                $messaggiPerForm .= "<li>Credenziali errate. Riprova.</li>";
            }
        }
        catch(Throwable $e) {
            $messaggiPerForm .= "<li>Errore: ".$e -> getMessage()."</li>";
        }
    }
}

$paginaHTML = str_replace("{messaggi}", $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>
