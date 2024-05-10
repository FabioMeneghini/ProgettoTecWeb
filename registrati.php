<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

/*if(isset($_SESSION['admin'])) { //se l'utente è già loggato, viene reindirizzato alla sua homepage
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
    } else {
        header("Location: utente.php");
    }
}*/

$paginaHTML = file_get_contents("template/templateRegistrati.html");

function controllaInput($nome, $cognome, $username, $email, $password1, $password2, $data) { //da inserire eventualmente altri controlli su username e password
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
    if($data == "") {
        $messaggi .= "<li>La data di nascita non può essere vuota</li>";
    }
    if($data > date('Y-m-d')) {
        $messaggi .= "<li>La data di nascita non può essere futura</li>";
    }
    //aggiungere controllo regex per email
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

$messaggiPerForm = "";
$listaGeneri = "";

$ok = true;
if(isset($_POST['registrati'])) {
    $nome = trim($_POST['name']);
    $cognome = trim($_POST['cognome']);
    $data = date('Y-m-d', strtotime($_POST['data']));
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $tmp = controllaInput($nome, $cognome, $username, $email, $password1, $password2, $data);
    $ok = $tmp['ok'];
    $messaggiPerForm .= $tmp['messaggi'];

    if($ok) { //si connette al database solamente se i dati inseriti sono validi
        try {
            $connection = new DBAccess();
            $connectionOk = $connection -> openDBConnection();

            if($connectionOk) {
                $resultListaGeneri = $connection -> getListaGeneri();
                foreach($resultListaGeneri as $genere) {
                    $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
                }
                if($connection -> usernameUnico($username)) {
                    $erroriRegistrazione = $connection -> registraUtente($nome, $cognome, $username, $email, $password1, $data);
                    $connection -> closeConnection();
                    if($erroriRegistrazione == "") {
                        //$messaggiPerForm .= "<li>Registrazione avvenuta con successo</li>";
                        $_SESSION['username'] = $username; //salva lo username in una variabile di sessione
                        $_SESSION['nome'] = $nome;
                        $_SESSION['cognome'] = $cognome;
                        $_SESSION['email'] = $email;
                        $_SESSION['admin'] = false;
                        $_SESSION['data'] = $data;
                        header("Location: index.php"); //dovrebbe mostrare un messaggio di successo dopo aver mandato alla pagina di login
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
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);

echo $paginaHTML;
?>
