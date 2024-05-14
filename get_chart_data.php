<?php
require_once "DBAccess.php";
use DB\DBAccess;

// Crea la connessione al database
$connection = new DBAccess();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    // Genera i dati che vuoi inviare a JavaScript
    $data = [
        "labels" => ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio"],
        "values" => [10, 20, 30, 40, 50] // Esempio: sostituiscilo con i tuoi dati
    ];

    // Converte i dati in JSON e li invia come risposta
    header('Content-Type: application/json'); // Imposta il tipo di contenuto come JSON
    echo json_encode($data); // Invia i dati JSON al client
} else {
    http_response_code(500); // Restituisce un errore HTTP se la connessione fallisce
    echo json_encode(["error" => "Connessione al database fallita"]);
}
