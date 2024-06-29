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

$paginaHTML = file_get_contents("template/templateAccedi.html");

function controllaInput($username, $password) { //da inserire eventualmente altri controlli su username e password
    $messaggi = "";
    if($username == "") {
        $messaggi .= "<li>Lo <span lang=\"en\">username</span> non può essere vuoto</li>";
    }
    if($password == "") {
        $messaggi .= "<li>La <span lang=\"en\">password</span> non può essere vuota</li>";
    }
    return array("ok"=>$messaggi == "", "messaggi"=>$messaggi);
}

$messaggiPerForm = "";
$listaGeneri = "";

$connection = new DBAccess();
$connectionOk = $connection -> openDBConnection();
if($connectionOk) {
    //username
    if(isset($_POST['accedi'])) {
        $username = trim($_POST['username']);
        $username = strip_tags($username); //elimina eventuali tag html
        $username = htmlentities($username); //trasforma i caratteri html in entità

        $password = $_POST['password'];
        $password = strip_tags($password);
        $password = htmlentities($password);
    
        $tmp = controllaInput($username, $password);
        $ok = $tmp['ok'];
        $messaggiPerForm .= $tmp['messaggi'];
        if($ok) {
            $user = $connection -> login($username, $password);
            if($user!=null) {
                $_SESSION['username'] = $user['username']; //salva lo username in una variabile di sessione
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['cognome'] = $user['cognome'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['data_nascita'] = $user['data_nascita'];
                $_SESSION['data_iscrizione'] = $user['data_iscrizione'];
                if($user['admin']==1) {
                    $_SESSION['admin'] = true;
                    header("Location: admin.php?accesso=1");
                    exit();
                }
                else {
                    $_SESSION['admin'] = false;
                    header("Location: utente.php?accesso=1");
                    exit();
                }
            } else {
                $messaggiPerForm .= "<li>Credenziali errate. Riprova.</li>";
            }
        }
    }
    $resultListaGeneri = $connection -> getListaGeneri();
    $connection -> closeConnection();
    foreach($resultListaGeneri as $genere) {
        $listaGeneri .= '<li><a href="genere.php?genere='.$genere["nome"].'">'.$genere["nome"].'</a></li>';
    }
} else {
    header("Location: 500.php");
    exit();
}


$paginaHTML = str_replace("{messaggi}", $messaggiPerForm=="" ? "" : "<ul class=\"messaggiErrore\">".$messaggiPerForm."</ul>", $paginaHTML);
$paginaHTML = str_replace("{listaGeneri}", $listaGeneri, $paginaHTML);
echo $paginaHTML;
?>
