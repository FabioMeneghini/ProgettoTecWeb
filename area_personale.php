<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
    exit();
}

$isAdmin = true; 
if($_SESSION['admin'] != 1) 
    $isAdmin = false;
//utenti
$userMenu ='<dt><a href="utente.php"><span lang="en">Home</span></a></dt>
    <dt><a href="stai_leggendo.php">Libri che stai leggendo</a></dt>
    <dt><a href="terminati.php">Libri terminati</a></dt>
    <dt><a href="da_leggere.php">Libri da leggere</a></dt>
    <dt><a href="generi.php">Generi:</a></dt>
    {listaGeneri}
    <dt><a href="statistiche.php">Statistiche</a></dt>
    <dt>Area Personale</dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

//admin
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="generi.php">Generi:</a></dt>
    {listaGeneri}
    <dt>Area Personale</dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

$menu = $isAdmin ? $adminMenu : $userMenu;

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

function controllaPassword($password_old, $password1, $password2) {
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
            $messaggi .= "<li>La nuova password deve contenere almeno un numero, una lettera maiuscola e una minuscola</li>";
        }
        else if($password1 != $password2) {
            $messaggi .= "<li>Le due password non coincidono</li>";
        }
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

function controllaEmail($email) {
    $messaggi = "";
    if($email == "") {
        $messaggi .= "<li>L'email non può essere vuota</li>";
    }
    else if($email == $_SESSION["email"]) {
        $messaggi .= "<li>L'email è uguale a quella precedente</li>";
    }
    else {
        $regexOk = preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
        if(!$regexOk) {
            $messaggi .= "<li>L'email non è valida</li>";
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
$successoUsername = "";
$successoEmail = "";
$successoPassword = "";
$data = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    $data = $connection -> getDataIscrione($_SESSION['username']);
    //username
    if(isset($_POST['cambia_username'])) { //se è stato premuto il pulsante per cambiare lo username
        $username = trim($_POST['username']);
        $tmp = controllaUsername($username);
        if($tmp['ok']) {
            if($connection -> verificaUsername($username)) {
                $messaggiUsername .= "<li>Lo username '".$username."' è già in uso</li>";
            }
            else {
                $connection -> modificaUsername($_SESSION['username'], $username);
                $_SESSION['username'] = $username;
                $successoUsername = '<span lang="en">Username</span> modificato con successo';
            }
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
                $connection -> modificaPassword($_SESSION['username'], $password_new1);
                $successoPassword = '<span lang="en">Password</span> modificata con successo';
            }
            else {
                $messaggiPassword .= $tmp['messaggi'];
            }
        }
        else {
            $messaggiPassword .= "<li>La password vecchia non è corretta</li>";
        }
    }

    //mail
    if(isset($_POST['cambia_email'])) {
        $email = trim($_POST['email']);
        $tmp = controllaEmail($email);
        if($tmp['ok']) {
            $connection -> modificaEmail($_SESSION['username'], $email);
            $_SESSION['email'] = $email;
            $successoEmail = '<span lang="en">Email</span> modificata con successo';
        }
        else {
            $messaggiEmail .= $tmp['messaggi'];
        }
    }

    //disconnetti
    if(isset($_POST['disconnetti'])) {
        session_destroy();
        header("Location: index.php");
        exit();
    }

    //elimina
    if(isset($_POST['elimina'])) {
        $connection -> eliminaUtente($_SESSION['username']);
        session_destroy();
        header("Location: index.php");
        exit();
    }

    $resultGeneri = $connection -> getListaGeneri();
    $connection -> closeConnection();
    foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></dd>';
    }
}
else {
    $messaggi.="<li>Errore di connessione al database</li>";
}

$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{nome}", $_SESSION["nome"], $paginaHTML);
$paginaHTML = str_replace("{usernameattuale}", $_SESSION["username"], $paginaHTML);
$paginaHTML = str_replace("{emailattuale}", $_SESSION["email"], $paginaHTML);
$paginaHTML = str_replace("{data_iscrizione}", $data, $paginaHTML);
$paginaHTML = str_replace("{messaggi}", $messaggi=="" ? "" : "<ul class=\"messaggiErrore\">".$messaggi."</ul>", $paginaHTML);
$paginaHTML = str_replace("{messaggiUsername}", $messaggiUsername=="" ? "" : "<ul class=\"messaggiErrore\">".$messaggiUsername."</ul>", $paginaHTML);
$paginaHTML = str_replace("{messaggiEmail}", $messaggiEmail=="" ? "" : "<ul class=\"messaggiErrore\">".$messaggiEmail."</ul>", $paginaHTML);
$paginaHTML = str_replace("{messaggiPassword}", $messaggiPassword=="" ? "" : "<ul class=\"messaggiErrore\">".$messaggiPassword."</ul>", $paginaHTML);
$paginaHTML = str_replace("{successoUsername}", $successoUsername=="" ? "" : "<div class=\"messaggiSuccesso\">".$successoUsername."</div>", $paginaHTML);
$paginaHTML = str_replace("{successoEmail}", $successoEmail=="" ? "" : "<div class=\"messaggiSuccesso\">".$successoEmail."</div>", $paginaHTML);
$paginaHTML = str_replace("{successoPassword}", $successoPassword=="" ? "" : "<div class=\"messaggiSuccesso\">".$successoPassword."</div>", $paginaHTML);

echo $paginaHTML;

?>