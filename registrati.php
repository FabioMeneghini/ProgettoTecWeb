<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(isset($_SESSION['admin'])) { //se l'utente è già loggato, viene reindirizzato alla sua homepage
    if($_SESSION['admin'] == 1) {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: utente.php");
        exit();
    }
}

$paginaHTML = file_get_contents("template/templateRegistrati.html");

function controllaInput($nome, $cognome, $username, $email, $password1, $password2, $data) {
    $messaggi = "";
    if($nome == "") {
        $messaggi .= "<li>Il nome non può essere vuoto</li>";
    }
    else if(strlen($nome) > 25) {
        $messaggi .= "<li>Il nome non può essere più lungo di 25 caratteri</li>";
    }
    if($cognome == "") {
        $messaggi .= "<li>Il cognome non può essere vuoto</li>";
    }
    else if(strlen($cognome) > 25) {
        $messaggi .= "<li>Il cognome non può essere più lungo di 25 caratteri</li>";
    }
    if($username == "") {
        $messaggi .= "<li>Lo <span lang=\"en\">username</span> non può essere vuoto</li>";
    }
    else if(strlen($username) <= 2) {
        $messaggi .= "<li>Lo <span lang=\"en\">username</span> non può essere più corto di 3 caratteri</li>";
    }
    else if(strlen($username) > 25) {
        $messaggi .= "<li>Lo <span lang=\"en\">username</span> non può essere più lungo di 25 caratteri</li>";
    }
    if($email == "") {
        $messaggi .= "<li>L'<span lang=\"en\">email</span> non può essere vuota</li>";
    }
    else if(strlen($email) > 60) {
        $messaggi .= "<li>L'<span lang=\"en\">email</span> non può essere più lunga di 50 caratteri</li>";
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messaggi .= "<li>L'<span lang=\"en\">email</span> inserita non è valida</li>";
    }
    if($password1 == "") {
        $messaggi .= "<li>La <span lang=\"en\">password</span> non può essere vuota</li>";
    }
    else if(strlen($password1) < 8) {
        $messaggi .= "<li>La <span lang=\"en\">password</span> non può essere più corta di 8 caratteri</li>";
    }
    else if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password1)) {
        $messaggi .= "<li>La <span lang=\"en\">password</span> deve contenere almeno un numero, una lettera minuscola e una lettera maiuscola</li>";
    }
    else if($password1 != $password2) {
        $messaggi .= "<li>Le due <span lang=\"en\">password</span> non coincidono</li>";
    }
    if($data == "") {
        $messaggi .= "<li>La data di nascita non può essere vuota</li>";
    }
    else if($data > date('Y-m-d')) {
        $messaggi .= "<li>La data di nascita non può essere futura</li>";
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

function pulisciInput($input) {
    $input = trim($input);
    $input = strip_tags($input);
    $input = htmlentities($input);
    return $input;
}

$messaggiPerForm = "";
$listaGeneri = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();

if($connectionOk) {
    $resultListaGeneri = $connection -> getListaGeneri();
    foreach($resultListaGeneri as $genere) {
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }

    $ok = true;
    if(isset($_POST['registrati'])) {
        $nome = pulisciInput($_POST['name']);
        $cognome = pulisciInput($_POST['cognome']);
        $data = date('Y-m-d', strtotime($_POST['data']));
        $username = pulisciInput($_POST['username']);
        $email = pulisciInput($_POST['email']);
        $password1 = pulisciInput($_POST['password1']);
        $password2 = pulisciInput($_POST['password2']);

        $tmp = controllaInput($nome, $cognome, $username, $email, $password1, $password2, $data);
        $ok = $tmp['ok'];
        $messaggiPerForm .= $tmp['messaggi'];

        if($ok) {
            if($connection -> usernameUnico($username)) {
                $erroriRegistrazione = $connection -> registraUtente($nome, $cognome, $username, $email, $password1, $data);
                $connection -> closeConnection();
                if($erroriRegistrazione == "") {
                    $_SESSION['username'] = $username;
                    $_SESSION['nome'] = $nome;
                    $_SESSION['cognome'] = $cognome;
                    $_SESSION['email'] = $email;
                    $_SESSION['admin'] = false;
                    $_SESSION['data'] = $data;
                    header("Location: utente.php?registrato=1");
                    exit();
                } else {
                    $messaggiPerForm .= "<li>".$erroriRegistrazione."</li>";
                }
            } else {
                $messaggiPerForm .= '<li>Lo <span lang="en">username</span> inserito è già utilizzato da un altro utente</li>';
            }
        }
    }
}
else {
    header("Location: 500.php");
    exit();
}
$paginaHTML = str_replace("{messaggi}", $messaggiPerForm=="" ? "" : "<ul class=\"messaggiErrore\">".$messaggiPerForm."</ul>", $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);

echo $paginaHTML;
?>
