<?php

require_once "DBAccess.php";
use DB\DBAccess;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

setlocale(LC_ALL, 'it_IT');

$paginaHTML = file_get_contents("template/templateAccedi.html");

function pulisciInput($value) {
    $value = trim($value); //elimina gli spazi
    $value = strip_tags($value); //rimuovi tag html
    $value = htmlentities($value); //converte i caratteri speciali in entità html
    return $value;
}

$messaggiPerForm = "";

$ok = true;
if(isset($_POST['submit'])) {
    $username = pulisciInput($_POST['username']);
    if(strlen($username) <= 2) {
        $messaggiPerForm .= "<li>Lo username non può essere più corto di 3 caratteri</li>";
        $ok = false;
    }
    //$password = pulisciInput($_POST['password']);
    $password = $_POST['password'];

    if($ok) {
        try {
            $connection = new DBAccess();
            $connectionOk = $connection -> openDBConnection();

            $user = $connection -> login($username, $password);
            $connection -> closeConnection();
            if($user!=null) {
                //header("Location: pagina_successo.php");
                $messaggiPerForm .= "<li>Login effettuato con successo</li>";
            } else {
                $messaggiPerForm .= "<li>Credenziali errate. Riprova.</li>";
            }
        }
        catch(Throwable $e) {
            $messaggiPerForm .= "<li>Errore di connessione ad database: ".$e -> getMessage()."</li>";
        }
    }
}

$paginaHTML = str_replace("{messaggi}", $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>
