<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

/*if(!isset($_SESSION['username'])) {
    header("Location: accedi.php");
}if($_SESSION['admin'] != 1) {
        header("Location: utente.php");
    }
*/
$isAdmin = true; 
if($_SESSION['admin'] != 1) 
    $isAdmin = false;
//utenti
$userMenu ='<dt><a href="utente.php"><span lang="en">Home</span></a></dt>
    <dt><a href="stai_leggendo.php">Libri che stai leggendo</a></dt>
    <dt><a href="terminati.php">Libri terminati</a></dt>
    <dt><a href="da_leggere.php">Libri da leggere</a></dt>
    <dt><a href="recensione.php">Aggiungi Recensione</a></dt>
    <dt>Lista Generi:</dt>
    {listaGeneri}
    <dt><a href="statistiche.php">Statistiche</a></dt>
    <dt>Area Personale</dt>
    <dt><a href="cerca.php">Cerca</a></dt>';

//admin
$adminMenu = '<dt><a href="admin.php"><span lang="en">Home</span></a></dt>
    <dt><a href="aggiungi_libro.php">Aggiungi un libro</a></dt>
    <dt><a href="tutti_libri.php">Catalogo libri</a></dt>
    <dt><a href="tutti_utenti.php">Archivio utenti</a></dt>
    <dt><a href="modifica_libro.php">Modifica Libro</a></dt>
    <dt>Categorie</dt>
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
            $messaggi .= "<li>La password deve contenere almeno un numero, una lettera maiuscola e una minuscola</li>";
        }
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

function controllaEmail($email) {
    $messaggi = "";
    $regexOk = preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email); //da testare
    if(!$regexOk) {
        $messaggi .= "<li>L'email non è valida</li>";
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
                $successoUsername = '<span lang="en">Username</span> modificato con successo';
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
        }

        //elimina
        if(isset($_POST['elimina'])) {
            $connection -> eliminaUtente($_SESSION['username']);
            session_destroy();
            header("Location: index.php");
        }

        $resultGeneri = $connection -> getListaGeneri();
        $connection -> closeConnection();
        foreach($resultGeneri as $genere) { //per ogni genere, creo una lista di libri di quel genere
            $listaGeneri .= '<dd><a href="genere.php?genere='.$genere["genere"].'">'.$genere["genere"].'</a></dd>';
        }
    }
    else {
        $messaggi.="<li>Connessione fallita</li>";
    }
}
catch(Throwable $e) {
    $messaggi.="<li>Errore: ".$e -> getMessage()."</li>";
}

$paginaHTML = str_replace("{menu}", $menu , $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
$paginaHTML = str_replace("{nome}", $_SESSION["nome"], $paginaHTML);
$paginaHTML = str_replace("{usernameattuale}", $_SESSION["username"], $paginaHTML);
$paginaHTML = str_replace("{emailattuale}", $_SESSION["email"], $paginaHTML);
$paginaHTML = str_replace("{messaggi}", $messaggi, $paginaHTML);
$paginaHTML = str_replace("{messaggiUsername}", $messaggiUsername, $paginaHTML);
$paginaHTML = str_replace("{messaggiEmail}", $messaggiEmail, $paginaHTML);
$paginaHTML = str_replace("{messaggiPassword}", $messaggiPassword, $paginaHTML);
$paginaHTML = str_replace("{successoUsername}", $successoUsername, $paginaHTML);
$paginaHTML = str_replace("{successoEmail}", $successoEmail, $paginaHTML);
$paginaHTML = str_replace("{successoPassword}", $successoPassword, $paginaHTML);



echo $paginaHTML;

?>