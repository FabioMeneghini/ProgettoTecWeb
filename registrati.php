<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("template/templateRegistrati.html");

function controllaInput($nome, $cognome, $username, $email, $password1, $password2) { //da inserire eventualmente altri controlli su username e password
    $messaggi = "";
    if($nome == "") {
        $messaggi .= "<li>Il nome non può essere vuoto</li>";
    }
    if($cognome == "") {
        $messaggi .= "<li>Il cognome non può essere vuoto</li>";
    }
    if($username == "") {
        $messaggi .= "<li>Lo username non può essere vuoto</li>";
    }
    if($email == "") {
        $messaggi .= "<li>L'email non può essere vuota</li>";
    }
    if($password1 == "") {
        $messaggi .= "<li>La password non può essere vuota</li>";
    }
    if($password1 != $password2) {
        $messaggi .= "<li>Le due password non coincidono</li>";
    }
    if(strlen($username) <= 2) {
        $messaggi .= "<li>Lo username non può essere più corto di 3 caratteri</li>";
    }
    if(strlen($password1) <= 7) {
        $messaggi .= "<li>La password non può essere più corta di 8 caratteri</li>";
    }
    //aggiungere controllo regex per email
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

$messaggiPerForm = "";

$ok = true;
if(isset($_POST['registrati'])) {
    $nome = trim($_POST['firstName']);
    $cognome = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $tmp = controllaInput($nome, $cognome, $username, $email, $password1, $password2);
    $ok = $tmp['ok'];
    $messaggiPerForm .= $tmp['messaggi'];

    if($ok) { //si connette al database solamente se i dati inseriti sono validi
        try {
            $connection = new DBAccess();
            $connectionOk = $connection -> openDBConnection();

            if($connectionOk) {
                if($connection -> usernameUnico($username)) {
                    $erroriRegistrazione = $connection -> registraUtente($nome, $cognome, $username, $email, $password1);
                    $connection -> closeConnection();

                    if($erroriRegistrazione == "") {
                        //header("Location: accedi.php");
                        $messaggiPerForm .= "<li>Registrazione avvenuta con successo</li>";
                    } else {
                        $messaggiPerForm .= "<li>".$erroriRegistrazione."</li>";
                    }
                } else {
                    $messaggiPerForm .= "<li>Lo username inserito è già presente nel database</li>";
                }
            } else {
                $messaggiPerForm .= "<li>Errore di connessione al database</li>";
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
